<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendedor_id')->nullable()->constrained('vendedors')->cascadeOnDelete();
            $table->decimal('valor_alvo', 15, 2);                  // meta em R$
            $table->date('inicio');                                // início da vigência
            $table->date('fim');                                   // fim da vigência
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metas');
    }
};
