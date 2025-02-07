<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDivision extends Model
{
    use HasFactory;

    protected $table = 'user_division';

    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
