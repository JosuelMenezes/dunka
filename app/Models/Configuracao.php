<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = [
        'nome_fantasia', 'cnpj', 'ie', 'telefone', 'email', 'endereco', 'logomarca'
    ];
}
