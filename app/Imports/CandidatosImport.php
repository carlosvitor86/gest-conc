<?php

// Salve ou substitua em: app/Imports/CandidatosImport.php

namespace App\Imports;

use App\Models\Candidato;
use App\Models\Fase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use Illuminate\Validation\Rule;
use App\Models\Cargo;
use App\Models\ChamamentoCandidato;
use App\Models\Chamamento;
use App\Models\Concurso;

class CandidatosImport implements OnEachRow, WithHeadingRow
{
    private int $cargoId;
    private int $concursoId;
    private ?Fase $primeiraFase;

    public function __construct(int $cargoId, int $concursoId)
    {
        $this->cargoId = $cargoId;
        $this->concursoId = $concursoId;
        // Pega a primeira fase do cargo para usar em todas as linhas
        $this->primeiraFase = Fase::where('cargo_id', $this->cargoId)->orderBy('ordem', 'asc')->first();
    }

    public function onRow(Row $row)
    {
        $data = $row->toArray();

        // Validação manual de cada linha
        $validator = Validator::make(
            $data,
            [
                'inscricao' => ['required', Rule::unique('candidatos')->where('concurso_id', $this->concursoId)],
                'classificacao_geral' => ['required', 'integer', Rule::unique('candidatos')->where('cargo_id', $this->cargoId)],
                'nome_completo' => 'required|string',
                'nota_final' => 'required|numeric',
                'tipo_vaga' => 'required|in:PCD,Cotas,Ampla_concorrencia',
            ],
            [
                'inscricao.unique' => "A inscrição {$data['inscricao']} já existe neste concurso.",
                'classificacao_geral.unique' => "A classificação {$data['classificacao_geral']} já existe para este cargo.",
                'inscricao.required' => 'O campo inscrição é obrigatório.',
                'nome_completo.required' => 'O campo nome completo é obrigatório.',
                'nota_final.required' => 'O campo nota final é obrigatório.',
                'tipo_vaga.required' => 'O campo tipo de vaga é obrigatório.',
            ],
        );

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw new \Exception("Erro na linha {$row->getIndex()}: " . implode(', ', $errors));
        }

        DB::transaction(function () use ($data) {
            // 1. Cria o Candidato
            $candidato = Candidato::create([
                'concurso_id' => $this->concursoId,
                'cargo_id' => $this->cargoId,
                'inscricao' => $data['inscricao'],
                'nome_completo' => $data['nome_completo'],
                'nota_final' => $data['nota_final'],
                'classificacao_geral' => $data['classificacao_geral'],
                'classificacao_cota' => $data['classificacao_cota'] ?? null,
                'tipo_vaga' => $data['tipo_vaga'],
            ]);

            // 2. Se existe uma primeira fase, associa o candidato a ela
            if ($this->primeiraFase) {
                $chamamento = $candidato->cargo->concurso->chamamentos()->firstOrCreate(['numero_chamamento' => 'Chamamento Principal'], ['data_publicacao' => now(), 'prazo_apresentacao' => now()->addDays(30)]);

                $chamamentoCandidato = $chamamento->chamamentoCandidato()->create([
                    'candidato_id' => $candidato->id,
                    'status' => 'Convocado',
                ]);

                $chamamentoCandidato->fases()->create([
                    'fase_id' => $this->primeiraFase->id,
                    'status' => 'pendente',
                    'observacoes' => 'Candidato importado e colocado automaticamente na fase inicial.',
                ]);
            }
        });
    }
}
