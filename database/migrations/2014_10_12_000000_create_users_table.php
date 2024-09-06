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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable();
            $table->string('tel')->unique()->nullable();
            $table->text('name')->nullable();
            $table->text('surname')->nullable();
            $table->text('surname_2')->nullable();
            $table->text('nickname')->nullable();
            $table->boolean('nickname_true')->default(0);
            $table->text('site')->nullable();
            $table->text('instagram')->nullable();
            $table->text('whatsapp')->nullable();
            $table->longText('categories_id')->nullable();
            $table->bigInteger('cities_id')->nullable();
            $table->integer('cost_from')->nullable();
            $table->integer('cost_up')->nullable();
            $table->text('password')->nullable();
            $table->text('details')->nullable();
            $table->text('about_yourself')->nullable();
            $table->longText('language')->nullable();
            $table->text('photos')->nullable();
            $table->longText('gallery')->nullable();
            $table->text('role')->nullable();
            $table->string('verification_code', 4)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
