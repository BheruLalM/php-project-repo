<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complainant_name');
            $table->text('contact');    // stored encrypted via model mutator
            $table->text('statement');
            $table->enum('status', ['open', 'under_investigation', 'closed'])->default('open');

            $table->foreignId('assigned_officer_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->foreignId('crime_record_id')
                  ->nullable()
                  ->constrained('crime_records')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
