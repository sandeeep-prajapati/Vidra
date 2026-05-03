<?php

namespace App\Packages\StudentManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\StudentManagement\Models\HealthRecord;
use App\Packages\StudentManagement\Models\ParentInfo;
use App\Packages\StudentManagement\Models\PreviousEducation;
use App\Packages\StudentManagement\Models\PromotionHistory;
use App\Packages\StudentManagement\Models\Student;
use App\Packages\StudentManagement\Models\StudentActivityLog;
use App\Packages\StudentManagement\Models\StudentContact;
use App\Packages\StudentManagement\Models\StudentDocument;
use App\Packages\StudentManagement\Models\StudentEnrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with(['parentInfo', 'healthRecord', 'enrollments.batch'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest('student_id')
            ->paginate(10)
            ->withQueryString();

        return view('student-management::student.index', compact('students'));
    }

    public function create(): View
    {
        return view('student-management::student.create', $this->lookupOptions());
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'blood_group' => 'nullable|string|max:5',
            'nationality' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'current_address' => 'required|string',
            'permanent_address' => 'required|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'date_of_admission' => 'required|date',
            'admission_number' => 'required|string|max:50|unique:students',
            'status' => 'in:Active,Inactive,Graduated,Transferred',
            // Previous education (web form)
            'previous_educations' => 'nullable|array',
            'previous_educations.*.school_name' => 'nullable|string|max:150',
            'previous_educations.*.board' => 'nullable|string|max:100',
            'previous_educations.*.class_completed' => 'nullable|string|max:20',
            'previous_educations.*.percentage' => 'nullable|numeric|between:0,100',
            'previous_educations.*.year_of_passing' => 'nullable|integer|digits:4',
            // Parent info
            'father_name' => 'nullable|string|max:100',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:100',
            'father_occupation' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:100',
            'mother_occupation' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:100',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_address' => 'nullable|string',
            // Health record
            'height_cm' => 'nullable|numeric|between:0,250',
            'weight_kg' => 'nullable|numeric|between:0,300',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'vaccination_status' => 'nullable|string|max:255',
            'contacts' => 'nullable|array',
            'contacts.*.contact_type' => 'nullable|string|in:phone,mobile,emergency,work,fax,other',
            'contacts.*.contact_value' => 'nullable|string|max:100',
            'contacts.*.is_primary' => 'nullable|boolean',
            'contacts.*.label' => 'nullable|string|max:50',
        ]);

        $studentData = $this->studentData($validated);
        $studentData['status'] = $studentData['status'] ?? 'Active';

        if ($request->hasFile('profile_photo')) {
            $studentData['profile_photo'] = Storage::url(
                $request->file('profile_photo')->store('student-photos', 'public')
            );
        }

        $student = Student::create($studentData);
        $this->logActivity($student, 'admission', 'Student admitted', null, $studentData);

        $parentData = $this->parentData($validated);
        if (! empty(array_filter($parentData))) {
            $parentData['student_id'] = $student->student_id;
            ParentInfo::create($parentData);
        }

        $this->syncPreviousEducations($student, $validated['previous_educations'] ?? []);

        $healthData = $this->healthData($validated);
        if (! empty(array_filter($healthData))) {
            $healthData['student_id'] = $student->student_id;
            HealthRecord::create($healthData);
            $this->logActivity($student, 'health_update', 'Health record created', null, $healthData);
        }

        $this->syncContacts($student, $validated['contacts'] ?? []);

        Event::dispatch('webhook.student.created', [$student->only([
            'student_id', 'first_name', 'last_name', 'admission_number', 'gender', 'status',
        ])]);

        if ($request->expectsJson()) {
            $student->load(['parentInfo', 'previousEducations', 'healthRecord', 'contacts']);

            return response()->json($student, 201);
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully! Student "'.$student->first_name.' '.$student->last_name.'" has been added.');
    }

    public function show(Student $student): View
    {
        $student->load([
            'parentInfo',
            'previousEducations',
            'healthRecord',
            'healthRecords',
            'enrollments.batch',
            'enrollments.academicYear',
            'promotionHistory.fromBatch',
            'promotionHistory.toBatch',
            'promotionHistory.fromAcademicYear',
            'promotionHistory.toAcademicYear',
            'documents',
            'activityLogs',
            'contacts',
        ]);

        return view('student-management::student.show', array_merge(compact('student'), $this->lookupOptions()));
    }

    public function edit(Student $student): View
    {
        $student->load(['parentInfo', 'previousEducations', 'healthRecord', 'contacts']);

        return view('student-management::student.edit', compact('student'));
    }

    public function update(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender' => 'in:Male,Female,Other',
            'blood_group' => 'nullable|string|max:5',
            'nationality' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'current_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'profile_photo' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'date_of_admission' => 'nullable|date',
            'admission_number' => 'nullable|string|max:50|unique:students,admission_number,'.$student->student_id.',student_id',
            'status' => 'in:Active,Inactive,Graduated,Transferred',
            'father_name' => 'nullable|string|max:100',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:100',
            'father_occupation' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:100',
            'mother_occupation' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:100',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_address' => 'nullable|string',
            'height_cm' => 'nullable|numeric|between:0,250',
            'weight_kg' => 'nullable|numeric|between:0,300',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'vaccination_status' => 'nullable|string|max:255',
            'previous_educations' => 'nullable|array',
            'previous_educations.*.school_name' => 'nullable|string|max:150',
            'previous_educations.*.board' => 'nullable|string|max:100',
            'previous_educations.*.class_completed' => 'nullable|string|max:20',
            'previous_educations.*.percentage' => 'nullable|numeric|between:0,100',
            'previous_educations.*.year_of_passing' => 'nullable|integer|digits:4',
            'contacts' => 'nullable|array',
            'contacts.*.contact_type' => 'nullable|string|in:phone,mobile,emergency,work,fax,other',
            'contacts.*.contact_value' => 'nullable|string|max:100',
            'contacts.*.is_primary' => 'nullable|boolean',
            'contacts.*.label' => 'nullable|string|max:50',
        ]);

        $oldStudentData = $student->only(array_keys($this->studentData($validated)));
        $studentData = $this->studentData($validated);

        if ($request->hasFile('profile_photo')) {
            if ($student->profile_photo && str_starts_with($student->profile_photo, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $student->profile_photo));
            }
            $studentData['profile_photo'] = Storage::url(
                $request->file('profile_photo')->store('student-photos', 'public')
            );
        } elseif ($request->input('profile_photo_clear') === '1') {
            if ($student->profile_photo && str_starts_with($student->profile_photo, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $student->profile_photo));
            }
            $studentData['profile_photo'] = null;
        }

        $student->update($studentData);

        if (array_key_exists('status', $studentData) && $oldStudentData['status'] !== $studentData['status']) {
            $this->logActivity($student, 'status_change', 'Student status changed', ['status' => $oldStudentData['status']], ['status' => $studentData['status']]);
        }

        $parentData = $this->parentData($validated);
        if (! empty(array_filter($parentData))) {
            $parentInfo = $student->parentInfo ?? new ParentInfo(['student_id' => $student->student_id]);
            $parentInfo->fill($parentData);
            if (! $parentInfo->exists) {
                $parentInfo->student_id = $student->student_id;
            }
            $parentInfo->save();
        }

        $healthData = $this->healthData($validated);
        if (! empty(array_filter($healthData))) {
            $healthRecord = $student->healthRecord ?? new HealthRecord(['student_id' => $student->student_id]);
            $oldHealthData = $healthRecord->exists ? $healthRecord->only(array_keys($healthData)) : null;
            $healthRecord->fill($healthData);
            if (! $healthRecord->exists) {
                $healthRecord->student_id = $student->student_id;
            }
            $healthRecord->save();
            $this->logActivity($student, 'health_update', 'Health record updated', $oldHealthData, $healthData);
        }

        if (array_key_exists('previous_educations', $validated)) {
            $this->syncPreviousEducations($student, $validated['previous_educations'] ?? []);
        }

        if (array_key_exists('contacts', $validated)) {
            $this->syncContacts($student, $validated['contacts'] ?? []);
        }

        Event::dispatch('webhook.student.updated', [$student->only([
            'student_id', 'first_name', 'last_name', 'admission_number', 'gender', 'status',
        ])]);

        if ($request->expectsJson()) {
            $student->load(['parentInfo', 'previousEducations', 'healthRecord', 'contacts']);

            return response()->json(['success' => true, 'student' => $student]);
        }

        return redirect()->route('students.show', $student)->with('success', 'Student "'.$student->first_name.' '.$student->last_name.'" updated successfully!');
    }

    public function destroy(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $student->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    // Parent Info CRUD
    public function storeParent(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'father_name' => 'nullable|string|max:100',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:100',
            'father_occupation' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:100',
            'mother_occupation' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:100',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_address' => 'nullable|string',
        ]);

        $parentInfo = ParentInfo::create(array_merge($validated, ['student_id' => $student->student_id]));

        return response()->json(['success' => true, 'parent' => $parentInfo], 201);
    }

    public function updateParent(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'father_name' => 'nullable|string|max:100',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:100',
            'father_occupation' => 'nullable|string|max:100',
            'mother_name' => 'nullable|string|max:100',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:100',
            'mother_occupation' => 'nullable|string|max:100',
            'guardian_name' => 'nullable|string|max:100',
            'guardian_relationship' => 'nullable|string|max:50',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_address' => 'nullable|string',
        ]);

        $parentInfo = $student->parentInfo ?? new ParentInfo(['student_id' => $student->student_id]);
        $parentInfo->fill($validated);
        $parentInfo->save();

        return response()->json(['success' => true, 'parent' => $parentInfo]);
    }

    // Previous Education CRUD
    public function storeEducation(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'school_name' => 'required|string|max:150',
            'board' => 'required|string|max:100',
            'class_completed' => 'required|string|max:20',
            'percentage' => 'nullable|numeric|between:0,100',
            'year_of_passing' => 'required|integer|digits:4',
        ]);
        $validated['student_id'] = $student->student_id;

        $education = PreviousEducation::create($validated);

        return response()->json(['success' => true, 'education' => $education], 201);
    }

    public function updateEducation(Request $request, Student $student, PreviousEducation $education): JsonResponse
    {
        if ($education->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'school_name' => 'nullable|string|max:150',
            'board' => 'nullable|string|max:100',
            'class_completed' => 'nullable|string|max:20',
            'percentage' => 'nullable|numeric|between:0,100',
            'year_of_passing' => 'nullable|integer|digits:4',
        ]);

        $education->update($validated);

        return response()->json(['success' => true, 'education' => $education]);
    }

    public function destroyEducation(Student $student, PreviousEducation $education): JsonResponse
    {
        if ($education->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $education->delete();

        return response()->json(['success' => true]);
    }

    // Health Record CRUD
    public function indexHealthRecords(Student $student): JsonResponse
    {
        return response()->json($student->healthRecords()->get());
    }

    public function storeHealthRecord(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'height_cm' => 'nullable|numeric|between:0,250',
            'weight_kg' => 'nullable|numeric|between:0,300',
            'blood_group' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'vaccination_status' => 'nullable|string|max:255',
        ]);
        $validated['student_id'] = $student->student_id;

        $healthRecord = HealthRecord::create($validated);
        $this->logActivity($student, 'health_update', 'Health record created', null, $validated);

        return $this->respond($request, ['success' => true, 'health_record' => $healthRecord], 201, 'Health record saved.');
    }

    public function updateHealthRecord(Request $request, Student $student, HealthRecord $healthRecord): JsonResponse
    {
        if ($healthRecord->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'height_cm' => 'nullable|numeric|between:0,250',
            'weight_kg' => 'nullable|numeric|between:0,300',
            'blood_group' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'vaccination_status' => 'nullable|string|max:255',
        ]);

        $healthRecord->update($validated);

        return response()->json(['success' => true, 'health_record' => $healthRecord]);
    }

    public function destroyHealthRecord(Student $student, HealthRecord $healthRecord): JsonResponse
    {
        if ($healthRecord->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $healthRecord->delete();

        return response()->json(['success' => true]);
    }

    // ==================== Student Enrollment ====================

    public function enrollStudent(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => 'required|integer|exists:batches,batch_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'enrollment_date' => 'required|date',
            'status' => 'nullable|string|in:Active,Inactive,Graduated,Transferred',
            'remarks' => 'nullable|string',
        ]);

        // Check if already enrolled in same batch and academic year
        $existing = StudentEnrollment::where('student_id', $student->student_id)
            ->where('batch_id', $validated['batch_id'])
            ->where('academic_year_id', $validated['academic_year_id'] ?? null)
            ->first();

        if ($existing) {
            if (! $request->expectsJson()) {
                return back()->withInput()->with('error', 'Student already enrolled in this batch.');
            }

            return response()->json(['error' => 'Student already enrolled in this batch'], 409);
        }

        $validated['student_id'] = $student->student_id;
        $enrollment = StudentEnrollment::create($validated);

        // Log activity
        $this->logActivity($student, 'enrollment', 'Student enrolled in batch');

        return $this->respond($request, ['success' => true, 'enrollment' => $enrollment], 201, 'Student enrolled successfully.');
    }

    public function updateEnrollment(Request $request, Student $student, StudentEnrollment $enrollment): JsonResponse
    {
        if ($enrollment->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'enrollment_date' => 'nullable|date',
            'status' => 'nullable|string|in:Active,Inactive,Graduated,Transferred',
            'remarks' => 'nullable|string',
        ]);

        $enrollment->update($validated);

        return response()->json(['success' => true, 'enrollment' => $enrollment]);
    }

    public function destroyEnrollment(Student $student, StudentEnrollment $enrollment): JsonResponse
    {
        if ($enrollment->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $enrollment->delete();

        return response()->json(['success' => true]);
    }

    // ==================== Promotion History ====================

    public function promoteStudent(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'from_batch_id' => 'required|integer|exists:batches,batch_id',
            'to_batch_id' => 'required|integer|exists:batches,batch_id|different:from_batch_id',
            'from_academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'to_academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'promotion_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $validated['student_id'] = $student->student_id;

        $promotion = PromotionHistory::create($validated);

        // Update enrollment status if needed
        $oldEnrollment = StudentEnrollment::where('student_id', $student->student_id)
            ->where('batch_id', $validated['from_batch_id'])
            ->where('status', 'Active')
            ->first();

        if ($oldEnrollment) {
            $oldEnrollment->update(['status' => 'Graduated']);
        }

        // Create or update new enrollment
        $newEnrollment = StudentEnrollment::firstOrCreate(
            [
                'student_id' => $student->student_id,
                'batch_id' => $validated['to_batch_id'],
                'academic_year_id' => $validated['to_academic_year_id'] ?? null,
            ],
            [
                'enrollment_date' => $validated['promotion_date'],
                'status' => 'Active',
            ]
        );

        $this->logActivity($student, 'promotion', 'Student promoted to new batch', null, [
            'from_batch_id' => $validated['from_batch_id'],
            'to_batch_id' => $validated['to_batch_id'],
        ]);

        return $this->respond($request, ['success' => true, 'promotion' => $promotion, 'new_enrollment' => $newEnrollment], 201, 'Student promoted successfully.');
    }

    public function destroyPromotion(Student $student, PromotionHistory $promotion): JsonResponse
    {
        if ($promotion->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $promotion->delete();

        return response()->json(['success' => true]);
    }

    // ==================== Student Documents ====================

    public function indexDocuments(Student $student): JsonResponse
    {
        $documents = $student->documents()->with('uploader')->get();

        return response()->json($documents);
    }

    public function storeDocument(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'document_type' => 'required|string|in:birth_certificate,transfer_certificate,character_certificate,previous_school_certificate,medical_certificate,passport,id_proof,address_proof,photograph,other',
            'document_name' => 'required|string|max:255',
            'file_path'     => 'required|file|max:10240',
            'notes'         => 'nullable|string',
            'expiry_date'   => 'nullable|date',
        ]);

        $file = $request->file('file_path');

        $validated['student_id'] = $student->student_id;
        $validated['file_path']  = Storage::url($file->store('student-documents', 'public'));
        $validated['file_size']  = $file->getSize();
        $validated['mime_type']  = $file->getMimeType();

        $document = StudentDocument::create($validated);
        $this->logActivity($student, 'document_upload', "Document uploaded: {$document->document_type}");

        return $this->respond($request, ['success' => true, 'document' => $document], 201, 'Document saved.');
    }

    public function updateDocument(Request $request, Student $student, StudentDocument $document): JsonResponse
    {
        if ($document->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'document_type' => 'nullable|string|in:birth_certificate,transfer_certificate,character_certificate,previous_school_certificate,medical_certificate,passport,id_proof,address_proof,photograph,other',
            'document_name' => 'nullable|string|max:255',
            'file_path' => 'nullable|string|max:500',
            'file_size' => 'nullable|integer',
            'mime_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);

        $document->update($validated);

        return response()->json(['success' => true, 'document' => $document]);
    }

    public function destroyDocument(Student $student, StudentDocument $document): JsonResponse
    {
        if ($document->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $document->delete();

        return response()->json(['success' => true]);
    }

    // ==================== Activity Logs ====================

    public function indexActivityLogs(Student $student): JsonResponse
    {
        $logs = $student->activityLogs()->with('changer')->paginate(20);

        return response()->json($logs);
    }

    // ==================== Student Contacts ====================

    public function indexContacts(Student $student): JsonResponse
    {
        $contacts = $student->contacts()->get();

        return response()->json($contacts);
    }

    public function storeContact(Request $request, Student $student): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'contact_type' => 'required|string|in:phone,mobile,emergency,work,fax,other',
            'contact_value' => 'required|string|max:100',
            'is_primary' => 'boolean',
            'label' => 'nullable|string|max:50',
        ]);

        // If setting as primary, unset other primary contacts
        if (isset($validated['is_primary']) && $validated['is_primary']) {
            $student->contacts()->where('is_primary', true)->update(['is_primary' => false]);
        }

        $validated['student_id'] = $student->student_id;
        $contact = StudentContact::create($validated);

        // Log activity
        $this->logActivity($student, 'other', "Contact added: {$contact->contact_type}");

        return $this->respond($request, ['success' => true, 'contact' => $contact], 201, 'Contact saved.');
    }

    public function updateContact(Request $request, Student $student, StudentContact $contact): JsonResponse
    {
        if ($contact->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'contact_type' => 'nullable|string|in:phone,mobile,emergency,work,fax,other',
            'contact_value' => 'nullable|string|max:100',
            'is_primary' => 'boolean',
            'label' => 'nullable|string|max:50',
        ]);

        // If setting as primary, unset other primary contacts
        if (isset($validated['is_primary']) && $validated['is_primary']) {
            $student->contacts()->where('is_primary', true)->update(['is_primary' => false]);
        }

        $contact->update($validated);

        return response()->json(['success' => true, 'contact' => $contact]);
    }

    public function destroyContact(Student $student, StudentContact $contact): JsonResponse
    {
        if ($contact->student_id !== $student->student_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $contact->delete();

        return response()->json(['success' => true]);
    }

    // ==================== Helper Methods ====================

    private function studentData(array $data): array
    {
        return array_intersect_key($data, array_flip([
            'first_name',
            'last_name',
            'date_of_birth',
            'gender',
            'blood_group',
            'nationality',
            'religion',
            'current_address',
            'permanent_address',
            'phone_number',
            'email',
            // 'profile_photo' handled separately as file upload
            'date_of_admission',
            'admission_number',
            'status',
        ]));
    }

    private function parentData(array $data): array
    {
        return array_intersect_key($data, array_flip([
            'father_name',
            'father_phone',
            'father_email',
            'father_occupation',
            'mother_name',
            'mother_phone',
            'mother_email',
            'mother_occupation',
            'guardian_name',
            'guardian_relationship',
            'guardian_phone',
            'guardian_address',
        ]));
    }

    private function healthData(array $data): array
    {
        return array_intersect_key($data, array_flip([
            'height_cm',
            'weight_kg',
            'blood_group',
            'allergies',
            'medical_conditions',
            'vaccination_status',
        ]));
    }

    private function syncPreviousEducations(Student $student, array $educations): void
    {
        if ($educations === []) {
            return;
        }

        $student->previousEducations()->delete();

        foreach ($educations as $education) {
            if (empty(array_filter($education ?? []))) {
                continue;
            }

            $student->previousEducations()->create($education);
        }
    }

    private function syncContacts(Student $student, array $contacts): void
    {
        if ($contacts === []) {
            return;
        }

        $student->contacts()->delete();

        foreach ($contacts as $contact) {
            if (empty($contact['contact_value'])) {
                continue;
            }

            $student->contacts()->create([
                'contact_type' => $contact['contact_type'] ?? 'phone',
                'contact_value' => $contact['contact_value'],
                'is_primary' => (bool) ($contact['is_primary'] ?? false),
                'label' => $contact['label'] ?? null,
            ]);
        }
    }

    private function lookupOptions(): array
    {
        return [
            'batches' => Schema::hasTable('batches')
                ? DB::table('batches')->orderBy('batch_name')->get()
                : collect(),
            'academicYears' => Schema::hasTable('academic_years')
                ? DB::table('academic_years')->orderByDesc('start_date')->orderByDesc('academic_year_id')->get()
                : collect(),
        ];
    }

    private function respond(Request $request, array $payload, int $status, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json($payload, $status);
        }

        return back()->with('success', $message);
    }

    private function logActivity(Student $student, string $type, string $description, ?array $oldValue = null, ?array $newValue = null): void
    {
        try {
            $logData = [
                'student_id' => $student->student_id,
                'activity_type' => $type,
                'description' => $description,
                'changed_by' => auth()->id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];

            if ($oldValue !== null || $newValue !== null) {
                $logData['old_value'] = $oldValue;
                $logData['new_value'] = $newValue;
            }

            StudentActivityLog::create($logData);
        } catch (\Exception $e) {
            // Silently fail logging errors
        }
    }
}
