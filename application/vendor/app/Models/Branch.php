<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branch';
    protected $fillable = [
     'uen', 'branch_name', 'personan_incharge_name','nick_name','mobile_number','fax_number','email','address','postal_code','country','created_at','updated_at'
    ];
}
