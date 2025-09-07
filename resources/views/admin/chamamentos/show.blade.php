@extends('admin.layouts.app')

@section('title', 'Detalhes do Chamamento')
@section('page-title', 'Gerir Chamamento: ' . $chamamento->numero_chamamento)

@section('page-actions')
    <a href="{{ route('chamamentos.index') }}" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
        Voltar para a Lista
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Candidatos Convocados</h3>
        <div class="card-actions">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-candidate-modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Adicionar Candidatos à Convocação
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Class.</th>
                    <th>Nome</th>
                    <th>Inscrição</th>
                    <th>Status Atual</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($chamamento->chamamentoCandidato as $convocado)
                    <tr>
                        <td>{{ $convocado->candidato->classificacao_geral }}º</td>
                        <td>
                            <a href="{{ route('candidatos.show', $convocado->candidato) }}">{{ $convocado->candidato->nome_completo }}</a>
                        </td>
                        <td class="text-muted">{{ $convocado->candidato->inscricao }}</td>
                        <td>
                            <form class="status-update-form" action="{{ route('chamamento-candidato.updateStatus', $convocado->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm status-select" style="width: 200px;">
                                    <option value="Convocado" @if($convocado->status == 'Convocado') selected @endif>Convocado</option>
                                    <option value="Apresentou Documentação" @if($convocado->status == 'Apresentou Documentação') selected @endif>Apresentou Documentação</option>
                                    <option value="Apto para Posse" @if($convocado->status == 'Apto para Posse') selected @endif>Apto para Posse</option>
                                    <option value="Tomou Posse" @if($convocado->status == 'Tomou Posse') selected @endif>Tomou Posse</option>
                                    <option value="Desistiu" @if($convocado->status == 'Desistiu') selected @endif>Desistiu</option>
                                    <option value="Eliminado" @if($convocado->status == 'Eliminado') selected @endif>Eliminado</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Nenhum candidato convocado neste chamamento.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Adicionar Candidatos -->
<div class="modal modal-blur fade" id="add-candidate-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('chamamentos.candidatos.store', $chamamento) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar Candidatos à Convocação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Selecione os candidatos disponíveis para adicionar a este chamamento.</p>
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th class="w-1"><input type="checkbox" id="select-all-modal"></th>
                                    <th>Class.</th>
                                    <th>Nome</th>
                                    <th>Cargo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($candidatosDisponiveis as $candidato)
                                <tr>
                                    <td><input type="checkbox" class="form-check-input" name="candidato_ids[]" value="{{ $candidato->id }}"></td>
                                    <td>{{ $candidato->classificacao_geral }}º</td>
                                    <td>{{ $candidato->nome_completo }}</td>
                                    <td class="text-muted">{{ $candidato->cargo->nome }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Adicionar Selecionados</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Script para auto-submissão do formulário de status
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('.status-update-form').submit();
        });
    });

    // Script para selecionar todos os candidatos no modal
    const selectAllCheckbox = document.getElementById('select-all-modal');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('#add-candidate-modal input[name="candidato_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});
</script>
@endpush
