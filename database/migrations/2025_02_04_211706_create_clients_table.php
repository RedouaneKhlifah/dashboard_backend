<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('company')->unique(); // company_ice of the client
            $table->string('ice')->unique(); // Unique ICE number
            $table->string('email')->unique(); // Unique email address
            $table->string('phone'); // Phone number
            $table->string('country'); // Country
            $table->string('city'); // City
            $table->string('address'); // Address
            $table->timestamps();
            $table->softDeletes(); // Created at and updated at timestamps
        });
    }


    public function down(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        
        // Optionally, if you need to drop a check constraint and your MySQL version supports it,
        // use the DROP CHECK syntax. (Otherwise, you may omit this if the table is being dropped.)
        // DB::statement('ALTER TABLE tickets DROP CHECK client_id_required_for_exit');
        
        Schema::dropIfExists('clients'); // Drop the table if the migration is rolled back

        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
    
};