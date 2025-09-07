@extends('admin.layouts.app')

@section('title', 'Detalhes do Concurso')
@section('page-title', 'Detalhes do Concurso')

@section('page-actions')
    <a href="{{ route('concursos.index') }}" class="btn">
      Voltar para a Lista
    </a>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Cargos do Concurso</h3>
                <div class="card-actions">
                    <a href="{{ route('concursos.cargos.create', $concurso) }}" class="btn btn-primary">
                        Novo Cargo
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Cargo</th>
                                <th>Vagas (Ampla)</th>
                                <th>Vagas (PCD)</th>
                                <th>Vagas (Cotas)</th>
                                <th>Local</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($concurso->cargos as $cargo)
                                <tr>
                                    <td>
                                        <a href="{{ route('cargos.show', $cargo) }}" class="text-reset" title="Gerenciar Fases do Cargo">{{ $cargo->nome }}</a>
                                    </td>
                                    <td>{{ $cargo->vagas_ampla_concorrencia }}</td>
                                    <td>{{ $cargo->vagas_pcd }}</td>
                                    <td>{{ $cargo->vagas_cotas }}</td>
                                    <td>{{ $cargo->local_vaga }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <a href="{{ route('cargos.edit', $cargo) }}" class="btn btn-icon btn-warning" title="Editar" data-bs-toggle="tooltip">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                            <a href="#" class="btn btn-icon btn-danger" title="Excluir" data-bs-toggle="tooltip" onclick="event.preventDefault(); if(confirm('Tem certeza que deseja excluir este cargo?')) { document.getElementById('delete-cargo-form-{{ $cargo->id }}').submit(); }">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </a>
                                            <form id="delete-cargo-form-{{ $cargo->id }}" action="{{ route('cargos.destroy', $cargo) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">Nenhum cargo cadastrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informações Gerais</h3>
                <div class="card-actions">
                    <a href="{{ route('concursos.edit', $concurso) }}" class="btn btn-sm btn-primary">Editar</a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-5">Nome:</dt>
                    <dd class="col-7">{{ $concurso->nome }}</dd>
                    <dt class="col-5">Ano:</dt>
                    <dd class="col-7">{{ $concurso->ano }}</dd>
                    <dt class="col-5">Órgão:</dt>
                    <dd class="col-7">{{ $concurso->orgao }}</dd>
                    <dt class="col-5">Banca:</dt>
                    <dd class="col-7">{{ $concurso->banca_organizadora }}</dd>
                    <dt class="col-5">Homologação:</dt>
                    <dd class="col-7">{{ \Carbon\Carbon::parse($concurso->data_homologacao)->format('d/m/Y') }}</dd>
                    <dt class="col-5">Status:</dt>
                    <dd class="col-7">{{ $concurso->status }}</dd>
                    <dt class="col-5">Edital:</dt>
                    <dd class="col-7">
                        @if($concurso->edital_path)
                            <a href="{{ Storage::url($concurso->edital_path) }}" target="_blank">Visualizar PDF</a>
                        @else
                            Nenhum edital anexado.
                        @endif
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection