<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends User
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('applicant', function (Builder $builder) {
            $builder->where('type', 'applicant');
        });
    }
}
