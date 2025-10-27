<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'parent_name',
        'parent_email',
        'parent_phone_number',
        'total_price',
        'status',
    ];

    /**
     * Get the student that this order belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get all of the items for this order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
