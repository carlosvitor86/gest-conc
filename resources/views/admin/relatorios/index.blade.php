@extends('admin.layouts.app')

@section('title', 'Relatórios')
@section('page-title', 'Relatórios e Exportações')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Gerar Relatório de Aprovados</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Selecione um concurso e um cargo para gerar uma lista completa de todos os candidatos aprovados em formato PDF.</p>
        <form action="{{ route('relatorios.exportar.aprovados.pdf') }}" method="POST" target="_blank">
            @csrf
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="concurso_id" class="form-label">Concurso</label>
                    <select name="concurso_id" id="concurso_id" class="form-select" required>
                        <option value="">-- Selecione um concurso --</option>
                        @foreach ($concursos as $concurso)
                            <option value="{{ $concurso->id }}">{{ $concurso->ano }} - {{ $concurso->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="cargo_id" class="form-label">Cargo</label>
                    <select name="cargo_id" id="cargo_id" class="form-select" disabled required>
                        <option value="">-- Selecione um concurso primeiro --</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Gerar PDF</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Outros cards de relatórios podem ser adicionados aqui no futuro -->
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const concursoSelect = document.getElementById('concurso_id');
    const cargoSelect = document.getElementById('cargo_id');

    concursoSelect.addEventListener('change', function () {
        const concursoId = this.value;
        cargoSelect.innerHTML = '<option value="">A carregar...</option>';
        cargoSelect.disabled = true;

        if (!concursoId) {
            cargoSelect.innerHTML = '<option value="">-- Selecione um concurso primeiro --</option>';
            return;
        }

        // Usamos a rota que já existe para obter os cargos
        fetch(`/concursos/${concursoId}/get-cargos`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Selecione um cargo --</option>';
                data.forEach(function (cargo) {
                    options += `<option value="${cargo.id}">${cargo.nome}</option>`;
                });
                cargoSelect.innerHTML = options;
                cargoSelect.disabled = false;
            });
    });
});
</script>
@endpush