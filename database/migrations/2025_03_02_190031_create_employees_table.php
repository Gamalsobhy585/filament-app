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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('address');
            $table->char('zipcode', 10);
            $table->date('date_of_birth');
            $table->date('date_of_hired');
            $table->foreignId('country_id')->constrained('countries')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('state_id')->constrained('states')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
