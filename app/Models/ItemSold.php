<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSold extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_name',
        'price',
        'quantity',
        'total',
        'costumer_name',
        'receipt_number',
        'cashier',
    ];

}
