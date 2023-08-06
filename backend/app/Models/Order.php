<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['amount', 'users_id', 'address_id', 'delivery_id', 'payments_id', 'products_id'];
    
    use HasFactory;
}
