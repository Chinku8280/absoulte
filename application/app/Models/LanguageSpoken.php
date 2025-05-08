<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageSpoken extends Model {
    use HasFactory;
    protected $table = 'language_spoken';
    protected $fillable = [ 'language_name' ];
}
