<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facture_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facture_id')->nullable()->constrained()->nullOnDelete();
            // Make product_id nullable since we are using nullOnDelete()
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price_unitaire', 10, 2);
            $table->integer('quantity');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facture_product');
    }
};
