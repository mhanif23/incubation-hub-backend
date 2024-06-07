<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('type', 'admin');
        });
    }
}
