<?php

// Crie este novo arquivo em: app/Http/Controllers/CargoController.php
namespace App\Http\Controllers;

use App\Models\Concurso;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CargoController extends Controller
{
    /**
     * Mostra o formulário para criar um novo cargo associado a um concurso.
     */
    public function create(Concurso $concurso): View
    {
        return view('admin.cargos.create', compact('concurso'));
    }

    /**
     * Salva um novo cargo no banco de dados.
     */
    public function store(Request $request, Concurso $concurso): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'local_vaga' => 'required|string|max:255',
            'vagas_ampla_concorrencia' => 'required|integer|min:0',
            'vagas_pcd' => 'required|integer|min:0',
            'vagas_cotas' => 'required|integer|min:0',
        ]);

        $concurso->cargos()->create($request->all());

        return redirect()->route('concursos.show', $concurso)
                         ->with('success', 'Cargo cadastrado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um cargo existente.
     */
    public function edit(Cargo $cargo): View
    {
        // A view para este método será criada no próximo artefato
        return view('admin.cargos.edit', compact('cargo'));
    }

    /**
     * Atualiza um cargo existente no banco de dados.
     */
    public function update(Request $request, Cargo $cargo): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'local_vaga' => 'required|string|max:255',
            'vagas_ampla_concorrencia' => 'required|integer|min:0',
            'vagas_pcd' => 'required|integer|min:0',
            'vagas_cotas' => 'required|integer|min:0',
        ]);

        $cargo->update($request->all());

        return redirect()->route('concursos.show', $cargo->concurso_id)
                         ->with('success', 'Cargo atualizado com sucesso!');
    }

    /**
     * Remove um cargo do banco de dados.
     */
    public function destroy(Cargo $cargo): RedirectResponse
    {
        $concurso = $cargo->concurso; // Pega o concurso pai antes de excluir
        $cargo->delete();

        return redirect()->route('concursos.show', $concurso)
                         ->with('success', 'Cargo excluído com sucesso!');
    }

    public function show(Cargo $cargo): View
    {
        $cargo->load([
            'fases' => function ($query) {
                $query->orderBy('ordem', 'asc');
            }, 
            'candidatos' => function ($query) {
                $query->orderBy('classificacao_geral', 'asc');
            }
        ]);
        
        return view('admin.cargos.show', compact('cargo'));
    }
}