<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    use HasFactory;
    protected $table = 'company';
    protected $fillable = [ 'company_name', 'person_incharge_name', 'contact_number', 'email_id', 'quotation_templete', 'quotation_templete', 'description', 'created_at', 'updated_at' ];
}
