<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Expert extends Authenticatable
{

    protected $table='experts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pays',
    ];

   public function user()
    {
        return $this->morphOne('User', 'userable');
    }
}
