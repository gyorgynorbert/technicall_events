<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'school_id',
        'name',
    ];

    /**
     * A Grade (class) belongs to one School.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * A Grade can have many Students.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
