<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashierAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'cashier_id',
        'name',
    ];
}
