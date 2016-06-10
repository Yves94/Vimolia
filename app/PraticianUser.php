<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PraticianUser extends Model
{
    protected $table = 'pratician_users';

    protected $fillable = ['profession','siret','degree'];

    protected $hidden = [];
    public $timestamps = false;
    public function User()
    {
        return $this->morphOne('App\User', 'userable');
    }
    public function professions()
    {
        return $this->belongsToMany('App\Profession');
    }
}
