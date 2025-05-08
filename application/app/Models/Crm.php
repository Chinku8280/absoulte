<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crm extends Model
{
    use HasFactory;
    protected $table = 'customers';
     protected $fillable = [
     'customer_type','person_incharge_name', 'nick_name','mobile_number','fax_number','email','credit_limit','remark','payment_terms','status','uen','group_company_name','individual_company_name','saluation',
     'territory','language_spoken','cleaning_type','customer_remark','additional_info_status','created_by','customer_name','default_address','branch_name', 'lead_source', 'pending_invoice_limit', 'renewal'
    ];
    public function serviceAddress()
    {
        return $this->hasOne(ServiceAddress::class, 'customer_id', 'id');
    }
    
}