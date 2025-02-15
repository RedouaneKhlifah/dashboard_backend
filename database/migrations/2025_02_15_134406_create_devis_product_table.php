<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devis_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('price_unitaire', 10, 2);
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devis_product');
    }
};