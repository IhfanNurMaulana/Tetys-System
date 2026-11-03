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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('nis', 30)->unique();
        $table->string('name', 100);
        $table->enum('gender', ['L', 'P']);
        $table->string('place_of_birth', 50);
        $table->date('date_of_birth');
        $table->foreignId('grade_id');
        $table->string('photo', 100)->nullable();
        $table->decimal('balance', 10, 2)->default(0.00);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
