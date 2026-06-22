<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_skill', function (Blueprint $table) {
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
            $table->timestamps();

            $table->primary(['job_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_skill');
    }
};
