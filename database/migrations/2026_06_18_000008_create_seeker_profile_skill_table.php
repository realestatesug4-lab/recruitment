<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seeker_profile_skill', function (Blueprint $table) {
            $table->foreignId('seeker_profile_id')->constrained('seeker_profiles')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('skills')->onDelete('cascade');
            $table->timestamps();

            $table->primary(['seeker_profile_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seeker_profile_skill');
    }
};
