<?php

namespace App\Packages\StaffManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\StaffManagement\Models\Department;
use App\Packages\StaffManagement\Models\PerformanceReview;
use App\Packages\StaffManagement\Models\Qualification;
use App\Packages\StaffManagement\Models\SalaryDetail;
use App\Packages\StaffManagement\Models\Staff;
use App\Packages\StaffManagement\Models\StaffAttendance;
use App\Packages\StaffManagement\Models\StaffDepartmentAssignment;
use App\Packages\StaffManagement\Models\StaffLeaveRequest;
use App\Packages\StaffManagement\Models\TeacherAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(Request $request): View
    {
        $staff = Staff::with(['department', 'latestSalary'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->department_id))
            ->latest('staff_id')
            ->paginate(10)
            ->withQueryString();

        return view('staff-management::staff.index', [
            'staff' => $staff,
            'departments' => Department::orderBy('department_name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('staff-management::staff.create', $this->lookupData());
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $this->validateStaff($request, true);
        $staffData = $this->staffData($validated);

        if ($request->hasFile('photo')) {
            $staffData['photo'] = Storage::url(
                $request->file('photo')->store('staff-photos', 'public')
            );
        }

        $staff = Staff::create($staffData);

        $this->syncQualifications($staff, $validated['qualifications'] ?? []);
        $this->syncTeacherAssignments($staff, $validated['teacher_assignments'] ?? []);
        $this->syncDepartmentAssignments($staff, $validated['department_assignments'] ?? []);

        Event::dispatch('webhook.staff.created', [$staff->only([
            'staff_id', 'first_name', 'last_name', 'email', 'designation', 'status',
        ])]);

        if ($request->expectsJson()) {
            return response()->json($staff->load($this->relations()), 201);
        }

        return redirect()->route('staff.show', $staff)->with('success', 'Staff member created successfully.');
    }

    public function show(Staff $staff): View
    {
        $staff->load($this->relations());

        return view('staff-management::staff.show', array_merge(['staffMember' => $staff], $this->lookupData()));
    }

    public function edit(Staff $staff): View
    {
        $staff->load(['qualifications', 'teacherAssignments', 'departmentAssignments']);

        return view('staff-management::staff.edit', array_merge(['staffMember' => $staff], $this->lookupData()));
    }

    public function update(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $this->validateStaff($request, false, $staff);
        $staffData = $this->staffData($validated);

        if ($request->hasFile('photo')) {
            // Delete old photo from storage if it's a stored file
            if ($staff->photo && str_starts_with($staff->photo, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $staff->photo));
            }
            $staffData['photo'] = Storage::url(
                $request->file('photo')->store('staff-photos', 'public')
            );
        } elseif ($request->input('photo_clear') === '1') {
            if ($staff->photo && str_starts_with($staff->photo, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $staff->photo));
            }
            $staffData['photo'] = null;
        }

        $staff->update($staffData);

        if (array_key_exists('qualifications', $validated)) {
            $this->syncQualifications($staff, $validated['qualifications'] ?? []);
        }
        if (array_key_exists('teacher_assignments', $validated)) {
            $this->syncTeacherAssignments($staff, $validated['teacher_assignments'] ?? []);
        }
        if (array_key_exists('department_assignments', $validated)) {
            $this->syncDepartmentAssignments($staff, $validated['department_assignments'] ?? []);
        }

        Event::dispatch('webhook.staff.updated', [$staff->only([
            'staff_id', 'first_name', 'last_name', 'email', 'designation', 'status',
        ])]);

        if ($request->expectsJson()) {
            return response()->json($staff->load($this->relations()));
        }

        return redirect()->route('staff.show', $staff)->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $staff->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }

    public function storeDepartment(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:100|unique:departments,department_name',
            'description' => 'nullable|string',
        ]);

        $department = Department::create($validated);

        return $this->respond($request, ['success' => true, 'department' => $department], 201, 'Department saved.');
    }

    public function storeQualification(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'degree' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'university_name' => 'required|string|max:150',
            'year_of_completion' => 'required|integer|digits:4',
        ]);

        $qualification = $staff->qualifications()->create($validated);

        return $this->respond($request, ['success' => true, 'qualification' => $qualification], 201, 'Qualification saved.');
    }

    public function destroyQualification(Staff $staff, Qualification $qualification): JsonResponse
    {
        abort_if($qualification->staff_id !== $staff->staff_id, 403);
        $qualification->delete();

        return response()->json(['success' => true]);
    }

    public function storeTeacherAssignment(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:50',
            'subject' => 'required|string|max:100',
        ]);

        $assignment = $staff->teacherAssignments()->create($validated);

        return $this->respond($request, ['success' => true, 'assignment' => $assignment], 201, 'Teacher assignment saved.');
    }

    public function storeAttendance(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,On Leave,Late',
            'remarks' => 'nullable|string',
        ]);

        $attendance = $staff->attendances()->create($validated);

        return $this->respond($request, ['success' => true, 'attendance' => $attendance], 201, 'Attendance saved.');
    }

    public function storeSalary(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|between:0,9999999.99',
            'allowances' => 'nullable|numeric|between:0,9999999.99',
            'deductions' => 'nullable|numeric|between:0,9999999.99',
            'net_salary' => 'nullable|numeric|between:0,9999999.99',
            'payment_date' => 'required|date',
        ]);
        $validated['allowances'] = $validated['allowances'] ?? 0;
        $validated['deductions'] = $validated['deductions'] ?? 0;
        $validated['net_salary'] = $validated['net_salary'] ?? ($validated['basic_salary'] + $validated['allowances'] - $validated['deductions']);

        $salary = $staff->salaries()->create($validated);

        return $this->respond($request, ['success' => true, 'salary' => $salary], 201, 'Salary record saved.');
    }

    public function storeReview(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'review_period_start' => 'required|date',
            'review_period_end' => 'required|date|after_or_equal:review_period_start',
            'rating' => 'nullable|numeric|between:0,5',
            'comments' => 'nullable|string',
            'reviewed_by' => 'nullable|string|max:100',
        ]);

        $review = $staff->performanceReviews()->create($validated);

        return $this->respond($request, ['success' => true, 'review' => $review], 201, 'Performance review saved.');
    }

    public function storeLeaveRequest(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'leave_type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'status' => 'nullable|in:Pending,Approved,Rejected,Cancelled',
            'approved_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]);
        if (($validated['status'] ?? 'Pending') === 'Approved') {
            $validated['approved_at'] = now();
        }

        $leaveRequest = $staff->leaveRequests()->create($validated);

        return $this->respond($request, ['success' => true, 'leave_request' => $leaveRequest], 201, 'Leave request saved.');
    }

    public function storeDepartmentAssignment(Request $request, Staff $staff): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'is_primary' => 'nullable|boolean',
            'assigned_date' => 'nullable|date',
        ]);

        if (! empty($validated['is_primary'])) {
            $staff->departmentAssignments()->update(['is_primary' => false]);
            $staff->update(['department_id' => $validated['department_id']]);
        }

        $assignment = $staff->departmentAssignments()->updateOrCreate(
            ['department_id' => $validated['department_id']],
            [
                'is_primary' => (bool) ($validated['is_primary'] ?? false),
                'assigned_date' => $validated['assigned_date'] ?? null,
            ]
        );

        return $this->respond($request, ['success' => true, 'department_assignment' => $assignment], 201, 'Department assignment saved.');
    }

    private function validateStaff(Request $request, bool $creating, ?Staff $staff = null): array
    {
        return $request->validate([
            'first_name' => [$creating ? 'required' : 'nullable', 'string', 'max:100'],
            'last_name' => [$creating ? 'required' : 'nullable', 'string', 'max:100'],
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string',
            'nationality' => 'nullable|string|max:50',
            'joining_date' => 'nullable|date',
            'department_id' => 'nullable|exists:departments,department_id',
            'designation' => 'nullable|string|max:100',
            'employment_type' => 'nullable|in:Permanent,Temporary,Contract',
            'status' => 'nullable|in:Active,Inactive,Resigned',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'qualifications' => 'nullable|array',
            'qualifications.*.degree' => 'nullable|string|max:100',
            'qualifications.*.specialization' => 'nullable|string|max:100',
            'qualifications.*.university_name' => 'nullable|string|max:150',
            'qualifications.*.year_of_completion' => 'nullable|integer|digits:4',
            'teacher_assignments' => 'nullable|array',
            'teacher_assignments.*.class_name' => 'nullable|string|max:50',
            'teacher_assignments.*.subject' => 'nullable|string|max:100',
            'department_assignments' => 'nullable|array',
            'department_assignments.*.department_id' => 'nullable|exists:departments,department_id',
            'department_assignments.*.is_primary' => 'nullable|boolean',
            'department_assignments.*.assigned_date' => 'nullable|date',
        ]);
    }

    private function staffData(array $data): array
    {
        $staffData = array_intersect_key($data, array_flip([
            'first_name', 'last_name', 'date_of_birth', 'gender', 'phone_number', 'email',
            'address', 'nationality', 'joining_date', 'department_id', 'designation',
            'employment_type', 'status',
            // 'photo' handled separately as file upload
        ]));
        $staffData['status'] = $staffData['status'] ?? 'Active';

        return $staffData;
    }

    private function syncQualifications(Staff $staff, array $qualifications): void
    {
        if ($qualifications === []) {
            return;
        }
        $staff->qualifications()->delete();
        foreach ($qualifications as $qualification) {
            if (empty(array_filter($qualification ?? []))) {
                continue;
            }
            $staff->qualifications()->create($qualification);
        }
    }

    private function syncTeacherAssignments(Staff $staff, array $assignments): void
    {
        if ($assignments === []) {
            return;
        }
        $staff->teacherAssignments()->delete();
        foreach ($assignments as $assignment) {
            if (empty(array_filter($assignment ?? []))) {
                continue;
            }
            $staff->teacherAssignments()->create($assignment);
        }
    }

    private function syncDepartmentAssignments(Staff $staff, array $assignments): void
    {
        if ($assignments === []) {
            return;
        }
        $staff->departmentAssignments()->delete();
        foreach ($assignments as $assignment) {
            if (empty($assignment['department_id'])) {
                continue;
            }
            $staff->departmentAssignments()->create([
                'department_id' => $assignment['department_id'],
                'is_primary' => (bool) ($assignment['is_primary'] ?? false),
                'assigned_date' => $assignment['assigned_date'] ?? null,
            ]);
        }
    }

    private function lookupData(): array
    {
        return ['departments' => Department::orderBy('department_name')->get()];
    }

    private function relations(): array
    {
        return [
            'department',
            'departmentAssignments.department',
            'qualifications',
            'teacherAssignments',
            'attendances',
            'salaries',
            'latestSalary',
            'performanceReviews',
            'leaveRequests',
        ];
    }

    private function respond(Request $request, array $payload, int $status, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json($payload, $status);
        }

        return back()->with('success', $message);
    }
}
