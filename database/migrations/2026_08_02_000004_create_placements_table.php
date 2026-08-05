<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('context');
            $table->string('position')->default('inline');
            $table->json('device_targeting')->nullable();
            $table->json('audience')->nullable();
            $table->unsignedBigInteger('revive_zone_id')->nullable();
            $table->string('status')->default('active');
            $table->unsignedSmallInteger('sort_order')->default(100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placements');
    }
};