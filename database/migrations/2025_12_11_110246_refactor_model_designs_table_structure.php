<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Clear existing data since structure is changing completely
        DB::table('model_designs')->truncate();

        Schema::table('model_designs', function (Blueprint $table) {
            // Drop old columns if they exist
            if (Schema::hasColumn('model_designs', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('model_designs', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('model_designs', 'sort_order')) {
                $table->dropColumn('sort_order');
            }
        });

        Schema::table('model_designs', function (Blueprint $table) {
            // Add foreign keys only if they don't exist
            if (!Schema::hasColumn('model_designs', 'industry_id')) {
                $table->foreignId('industry_id')->after('id')->constrained('industries')->onDelete('cascade');
            }
            if (!Schema::hasColumn('model_designs', 'category_id')) {
                $table->foreignId('category_id')->after('industry_id')->constrained('categories')->onDelete('cascade');
            }
            if (!Schema::hasColumn('model_designs', 'product_type_id')) {
                $table->foreignId('product_type_id')->after('category_id')->constrained('product_types')->onDelete('cascade');
            }
            if (!Schema::hasColumn('model_designs', 'shoot_type_id')) {
                $table->foreignId('shoot_type_id')->after('product_type_id')->constrained('shoot_types')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('model_designs', function (Blueprint $table) {
            // Drop foreign keys
            if (Schema::hasColumn('model_designs', 'industry_id')) {
                $table->dropForeign(['industry_id']);
                $table->dropColumn('industry_id');
            }
            if (Schema::hasColumn('model_designs', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            if (Schema::hasColumn('model_designs', 'product_type_id')) {
                $table->dropForeign(['product_type_id']);
                $table->dropColumn('product_type_id');
            }
            if (Schema::hasColumn('model_designs', 'shoot_type_id')) {
                $table->dropForeign(['shoot_type_id']);
                $table->dropColumn('shoot_type_id');
            }
        });

        Schema::table('model_designs', function (Blueprint $table) {
            // Restore old columns
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->integer('sort_order')->default(0)->after('status');
        });
    }
};
