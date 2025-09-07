@extends('admin.layouts.app')

@section('title', 'Novo Chamamento')
@section('page-title', 'Registar Novo Chamamento')

@section('content')
    <form action="{{ route('chamamentos.store') }}" method="POST" class="card">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Vincular ao Concurso</label>
                    <select name="concurso_id" class="form-select" required>
                        <option value="">-- Selecione um concurso --</option>
                        @foreach ($concursos as $concurso)
                            <option value="{{ $concurso->id }}">{{ $concurso->ano }} - {{ $concurso->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Número ou Nome do Chamamento</label>
                    <input type="text" class="form-control" name="numero_chamamento"
                        value="{{ old('numero_chamamento') }}" placeholder="Ex: 1º Chamamento, Convocação 001/2025"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Data de Publicação</label>
                    <input type="date" class="form-control" name="data_publicacao" value="{{ old('data_publicacao') }}"
                        required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prazo para Apresentação</label>
                    <input type="date" class="form-control" name="prazo_apresentacao"
                        value="{{ old('prazo_apresentacao') }}" required>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('chamamentos.index') }}" class="btn">Cancelar</a>
            <button type="submit" class="btn btn-primary">Criar Chamamento</button>
        </div>
    </form>
@endsection
