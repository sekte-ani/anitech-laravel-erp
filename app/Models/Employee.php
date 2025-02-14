<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $guarded = ['id'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_employee');
    }

    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'user_division');
    }

    public function user()
    {
        return $this->hasOne(related: User::class);
    }
}
