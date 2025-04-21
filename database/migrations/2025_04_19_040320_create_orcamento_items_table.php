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
    Schema::create('orcamento_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
        $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2);
        $table->decimal('total', 10, 2);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_items');
    }
};
