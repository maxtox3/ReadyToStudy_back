<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'name', 'discipline_id'
    ];

    public function test()
    {
        return $this->hasMany(Test::class);
    }
}
