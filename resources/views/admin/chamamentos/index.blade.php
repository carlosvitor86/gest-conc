@extends('admin.layouts.app')

@section('title', 'Chamamentos')
@section('page-title', 'Gerir Chamamentos e Convocações')

@section('page-actions')
    <a href="{{ route('chamamentos.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M12 5l0 14"></path>
            <path d="M5 12l14 0"></path>
        </svg>
        Novo Chamamento
    </a>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Histórico de Chamamentos</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Número do Chamamento</th>
                        <th>Concurso Vinculado</th>
                        <th>Data de Publicação</th>
                        <th>Prazo para Apresentação</th>
                        <th class="w-1"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($chamamentos as $chamamento)
                        <tr>
                            <td>{{ $chamamento->numero_chamamento }}</td>
                            <td class="text-muted">{{ $chamamento->concurso->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($chamamento->data_publicacao)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($chamamento->prazo_apresentacao)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('chamamentos.show', $chamamento) }}" class="btn btn-sm">Gerir
                                    Convocados</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum chamamento registado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center">
            {{ $chamamentos->links() }}
        </div>
    </div>
@endsection
