<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('banner');
            $table->string('format')->default('image');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('image_url')->nullable();
            $table->string('click_url')->nullable();
            $table->string('cta_text')->nullable();
            $table->text('html')->nullable();
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->string('status')->default('active');
            $table->json('metadata')->nullable();
            $table->unsignedBigInteger('external_banner_id')->nullable();
            $table->unsignedTinyInteger('weight')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('creatives');
    }
};