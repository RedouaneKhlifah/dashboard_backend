<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete(); 
            $table->string('reference');
            $table->date('devis_date');
            $table->date('expiration_date');
            $table->decimal('tva', 5, 2);
            $table->enum('remise_type', ['PERCENT', 'FIXED']);
            $table->decimal('remise', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down()
     {
         Schema::dropIfExists('devis');
     }
};
