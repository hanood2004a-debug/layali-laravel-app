<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $t) {
            $t->text('details')->nullable()->after('description');       // شرح الوجبة
            $t->string('ingredients', 300)->nullable()->after('details'); // المكوّنات مفصولة بفاصلة
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', fn (Blueprint $t) => $t->dropColumn(['details', 'ingredients']));
    }
};
