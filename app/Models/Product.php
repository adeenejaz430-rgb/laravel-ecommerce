<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'images',
        'category',
        'featured',
        'quantity',
        'average_rating',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'images'   => 'array',   // JSON array of strings (image URLs)
    ];

    // Ratings embedded array in Mongo -> hasMany relation
    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    // Match previous User model relations
    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')
                    ->withTimestamps();
    }

    public function inCarts()
    {
        return $this->belongsToMany(User::class, 'cart_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
