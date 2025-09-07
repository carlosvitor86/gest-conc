<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico do Candidato - {{ $candidato->nome_completo }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        h1, h2, h3 { font-weight: normal; }
        h1 { font-size: 24px; text-align: center; margin-bottom: 0; }
        h2 { font-size: 18px; text-align: center; margin-top: 5px; margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
        .info-section { margin-bottom: 20px; border: 1px solid #eee; padding: 15px; border-radius: 5px; }
        .info-section dt { font-weight: bold; float: left; width: 150px; }
        .info-section dd { margin-left: 160px; }
        .history-list { list-style: none; padding: 0; }
        .history-list li { border-bottom: 1px solid #eee; padding: 10px 0; }
        .history-list li:last-child { border-bottom: none; }
        .status { font-weight: bold; padding: 2px 6px; border-radius: 4px; color: #fff; }
        .status-apto { background-color: #2fb344; }
        .status-inapto { background-color: #d63939; }
        .status-pendente { background-color: #f59f00; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #777; }
    </style>
</head>
<body>

    <div class="footer">
        Gerado em: {{ now()->format('d/m/Y H:i:s') }}
    </div>

    <h1>Dossiê do Candidato</h1>
    <h2>{{ $candidato->cargo->concurso->nome }}</h2>

    <div class="info-section">
        <h3>Informações do Candidato</h3>
        <dl>
            <dt>Nome:</dt>
            <dd>{{ $candidato->nome_completo }}</dd>
            <dt>Inscrição:</dt>
            <dd>{{ $candidato->inscricao }}</dd>
            <dt>Cargo:</dt>
            <dd>{{ $candidato->cargo->nome }}</dd>
            <dt>Classificação Geral:</dt>
            <dd>{{ $candidato->classificacao_geral }}º</dd>
        </dl>
    </div>

    <div class="info-section">
        <h3>Histórico de Fases</h3>
        <ul class="history-list">
            @forelse($candidato->chamamentoCandidato->first()->fases->sortBy('created_at') as $historico)
                <li>
                    <strong>Fase: {{ $historico->fase->nome }}</strong> - 
                    <span class="status 
                        @if($historico->status == 'apto') status-apto
                        @elseif($historico->status == 'inapto') status-inapto
                        @else status-pendente @endif">
                        {{ ucfirst(str_replace('_', ' ', $historico->status)) }}
                    </span>
                    <br>
                    <small>Processado em: {{ $historico->created_at->format('d/m/Y H:i') }}
                    @if($historico->processedBy)
                        | por: {{ $historico->processedBy->name }}
                    @endif
                    </small>
                    @if($historico->observacoes)
                        <p style="margin-top: 5px; padding-left: 10px; border-left: 2px solid #eee;">
                            <strong>Observações:</strong> {{ $historico->observacoes }}
                        </p>
                    @endif
                </li>
            @empty
                <li>Nenhum histórico de fases encontrado.</li>
            @endforelse
        </ul>
    </div>

</body>
</html>