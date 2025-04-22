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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description', 512);
            $table->string('category');
            $table->float('price', 2);
            $table->float('discount_percentage', 2);
            $table->float('rating', 2);
            $table->integer('stock');
            $table->string('brand');
            $table->string('sku');
            $table->integer('weight');
            $table->float('width', 2);
            $table->float('height', 2);
            $table->float('depth', 2);
            $table->string('warranty_information');
            $table->string('shipping_information');
            $table->string('availability_status');
            $table->string('return_policy');
            $table->integer('minimum_order_quantity');
            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
