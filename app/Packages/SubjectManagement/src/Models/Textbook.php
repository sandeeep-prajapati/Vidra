<?php

namespace App\Packages\SubjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Textbook extends Model
{
    protected $table = 'textbooks';

    protected $primaryKey = 'textbook_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'subject_id',
        'title',
        'author',
        'publisher',
        'edition',
        'isbn',
        'textbook_file_path',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }
}
