<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = ['category_id', 'name', 'price', 'description', 'details', 'ingredients', 'image', 'active', 'sort'];

    protected $casts = ['active' => 'boolean'];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? asset('images/' . $this->image) : asset('logo.svg');
    }
}
