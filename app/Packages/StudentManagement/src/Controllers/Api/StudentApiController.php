<?php

namespace App\Packages\StudentManagement\Controllers\Api;

use App\Packages\CorePackage\Controllers\BaseController;
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
use Illuminate\Http\Request;

/**
 * @group Student Management
 */
class StudentApiController extends BaseController
{
    public function __construct()
    {
        $this->model = new Student;
        $this->resourceName = 'student';
    }

    public function index(): JsonResponse
    {
        $students = Student::with(['parentInfo', 'previousEducations', 'healthRecord', 'enrollments'])->paginate(10);

        return response()->json($students);
    }

    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        $query = Student::query()->select('student_id', 'first_name', 'last_name', 'admission_number');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('admission_number', 'like', "%{$q}%");
            });
        }

        $students = $query->orderBy('first_name')->limit(30)->get();

        return response()->json($students->map(fn ($s) => [
            'id'   => $s->student_id,
            'text' => $s->first_name . ' ' . $s->last_name . ' (' . $s->admission_number . ')',
        ]));
    }

    public function store(Request $request): JsonResponse
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
            'profile_photo' => 'nullable|string|max:255',
            'date_of_admission' => 'required|date',
            'admission_number' => 'required|string|max:50|unique:students',
            'status' => 'in:Active,Inactive,Graduated,Transferred',
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
            // Previous education
            'previous_educations' => 'nullable|array',
            'previous_educations.*.school_name' => 'required_with:previous_educations|string|max:150',
            'previous_educations.*.board' => 'required_with:previous_educations|string|max:100',
            'previous_educations.*.class_completed' => 'required_with:previous_educations|string|max:20',
            'previous_educations.*.percentage' => 'nullable|numeric|between:0,100',
            'previous_educations.*.year_of_passing' => 'required_with:previous_educations|integer|digits:4',
            // Health record
            'height_cm' => 'nullable|numeric|between:0,250',
            'weight_kg' => 'nullable|numeric|between:0,300',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'vaccination_status' => 'nullable|string|max:255',
        ]);

        $studentData = $request->only([
            'first_name', 'last_name', 'date_of_birth', 'gender', 'blood_group',
            'nationality', 'religion', 'current_address', 'permanent_address',
            'phone_number', 'email', 'profile_photo', 'date_of_admission',
            'admission_number', 'status',
        ]);
        $studentData['status'] = $studentData['status'] ?? 'Active';
        $student = Student::create($studentData);

        // Create parent info if provided
        $parentData = $request->only([
            'father_name', 'father_phone', 'father_email', 'father_occupation',
            'mother_name', 'mother_phone', 'mother_email', 'mother_occupation',
            'guardian_name', 'guardian_relationship', 'guardian_phone', 'guardian_address',
        ]);
        if (! empty(array_filter($parentData))) {
            $parentData['student_id'] = $student->student_id;
            ParentInfo::create($parentData);
        }

        // Create previous educations if provided
        if ($request->has('previous_educations')) {
            foreach ($request->input('previous_educations') as $edu) {
                $edu['student_id'] = $student->student_id;
                PreviousEducation::create($edu);
            }
        }

        // Create health record if provided
        $healthData = $request->only([
            'height_cm', 'weight_kg', 'blood_group', 'allergies',
            'medical_conditions', 'vaccination_status',
        ]);
        if (! empty(array_filter($healthData))) {
            $healthData['student_id'] = $student->student_id;
            HealthRecord::create($healthData);
        }

        $student->load(['parentInfo', 'previousEducations', 'healthRecord']);

        return response()->json($student, 201);
    }

    public function show(Student $student): JsonResponse
    {
        $student->load([
            'parentInfo',
            'previousEducations',
            'healthRecord',
            'healthRecords',
            'enrollments.batch',
            'enrollments.academicYear',
            'documents',
            'activityLogs',
            'contacts',
        ]);

        return response()->json($student);
    }

    public function update(Request $request, Student $student): JsonResponse
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
            'profile_photo' => 'nullable|string|max:255',
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

        $student->update($this->studentData($validated));

        $parentData = $this->parentData($validated);
        if (! empty(array_filter($parentData))) {
            $parentInfo = $student->parentInfo ?? new ParentInfo(['student_id' => $student->student_id]);
            $parentInfo->fill($parentData);
            $parentInfo->save();
        }

        $healthData = $this->healthData($validated);
        if (! empty(array_filter($healthData))) {
            $healthRecord = $student->healthRecord ?? new HealthRecord(['student_id' => $student->student_id]);
            $healthRecord->fill($healthData);
            if (! $healthRecord->exists) {
                $healthRecord->student_id = $student->student_id;
            }
            $healthRecord->save();
        }

        if (array_key_exists('previous_educations', $validated)) {
            $student->previousEducations()->delete();
            foreach ($validated['previous_educations'] ?? [] as $education) {
                if (empty(array_filter($education ?? []))) {
                    continue;
                }
                $student->previousEducations()->create($education);
            }
        }

        if (array_key_exists('contacts', $validated)) {
            $student->contacts()->delete();
            foreach ($validated['contacts'] ?? [] as $contact) {
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

        $student->load(['parentInfo', 'previousEducations', 'healthRecord', 'contacts']);

        return response()->json($student);
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully']);
    }

    // Parent Info endpoints
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

    // Previous Education endpoints
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

    // Health Record endpoints
    public function indexHealthRecords(Student $student): JsonResponse
    {
        return response()->json($student->healthRecords()->get());
    }

    public function storeHealthRecord(Request $request, Student $student): JsonResponse
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

        return response()->json(['success' => true, 'health_record' => $healthRecord], 201);
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

    public function enrollStudent(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'batch_id' => 'required|integer|exists:batches,batch_id',
            'academic_year_id' => 'nullable|integer|exists:academic_years,academic_year_id',
            'enrollment_date' => 'required|date',
            'status' => 'nullable|string|in:Active,Inactive,Graduated,Transferred',
            'remarks' => 'nullable|string',
        ]);

        $existing = StudentEnrollment::where('student_id', $student->student_id)
            ->where('batch_id', $validated['batch_id'])
            ->where('academic_year_id', $validated['academic_year_id'] ?? null)
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Student already enrolled in this batch'], 409);
        }

        $validated['student_id'] = $student->student_id;
        $enrollment = StudentEnrollment::create($validated);

        $this->logActivity($student, 'enrollment', 'Student enrolled in batch');

        $enrollment->load(['batch', 'academicYear']);

        return response()->json(['success' => true, 'enrollment' => $enrollment], 201);
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

    public function indexEnrollments(Student $student): JsonResponse
    {
        $enrollments = $student->enrollments()->with(['batch', 'academicYear'])->get();

        return response()->json($enrollments);
    }

    // ==================== Promotion History ====================

    public function promoteStudent(Request $request, Student $student): JsonResponse
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

        $oldEnrollment = StudentEnrollment::where('student_id', $student->student_id)
            ->where('batch_id', $validated['from_batch_id'])
            ->where('status', 'Active')
            ->first();

        if ($oldEnrollment) {
            $oldEnrollment->update(['status' => 'Graduated']);
        }

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

        $this->logActivity($student, 'promotion', 'Student promoted to new batch', [
            'from_batch_id' => $validated['from_batch_id'],
            'to_batch_id' => $validated['to_batch_id'],
        ]);

        $promotion->load(['fromBatch', 'toBatch', 'fromAcademicYear', 'toAcademicYear']);

        return response()->json(['success' => true, 'promotion' => $promotion, 'new_enrollment' => $newEnrollment], 201);
    }

    public function indexPromotions(Student $student): JsonResponse
    {
        $promotions = $student->promotionHistory()->with(['fromBatch', 'toBatch', 'fromAcademicYear', 'toAcademicYear'])->get();

        return response()->json($promotions);
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

    public function storeDocument(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'document_type' => 'required|string|in:birth_certificate,transfer_certificate,character_certificate,previous_school_certificate,medical_certificate,passport,id_proof,address_proof,photograph,other',
            'document_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:500',
            'file_size' => 'nullable|integer',
            'mime_type' => 'nullable|string|max:100',
            'uploaded_by' => 'nullable|integer|exists:users,id',
            'notes' => 'nullable|string',
            'expiry_date' => 'nullable|date',
        ]);

        $validated['student_id'] = $student->student_id;

        $document = StudentDocument::create($validated);

        $this->logActivity($student, 'document_upload', "Document uploaded: {$document->document_type}");

        return response()->json(['success' => true, 'document' => $document], 201);
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

    public function storeContact(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'contact_type' => 'required|string|in:phone,mobile,emergency,work,fax,other',
            'contact_value' => 'required|string|max:100',
            'is_primary' => 'boolean',
            'label' => 'nullable|string|max:50',
        ]);

        if (isset($validated['is_primary']) && $validated['is_primary']) {
            $student->contacts()->where('is_primary', true)->update(['is_primary' => false]);
        }

        $validated['student_id'] = $student->student_id;
        $contact = StudentContact::create($validated);

        $this->logActivity($student, 'other', "Contact added: {$contact->contact_type}");

        return response()->json(['success' => true, 'contact' => $contact], 201);
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
            'profile_photo',
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

    private function logActivity(Student $student, string $type, string $description, ?array $changes = null): void
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

            if ($changes) {
                $logData['old_value'] = null;
                $logData['new_value'] = $changes;
            }

            StudentActivityLog::create($logData);
        } catch (\Exception $e) {
            // Silently fail logging errors
        }
    }
}
