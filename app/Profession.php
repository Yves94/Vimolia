<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    public function Users()
    {
        return $this->belongsToMany('App\PraticianUser');
    }
}
