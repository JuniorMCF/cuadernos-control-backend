<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable =[
        "client_id",
        "client_name",
        "service_id",
        "service_name",
        'enterprise_id',
        'price_actual',
        'quantity',
        'tax',
        'tax_aplicated',
        'discount',
        'sid',
        'registered_amount',
        'invoice_id',
        'invoice',
        "status",
        "serie",
        "client_description"
    ];
}
