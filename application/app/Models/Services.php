<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $fillable = [ 'company','service_name','man_power','product_code','hour_session','weekly_freq','total_session','price','status','description','created_at','updated_at', 'created_by', 'created_by_name'];
}
