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
        Schema::table('table_statistics_for_executors', function (Blueprint $table) {
            $table->unsignedInteger('view_profile')->default(0)->after('click_contacts'); // Добавляем новую колонку после 'click_contacts'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_statistics_for_executors', function (Blueprint $table) {
            $table->dropColumn('view_profile'); // Удаляем колонку при откате
        });
    }
};
