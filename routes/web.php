<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController; 
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\CofrinhoController;
use App\Models\Transacao; 
use Illuminate\Support\Facades\Auth; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota da Página Inicial
Route::get('/', function () {
    return redirect()->route('login');
});

// Rota do Dashboard (Atualizada para o Layout Profissional)
Route::get('/dashboard', function () {
    $userId = Auth::id();

    // 1. Calcular Totais Separados (Para os 3 Cards)
    $totalReceitas = Transacao::where('id_usuario', $userId)->where('tipo', 'receita')->sum('valor');
    $totalDespesas = Transacao::where('id_usuario', $userId)->where('tipo', 'despesa')->sum('valor');
    
    // O Saldo é a diferença
    $saldo = $totalReceitas - $totalDespesas;

    // 2. Buscar as 5 Transações Mais Recentes
    $recentes = Transacao::where('id_usuario', $userId)
                            ->with('categoria') 
                            ->latest('data')    
                            ->limit(5)          
                            ->get();
    
    // 3. Enviar TUDO para a View
    return view('dashboard', [
        'totalReceitas' => $totalReceitas, // Envia total de Entradas
        'totalDespesas' => $totalDespesas, // Envia total de Saídas
        'saldo' => $saldo,                 // Envia o Saldo Final
        'recentes' => $recentes            // Envia a lista
    ]);

})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas que exigem login
Route::middleware('auth')->group(function () {
    // Rotas do Perfil (do Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Categorias
    Route::resource('categorias', CategoriaController::class);
    
    // CRUD Transações
    Route::resource('transacoes', TransacaoController::class)
         ->parameters(['transacoes' => 'transacao']); 
         
    // CRUD Cofrinhos
    Route::resource('cofrinhos', CofrinhoController::class)
         ->parameters(['cofrinhos' => 'cofrinho']);
         
    // Rotas Extras do Cofrinho (Depositar/Retirar)
    Route::post('/cofrinhos/{cofrinho}/depositar', [CofrinhoController::class, 'depositar'])->name('cofrinhos.depositar');
    Route::post('/cofrinhos/{cofrinho}/retirar', [CofrinhoController::class, 'retirar'])->name('cofrinhos.retirar');
});

// Arquivo de rotas de autenticação (login, registro)
require __DIR__.'/auth.php';