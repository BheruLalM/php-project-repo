<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crime_records', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();   // format: CRMS-YYYY-XXXXX
            $table->string('crime_type');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->date('date_of_occurrence')->nullable();
            $table->enum('status', ['open', 'under_investigation', 'closed', 'archived'])->default('open');

            // Foreign keys
            $table->foreignId('assigned_officer_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('criminal_id')
                  ->nullable()
                  ->constrained('criminals')
                  ->nullOnDelete();

            $table->timestamp('archived_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crime_records');
    }
};
