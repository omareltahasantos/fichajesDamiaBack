<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $filliable = ['user_id', 'customerId', 'name', 'description', 'date_start', 'date_end'];

}