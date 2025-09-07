@extends('admin.layouts.app')

@section('title', 'Dossiê do Candidato')
@section('page-title', 'Dossiê do Candidato')

@section('page-actions')
    <div class="btn-list">
        <a href="{{ url()->previous() }}" class="btn">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M5 12l14 0" />
                <path d="M5 12l6 6" />
                <path d="M5 12l6 -6" />
            </svg>
            Voltar
        </a>
        <a href="{{ route('candidatos.historico.pdf', $candidato) }}" target="_blank" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                <path d="M7 13m0 2a2 2 0 0 1 0 -4h10a2 2 0 0 1 0 4z" />
            </svg>
            Imprimir
        </a>
    </div>
@endsection

@section('content')
    <div class="row g-4">
        <!-- Coluna da Esquerda: Informações e Ação -->
        <div class="col-lg-4">
            <!-- Card de Informações do Candidato -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">{{ $candidato->nome_completo }}</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Inscrição:</dt>
                        <dd class="col-7">{{ $candidato->inscricao }}</dd>
                        <dt class="col-5">Cargo:</dt>
                        <dd class="col-7">{{ $candidato->cargo->nome }}</dd>
                        <dt class="col-5">Classificação Geral:</dt>
                        <dd class="col-7"><strong>{{ $candidato->classificacao_geral }}º</strong></dd>
                        <dt class="col-5">Classificação Cota:</dt>
                        <dd class="col-7">
                            {{ $candidato->classificacao_cota ? $candidato->classificacao_cota . 'º' : 'N/A' }}</dd>
                        <dt class="col-5">Nota Final:</dt>
                        <dd class="col-7">{{ number_format($candidato->nota_final, 3, ',', '.') }}</dd>
                        <dt class="col-5">Tipo de Vaga:</dt>
                        <dd class="col-7">{{ str_replace('_', ' ', $candidato->tipo_vaga) }}</dd>
                    </dl>
                </div>
            </div>

            <!-- Histórico de Reclassificações (sem o formulário de aprovação) -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Histórico de Reclassificações</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse ($candidato->reclassificacoes->sortByDesc('created_at') as $reclassificacao)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        @if ($reclassificacao->status == 'aprovado')
                                            <span class="badge bg-success">Aprovada</span>
                                        @elseif($reclassificacao->status == 'rejeitado')
                                            <span class="badge bg-danger">Rejeitada</span>
                                        @else
                                            <span class="badge bg-warning">Pendente</span>
                                        @endif
                                    </div>
                                    <div class="col text-truncate">
                                        <div class="text-body d-block">Motivo: {{ $reclassificacao->motivo }}</div>
                                        <!-- ... (restante da informação do histórico) ... -->
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <p class="text-muted text-center">Nenhum histórico de reclassificação.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico de Fases -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Histórico de Fases de: {{ $candidato->nome_completo }}</h3>
                </div>
                <ul class="list-group card-list-group">
                    @php
                        // Garante que a relação existe antes de tentar aceder
                        $chamamentoCandidato = $candidato->chamamentoCandidato->first();
                    @endphp
                    @if ($chamamentoCandidato)
                        @forelse($chamamentoCandidato->fases->sortBy('created_at') as $historico)
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="badge 
                                @if ($historico->status == 'apto') bg-success-lt @elseif($historico->status == 'inapto') bg-danger-lt @else bg-warning-lt @endif 
                                me-3">{{ ucfirst(str_replace('_', ' ', $historico->status)) }}</span>
                                    <div>
                                        <div><strong>Fase:</strong> {{ $historico->fase->nome }}</div>
                                        <div class="text-muted">
                                            Em {{ $historico->created_at->format('d/m/Y H:i') }}
                                            @if ($historico->processedBy)
                                                | Por: {{ $historico->processedBy->name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ($historico->observacoes)
                                    <div class="mt-2 ps-2 border-start">
                                        <strong>Observações:</strong>
                                        <p class="text-muted mb-0">{{ $historico->observacoes }}</p>
                                    </div>
                                @endif
                                @if ($historico->documento_path)
                                    <div class="mt-2">
                                        <a href="{{ Storage::url($historico->documento_path) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">Ver Documento Anexado</a>
                                    </div>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item">
                                <p class="text-muted text-center">Nenhum histórico de fases encontrado para este candidato.
                                </p>
                            </li>
                        @endforelse
                    @else
                        <li class="list-group-item">
                            <p class="text-muted text-center">Candidato ainda não foi promovido para nenhuma fase.</p>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
