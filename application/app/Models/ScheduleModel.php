<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleModel extends Model
{
    use HasFactory;
    protected $table = 'tble_schedule';
    protected $primeryKey ='id';
     //protected $fillable = [ 'id','type','postal_code','unit_no','address','frequency','indefinitely','start_time','end_time','days','created_at', 'updated_at'];

}
