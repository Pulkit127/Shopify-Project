<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'shopify_order_id',
        'admin_graphql_api_id',
        'app_id',
        'buyer_accepts_marketing',
        'browser_ip',
        'checkout_id',
        'checkout_token',
        'confirmed',
        'currency',
        'created_at',
        'updated_at',
        'total_price',
        'total_discounts',
        'total_line_items_price',
        'total_tip_received',
        'total_weight',
        'financial_status',
        'fulfillment_status',
        'order_status_url',
    ];

}
