<?php

namespace App\Packages\StudentManagement\Models;

use App\Packages\RbacManagement\Models\User;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    protected $table = 'student_documents';

    protected $primaryKey = 'document_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'document_type',
        'document_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
        'notes',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
