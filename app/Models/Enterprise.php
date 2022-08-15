<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;

    protected $fillable=[
        'logo',
        'name',
        'address',
        'latitude',
        'longitude',
        'ruc',
        'country_id',
        'coin_id',
        'user_id',
        'color',
        'dpto',
        'province',
        'district',
        'banco',
        'propietary',
        'nro_cta',
        'email',
        'phone_contact_one',
        'phone_contact_two',
        'created_at',
        'updated_at'
    ];


    public function coins()
    {
        return $this->hasMany(EnterpriseCoins::class, 'coin_id');
    }
    public function countries()
    {
        return $this->hasMany(EnterpriseCountry::class, 'country_id');
    }
}
