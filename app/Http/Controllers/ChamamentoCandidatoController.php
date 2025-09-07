<?php

namespace App\Http\Controllers;

use App\Models\ChamamentoCandidato;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ChamamentoCandidatoController extends Controller
{
    /**
     * Atualiza o status de um candidato dentro de um chamamento e ajusta as vagas.
     */
    public function updateStatus(Request $request, ChamamentoCandidato $chamamentoCandidato): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:Convocado,Apresentou Documentação,Apto para Posse,Tomou Posse,Desistiu,Eliminado',
        ]);

        $statusAntigo = $chamamentoCandidato->status;
        $novoStatus = $request->status;
        $candidato = $chamamentoCandidato->candidato;
        $cargo = $candidato->cargo;

        // Se o status não mudou, não faz nada
        if ($statusAntigo === $novoStatus) {
            return back();
        }
        
        DB::transaction(function () use ($chamamentoCandidato, $statusAntigo, $novoStatus, $candidato, $cargo) {
            // Atualiza o status do candidato no chamamento
            $chamamentoCandidato->status = $novoStatus;
            $chamamentoCandidato->data_status = now();
            $chamamentoCandidato->save();

            // Lógica para decrementar vagas
            if ($novoStatus === 'Tomou Posse') {
                $this->ajustarVaga($cargo, $candidato->tipo_vaga, 'decrementar');
            }

            // Lógica para reverter vagas (ex: anulação de posse)
            if ($statusAntigo === 'Tomou Posse' && ($novoStatus === 'Desistiu' || $novoStatus === 'Eliminado')) {
                $this->ajustarVaga($cargo, $candidato->tipo_vaga, 'incrementar');
            }
        });

        return back()->with('success', 'Status do candidato atualizado com sucesso.');
    }

    /**
     * Função auxiliar para ajustar o contador de vagas.
     *
     * @param Cargo $cargo
     * @param string $tipoVaga
     * @param string $acao 'incrementar' ou 'decrementar'
     */
    protected function ajustarVaga(Cargo $cargo, string $tipoVaga, string $acao): void
    {
        $colunaVaga = '';
        switch ($tipoVaga) {
            case 'PCD':
                $colunaVaga = 'vagas_pcd';
                break;
            case 'Cotas':
                $colunaVaga = 'vagas_cotas';
                break;
            case 'Ampla_concorrencia':
                $colunaVaga = 'vagas_ampla_concorrencia';
                break;
        }

        if (!empty($colunaVaga)) {
            if ($acao === 'incrementar') {
                $cargo->increment($colunaVaga);
            } elseif ($acao === 'decrementar' && $cargo->$colunaVaga > 0) {
                $cargo->decrement($colunaVaga);
            }
        }
    }
}