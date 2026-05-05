<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryCategory extends Model
{
    protected $table = 'library_categories';
    protected $fillable = ['name', 'description'];
    public $timestamps = true;

    public function books(): HasMany
    {
        return $this->hasMany(LibraryBook::class, 'category_id');
    }
}
