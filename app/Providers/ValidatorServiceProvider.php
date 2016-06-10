<?php

namespace App\Providers;


use Carbon\Carbon;
use Validator;
use Illuminate\Support\ServiceProvider;


class ValidatorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('min_age', function ($attribute, $value, $parameters, $validator) {
            $d=explode('/',$value);
            $age = Carbon::createFromDate($d[2],$d[1],$d[0])->age;
            $minAge = $parameters[0];
            if ($age >= $minAge) {
                return true;
            }
            return false;
        });

    }

    public function register()
    {
        // TODO: Implement register() method.
    }
}