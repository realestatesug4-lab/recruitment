<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add the minimum useful indexes for admin dashboards, job browsing,
     * and application status queries without over-indexing the database.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_super_admin')) {
                $table->boolean('is_super_admin')->default(false)->after('role');
            }

            $table->index(['is_super_admin', 'role'], 'idx_users_admin_lookup');
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->index(['company_id', 'status', 'published_at'], 'idx_job_posts_company_status_published');
            $table->index(['status', 'published_at'], 'idx_job_posts_status_published');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'idx_applications_status_created');
            $table->index(['seeker_id', 'status'], 'idx_applications_seeker_status');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->index(['verification_status', 'updated_at'], 'idx_companies_verification_updated');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_admin_lookup');
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->dropIndex('idx_job_posts_company_status_published');
            $table->dropIndex('idx_job_posts_status_published');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('idx_applications_status_created');
            $table->dropIndex('idx_applications_seeker_status');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex('idx_companies_verification_updated');
        });
    }
};
