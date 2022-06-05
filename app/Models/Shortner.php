<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shortner extends Model
{
    use HasFactory;
    protected $table = 'shortners';
    protected $fillable = [
        'short_url','long_url','total_visit'
    ];
}