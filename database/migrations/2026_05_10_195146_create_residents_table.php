<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();

            // =====================
            // PERSONAL INFORMATION
            // =====================
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->enum('sex', ['Male', 'Female']);
            $table->date('birth_date');
            $table->enum('civil_status', ['Single', 'Married', 'Widow', 'Separated']);

            // =====================
            // LOCATION INFORMATION
            // =====================
            $table->string('purok');
            $table->string('house_number');
            $table->string('street')->nullable();

            // =====================
            // FAMILY INFORMATION (SINGLE TABLE ONLY)
            // =====================
            $table->string('household_name');
            $table->enum('relationship_to_head', ['Head', 'Spouse', 'Child', 'Relative']);

            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('guardian_name')->nullable();

            // =====================
            // CONTACT INFORMATION
            // =====================
            $table->string('contact_number')->nullable();

            // =====================
            // SOCIO-ECONOMIC DATA
            // =====================
            $table->string('occupation')->nullable();

            // =====================
            // FLAGS
            // =====================
            $table->boolean('is_voter')->default(false);
            $table->boolean('is_pwd')->default(false);
            $table->string('pwd_type')->nullable();

            // =====================
            // STATUS
            // =====================
            $table->enum('resident_status', ['Active', 'Moved Out', 'Deceased'])
                ->default('Active');

            // =====================
            // TIMESTAMPS
            // =====================
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
