<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadPriceInfo extends Model
{
    use HasFactory;
    protected $table = 'lead_price_info';
    protected $fillable = [ 'id','lead_id','deposite_type','date_of_cleaning','time_of_cleaning','created_at', 'updated_at'];
}
