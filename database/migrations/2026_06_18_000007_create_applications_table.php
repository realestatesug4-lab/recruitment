<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('seeker_id')->constrained('users')->onDelete('cascade');
            $table->text('cover_letter')->nullable();
                $table->string('resume_path')->nullable();
                $table->decimal('match_score', 5, 2)->nullable();
                $table->timestamp('applied_at')->nullable();
            $table->string('status')->default('submitted');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamps();

            $table->unique(['job_id', 'seeker_id']);
            $table->index('seeker_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
