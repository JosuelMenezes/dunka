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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->decimal('desconto_percentual', 5, 2)->default(0)->after('observacoes');
            $table->decimal('desconto_valor', 10, 2)->default(0)->after('desconto_percentual');
            $table->decimal('valor_total', 10, 2)->default(0)->after('desconto_valor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn(['desconto_percentual', 'desconto_valor', 'valor_total']);
        });
    }
};
