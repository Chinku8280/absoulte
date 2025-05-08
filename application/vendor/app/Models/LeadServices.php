<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadServices extends Model {
    use HasFactory;
    protected $table = 'lead_services_detail';
    protected $fillable = [ 'id', 'service_id', 'lead_id', 'name', 'description', 'unit_price', 'quantity', 'discount', 'gross_amount', 'created_at', 'updated_at' ];
}
