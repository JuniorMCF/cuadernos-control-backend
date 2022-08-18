<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable =[
        "client_id",
        "client_name",
        "product_id",
        "product_name",
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
