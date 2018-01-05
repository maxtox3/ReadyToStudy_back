<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'name'
    ];

    public function theme()
    {
        return $this->hasMany(Theme::class);
    }

    public function teacher()
    {
        return $this->hasOne(User::class);
    }

    public function group()
    {
        return $this->belongsToMany(Group::class);
    }
}
