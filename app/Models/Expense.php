<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    protected $guarded = ['id'];

    protected static function boot() {
        parent::boot();
    
        static::created(function ($expense) {
            Audit::create([
                'user_id' => Auth::id(),
                'expenses_id' => $expense->id,
                'action' => 'insert',
                'new_data' => json_encode($expense->attributesToArray()),
                'ip_address' => request()->ip(),
            ]);
        });
    
        static::updated(function ($expense) {
            Audit::create([
                'user_id' => Auth::id(),
                'expenses_id' => $expense->id,
                'action' => 'update',
                'old_data' => json_encode($expense->getOriginal()),
                'new_data' => json_encode($expense->getDirty()),
                'ip_address' => request()->ip(),
            ]);
        });
    
        static::deleted(function ($expense) {
            Audit::create([
                'user_id' => Auth::id(),
                'expenses_id' => $expense->id,
                'action' => 'delete',
                'old_data' => json_encode($expense->getOriginal()),
                'new_data' => null,
                'ip_address' => request()->ip(),
            ]);
        });
    }

    public function audit()
    {
        return $this->hasMany(Audit::class);
    }

    public function dashboard()
    {
        return $this->belongsTo(DashboardExpense::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryExpense::class, 'category_id');
    }
}
