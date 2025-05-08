<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'lead_customer_details';
    protected $fillable = ['id', 'unique_id', 'customer_id', 'service_address', 'billing_address', 'tax', 'tax_percent', 'amount', 'grand_total', 'schedule_date', 'status', 'created_at', 'updated_at', 'time_of_cleaning', 'quotation_no', 'company_id', 'created_by', 'created_by_name', 'created_at', 'updated_at'];
}
