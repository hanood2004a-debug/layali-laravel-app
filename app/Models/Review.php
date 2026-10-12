<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['menu_item_id', 'name', 'rating', 'comment', 'approved', 'ip_hash'];

    protected $casts = ['approved' => 'boolean'];

    public function item()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
