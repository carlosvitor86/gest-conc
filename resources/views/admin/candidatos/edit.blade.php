@extends('admin.layouts.app')

@section('title', 'Editar Candidato')
@section('page-title', 'Editar Candidato: ' . $candidato->nome_completo)

@section('page-actions')
    <a href="{{ route('cargos.show', $candidato->cargo_id) }}" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
        Voltar para o Cargo
    </a>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('candidatos.update', $candidato) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome_completo" class="form-control" value="{{ old('nome_completo', $candidato->nome_completo) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nº de Inscrição</label>
                    <input type="text" name="inscricao" class="form-control" value="{{ $candidato->inscricao }}" disabled>
                    <small class="form-hint">O número de inscrição não pode ser alterado.</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nota Final</label>
                    <input type="number" step="0.001" name="nota_final" class="form-control" value="{{ old('nota_final', $candidato->nota_final) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Classificação Geral</label>
                    <input type="number" name="classificacao_geral" class="form-control" value="{{ old('classificacao_geral', $candidato->classificacao_geral) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Classificação (Cota)</label>
                    <input type="number" name="classificacao_cota" class="form-control" value="{{ old('classificacao_cota', $candidato->classificacao_cota) }}">
                </div>
                 <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Vaga</label>
                    <select name="tipo_vaga" class="form-select" required>
                        <option value="Ampla_concorrencia" @if(old('tipo_vaga', $candidato->tipo_vaga) == 'Ampla_concorrencia') selected @endif>Ampla Concorrência</option>
                        <option value="PCD" @if(old('tipo_vaga', $candidato->tipo_vaga) == 'PCD') selected @endif>PCD</option>
                        <option value="Cotas" @if(old('tipo_vaga', $candidato->tipo_vaga) == 'Cotas') selected @endif>Cotas</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('cargos.show', $candidato->cargo_id) }}" class="btn">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@endsection