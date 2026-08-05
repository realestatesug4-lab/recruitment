<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type')->default('CPC');
            $table->string('objective')->nullable();
            $table->decimal('budget_total', 16, 2)->default(0);
            $table->decimal('budget_spent', 16, 2)->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('status')->default('active');
            $table->unsignedTinyInteger('priority')->default(50);
            $table->json('targeting')->nullable();
            $table->unsignedBigInteger('external_campaign_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};