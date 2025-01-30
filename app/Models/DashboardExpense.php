<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DashboardExpense extends Model
{
    use HasFactory;
    
    protected $table = 'dashboard_expenses';

    protected $guarded = ['id'];

    public function expense()
    {
        return $this->hasMany(Expense::class, 'dashboard_id');
    }
}
