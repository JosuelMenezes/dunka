<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pedido_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pedido_id')->constrained()->onDelete('cascade');
        $table->foreignId('produto_id')->constrained()->onDelete('cascade');
        $table->integer('quantidade');
        $table->decimal('preco_unitario', 10, 2);
        $table->decimal('total', 10, 2);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
