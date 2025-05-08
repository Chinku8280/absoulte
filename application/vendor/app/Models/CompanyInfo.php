<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;
     protected $table = 'company_info';
     protected $fillable = [
     'customer_id', 'contact_name', 'mobile_no','fax_no','email','created_at','updated_at'
    ];
}
