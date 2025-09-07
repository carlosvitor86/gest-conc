<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Lista de Aprovados - {{ $cargo->nome }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
        }

        .header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="footer">
        Gerado em: {{ now()->format('d/m/Y H:i:s') }}
    </div>

    <div class="header">
        <h1>Lista de Aprovados</h1>
        <h2>Concurso: {{ $cargo->concurso->nome }} ({{ $cargo->concurso->ano }})</h2>
        <p>Cargo: <strong>{{ $cargo->nome }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Class. Geral</th>
                <th>Class. Cota</th>
                <th>Inscrição</th>
                <th>Nome Completo</th>
                <th>Nota Final</th>
                <th>Tipo de Vaga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($candidatos as $candidato)
                <tr>
                    <td>{{ $candidato->classificacao_geral }}º</td>
                    <td>{{ $candidato->classificacao_cota ? $candidato->classificacao_cota . 'º' : '-' }}</td>
                    <td>{{ $candidato->inscricao }}</td>
                    <td>{{ $candidato->nome_completo }}</td>
                    <td>{{ number_format($candidato->nota_final, 3, ',', '.') }}</td>
                    <td>{{ str_replace('_', ' ', $candidato->tipo_vaga) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
