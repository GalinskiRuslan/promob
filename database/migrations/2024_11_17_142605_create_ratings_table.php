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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Пользователь, который ставит оценку
            $table->unsignedBigInteger('rated_user_id'); // Пользователь, которому ставят оценку
            $table->tinyInteger('rating')->unsigned(); // Оценка (например, от 1 до 5)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('rated_user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['user_id', 'rated_user_id']); // Гарантирует, что одна пара user_id и rated_user_id уникальна
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
