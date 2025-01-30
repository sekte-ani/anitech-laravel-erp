<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryExpense extends Model
{
    use HasFactory;

    protected $table = 'category_expenses';

    protected $guarded = ['id'];
}
