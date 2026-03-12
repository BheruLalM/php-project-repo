<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criminals', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('alias')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('physical_markers')->nullable();  // scars, tattoos, height, etc.
            $table->string('photo_path')->nullable();      // stored via Storage facade
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['wanted', 'arrested', 'released', 'deceased'])->default('wanted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criminals');
    }
};
