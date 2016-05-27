<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    //use EntrustUserTrait;
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['civility', 'name', 'firstname', 'phone', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $sortable = ['username', 'email', 'created_at'];

    public function userable()
    {
        return $this->morphTo();
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function getUserableTypeReadable()
    {
        if ($this->userable_type == 'App\PublicUser') {
            return "Publique";
        }
        if ($this->userable_type == 'App\AdminUser') {
            return "Administrateur";
        }
        if ($this->userable_type == 'App\PraticianUser') {
            return "Praticien";
        }
        if ($this->userable_type == 'Expert\User') {
            return "Expert";
        }
        return null;
    }

    public function isPublic() {
        if($this->userable_type =='App\PublicUser') {
            return true;
        }
        return false;
    }

    public function idAdmin() {
        if($this->userable_type =='App\AdminUser') {
            return true;
        }
        return false;
    }

    public function isPratician() {
        if($this->userable_type =='App\PraticianUser') {
            return true;
        }
        return false;
    }

    public function isExpert() {
        if($this->userable_type =='Expert\User') {
            return true;
        }
        return false;
    }

    public function isValidBirthday() {
        if($this->isPublic() && $this->userable->birthday != '0000-00-00') {
            return true;
        }
        return false;
    }

    public function getMainAddress() {
        foreach ($this->addresses as $address) {
            if ($address->type_address == "main") {
                return $address;
            }
        }
        return null;
    }

    public function getDeliveryAddresses(){
        $addresses = [];
        foreach ($this->addresses as $address) {
            if ($address->type_address == "delivery") {
                $addresses[]=$address;
            }
        }
        return $addresses;
    }

    public function getBillingAddresses(){
        $addresses = [];
        foreach ($this->addresses as $address) {
            if ($address->type_address == "billing") {
                $addresses[]=$address;
            }
        }
        return $addresses;
    }
}
