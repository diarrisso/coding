<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teasers', function (Blueprint $table) {
             $table->renameColumn('image_path', 'image_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teasers', function (Blueprint $table) {
            $table->renameColumn('image_name', 'image_path');
        });
    }
};
