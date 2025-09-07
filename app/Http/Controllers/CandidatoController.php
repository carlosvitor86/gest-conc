<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Fase;
use App\Models\Candidato;
use App\Imports\CandidatosImport;
use App\Exports\CandidatosTemplateExport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFileType;
use Maatwebsite\Excel\Validators\ValidationException;
use PDF;

class CandidatoController extends Controller
{
    /**
     * Processa a importação da planilha de candidatos.
     */
    public function import(Request $request, Cargo $cargo): RedirectResponse
    {
        $request->validate([
            'arquivo_candidatos' => 'required|file|mimes:xlsx,csv,xls',
        ]);

        // Validação prévia para garantir que o cargo tem fases.
        $temFases = Fase::where('cargo_id', $cargo->id)->exists();
        if (!$temFases) {
            return back()->withErrors(['Não é possível importar. O cargo selecionado não possui fases cadastradas.']);
        }

        try {
            Excel::import(new CandidatosImport($cargo->id, $cargo->concurso_id), $request->file('arquivo_candidatos'));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors([$e->getMessage()]);
        }

        return redirect()
            ->route('gestao.candidatos.index', [
                'concurso_id' => $cargo->concurso_id,
                'cargo_id' => $cargo->id,
            ])
            ->with('status', 'import-success');
    }

    /**
     * Faz o download da planilha modelo para importação.
     */
    public function downloadTemplate()
    {
        return Excel::download(new CandidatosTemplateExport(), 'modelo_importacao_candidatos.xlsx', ExcelFileType::XLSX);
    }

    /**
     * Mostra o formulário para editar um candidato.
     */
    public function edit(Candidato $candidato): View
    {
        return view('admin.candidatos.edit', compact('candidato'));
    }

    /**
     * Atualiza os dados de um candidato no banco.
     */
    public function update(Request $request, Candidato $candidato): RedirectResponse
    {
        $request->validate([
            'nome_completo' => 'required|string|max:255',
            'nota_final' => 'required|numeric',
            'classificacao_geral' => 'required|integer',
            'classificacao_cota' => 'nullable|integer',
            'tipo_vaga' => 'required|in:PCD,Cotas,Ampla_concorrencia',
        ]);

        $candidato->update($request->all());

        return redirect(route('cargos.show', $candidato->cargo_id) . '#tab-candidatos')->with('success', 'Candidato atualizado com sucesso!');
    }

    /**
     * Remove um candidato do banco de dados.
     */
    public function destroy(Candidato $candidato): RedirectResponse
    {
        $cargoId = $candidato->cargo_id;
        $candidato->delete();

        return redirect(route('cargos.show', $cargoId) . '#tab-candidatos')->with('success', 'Candidato removido com sucesso!');
    }

    public function show(Candidato $candidato): View
    {
        // Carrega o histórico completo de fases, incluindo o nome da fase e o nome do utilizador que processou
        $candidato->load(['cargo.concurso', 'chamamentoCandidato.fases.fase', 'chamamentoCandidato.fases.processedBy']);
        return view('admin.candidatos.show', compact('candidato'));
    }

    public function downloadHistoricoPDF(Candidato $candidato)
    {
        // Carrega as relações necessárias para o histórico
        $candidato->load(['cargo.concurso', 'chamamentoCandidato.fases.fase', 'chamamentoCandidato.fases.processedBy']);

        // Gera o PDF a partir de uma view dedicada
        $pdf = PDF::loadView('admin.candidatos.historico_pdf', compact('candidato'));

        // Define o nome do ficheiro e transmite-o para o navegador
        return $pdf->stream('historico_' . $candidato->inscricao . '.pdf');
    }
}
