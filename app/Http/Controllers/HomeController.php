<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
class HomeController extends Controller
{
    public function handleOrderWebhook(REQUEST $request)
    {
        $this->validateShopifyWebhook($request);
        $data = $request->json()->all();
        $orderData = $data;

        // Store order data in the database
        $order = new Order();
        $order->shopify_order_id = $orderData['id'];
        $order->admin_graphql_api_id = $orderData['admin_graphql_api_id'];
        $order->app_id = $orderData['app_id'];
        $order->buyer_accepts_marketing = $orderData['buyer_accepts_marketing'];
        $order->browser_ip = $orderData['browser_ip'];
        $order->checkout_id = $orderData['checkout_id'];
        $order->checkout_token = $orderData['checkout_token'];
        $order->confirmed = $orderData['confirmed'];
        $order->currency = $orderData['currency'];
        $order->created_at = $orderData['created_at'];
        $order->updated_at = $orderData['updated_at'];
        $order->total_price = $orderData['total_price'];
        $order->total_discounts = $orderData['total_discounts'];
        $order->total_line_items_price = $orderData['total_line_items_price'];
        $order->total_tip_received = $orderData['total_tip_received'];
        $order->total_weight = $orderData['total_weight'];
        $order->financial_status = $orderData['financial_status'];
        $order->fulfillment_status = $orderData['fulfillment_status'];
        $order->order_status_url = $orderData['order_status_url'];

        // Add more fields as needed
        $order->save();
    }

}
