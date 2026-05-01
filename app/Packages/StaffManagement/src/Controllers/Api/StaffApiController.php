<?php

namespace App\Packages\StaffManagement\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Packages\StaffManagement\Models\Department;
use App\Packages\StaffManagement\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Staff Management
 */
class StaffApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $staff = Staff::with(['department', 'latestSalary'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('department_id'), fn ($query) => $query->where('department_id', $request->department_id))
            ->paginate(10);

        return response()->json($staff);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateStaff($request, true);
        $staff = Staff::create($this->staffData($validated));

        $this->syncChildren($staff, $validated);

        return response()->json($staff->load($this->relations()), 201);
    }

    public function show(Staff $staff): JsonResponse
    {
        return response()->json($staff->load($this->relations()));
    }

    public function update(Request $request, Staff $staff): JsonResponse
    {
        $validated = $this->validateStaff($request, false);
        $staff->update($this->staffData($validated));

        $this->syncChildren($staff, $validated);

        return response()->json($staff->load($this->relations()));
    }

    public function destroy(Staff $staff): JsonResponse
    {
        $staff->delete();

        return response()->json(['success' => true]);
    }

    public function departments(): JsonResponse
    {
        return response()->json(Department::withCount('staff')->orderBy('department_name')->get());
    }

    public function storeDepartment(Request $request): JsonResponse
    {
        $department = Department::create($request->validate([
            'department_name' => 'required|string|max:100|unique:departments,department_name',
            'description' => 'nullable|string',
        ]));

        return response()->json(['success' => true, 'department' => $department], 201);
    }

    public function storeAttendance(Request $request, Staff $staff): JsonResponse
    {
        $attendance = $staff->attendances()->create($request->validate([
            'date' => 'required|date',
            'status' => 'required|in:Present,Absent,On Leave,Late',
            'remarks' => 'nullable|string',
        ]));

        return response()->json(['success' => true, 'attendance' => $attendance], 201);
    }

    public function storeSalary(Request $request, Staff $staff): JsonResponse
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

        return response()->json(['success' => true, 'salary' => $staff->salaries()->create($validated)], 201);
    }

    public function storeLeaveRequest(Request $request, Staff $staff): JsonResponse
    {
        $leave = $staff->leaveRequests()->create($request->validate([
            'leave_type' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'status' => 'nullable|in:Pending,Approved,Rejected,Cancelled',
            'approved_by' => 'nullable|string|max:100',
            'remarks' => 'nullable|string',
        ]));

        return response()->json(['success' => true, 'leave_request' => $leave], 201);
    }

    public function storeReview(Request $request, Staff $staff): JsonResponse
    {
        $review = $staff->performanceReviews()->create($request->validate([
            'review_period_start' => 'required|date',
            'review_period_end' => 'required|date|after_or_equal:review_period_start',
            'rating' => 'nullable|numeric|between:0,5',
            'comments' => 'nullable|string',
            'reviewed_by' => 'nullable|string|max:100',
        ]));

        return response()->json(['success' => true, 'review' => $review], 201);
    }

    private function validateStaff(Request $request, bool $creating): array
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
            'photo' => 'nullable|string|max:255',
            'qualifications' => 'nullable|array',
            'teacher_assignments' => 'nullable|array',
            'department_assignments' => 'nullable|array',
        ]);
    }

    private function staffData(array $data): array
    {
        $staffData = array_intersect_key($data, array_flip([
            'first_name', 'last_name', 'date_of_birth', 'gender', 'phone_number', 'email',
            'address', 'nationality', 'joining_date', 'department_id', 'designation',
            'employment_type', 'status', 'photo',
        ]));
        $staffData['status'] = $staffData['status'] ?? 'Active';

        return $staffData;
    }

    private function syncChildren(Staff $staff, array $data): void
    {
        if (array_key_exists('qualifications', $data)) {
            $staff->qualifications()->delete();
            foreach ($data['qualifications'] ?? [] as $item) {
                if (! empty(array_filter($item ?? []))) {
                    $staff->qualifications()->create($item);
                }
            }
        }

        if (array_key_exists('teacher_assignments', $data)) {
            $staff->teacherAssignments()->delete();
            foreach ($data['teacher_assignments'] ?? [] as $item) {
                if (! empty(array_filter($item ?? []))) {
                    $staff->teacherAssignments()->create($item);
                }
            }
        }

        if (array_key_exists('department_assignments', $data)) {
            $staff->departmentAssignments()->delete();
            foreach ($data['department_assignments'] ?? [] as $item) {
                if (! empty($item['department_id'])) {
                    $staff->departmentAssignments()->create($item);
                }
            }
        }
    }

    private function relations(): array
    {
        return ['department', 'departmentAssignments.department', 'qualifications', 'teacherAssignments', 'attendances', 'salaries', 'latestSalary', 'performanceReviews', 'leaveRequests'];
    }
}
