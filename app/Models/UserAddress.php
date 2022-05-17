<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_addresses';

    public $fillable = [
        'user_id',
        'country_iso',
        'city',
        'street',
        'house_number',
        'zip_code',
    ];
}
