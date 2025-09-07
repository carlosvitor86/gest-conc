<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Reclassificacao;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReclassificacaoController extends Controller
{
    /**
     * Exibe a lista de solicitações de reclassificação pendentes.
     */
    public function index(): View
    {
        $solicitacoes = Reclassificacao::where('status', 'solicitado')
                                      ->with(['candidato.cargo.concurso'])
                                      ->latest()
                                      ->get();
                                      
        return view('admin.reclassificacoes.index', compact('solicitacoes'));
    }

    /**
     * Armazena uma nova solicitação de reclassificação.
     */
    public function store(Request $request, Candidato $candidato): RedirectResponse
    {
        $request->validate([
            'motivo' => 'required|string',
            'documento' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ]);

        $data = $request->only('motivo');
        $data['candidato_id'] = $candidato->id;

        if ($request->hasFile('documento')) {
            $path = $request->file('documento')->store('reclassificacoes', 'public');
            $data['documento_path'] = $path;
        }

        Reclassificacao::create($data);

        return redirect()->route('candidatos.show', $candidato)
                         ->with('success', 'Solicitação de reclassificação registada com sucesso.');
    }

    /**
     * Aprova ou rejeita uma solicitação de reclassificação.
     */
    public function update(Request $request, Reclassificacao $reclassificacao): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:aprovado,rejeitado',
            'nova_classificacao_geral' => 'nullable|integer|required_if:status,aprovado',
        ]);

        $reclassificacao->status = $request->status;
        $reclassificacao->aprovado_por_user_id = Auth::id();
        $reclassificacao->data_aprovacao = now();

        if ($request->status === 'aprovado') {
            $candidato = $reclassificacao->candidato;
            $candidato->classificacao_geral = $request->nova_classificacao_geral;
            
            if ($request->filled('nova_classificacao_cota')) {
                $candidato->classificacao_cota = $request->nova_classificacao_cota;
            }
            
            $candidato->save();
            $reclassificacao->nova_classificacao_geral = $request->nova_classificacao_geral;
            $reclassificacao->save();
            
            return redirect()->route('reclassificacoes.index')
                             ->with('success', 'Reclassificação aprovada e classificação do candidato atualizada.');
        }

        $reclassificacao->save();

        return redirect()->route('reclassificacoes.index')
                         ->with('success', 'Solicitação de reclassificação rejeitada.');
    }
}

