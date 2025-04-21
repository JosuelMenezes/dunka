<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('ean')->nullable();
            $table->string('ncm')->nullable();
            $table->decimal('preco', 10, 2);
            $table->integer('estoque')->default(0);
            $table->foreignId('industria_id')->constrained('industrias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
}
