@extends('admin.layouts.app')

@section('title', 'Gestão de Candidatos')
@section('page-title', 'Gestão de Candidatos')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filtros de Pesquisa</h3>
    </div>
    <div class="card-body">
        <form id="filter-form" action="{{ route('gestao.candidatos.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-5">
                    <label for="concurso_id" class="form-label">Selecione o Concurso</label>
                    <select name="concurso_id" id="concurso_id" class="form-select">
                        <option value="">-- Todos os Concursos --</option>
                        @foreach ($concursos as $concurso)
                            <option value="{{ $concurso->id }}" @if($selectedConcursoId == $concurso->id) selected @endif>{{ $concurso->ano }} - {{ $concurso->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="cargo_id" class="form-label">Selecione o Cargo</label>
                    <select name="cargo_id" id="cargo_id" class="form-select" @if($cargos->isEmpty()) disabled @endif>
                        <option value="">-- Selecione um Concurso Primeiro --</option>
                         @foreach ($cargos as $cargo)
                            <option value="{{ $cargo->id }}" @if($selectedCargoId == $cargo->id) selected @endif>{{ $cargo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($selectedCargoId)
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Lista de Candidatos</h3>
        <div class="card-actions">
            <!-- Botão agora apenas tem um ID, o JS irá controlá-lo -->
            <button type="button" class="btn btn-success" id="open-promote-modal-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-track-next-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M2 5v14a1 1 0 0 0 1.555 .832l9.22 -6.27a1 1 0 0 0 0 -1.664l-9.22 -6.27a1 1 0 0 0 -1.555 .832z" stroke-width="0" fill="currentColor" /><path d="M18 5v14a1 1 0 0 0 2 0v-14a1 1 0 0 0 -2 0z" stroke-width="0" fill="currentColor" /></svg>
                Promover para Próxima Fase
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" id="select-all-candidatos"></th>
                    <th>Class.</th>
                    <th>Nome</th>
                    <th>Inscrição</th>
                    <th>Fase Atual</th>
                    <th>Status na Fase</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($candidatos as $candidato)
                @php
                    $ultimaFaseCandidato = $candidato->chamamentoCandidato->first()->fases->first() ?? null;
                    $nomeUltimaFase = $ultimaFaseCandidato->fase->nome ?? 'Aprovado';
                    $statusUltimaFase = $ultimaFaseCandidato->status ?? 'N/A';
                @endphp
                <tr>
                    <td><input class="form-check-input m-0 align-middle" type="checkbox" name="candidato_ids_table[]" value="{{ $candidato->id }}"></td>
                    <td><span class="badge bg-primary-lt">{{ $candidato->classificacao_geral }}º</span></td>
                    <td><a href="{{ route('candidatos.show', $candidato) }}">{{ $candidato->nome_completo }}</a></td>
                    <td class="text-muted">{{ $candidato->inscricao }}</td>
                    <td>{{ $nomeUltimaFase }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $statusUltimaFase)) }}</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum candidato encontrado para este cargo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Modal de Promoção -->
<div class="modal modal-blur fade" id="promote-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="promote-form" action="{{ route('gestao.candidatos.promover') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Promover Candidatos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Você está prestes a promover <strong id="candidate-count">0</strong> candidato(s).</p>
                    <div class="mb-3">
                        <label for="fase_destino_id_modal" class="form-label">Selecione a fase de destino:</label>
                        <select name="fase_destino_id" id="fase_destino_id_modal" class="form-select" required>
                            <option value="">-- Selecione uma fase --</option>
                            @if(!$fases->isEmpty())
                                @foreach ($fases as $fase)
                                    <option value="{{ $fase->id }}">{{ $fase->ordem }} - {{ $fase->nome }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="documento_formalizacao_modal" class="form-label">Documento de Formalização (PDF)</label>
                        <input type="file" name="documento_formalizacao" id="documento_formalizacao_modal" class="form-control" required accept=".pdf">
                    </div>
                    <!-- Container para os IDs dos candidatos selecionados -->
                    <div id="selected-candidates-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="submit-promote-form" class="btn btn-success">Confirmar Promoção</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const concursoSelect = document.getElementById('concurso_id');
    const cargoSelect = document.getElementById('cargo_id');
    const openModalButton = document.getElementById('open-promote-modal-button');
    const promoteModalElement = document.getElementById('promote-modal');
    const promoteModal = new bootstrap.Modal(promoteModalElement);
    
    // Lógica para carregar cargos dinamicamente (sem alterações)
    concursoSelect.addEventListener('change', function () {
        const concursoId = this.value;
        cargoSelect.innerHTML = '<option value="">A carregar...</option>';
        cargoSelect.disabled = true;
        if (!concursoId) {
            cargoSelect.innerHTML = '<option value="">-- Selecione um Concurso Primeiro --</option>';
            return;
        }
        fetch(`/concursos/${concursoId}/get-cargos`)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">-- Todos os Cargos --</option>';
                data.forEach(function (cargo) {
                    options += `<option value="${cargo.id}">${cargo.nome}</option>`;
                });
                cargoSelect.innerHTML = options;
                cargoSelect.disabled = false;
            });
    });

    // LÓGICA ATUALIZADA - O JS agora controla a abertura do modal
    if(openModalButton) {
        openModalButton.addEventListener('click', function() {
            const candidateCountSpan = document.getElementById('candidate-count');
            const selectedCandidatesContainer = document.getElementById('selected-candidates-container');
            const checkedCandidatos = document.querySelectorAll('input[name="candidato_ids_table[]"]:checked');
            
            // 1. Verifica se algum candidato foi selecionado
            if (checkedCandidatos.length === 0) {
                alert('Por favor, selecione pelo menos um candidato para promover.');
                return; // Para a execução aqui
            }
            
            // 2. Prepara o modal com os dados
            selectedCandidatesContainer.innerHTML = '';
            candidateCountSpan.textContent = checkedCandidatos.length;
            checkedCandidatos.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'candidato_ids[]';
                hiddenInput.value = checkbox.value;
                selectedCandidatesContainer.appendChild(hiddenInput);
            });

            // 3. Abre o modal
            promoteModal.show();
        });
    }
    
    // Lógica para submeter o formulário (sem alterações)
    const submitPromoteButton = document.getElementById('submit-promote-form');
    if(submitPromoteButton) {
        submitPromoteButton.addEventListener('click', function() {
            document.getElementById('promote-form').submit();
        });
    }

    // Lógica para selecionar todos os checkboxes (sem alterações)
    const selectAllCheckbox = document.getElementById('select-all-candidatos');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('input[name="candidato_ids_table[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});
</script>
@endpush