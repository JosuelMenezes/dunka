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
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
        $table->foreignId('vendedor_id')->constrained()->onDelete('cascade');
        $table->date('data');
        $table->string('status')->default('Aberto');
        $table->text('observacoes')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
