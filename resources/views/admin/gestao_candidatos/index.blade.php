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
            <div class="ms-auto d-flex align-items-center gap-2">
                <!-- Campo de Pesquisa -->
                <div class="input-icon" style="width: 300px;">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                    </span>
                    <input type="text" id="search-candidate" class="form-control" placeholder="Pesquisar por nome ou inscrição...">
                </div>

                <!-- Botão de Importar -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#import-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                    Importar
                </button>
                
                <!-- Botão de Ações em Lote -->
                <div class="btn-group">
                  <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Ações em Lote
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#validate-status-modal">Validar Status</a>
                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#promote-modal">Promover Candidatos</a>
                  </div>
                </div>
            </div>
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
            <tbody id="candidate-table-body">
                @forelse ($candidatos as $candidato)
                @php
                    $chamamentoCandidato = $candidato->chamamentoCandidato->first();
                    $ultimaFaseCandidato = $chamamentoCandidato ? $chamamentoCandidato->fases->sortByDesc('created_at')->first() : null;
                    $nomeUltimaFase = $ultimaFaseCandidato->fase->nome ?? 'Aprovado';
                    $statusUltimaFase = $ultimaFaseCandidato->status ?? 'N/A';
                @endphp
                <tr class="candidate-row" data-searchable="{{ strtolower($candidato->nome_completo . ' ' . $candidato->inscricao) }}">
                    <td><input class="form-check-input m-0 align-middle" type="checkbox" name="candidato_ids_table[]" value="{{ $candidato->id }}"></td>
                    <td><span class="badge bg-primary-lt">{{ $candidato->classificacao_geral }}º</span></td>
                    <td><a href="{{ route('candidatos.show', $candidato) }}">{{ $candidato->nome_completo }}</a></td>
                    <td class="text-muted">{{ $candidato->inscricao }}</td>
                    <td>{{ $nomeUltimaFase }}</td>
                    <td>
                        <span class="badge 
                            @if($statusUltimaFase == 'apto') bg-success-lt @elseif($statusUltimaFase == 'inapto') bg-danger-lt @elseif($statusUltimaFase == 'apto_condicional') bg-warning-lt @else bg-secondary-lt @endif">
                            {{ ucfirst(str_replace('_', ' ', $statusUltimaFase)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Nenhum candidato encontrado para este cargo.</td>
                </tr>
                @endforelse
                <tr id="no-results-row" style="display: none;">
                    <td colspan="6" class="text-center">Nenhum resultado encontrado para a sua pesquisa.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Modal de Importação -->
<div class="modal modal-blur fade" id="import-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="import-form" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Importar Lista de Aprovados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="mb-3">
                        <label for="arquivo_candidatos" class="form-label">Ficheiro (CSV, XLSX)</label>
                        <input class="form-control" type="file" id="arquivo_candidatos" name="arquivo_candidatos" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                    </div>
                    <p class="form-hint">O ficheiro deve conter as colunas exatamente como no modelo.</p>
                    <a href="{{ route('cargos.template.download') }}" class="btn btn-outline-secondary w-100">Baixar Planilha Modelo</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="submit-import-form" class="btn btn-primary">Confirmar Importação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Validação de Status -->
<div class="modal modal-blur fade" id="validate-status-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="validate-status-form" action="{{ route('gestao.candidatos.validar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Validar Status dos Candidatos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Você está prestes a validar o status de <strong id="validate-candidate-count">0</strong> candidato(s).</p>
                    <div class="mb-3">
                        <label class="form-label">Selecione o novo status:</label>
                        <select name="status_fase" class="form-select" required>
                            <option value="apto">Apto</option>
                            <option value="inapto">Inapto</option>
                            <option value="apto_condicional">Apto com Condição</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Documento de Validação (PDF)</label>
                        <input type="file" name="documento_validacao" class="form-control" required accept=".pdf">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacao_validacao" class="form-control" rows="3"></textarea>
                    </div>
                    <div id="validate-selected-candidates-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="submit-validate-status-form" class="btn btn-primary">Confirmar Validação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Promoção -->
<div class="modal modal-blur fade" id="promote-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="promote-form" action="{{ route('gestao.candidatos.promover') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Promover Candidatos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Você está prestes a promover <strong id="promote-candidate-count">0</strong> candidato(s).</p>
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
                        <label class="form-label">Documento de Formalização (PDF)</label>
                        <input type="file" name="documento_formalizacao" class="form-control" required accept=".pdf">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observações</label>
                        <textarea name="observacao_promocao" class="form-control" rows="3"></textarea>
                    </div>
                    <div id="promote-selected-candidates-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="submit-promote-form" class="btn btn-success">Confirmar Promoção</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Carregamento -->
<div class="modal modal-blur fade" id="loading-modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div class="mb-3">
            <div class="progress">
              <div class="progress-bar progress-bar-indeterminate"></div>
            </div>
        </div>
        <h4>A processar a planilha...</h4>
        <div class="text-muted">Por favor, aguarde. Isto pode demorar alguns instantes.</div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal modal-blur fade" id="success-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-status bg-success"></div>
      <div class="modal-body text-center py-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-green icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path></svg>
        <h3>Sucesso!</h3>
        <div class="text-muted">A lista de candidatos foi importada com sucesso. A página será atualizada.</div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const concursoSelect = document.getElementById('concurso_id');
    const cargoSelect = document.getElementById('cargo_id');
    const importModalElement = document.getElementById('import-modal');
    const validateModalElement = document.getElementById('validate-status-modal');
    const promoteModalElement = document.getElementById('promote-modal');
    
    // Lógica para carregar cargos dinamicamente
    concursoSelect.addEventListener('change', function () {
        document.getElementById('filter-form').submit();
    });

    // Função auxiliar para preparar o modal com os candidatos selecionados
    function setupModal(modalElement, countElementId, containerElementId, alertMessage) {
        modalElement.addEventListener('show.bs.modal', function (event) {
            const checkedCandidatos = document.querySelectorAll('input[name="candidato_ids_table[]"]:checked');
            if (checkedCandidatos.length === 0) {
                event.preventDefault();
                alert(alertMessage);
                return;
            }
            document.getElementById(countElementId).textContent = checkedCandidatos.length;
            const container = document.getElementById(containerElementId);
            container.innerHTML = '';
            checkedCandidatos.forEach(checkbox => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'candidato_ids[]';
                hiddenInput.value = checkbox.value;
                container.appendChild(hiddenInput);
            });
        });
    }

    // Configurar todos os modais
    if (importModalElement) {
        importModalElement.addEventListener('show.bs.modal', function() {
            const cargoId = document.getElementById('cargo_id').value;
            if (!cargoId) {
                event.preventDefault();
                alert('Selecione um concurso e um cargo primeiro.');
            }
            document.getElementById('import-form').setAttribute('action', `/cargos/${cargoId}/importar-candidatos`);
        });
    }
    if (validateModalElement) {
        setupModal(validateModalElement, 'validate-candidate-count', 'validate-selected-candidates-container', 'Selecione pelo menos um candidato para validar o status.');
    }
    if (promoteModalElement) {
        setupModal(promoteModalElement, 'promote-candidate-count', 'promote-selected-candidates-container', 'Selecione pelo menos um candidato para promover.');
    }

    // Lógica para submeter os formulários dos modais
    document.getElementById('submit-import-form')?.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('loading-modal')).show();
        document.getElementById('import-form').submit();
    });
    document.getElementById('submit-validate-status-form')?.addEventListener('click', () => document.getElementById('validate-status-form').submit());
    document.getElementById('submit-promote-form')?.addEventListener('click', () => document.getElementById('promote-form').submit());
    
    // Lógica da Pesquisa
    const searchInput = document.getElementById('search-candidate');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const candidateRows = document.querySelectorAll('#candidate-table-body .candidate-row');
            let visibleRows = 0;
            candidateRows.forEach(row => {
                if (row.dataset.searchable.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            document.getElementById('no-results-row').style.display = visibleRows > 0 ? 'none' : '';
        });
    }

    // Lógica para selecionar todos
    const selectAllCheckbox = document.getElementById('select-all-candidatos');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', function () {
            document.querySelectorAll('input[name="candidato_ids_table[]"]').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // Lógica para mostrar o modal de sucesso após o redirecionamento
    const sessionStatus = @json(session('status'));
    if (sessionStatus === 'import-success') {
        const successModal = new bootstrap.Modal(document.getElementById('success-modal'));
        successModal.show();
        setTimeout(function() {
            successModal.hide();
        }, 2500);
    }
});
</script>
@endpush