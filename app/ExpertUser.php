<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpertUser extends Model
{
    protected $table = 'expert_users';

    protected $fillable = [];

    protected $hidden = [];
    public $timestamps = false;
    public function User()
    {
        return $this->morphOne('App\User', 'userable');
    }
}
