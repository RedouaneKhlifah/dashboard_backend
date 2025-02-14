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
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('first_name'); // First name of the client
            $table->string('last_name'); // Last name of the client
            $table->string('email')->unique(); // Unique email address
            $table->string('phone'); // Phone number
            $table->string('country'); // Country
            $table->string('city'); // City
            $table->string('address'); // Address
            $table->timestamps();
            $table->softDeletes(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients'); // Drop the table if the migration is rolled back
    }
};