<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('national_id')->unique();
            $table->string('address');
            $table->string('city');
            $table->date('date_of_engagement');
            $table->decimal('price_per_hour', 8, 2);
            $table->string('cnss_number');
            $table->date('birth_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
