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
            $table->date('tanggal');
            $table->string('item');
            $table->unsignedBigInteger('category_id');
            $table->enum('tipe', ['Pengeluaran', 'Pemasukan']);
            $table->enum('sumber', ['Project', 'Bulanan', 'Satu Kali', 'Tahunan']);
            $table->decimal('jumlah', 20, 2);
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
