<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Owner;
use App\Models\Admin;

class Type extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users() {
        return $this->belongsTo(User::class);
    }

    public function owners() {
        return $this->belongsTo(Owner::class);
    }

    public function admins() {
        return $this->belongsTo(Admin::class);
    }
}
