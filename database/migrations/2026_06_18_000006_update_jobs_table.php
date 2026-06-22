<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'company_id')) {
                $table->foreignId('company_id')->nullable()->constrained('companies')->after('id');
            }
            if (!Schema::hasColumn('jobs', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('title');
            }
            if (!Schema::hasColumn('jobs', 'description')) {
                $table->longText('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('jobs', 'job_type')) {
                $table->string('job_type')->default('full-time')->after('description');
            }
            if (!Schema::hasColumn('jobs', 'experience_level')) {
                $table->string('experience_level')->nullable()->after('type');
            }
            if (!Schema::hasColumn('jobs', 'location')) {
                $table->string('location')->nullable()->after('experience_level');
            }
            if (!Schema::hasColumn('jobs', 'salary_min')) {
                $table->decimal('salary_min', 12, 0)->nullable()->after('location');
            }
            if (!Schema::hasColumn('jobs', 'salary_max')) {
                $table->decimal('salary_max', 12, 0)->nullable()->after('salary_min');
            }
            if (!Schema::hasColumn('jobs', 'status')) {
                $table->string('status')->default('draft')->after('salary_max');
            }
            if (!Schema::hasColumn('jobs', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('jobs', 'deadline')) {
                $table->timestamp('deadline')->nullable()->after('published_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['company_id']);
        });
    }
};
