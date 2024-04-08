<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('shopify_order_id')->nullable();
            $table->string('admin_graphql_api_id')->nullable();
            $table->bigInteger('app_id')->nullable();
            $table->boolean('buyer_accepts_marketing');
            $table->string('browser_ip')->nullable();
            $table->bigInteger('checkout_id')->nullable();
            $table->string('checkout_token')->nullable();
            $table->boolean('confirmed');
            $table->string('currency');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->decimal('total_price', 8, 2);
            $table->decimal('total_discounts', 8, 2);
            $table->decimal('total_line_items_price', 8, 2);
            $table->decimal('total_tip_received', 8, 2);
            $table->decimal('total_weight', 8, 2);
            $table->string('financial_status');
            $table->string('fulfillment_status');
            $table->string('order_status_url')->nullable();
            // Add more fields as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
