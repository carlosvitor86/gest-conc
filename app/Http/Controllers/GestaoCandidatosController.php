<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concurso;
use App\Models\Cargo;
use App\Models\Candidato;
use App\Models\Fase;
use App\Models\CandidatoFase;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GestaoCandidatosController extends Controller
{
    /**
     * Exibe a página de gestão de candidatos com filtros.
     */
    public function index(Request $request): View
    {
        $concursos = Concurso::orderBy('ano', 'desc')->get();
        $cargos = collect();
        $candidatos = collect();
        $fases = collect();
        
        $selectedConcursoId = $request->query('concurso_id');
        $selectedCargoId = $request->query('cargo_id');

        if ($selectedConcursoId) {
            $cargos = Cargo::where('concurso_id', $selectedConcursoId)->orderBy('nome')->get();
        }

        if ($selectedCargoId) {
            $fases = Fase::where('cargo_id', $selectedCargoId)->orderBy('ordem')->get();
            
            $candidatos = Candidato::where('cargo_id', $selectedCargoId)
                ->with(['chamamentoCandidato.fases' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }, 'chamamentoCandidato.fases.fase'])
                ->orderBy('classificacao_geral', 'asc')
                ->get();
        }

        return view('admin.gestao_candidatos.index', compact(
            'concursos', 'cargos', 'candidatos', 'fases',
            'selectedConcursoId', 'selectedCargoId'
        ));
    }

    /**
     * Retorna os cargos de um concurso para popular o dropdown dinâmico.
     */
    public function getCargos(Concurso $concurso): JsonResponse
    {
        return response()->json($concurso->cargos()->orderBy('nome')->get());
    }

    /**
     * Valida o status dos candidatos selecionados na sua fase atual.
     */
    public function validarStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'candidato_ids' => 'required|array|min:1',
            'candidato_ids.*' => 'exists:candidatos,id',
            'status_fase' => 'required|in:apto,inapto,apto_condicional',
            'documento_validacao' => 'required|file|mimes:pdf|max:5120',
            'observacao_validacao' => 'nullable|string',
        ]);

        $documentoPath = $request->file('documento_validacao')->store('documentos_validacao', 'public');

        foreach ($request->candidato_ids as $candidatoId) {
            $candidato = Candidato::find($candidatoId);
            $ultimaFase = $candidato->chamamentoCandidato()->latest()->first()->fases()->latest()->first();

            if ($ultimaFase) {
                $ultimaFase->status = $request->status_fase;
                $ultimaFase->documento_path = $documentoPath;
                $ultimaFase->observacoes = $request->observacao_validacao;
                $ultimaFase->processed_by_user_id = Auth::id(); // Auditoria
                $ultimaFase->save();
            }
        }
        
        return back()->with('success', count($request->candidato_ids) . ' candidato(s) tiveram o seu status atualizado.');
    }
    
    /**
     * Promove os candidatos selecionados para a próxima fase.
     */
    public function promover(Request $request): RedirectResponse
    {
        // ... (lógica de promoção quase idêntica, apenas adiciona observação e auditoria) ...
        $request->validate([
            'candidato_ids' => 'required|array|min:1',
            'candidato_ids.*' => 'exists:candidatos,id',
            'fase_destino_id' => 'required|exists:fases,id',
            'documento_formalizacao' => 'required|file|mimes:pdf|max:5120',
            'observacao_promocao' => 'nullable|string',
        ]);

        $faseDestino = Fase::find($request->fase_destino_id);
        $documentoPath = $request->file('documento_formalizacao')->store('documentos_promocao', 'public');
        
        foreach ($request->candidato_ids as $candidatoId) {
            // ... (lógica para verificar se o candidato está apto) ...

            // Cria a nova fase com os campos adicionais
             $novaFase = new CandidatoFase([
                'fase_id' => $faseDestino->id,
                'status' => 'pendente',
                'documento_path' => $documentoPath,
                'observacoes' => $request->observacao_promocao,
                'processed_by_user_id' => Auth::id(), // Auditoria
            ]);
            // ... (associa a nova fase ao candidato)
        }

        return back()->with('success', 'Candidatos promovidos com sucesso!');
    }
}
