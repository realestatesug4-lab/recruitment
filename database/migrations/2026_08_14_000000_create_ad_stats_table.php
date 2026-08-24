<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_id')->constrained('placements')->cascadeOnDelete();
            $table->unsignedBigInteger('revive_zone_id')->nullable();
            $table->date('date');
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->float('ctr')->default(0);
            $table->float('ecpm')->default(0);
            $table->timestamps();

            $table->unique(['placement_id', 'date']);
            $table->index('date');
            $table->index('revive_zone_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_stats');
    }
};
