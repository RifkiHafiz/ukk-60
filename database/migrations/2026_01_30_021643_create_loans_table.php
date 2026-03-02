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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->text('image')->nullable();
            $table->string('loan_code');
            $table->foreignId('borrower_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->integer('quantity');
            $table->date('loan_date');
            $table->date('return_date')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['submitted', 'approved', 'borrowed', 'waiting', 'returned', 'rejected'])->default('submitted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
