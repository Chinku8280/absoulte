<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadPaymentInfo extends Model
{
    use HasFactory;
      protected $table = 'lead_payment_detail';
    protected $fillable = [ 'id','lead_id','payment_type','total_amount','advance_amount','file','created_at', 'updated_at'];
}
