<?php

namespace App\Models;

class Applicant extends User
{
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('applicant', function (Builder $builder) {
            $builder->where('type', 'applicant');
        });
    }
}
