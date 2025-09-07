@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Filtro de Concurso para Gráficos -->
<div class="card card-body mb-4">
    <form id="dashboard-filter-form" action="{{ route('dashboard') }}" method="GET">
        <div class="col">
            <label class="form-label">Analisar Dados do Concurso:</label>
            <select name="concurso_id_filtro" id="concurso-filter-select" class="form-select">
                <option value="">-- Selecione um concurso para ver os dados --</option>
                @foreach($concursosParaFiltro as $concurso)
                    <option value="{{ $concurso->id }}" @if($selectedConcursoId == $concurso->id) selected @endif>{{ $concurso->ano }} - {{ $concurso->nome }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<div class="row row-deck row-cards">
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Concursos Ativos</div>
        </div>
        <div class="h1 mb-3">{{ $totalConcursosAtivos }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Candidatos Registados</div>
        </div>
        <div class="h1 mb-3">{{ $totalCandidatos }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Candidatos Empossados</div>
        </div>
        <div class="h1 mb-3">{{ $totalEmpossados }}</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="subheader">Vagas Remanescentes</div>
        </div>
        <div class="h1 mb-3">{{ $vagasRemanescentes }}</div>
      </div>
    </div>
  </div>

  <!-- Gráficos -->
  <div class="col-lg-7">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Distribuição de Candidatos por Fase</h3>
        <p class="card-subtitle">
            @if($selectedConcursoId)
                Exibindo dados para o concurso selecionado.
            @else
                Selecione um concurso acima para ver os dados.
            @endif
        </p>
        <div class="h-80">
            <canvas id="fasesChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card">
      <div class="card-body">
        <h3 class="card-title">Concursos por Ano (Geral)</h3>
        <div class="h-80">
            <canvas id="concursosPorAnoChart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<!-- Adicione a CDN do Chart.js se ainda não estiver no seu layout principal -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Lógica para submissão automática do filtro
    const filterSelect = document.getElementById('concurso-filter-select');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            document.getElementById('dashboard-filter-form').submit();
        });
    }
    
    // Gráfico de Barras: Distribuição por Fase
    const ctxFases = document.getElementById('fasesChart');
    if (ctxFases) {
        new Chart(ctxFases, {
            type: 'bar',
            data: {
                labels: @json($fasesLabels),
                datasets: [{
                    label: 'Nº de Candidatos',
                    data: @json($fasesValores),
                    backgroundColor: 'rgba(25, 135, 84, 0.6)',
                    borderColor: 'rgba(25, 135, 84, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Gráfico de barras horizontais
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

    // Gráfico de Donut: Concursos por Ano
    const ctxConcursos = document.getElementById('concursosPorAnoChart');
    if (ctxConcursos) {
        new Chart(ctxConcursos, {
            type: 'doughnut',
            data: {
                labels: @json($concursosPorAnoLabels),
                datasets: [{
                    label: 'Total de Concursos',
                    data: @json($concursosPorAnoValores),
                    backgroundColor: [
                        '#0d6efd',
                        '#6c757d',
                        '#198754',
                        '#ffc107',
                        '#dc3545'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    }
});
</script>
@endpush