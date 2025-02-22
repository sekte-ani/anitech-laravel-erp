<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseArchive extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $guarded = ['id'];

    public function audit()
    {
        return $this->hasMany(Audit::class);
    }

    public function dashboard()
    {
        return $this->belongsTo(DashboardExpense::class, 'dashboard_id');
    }

    public function category()
    {
        return $this->belongsTo(CategoryExpense::class, 'category_id');
    }
}
