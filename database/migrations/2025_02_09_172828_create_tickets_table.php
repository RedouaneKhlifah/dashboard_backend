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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partenaire_id')->nullable()->constrained()->nullOnDelete(); // Set to NULL instead of deleting the ticket
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete(); // Set to NULL instead of deleting the ticket
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete(); // Set to NULL instead of deleting the ticket
            $table->integer('number_prints');
            $table->decimal('poids_brut', 10, 2); // Assuming weight in kg or other decimal format
            $table->decimal('poids_tare', 10, 2); // Assuming tare weight in kg or other decimal format
            $table->enum('status', ['ENTRY', 'EXIT']); // Enum for status
            $table->timestamps();
            $table->softDeletes(); // created_at and updated_at
        });

        // Add check constraint to enforce that client_id is NULL when status is ENTRY
        DB::statement('
            ALTER TABLE tickets
            ADD CONSTRAINT client_id_required_for_exit
            CHECK (
                (status = "ENTRY" AND client_id IS NULL) OR
                (status = "EXIT" AND client_id IS NOT NULL)
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the check constraint before dropping the table
        DB::statement('ALTER TABLE tickets DROP CONSTRAINT client_id_required_for_exit');

        Schema::dropIfExists('tickets');
    }
};
