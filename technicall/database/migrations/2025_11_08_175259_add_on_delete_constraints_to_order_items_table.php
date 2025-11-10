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
        Schema::table('order_items', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['product_id']);
            $table->dropForeign(['photo_id']);

            // Re-add with onDelete('restrict') to prevent deletion of products/photos that are in orders
            $table->foreign('product_id')->references('id')->on('products')->onDelete('restrict');
            $table->foreign('photo_id')->references('id')->on('photos')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the restricted foreign keys
            $table->dropForeign(['product_id']);
            $table->dropForeign(['photo_id']);

            // Restore original foreign keys without onDelete
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('photo_id')->references('id')->on('photos');
        });
    }
};
