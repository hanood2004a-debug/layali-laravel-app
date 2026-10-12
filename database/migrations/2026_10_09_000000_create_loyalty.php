<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $t) {
            $t->id();
            $t->string('phone', 20)->unique();      // رقم الجوال بعد التوحيد (0598...)
            $t->string('name', 100);
            $t->string('card_code', 12)->unique();   // رمز سري للاطلاع على النقاط
            $t->integer('points')->default(0);
            $t->unsignedInteger('lifetime_points')->default(0);
            $t->decimal('total_spent', 10, 2)->default(0);
            $t->unsignedInteger('visits')->default(0);
            $t->timestamps();
        });

        Schema::create('loyalty_transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('order_id')->nullable();
            $t->string('type', 12); // earn | redeem | adjust | reverse
            $t->integer('points');
            $t->string('note')->nullable();
            $t->timestamps();
            $t->index(['customer_id', 'created_at']);
        });

        Schema::table('orders', function (Blueprint $t) {
            $t->unsignedBigInteger('customer_id')->nullable()->index();
            $t->unsignedInteger('points_earned')->default(0);
            $t->unsignedInteger('points_redeemed')->default(0);
            $t->decimal('discount', 8, 2)->default(0);
            $t->boolean('loyalty_awarded')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->dropIndex(['customer_id']);
            $t->dropColumn(['customer_id', 'points_earned', 'points_redeemed', 'discount', 'loyalty_awarded']);
        });
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('customers');
    }
};
