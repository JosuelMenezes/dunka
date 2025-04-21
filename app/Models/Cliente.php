<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nome',
        'tipo_pessoa',
        'razao_social',
        'documento',
        'ativo',
        'email',
        'email_secundario',
        'contato',
        'telefone',
        'inscricao_estadual',
        'suframa',
        'endereco',
        'informacoes_adicionais',
    ];
    // Adicione também um método helper para verificar se está ativo
public function isAtivo()
{
    return $this->ativo == 1;
}
    // Para facilitar a validação e exibição
    public function isPessoaJuridica()
    {
        return $this->tipo_pessoa === 'J';
    }

    public function isPessoaFisica()
    {
        return $this->tipo_pessoa === 'F';
    }
}
