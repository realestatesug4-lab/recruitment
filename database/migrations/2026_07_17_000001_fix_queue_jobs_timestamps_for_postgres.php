<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('queue_jobs')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE queue_jobs ALTER COLUMN reserved_at TYPE bigint USING NULL');
            DB::statement('ALTER TABLE queue_jobs ALTER COLUMN available_at TYPE bigint USING NULL');
            DB::statement('ALTER TABLE queue_jobs ALTER COLUMN created_at TYPE bigint USING NULL');
        } else {
            Schema::table('queue_jobs', function (Blueprint $table) {
                $table->integer('reserved_at')->nullable()->change();
                $table->integer('available_at')->change();
                $table->integer('created_at')->change();
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('queue_jobs')) {
            return;
        }

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE queue_jobs ALTER COLUMN reserved_at TYPE timestamp USING NULL');
            DB::statement('ALTER TABLE queue_jobs ALTER COLUMN available_at TYPE timestamp USING NULL');
            DB::statement('ALTER TABLE queue_jobs ALTER COLUMN created_at TYPE timestamp USING NULL');
        }
    }
};
