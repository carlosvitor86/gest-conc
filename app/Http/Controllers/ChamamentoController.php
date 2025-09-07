<?php

namespace App\Http\Controllers;

use App\Models\Chamamento;
use App\Models\Concurso;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Candidato;

class ChamamentoController extends Controller
{
    /**
     * Exibe uma lista de todos os chamamentos.
     */
    public function index(): View
    {
        $chamamentos = Chamamento::with('concurso')->latest()->paginate(15);
        return view('admin.chamamentos.index', compact('chamamentos'));
    }

    /**
     * Mostra o formulário para criar um novo chamamento.
     */
    public function create(): View
    {
        $concursos = Concurso::where('status', 'Ativo')->orderBy('nome')->get();
        return view('admin.chamamentos.create', compact('concursos'));
    }

    /**
     * Armazena um novo chamamento na base de dados.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'concurso_id' => 'required|exists:concursos,id',
            'numero_chamamento' => 'required|string|max:255',
            'data_publicacao' => 'required|date',
            'prazo_apresentacao' => 'required|date|after_or_equal:data_publicacao',
        ]);

        Chamamento::create($request->all());

        return redirect()->route('chamamentos.index')->with('success', 'Chamamento criado com sucesso.');
    }

           /**
     * Exibe os detalhes de um chamamento específico e os candidatos disponíveis/convocados.
     */
    public function show(Chamamento $chamamento): View
    {
        // Carrega o chamamento com as suas relações
        $chamamento->load('concurso', 'chamamentoCandidato.candidato');

        // Obtém os IDs dos candidatos já convocados neste chamamento
        $idsCandidatosConvocados = $chamamento->chamamentoCandidato->pluck('candidato_id');

        // Obtém todos os candidatos do concurso que ainda não foram convocados para ESTE chamamento
        $candidatosDisponiveis = Candidato::where('concurso_id', $chamamento->concurso_id)
                                            ->whereNotIn('id', $idsCandidatosConvocados)
                                            ->orderBy('classificacao_geral', 'asc')
                                            ->get();

        return view('admin.chamamentos.show', compact('chamamento', 'candidatosDisponiveis'));
    }

    /**
     * Adiciona os candidatos selecionados a um chamamento.
     */
    public function adicionarCandidatos(Request $request, Chamamento $chamamento): RedirectResponse
    {
        $request->validate([
            'candidato_ids' => 'required|array|min:1',
            'candidato_ids.*' => 'exists:candidatos,id',
        ]);

        foreach ($request->candidato_ids as $candidatoId) {
            // Cria a entrada na tabela pivô 'chamamento_candidato'
            $chamamento->chamamentoCandidato()->create([
                'candidato_id' => $candidatoId,
                'status' => 'Convocado', // Status inicial
            ]);
        }

        return redirect()->route('chamamentos.show', $chamamento)
                         ->with('success', count($request->candidato_ids) . ' candidato(s) convocado(s) com sucesso!');
    }
}
