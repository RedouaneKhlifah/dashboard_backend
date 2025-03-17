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

        // Use a trigger instead of CHECK constraint
        DB::unprepared('
            CREATE TRIGGER check_client_id_before_insert BEFORE INSERT ON tickets
            FOR EACH ROW
            BEGIN
                IF (NEW.status = "EXIT" AND NEW.client_id IS NULL) THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "client_id is required for EXIT";
                END IF;
            END;
        ');

        DB::unprepared('
            CREATE TRIGGER check_client_id_before_update BEFORE UPDATE ON tickets
            FOR EACH ROW
            BEGIN
                IF (NEW.status = "EXIT" AND NEW.client_id IS NULL) THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "client_id is required for EXIT";
                END IF;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Drop triggers
        DB::unprepared('DROP TRIGGER IF EXISTS check_client_id_before_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS check_client_id_before_update');

        Schema::dropIfExists('tickets');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
