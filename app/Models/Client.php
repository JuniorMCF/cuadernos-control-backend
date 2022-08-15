<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable =[
        'first_name',
        'last_name',
        'contact_email',
        'phone_number_one',
        'phone_number_two',
        'dni',
        'enterprise_id',

    ];
}
