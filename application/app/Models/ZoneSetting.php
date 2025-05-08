<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneSetting extends Model
{
    use HasFactory;
    protected $table = 'zone_settings';
    protected $fillable = [ 'zone_name','postal_code','zone_color','status','created_at','updated_at' ];
}
