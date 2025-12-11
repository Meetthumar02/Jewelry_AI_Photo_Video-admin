<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('industries', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('industries', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        Schema::table('product_types', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
