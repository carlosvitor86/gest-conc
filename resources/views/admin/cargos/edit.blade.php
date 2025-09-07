@extends('admin.layouts.app')

@section('title', 'Editar Cargo')
@section('page-title', 'Editar Cargo: ' . $cargo->nome)

@section('page-actions')
    <a href="{{ route('concursos.show', $cargo->concurso_id) }}" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
        Voltar para o Concurso
    </a>
@endsection

@section('content')
<div class="card">
    <form action="{{ route('cargos.update', $cargo) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <h3 class="card-title mb-4">Detalhes do Cargo</h3>
            <div class="row row-cards">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Nome do Cargo</label>
                        <input type="text" class="form-control" name="nome" value="{{ old('nome', $cargo->nome) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Local da Vaga</label>
                        <input type="text" class="form-control" name="local_vaga" value="{{ old('local_vaga', $cargo->local_vaga) }}" required>
                    </div>
                </div>
                <div class="col-md-12">
                     <h4 class="card-title my-2">Distribuição de Vagas</h4>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Vagas - Ampla Concorrência</label>
                        <input type="number" class="form-control" name="vagas_ampla_concorrencia" value="{{ old('vagas_ampla_concorrencia', $cargo->vagas_ampla_concorrencia) }}" min="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Vagas - PcD</label>
                        <input type="number" class="form-control" name="vagas_pcd" value="{{ old('vagas_pcd', $cargo->vagas_pcd) }}" min="0" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Vagas - Cotas</label>
                        <input type="number" class="form-control" name="vagas_cotas" value="{{ old('vagas_cotas', $cargo->vagas_cotas) }}" min="0" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('concursos.show', $cargo->concurso_id) }}" class="btn">Cancelar</a>
            <button type="submit" class="btn btn-primary">Atualizar Cargo</button>
        </div>
    </form>
</div>
@endsection
