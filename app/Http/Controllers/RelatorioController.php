<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concurso;
use App\Models\Cargo;
use App\Models\Candidato;
use Illuminate\View\View;
use PDF;

class RelatorioController extends Controller
{
    /**
     * Exibe a página principal de relatórios com os filtros.
     */
    public function index(): View
    {
        $concursos = Concurso::orderBy('ano', 'desc')->get();
        return view('admin.relatorios.index', compact('concursos'));
    }

    /**
     * Gera e transmite um relatório em PDF com a lista de todos os aprovados de um cargo.
     */
    public function exportarAprovadosPDF(Request $request)
    {
        $request->validate([
            'concurso_id' => 'required|exists:concursos,id',
            'cargo_id' => 'required|exists:cargos,id',
        ]);

        $cargo = Cargo::with('concurso')->find($request->cargo_id);
        $candidatos = Candidato::where('cargo_id', $cargo->id)
                                ->orderBy('classificacao_geral', 'asc')
                                ->get();

        $pdf = PDF::loadView('admin.relatorios.aprovados_pdf', compact('cargo', 'candidatos'));
        
        return $pdf->stream('aprovados_' . $cargo->nome . '.pdf');
    }
}
