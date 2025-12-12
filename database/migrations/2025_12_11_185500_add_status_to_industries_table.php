<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('industries', function (Blueprint $table) {
            // Check if column exists first to be safe, though error suggests it doesn't
            if (!Schema::hasColumn('industries', 'status')) {
                $table->boolean('status')->default(true)->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('industries', function (Blueprint $table) {
            if (Schema::hasColumn('industries', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
