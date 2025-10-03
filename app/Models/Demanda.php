<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    use HasFactory;

    protected $table = '_tb_demanda';

    protected $fillable = [
        'fo',
        'cliente',
        'transportadora',
        'doca',
        'tipo',
        'quantidade',
        'peso',
        'valor_carga',
        'hora_agendada',
        'entrada',
        'saida',
        'status',
        'veiculo'
    ];
    
    public function history()
{
    return $this->hasMany(\App\Models\DemandaHistory::class, 'demanda_id');
}
}
