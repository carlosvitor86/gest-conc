<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class CandidatosTemplateExport implements WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'inscricao',
            'nome_completo',
            'nota_final',
            'classificacao_geral',
            'classificacao_cota ',
            'tipo_vaga'
        ];
    }
}