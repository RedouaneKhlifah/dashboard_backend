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
            $table->id(); // Auto-incrementing primary key
            $table->boolean('display_on_desktop')->default(false); // Boolean flag for desktop visibility
            $table->string('name'); // Product name
            $table->string('sku')->nullable()->default(''); // Optional SKU
            $table->string('unit')->default('kg'); // Unit of measurement (default: 'kg')
            $table->decimal('sale_price', 8, 2); // Selling price (8 digits total, 2 decimal places)
            $table->decimal('cost_price', 8, 2)->nullable()->default(0); // Optional cost price
            $table->text('description')->nullable()->default(''); // Optional description
            $table->decimal('tax', 8, 2)->nullable()->default(0); // Optional tax amount or percentage
            $table->decimal('stock', 8, 2)->default(0); // Current inventory quantity (default: 0)
            $table->decimal('reorder_point', 8, 2)->default(0); // Reorder alert threshold (default: 0)
            $table->timestamps();
            $table->softDeletes(); // Created at and updated at timestamps
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