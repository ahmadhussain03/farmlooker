<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ['pivot'];

    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class)->where('user_type', 'admin');
    }

    public function moderators()
    {
        return $this->belongsToMany(User::class)->where('user_type', 'moderator');
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
