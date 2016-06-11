<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['message'];

    protected $hidden = [];

    public function users()
    {
        return $this->hasMany('App\User');
    }

}
