@extends('admin.layouts.app')

@section('title', 'Lista de Concursos')

@section('page-title', 'Concursos')
@section('page-actions')
    <a href="{{ route('concursos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
        Novo Concurso
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ano</th>
                        <th>Órgão</th>
                        <th>Status</th>
                        <th>Data de Homologação</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($concursos as $concurso)
                        <tr>
                            <td>{{ $concurso->nome }}</td>
                            <td class="text-muted">{{ $concurso->ano }}</td>
                            <td class="text-muted">{{ $concurso->orgao }}</td>
                            <td>
                                @if($concurso->status == 'Ativo')
                                  <span class="badge bg-success me-1"></span> Ativo
                                @elseif($concurso->status == 'Concluído')
                                  <span class="badge bg-secondary me-1"></span> Concluído
                                @else
                                  <span class="badge bg-warning me-1"></span> Suspenso
                                @endif
                            </td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($concurso->data_homologacao)->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('concursos.show', $concurso) }}" class="btn btn-icon btn-primary" title="Visualizar" data-bs-toggle="tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6s-6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6s6.6 2 9 6" /></svg>
                                    </a>
                                    <a href="{{ route('concursos.edit', $concurso) }}" class="btn btn-icon btn-warning" title="Editar" data-bs-toggle="tooltip">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                    </a>
                                    <a href="#" class="btn btn-icon btn-danger" title="Excluir" data-bs-toggle="tooltip" onclick="event.preventDefault(); if(confirm('Tem certeza que deseja excluir este concurso?')) { document.getElementById('delete-form-{{ $concurso->id }}').submit(); }">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </a>
                                    <form id="delete-form-{{ $concurso->id }}" action="{{ route('concursos.destroy', $concurso) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Nenhum concurso encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $concursos->links() }}
    </div>
</div>
@endsection