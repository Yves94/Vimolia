<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicUser extends Model
{
    protected $table = 'public_users';

    protected $fillable = ['birthday'];

    protected $hidden = [];
    public $timestamps = false;
    public function User()
    {
        return $this->morphOne('App\User', 'userable');
    }
}
