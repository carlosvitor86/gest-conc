<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Fase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule; // Importe a classe Rule

class FaseController extends Controller
{
    /**
     * Salva uma nova fase para um cargo.
     */
    public function store(Request $request, Cargo $cargo): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ordem' => [
                'required',
                'integer',
                'min:1',
                // Garante que a ordem é única apenas para o cargo atual
                Rule::unique('fases')->where(function ($query) use ($cargo) {
                    return $query->where('cargo_id', $cargo->id);
                }),
            ],
        ], [
            // Mensagem de erro personalizada
            'ordem.unique' => 'O número de ordem informado já está em uso para este cargo.'
        ]);

        $cargo->fases()->create($request->all());

        return redirect()->route('cargos.show', $cargo)
                         ->with('success', 'Fase adicionada com sucesso!');
    }

    /**
     * Remove uma fase do banco de dados.
     */
    public function destroy(Fase $fase): RedirectResponse
    {
        $cargo = $fase->cargo;
        $fase->delete();

        return redirect()->route('cargos.show', $cargo)
                         ->with('success', 'Fase removida com sucesso!');
    }
}
