<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'grade_id',
        'name',
        'access_key',
    ];

    /**
     * A Student belongs to one Grade.
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * A Student can have many Photos.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * A Student can have many Orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
