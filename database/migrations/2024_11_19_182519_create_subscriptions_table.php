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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Связь с пользователями
            $table->enum('payment_status', ['pending', 'paid', 'expired'])->default('pending'); // Статус оплаты
            $table->timestamp('payment_expiry')->nullable(); // Срок действия подписки
            $table->string('order_id')->nullable();
            $table->timestamps();  // created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
