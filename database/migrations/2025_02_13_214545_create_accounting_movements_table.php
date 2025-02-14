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
        Schema::create('accounting_movements', function (Blueprint $table) {
            $table->id();
            $table->decimal('debit', 10, 2)->nullable();  // Si el movimiento es DEBE
            $table->decimal('credit', 10, 2)->nullable(); // Si el movimiento es HABER
            $table->foreignId('accounting_code_id')->constrained()->restrictOnDelete();
            $table->foreignId('accounting_entry_id')->constrained()->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_movements', function (Blueprint $table) {
            $table->dropForeign(['accounting_code_id']);
            $table->dropForeign(['accounting_entry_id']);
        });
        Schema::dropIfExists('accounting_movements');
    }
};
