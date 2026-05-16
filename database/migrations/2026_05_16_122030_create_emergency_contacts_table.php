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
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');          // Police, Fire, Hospital
            $table->string('number');        // 911, mobile number, etc
            $table->string('icon')->nullable(); // Ionicons name
            $table->string('color')->nullable(); // UI color (optional)
            $table->integer('priority')->default(0); // ordering
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
    }
};
