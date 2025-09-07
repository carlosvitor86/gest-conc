@extends('admin.layouts.app')

@section('title', 'Gerir Cargo')
@section('page-title', 'Gerir Cargo: ' . $cargo->nome)

@section('page-actions')
    <a href="{{ route('concursos.show', $cargo->concurso_id) }}" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
        Voltar para o Concurso
    </a>
@endsection

@section('content')
<!-- A gestão de candidatos foi movida para o seu próprio menu. Esta página foca-se apenas na gestão de fases. -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Fases do Processo Seletivo</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Defina as etapas sequenciais que os candidatos deste cargo deverão cumprir.</p>
        <div class="row g-4 mt-2">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h3 class="card-title">Adicionar Nova Fase</h3></div>
                    <div class="card-body">
                        <form action="{{ route('cargos.fases.store', $cargo) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nome da Fase</label>
                                <input type="text" name="nome" class="form-control" placeholder="Ex: Exame Médico" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ordem de Execução</label>
                                <input type="number" name="ordem" class="form-control" placeholder="1" min="1" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Adicionar Fase</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                 <div class="card">
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Ordem</th>
                                    <th>Nome da Fase</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cargo->fases->sortBy('ordem') as $fase)
                                    <tr>
                                        <td><span class="badge bg-blue-lt">{{ $fase->ordem }}</span></td>
                                        <td>{{ $fase->nome }}</td>
                                        <td>
                                            <a href="#" class="btn btn-icon btn-danger" title="Excluir Fase" onclick="event.preventDefault(); if(confirm('Tem a certeza?')) document.getElementById('delete-fase-{{ $fase->id }}').submit();">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                            <form action="{{ route('fases.destroy', $fase) }}" id="delete-fase-{{ $fase->id }}" method="POST" style="display: none;"> @csrf @method('DELETE') </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">Nenhuma fase registada.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>
@endsection
