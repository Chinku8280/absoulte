<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadUpdateStatus extends Model
{
    use HasFactory;
      protected $table = 'lead_update_status';
    protected $fillable = [ 'id','lead_id','deposite_type','quotation_templete','comment','created_at', 'updated_at'];

}
