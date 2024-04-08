<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
class WebhookController extends Controller
{
    public function orderCreate(REQUEST $request){
        $data = $this->verify_webhook();
        if(!empty($data)){
            $orderData = json_decode($data,true);
            Order::create([
                'shopify_order_id' => $orderData['id'],
                'admin_graphql_api_id' => $orderData['admin_graphql_api_id'],
                'app_id' => $orderData['app_id'],
                'buyer_accepts_marketing' => $orderData['buyer_accepts_marketing'],
                'browser_ip' => $orderData['browser_ip'],
                'checkout_id' => $orderData['checkout_id'],
                'checkout_token' => $orderData['checkout_token'],
                'confirmed' => $orderData['confirmed'],
                'currency' => $orderData['currency'],
                'created_at' => $orderData['created_at'],
                'updated_at' => $orderData['updated_at'],
                'total_price' => $orderData['total_price'],
                'total_discounts' => $orderData['total_discounts'],
                'total_line_items_price' => $orderData['total_line_items_price'],
                'total_tip_received' => $orderData['total_tip_received'],
                'total_weight' => $orderData['total_weight'],
                'financial_status' => $orderData['financial_status'],
                'fulfillment_status' => $orderData['fulfillment_status'],
                'order_status_url' => $orderData['order_status_url'],
            ]);
        } 
    }

    function verify_webhook(){
        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
        $data = file_get_contents('php://input');
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, env('SHOPIFY_ADMIN_API_SECRET'), true));
        if ($hmac_header == $calculated_hmac) {
            return $data;
        } else {
            return [];
        }
    }
}
