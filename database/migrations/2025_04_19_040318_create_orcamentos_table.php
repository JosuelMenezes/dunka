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
    Schema::create('orcamentos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        $table->foreignId('vendedor_id')->constrained('vendedors')->onDelete('cascade');
        $table->date('data')->default(now());
        $table->text('observacoes')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos');
    }
};
