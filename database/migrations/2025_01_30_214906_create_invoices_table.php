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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Internal', 'Order']);
            $table->unsignedBigInteger('order_id')->nullable();
            $table->date('date');
            $table->integer('invoice_number');
            $table->decimal('subtotal', 20, 2);
            $table->decimal('discount', 20, 2);
            $table->decimal('total', 20, 2);
            $table->decimal('amount_due', 20, 2);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
