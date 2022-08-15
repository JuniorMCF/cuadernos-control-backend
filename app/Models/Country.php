<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;


    public function enterprise()
    {
        return $this->hasMany(Enterprise::class, 'country_id');
    }
}
