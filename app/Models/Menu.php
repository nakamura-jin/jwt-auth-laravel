<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;
use App\Models\User;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description','owner_id', 'area_id', 'genre_id', 'price', 'image', 'quantity', 'product_code'];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'cart_id', 'menu_id', 'user_id', 'order_id');
    // }
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function genres()
    {
        return $this->hasOne(Genre::class);
    }
}
