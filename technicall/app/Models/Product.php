<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
    ];

    /**
     * A Product can be included in many OrderItems.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
