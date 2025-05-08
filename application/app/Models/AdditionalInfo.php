<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInfo extends Model
{
    use HasFactory;
    protected $table = 'additional_info';
    protected $fillable = ['customer_id','credit_limit','remark','payment_terms','status'];
}
