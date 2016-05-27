<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = ['address','postal_code','city', 'type_address'];

    protected $hidden = [];

    public function users()
    {
        return $this->hasMany('App\User');
    }

}
