<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\User;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description','owner_id', 'area_id', 'genre_id', 'price', 'image'];

    public function tag()
    {
        return $this->hasOne(Tag::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cart_id', 'menu_id', 'user_id', 'order_id');
    }
}
