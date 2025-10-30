<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    // This is correct from your old SchoolController
    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * The events that this school participates in.
     */
    public function events()
    {
        // The other side of the many-to-many relationship
        return $this->belongsToMany(Event::class, 'event_school');
    }

    /**
     * Get the grades for the school.
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
