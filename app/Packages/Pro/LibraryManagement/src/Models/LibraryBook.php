<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryBook extends Model
{
    protected $table = 'library_books';

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'isbn',
        'edition',
        'total_copies',
        'available_copies',
        'rack_number',
        'published_year',
        'description',
        'cover_image',
        'status',
    ];

    protected $casts = [
        'published_year' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LibraryCategory::class, 'category_id');
    }

    public function issues(): HasMany
    {
        return $this->hasMany(LibraryIssue::class, 'book_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(LibraryReservation::class, 'book_id');
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0 && $this->status === 'active';
    }
}
