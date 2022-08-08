<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hours extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'campaign_id', 'register_start', 'register_end', 'ubication_start', 'ubication_end', 'hours', 'type', 'validate'];
}
