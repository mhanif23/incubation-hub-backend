<?php

namespace App\Models;

class Employee extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('employee', function (Builder $builder) {
            $builder->where('type', 'employee');
        });
    }
}
