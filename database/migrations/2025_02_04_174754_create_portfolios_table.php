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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('category_portfolios')->noActionOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('client')->nullable();
            $table->string('url')->nullable();
            $table->string('images')->nullable();
            $table->foreignId('created_by')->constrained('users')->noActionOnDelete();
            $table->foreignId('updated_by')->constrained('users')->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
