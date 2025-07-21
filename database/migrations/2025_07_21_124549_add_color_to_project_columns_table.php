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
        Schema::table('project_columns', function (Blueprint $table) {
            $table->string('color', 7)->default('#e2e8f0')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_columns', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
