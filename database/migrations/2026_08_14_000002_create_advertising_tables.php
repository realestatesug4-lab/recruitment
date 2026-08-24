<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ad Zones - advertising placement slots on the website
        Schema::create('ad_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('revive_zone_id')->nullable()->index();
            $table->string('page_type');
            $table->string('position');
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->string('device_type')->default('all');
            $table->json('supported_formats')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(100);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['page_type', 'device_type']);
        });

        // Ad Packages - pricing tiers for advertising
        Schema::create('ad_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->string('currency')->default('UGX');
            $table->string('billing_type');
            $table->unsignedInteger('impression_limit')->nullable();
            $table->unsignedInteger('click_limit')->nullable();
            $table->unsignedSmallInteger('duration_days')->nullable();
            $table->json('zones_included')->nullable();
            $table->json('supported_formats')->nullable();
            $table->unsignedInteger('daily_impression_limit')->nullable();
            $table->float('discount_tier_1')->nullable();
            $table->float('discount_tier_2')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(100);
            $table->timestamps();
        });

        // Ad Orders - purchase orders from advertisers
        Schema::create('ad_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertiser_id')->constrained('advertisers')->cascadeOnDelete();
            $table->foreignId('package_id')->constrained('ad_packages')->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedInteger('total');
            $table->string('currency')->default('UGX');
            $table->text('notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['advertiser_id', 'status']);
            $table->index('package_id');
        });

        // Ad Invoices - billing documents
        Schema::create('ad_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ad_orders')->cascadeOnDelete();
            $table->string('invoice_number')->unique();
            $table->string('status')->default('draft');
            $table->date('issue_date');
            $table->date('due_date');
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedInteger('total');
            $table->unsignedInteger('amount_paid')->default(0);
            $table->string('currency')->default('UGX');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('invoice_number');
            $table->index(['order_id', 'status']);
        });

        // Ad Payments - payment records
        Schema::create('ad_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('ad_orders')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('ad_invoices')->nullOnDelete();
            $table->unsignedInteger('amount');
            $table->string('currency')->default('UGX');
            $table->string('payment_method');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('status')->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'status']);
            $table->index(['invoice_id', 'status']);
        });

        // Ad Placements - links campaigns to zones (ad_campaigns already created in previous migration)
        Schema::create('ad_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('ad_campaigns')->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('ad_zones')->cascadeOnDelete();
            $table->unsignedBigInteger('revive_campaign_id')->nullable();
            $table->unsignedBigInteger('revive_zone_id')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedSmallInteger('priority')->default(50);
            $table->unsignedSmallInteger('frequency_cap')->nullable();
            $table->json('geotargeting')->nullable();
            $table->json('device_targeting')->nullable();
            $table->json('audience_targeting')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['campaign_id', 'zone_id']);
            $table->index(['campaign_id', 'is_active']);
            $table->index(['zone_id', 'is_active']);
        });

        // Ad Campaign Metrics - daily statistics from Revive
        Schema::create('ad_campaign_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('ad_campaigns')->cascadeOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('ad_zones')->nullOnDelete();
            $table->foreignId('creative_id')->nullable()->constrained('creatives')->nullOnDelete();
            $table->date('date');
            $table->unsignedInteger('requests')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('conversions')->default(0);
            $table->float('ctr')->default(0);
            $table->unsignedSmallInteger('ctr_rank')->nullable();
            $table->float('ecpm')->default(0);
            $table->unsignedInteger('revenue')->default(0);
            $table->timestamp('synced_at')->useCurrent();
            $table->timestamps();
            $table->unique(['campaign_id', 'zone_id', 'date']);
            $table->index(['date', 'campaign_id']);
            $table->index('zone_id');
        });

        // Add the deferred foreign key on ad_campaigns.order_id now that ad_orders exists
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('ad_orders')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ad_campaigns', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
        });

        Schema::dropIfExists('ad_campaign_metrics');
        Schema::dropIfExists('ad_placements');
        Schema::dropIfExists('ad_payments');
        Schema::dropIfExists('ad_invoices');
        Schema::dropIfExists('ad_orders');
        Schema::dropIfExists('ad_packages');
        Schema::dropIfExists('ad_zones');
    }
};
