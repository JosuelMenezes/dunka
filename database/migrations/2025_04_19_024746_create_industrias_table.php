<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('industrias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj')->nullable();
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->decimal('comissao', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('industrias');
    }
};
