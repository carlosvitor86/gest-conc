@extends('admin.layouts.app')

@section('title', 'Editar Concurso')
@section('page-title', 'Editar Concurso: ' . $concurso->nome)

@section('content')
<form action="{{ route('concursos.update', $concurso) }}" method="POST" enctype="multipart/form-data" class="card">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nome do Concurso</label>
                <input type="text" class="form-control" name="nome" value="{{ old('nome', $concurso->nome) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Ano</label>
                <input type="number" class="form-control" name="ano" value="{{ old('ano', $concurso->ano) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Data de Homologação</label>
                <input type="date" class="form-control" name="data_homologacao" value="{{ old('data_homologacao', $concurso->data_homologacao) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Órgão</label>
                <input type="text" class="form-control" name="orgao" value="{{ old('orgao', $concurso->orgao) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Banca Organizadora</label>
                <input type="text" class="form-control" name="banca_organizadora" value="{{ old('banca_organizadora', $concurso->banca_organizadora) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Ativo" @if(old('status', $concurso->status) == 'Ativo') selected @endif>Ativo</option>
                    <option value="Concluído" @if(old('status', $concurso->status) == 'Concluído') selected @endif>Concluído</option>
                    <option value="Suspenso" @if(old('status', $concurso->status) == 'Suspenso') selected @endif>Suspenso</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Edital (PDF)</label>
                <input type="file" class="form-control" name="edital" accept=".pdf">
                @if($concurso->edital_path)
                <small class="form-text text-muted">
                    Edital atual: <a href="{{ Storage::url($concurso->edital_path) }}" target="_blank">Visualizar</a>
                </small>
                @endif
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('concursos.index') }}" class="btn">Cancelar</a>
        <button type="submit" class="btn btn-primary">Atualizar Concurso</button>
    </div>
</form>
@endsection