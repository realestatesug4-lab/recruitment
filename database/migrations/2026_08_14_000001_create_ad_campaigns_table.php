<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained('advertisers')->cascadeOnDelete();
            // order_id FK is deferred to the next migration (ad_orders doesn't exist yet)
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->unsignedInteger('budget')->default(0);
            $table->unsignedInteger('budget_spent')->default(0);
            $table->unsignedInteger('impression_goal')->nullable();
            $table->unsignedInteger('click_goal')->nullable();
            $table->unsignedInteger('conversion_goal')->nullable();
            $table->string('type')->default('CPM');
            $table->unsignedBigInteger('revive_campaign_id')->nullable()->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['advertiser_id', 'status']);
            $table->index(['status', 'starts_at', 'ends_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_campaigns');
    }
};
