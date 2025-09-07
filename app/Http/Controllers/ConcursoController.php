<?php

namespace App\Http\Controllers;

use App\Models\Concurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConcursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $concursos = Concurso::latest()->paginate(10);
        return view('admin.concursos.index', compact('concursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.concursos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|digits:4',
            'orgao' => 'required|string|max:255',
            'data_homologacao' => 'required|date',
            'banca_organizadora' => 'required|string|max:255',
            'status' => 'required|in:Ativo,Concluído,Suspenso',
            'edital' => 'nullable|file|mimes:pdf|max:5120', // PDF de até 5MB
        ]);

        $data = $request->except('edital');

        if ($request->hasFile('edital')) {
            $path = $request->file('edital')->store('editais', 'public');
            $data['edital_path'] = $path;
        }

        Concurso::create($data);

        return redirect()->route('concursos.index')->with('success', 'Concurso criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Concurso $concurso): View
    {
        // Carrega o concurso com seus cargos e fases para a view de detalhes
        $concurso->load('cargos.fases');
        return view('admin.concursos.show', compact('concurso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Concurso $concurso): View
    {
        return view('admin.concursos.edit', compact('concurso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Concurso $concurso): RedirectResponse
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|digits:4',
            'orgao' => 'required|string|max:255',
            'data_homologacao' => 'required|date',
            'banca_organizadora' => 'required|string|max:255',
            'status' => 'required|in:Ativo,Concluído,Suspenso',
            'edital' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $data = $request->except('edital');

        if ($request->hasFile('edital')) {
            // Deleta o edital antigo se existir
            if ($concurso->edital_path) {
                Storage::disk('public')->delete($concurso->edital_path);
            }
            $path = $request->file('edital')->store('editais', 'public');
            $data['edital_path'] = $path;
        }

        $concurso->update($data);

        return redirect()->route('concursos.index')->with('success', 'Concurso atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Concurso $concurso): RedirectResponse
    {
        // Deleta o arquivo do edital antes de deletar o registro
        if ($concurso->edital_path) {
            Storage::disk('public')->delete($concurso->edital_path);
        }

        $concurso->delete();

        return redirect()->route('concursos.index')->with('success', 'Concurso excluído com sucesso.');
    }
}
