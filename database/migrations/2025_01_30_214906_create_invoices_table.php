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
            $table->date('date');
            $table->string('invoice_number', 50);
            $table->decimal('subtotal', 20, 2);
            $table->decimal('discount', 20, 2);
            $table->decimal('total', 20, 2);
            $table->decimal('amount_due', 20, 2);
            $table->string('notes')->nullable();
            $table->enum('status', ['Pending', 'Paid', 'Cancelled'])->default('Pending');
            $table->timestamps();
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
