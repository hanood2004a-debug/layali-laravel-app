<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $t) {
            $t->string('payment_method')->default('cash')->after('status'); // cash | card | transfer
            $t->boolean('paid')->default(false)->after('payment_method');
            $t->timestamp('paid_at')->nullable()->after('paid');
        });

        Schema::create('expenses', function (Blueprint $t) {
            $t->id();
            $t->date('spent_at');
            $t->string('title', 120);
            $t->string('category', 40);
            $t->decimal('amount', 10, 2);
            $t->string('notes')->nullable();
            $t->timestamps();
            $t->index('spent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::table('orders', fn (Blueprint $t) => $t->dropColumn(['payment_method', 'paid', 'paid_at']));
    }
};
