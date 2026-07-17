<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('metric_key');
            $table->string('period')->default('daily');
            $table->date('date');
            $table->json('payload');
            $table->timestamps();

            $table->unique(['metric_key', 'period', 'date']);
            $table->index(['metric_key', 'period', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_metrics');
    }
};
