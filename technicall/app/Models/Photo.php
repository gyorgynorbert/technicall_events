<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'path',
        'label',
    ];

    /**
     * A Photo belongs to one Student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Helper attribute to get the public URL of the photo.
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}
