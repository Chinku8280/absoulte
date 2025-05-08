<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAddress extends Model
{
    use HasFactory;
       protected $table = 'service_address';
     protected $fillable = [
     'customer_id','postal_code', 'address','unit_number','person_incharge_name','zone','contact_no','email_id','territory','created_at','updated_at'
    ];
}
