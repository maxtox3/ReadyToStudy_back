<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'name'
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function discipline()
    {
        return $this->belongsToMany(Discipline::class);
    }
}
