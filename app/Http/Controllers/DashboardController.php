<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concurso;
use App\Models\Candidato;
use App\Models\Cargo;
use App\Models\ChamamentoCandidato;
use App\Models\Fase;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // --- Estatísticas Globais para os Cards ---
        $totalConcursosAtivos = Concurso::where('status', 'Ativo')->count();
        $totalCandidatos = Candidato::count();
        $totalEmpossados = ChamamentoCandidato::where('status', 'Tomou Posse')->count();
        $totalVagas = Cargo::sum(DB::raw('vagas_ampla_concorrencia + vagas_pcd + vagas_cotas'));
        $vagasRemanescentes = $totalVagas - $totalEmpossados;

        // --- Dados para Gráficos ---

        // Gráfico 1: Concursos por Ano (sempre global)
        $concursosPorAnoData = Concurso::query()->select('ano', DB::raw('count(*) as total'))->groupBy('ano')->orderBy('ano', 'desc')->limit(5)->pluck('total', 'ano');

        // Gráfico 2: Candidatos por Fase (interativo)
        $concursosParaFiltro = Concurso::where('status', 'Ativo')->orderBy('ano', 'desc')->get();
        $selectedConcursoId = $request->input('concurso_id_filtro');
        $fasesData = collect();

        if ($selectedConcursoId) {
            // CORREÇÃO: Adicionada a coluna 'fases.ordem' ao select e ao groupBy
            $fasesData = DB::table('candidato_fase')
                ->join('fases', 'candidato_fase.fase_id', '=', 'fases.id')
                ->join('cargos', 'fases.cargo_id', '=', 'cargos.id')
                ->where('cargos.concurso_id', $selectedConcursoId)
                ->select('fases.nome', 'fases.ordem', DB::raw('COUNT(DISTINCT candidato_fase.chamamento_candidato_id) as total'))
                ->groupBy('fases.nome', 'fases.ordem') // Agrupa também pela ordem
                ->orderBy('fases.ordem')
                ->pluck('total', 'nome');
        }

        return view('admin.dashboard', [
            // Dados dos Cards
            'totalConcursosAtivos' => $totalConcursosAtivos,
            'totalCandidatos' => $totalCandidatos,
            'totalEmpossados' => $totalEmpossados,
            'vagasRemanescentes' => $vagasRemanescentes,

            // Dados do Gráfico de Concursos
            'concursosPorAnoLabels' => $concursosPorAnoData->keys(),
            'concursosPorAnoValores' => $concursosPorAnoData->values(),

            // Dados do Gráfico de Fases (Interativo)
            'concursosParaFiltro' => $concursosParaFiltro,
            'selectedConcursoId' => $selectedConcursoId,
            'fasesLabels' => $fasesData->keys(),
            'fasesValores' => $fasesData->values(),
        ]);
    }
}
