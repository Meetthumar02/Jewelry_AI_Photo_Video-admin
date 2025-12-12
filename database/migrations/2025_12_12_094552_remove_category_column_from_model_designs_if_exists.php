<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_designs', function (Blueprint $table) {
            // Remove category column if it exists (conflicts with relationship)
            if (Schema::hasColumn('model_designs', 'category')) {
                $table->dropColumn('category');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_designs', function (Blueprint $table) {
            // Optionally restore the column
            // $table->string('category')->nullable();
        });
    }
};
