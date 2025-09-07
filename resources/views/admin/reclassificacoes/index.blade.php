@extends('admin.layouts.app')

@section('title', 'Reclassificações Pendentes')
@section('page-title', 'Gerir Solicitações de Reclassificação')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Solicitações Pendentes</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>Candidato</th>
                        <th>Concurso / Cargo</th>
                        <th>Motivo da Solicitação</th>
                        <th>Data da Solicitação</th>
                        <th class="w-1">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitacoes as $solicitacao)
                        <tr>
                            <td>
                                <a
                                    href="{{ route('candidatos.show', $solicitacao->candidato) }}">{{ $solicitacao->candidato->nome_completo }}</a>
                            </td>
                            <td class="text-muted">
                                {{ $solicitacao->candidato->cargo->concurso->nome }} <br>
                                <small>{{ $solicitacao->candidato->cargo->nome }}</small>
                            </td>
                            <td>{{ $solicitacao->motivo }}</td>
                            <td class="text-muted">{{ $solicitacao->created_at->format('d/m/Y') }}</td>
                            <td>
                                <form action="{{ route('reclassificacoes.update', $solicitacao) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group">
                                        <input type="number" name="nova_classificacao_geral"
                                            class="form-control form-control-sm" placeholder="Nova Class. Geral"
                                            style="min-width: 120px;">
                                        <button type="submit" name="status" value="aprovado"
                                            class="btn btn-sm btn-success">Aprovar</button>
                                        <button type="submit" name="status" value="rejeitado"
                                            class="btn btn-sm btn-danger">Rejeitar</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhuma solicitação pendente no momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
