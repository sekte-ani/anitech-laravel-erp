<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class);
    }
}

