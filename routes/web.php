<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConcursoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\FaseController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\ReclassificacaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GestaoCandidatosController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RelatorioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aqui é onde pode registar as rotas web para a sua aplicação.
|
*/

// Rota de raiz (página inicial)
Route::get('/', function () {
    // Se o utilizador estiver autenticado, vai para o dashboard.
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Se não, vai para a página de login.
    return redirect()->route('login');
});

// --- GRUPO DE ROTAS AUTENTICADAS ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard e Perfil do Utilizador
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- GRUPO PARA GESTORES E ADMINS ---
    Route::middleware(['role:Admin|Gestor de Concurso'])->group(function () {
        // Rota específica de download antes das rotas de recurso para evitar conflitos
        Route::get('/cargos/template-download', [CandidatoController::class, 'downloadTemplate'])->name('cargos.template.download');

        // CRUDs principais
        Route::resource('concursos', ConcursoController::class);
        Route::resource('concursos.cargos', CargoController::class)->shallow();
        Route::resource('candidatos', CandidatoController::class)->except(['index', 'create', 'store']);
        Route::resource('chamamentos', ChamamentoController::class)->except(['edit', 'update', 'destroy']);

        // Rotas de Ações Específicas
        Route::post('/cargos/{cargo}/fases', [FaseController::class, 'store'])->name('cargos.fases.store');
        Route::delete('/fases/{fase}', [FaseController::class, 'destroy'])->name('fases.destroy');
        Route::post('/cargos/{cargo}/importar-candidatos', [CandidatoController::class, 'import'])->name('cargos.candidatos.import');
        Route::post('/candidatos/{candidato}/reclassificacoes', [ReclassificacaoController::class, 'store'])->name('reclassificacoes.store');
        Route::patch('/reclassificacoes/{reclassificacao}', [ReclassificacaoController::class, 'update'])->name('reclassificacoes.update');
        Route::post('/chamamentos/{chamamento}/adicionar-candidatos', [ChamamentoController::class, 'adicionarCandidatos'])->name('chamamentos.candidatos.store');
        Route::patch('/chamamento-candidato/{chamamentoCandidato}/status', [ChamamentoCandidatoController::class, 'updateStatus'])->name('chamamento-candidato.updateStatus');

        Route::get('/candidatos/{candidato}/historico-pdf', [CandidatoController::class, 'downloadHistoricoPDF'])->name('candidatos.historico.pdf');

        // Novas rotas para a Gestão de Candidatos
        Route::get('/gestao-candidatos', [GestaoCandidatosController::class, 'index'])->name('gestao.candidatos.index');
        Route::get('/concursos/{concurso}/get-cargos', [GestaoCandidatosController::class, 'getCargos'])->name('gestao.candidatos.getcargos');
        Route::post('/gestao-candidatos/promover', [GestaoCandidatosController::class, 'promover'])->name('gestao.candidatos.promover');
        Route::post('/gestao-candidatos/validar-status', [GestaoCandidatosController::class, 'validarStatus'])->name('gestao.candidatos.validar');

        // Novas rotas para Relatórios
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::post('/relatorios/exportar-aprovados-pdf', [RelatorioController::class, 'exportarAprovadosPDF'])->name('relatorios.exportar.aprovados.pdf');

        // Adicione a nova rota de recurso
        Route::resource('reclassificacoes', ReclassificacaoController::class)->only(['index', 'update']);
        // A rota 'store' continua aninhada ao candidato, pois faz sentido ser criada a partir do dossiê dele.
        Route::post('/candidatos/{candidato}/reclassificacoes', [ReclassificacaoController::class, 'store'])->name('reclassificacoes.store');
    });

    // --- GRUPO EXCLUSIVO PARA ADMINS ---
    Route::middleware(['role:Admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});

require __DIR__ . '/auth.php';
