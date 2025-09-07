@extends('admin.layouts.app')

@section('title', 'Novo Concurso')
@section('page-title', 'Cadastrar Novo Concurso')

@section('content')
<form action="{{ route('concursos.store') }}" method="POST" enctype="multipart/form-data" class="card">
    @csrf
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nome do Concurso</label>
                <input type="text" class="form-control" name="nome" value="{{ old('nome') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Ano</label>
                <input type="number" class="form-control" name="ano" value="{{ old('ano', date('Y')) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Data de Homologação</label>
                <input type="date" class="form-control" name="data_homologacao" value="{{ old('data_homologacao') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Órgão</label>
                <input type="text" class="form-control" name="orgao" value="{{ old('orgao') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Banca Organizadora</label>
                <input type="text" class="form-control" name="banca_organizadora" value="{{ old('banca_organizadora') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Ativo" @if(old('status') == 'Ativo') selected @endif>Ativo</option>
                    <option value="Concluído" @if(old('status') == 'Concluído') selected @endif>Concluído</option>
                    <option value="Suspenso" @if(old('status') == 'Suspenso') selected @endif>Suspenso</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Edital (PDF)</label>
                <input type="file" class="form-control" name="edital" accept=".pdf">
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('concursos.index') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Salvar Concurso</button>
    </div>
</form>
@endsection