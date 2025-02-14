<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $guarded = ['id'];

    public function order()
    {
        return $this->hasMany(Order::class, 'order_id');
    }
}
