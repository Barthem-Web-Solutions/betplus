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
        Schema::create('digito_pay_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('withdrawal_id')->unsigned()->index();
            $table->foreign('withdrawal_id')->references('id')->on('withdrawals')->onDelete('cascade');
            $table->string('pix_key');
            $table->string('pix_type');
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('observation')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digito_pay_payments');
    }
};
