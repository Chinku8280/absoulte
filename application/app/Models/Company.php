<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model {
    use HasFactory;
    protected $table = 'company';
    protected $fillable = [
        'company_name',
        'person_incharge_name',
        'contact_number',
        'email_id',
        'description',
        'company_address',
        'website',
        'telephone',
        'fax',
        'co_register_no',
        'gst_register_no',
        'short_name',
        'bank_name',
        'ac_number',
        'bank_code',
        'branch_code',
        'uen_no',
        'company_logo',
        'qr_code',
        'created_by',
        'created_by_name'
    ];
}
