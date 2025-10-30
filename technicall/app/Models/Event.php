<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'event_date', // From your form
        'description', // From your form
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'event_date' => 'date', // Cast to Carbon instance
    ];

    /**
     * Get the schools associated with the event.
     */
    public function schools()
    {
        return $this->belongsToMany(School::class, 'event_school');
    }
}
