<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $t) {
            $t->id();
            $t->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $t->string('name', 60);
            $t->unsignedTinyInteger('rating');
            $t->string('comment', 500)->nullable();
            $t->boolean('approved')->default(false); // لا يظهر إلا بعد موافقة الإدارة
            $t->string('ip_hash', 64)->nullable();   // لمنع التكرار فقط (لا نخزّن IP صريح)
            $t->timestamps();
            $t->index(['menu_item_id', 'approved']);
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('reviews');
    }
};
