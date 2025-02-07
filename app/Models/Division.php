<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    protected $guarded = ['id'];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'user_division');
    }
}
