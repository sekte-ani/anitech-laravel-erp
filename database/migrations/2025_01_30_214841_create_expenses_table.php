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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('item');
            $table->unsignedBigInteger('category_id');
            $table->enum('type', ['Pengeluaran', 'Pemasukan']);
            $table->enum('frequency', ['Project', 'Bulanan', 'Satu Kali', 'Tahunan']);
            $table->decimal('amount', 20, 2);
            $table->decimal('balance', 20, 2);
            $table->unsignedBigInteger('dashboard_id');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('category_expenses')->onDelete('cascade');
            $table->foreign('dashboard_id')->references('id')->on('dashboard_expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
