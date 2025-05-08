<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalContact extends Model
{
    use HasFactory;
    protected $table = 'additional_contact';
     protected $fillable = [
     'customer_id', 'contact_name', 'mobile_no','created_by','updated_at'
    ];
}
