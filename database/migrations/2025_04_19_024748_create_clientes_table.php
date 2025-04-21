<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            // Alterando campo existente
            $table->renameColumn('cpf_cnpj', 'documento'); // Renomear para um nome mais genérico

            // Novos campos
            $table->enum('tipo_pessoa', ['F', 'J'])->default('F')->after('nome'); // F = Física, J = Jurídica
            $table->string('razao_social')->nullable()->after('tipo_pessoa');
            $table->string('nome_fantasia')->nullable()->after('razao_social');
            $table->string('email_secundario')->nullable()->after('email');
            $table->string('inscricao_estadual')->nullable()->after('email_secundario');
            $table->string('suframa')->nullable()->after('inscricao_estadual');
            $table->text('informacoes_adicionais')->nullable()->after('endereco');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->renameColumn('documento', 'cpf_cnpj');
            $table->dropColumn([
                'tipo_pessoa',
                'razao_social',
                'nome_fantasia',
                'email_secundario',
                'inscricao_estadual',
                'suframa',
                'informacoes_adicionais'
            ]);
        });
    }
};
