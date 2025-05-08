<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;
     protected $table = 'billing_address';
     protected $fillable = [
     'customer_id', 'postal_code', 'address','unit_number','created_at','updated_at'
    ];
}
