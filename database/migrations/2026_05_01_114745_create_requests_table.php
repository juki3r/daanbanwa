<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            // 🔐 link to user (IMPORTANT for token auth system)
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('full_name');
            $table->integer('age');
            $table->string('gender');
            $table->text('address');

            $table->string('document_type');
            $table->text('purpose');

            // Business Clearance optional fields
            $table->string('company_name')->nullable();
            $table->string('business_nature')->nullable();

            // admin control
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
