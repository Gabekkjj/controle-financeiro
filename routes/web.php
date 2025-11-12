<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController; 
use App\Http\Controllers\TransacaoController;
use App\Http\Controllers\CofrinhoController; // Importa o novo controller
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

// Rota do Dashboard (agora com lógica de backend)
Route::get('/dashboard', function () {
    $userId = Auth::id();

    // 1. Lógica para Calcular o Saldo (RF04)
    $receitas = Transacao::where('id_usuario', $userId)->where('tipo', 'receita')->sum('valor');
    $despesas = Transacao::where('id_usuario', $userId)->where('tipo', 'despesa')->sum('valor');
    $saldo = $receitas - $despesas;

    // 2. Lógica para Transações Recentes
    $recentes = Transacao::where('id_usuario', $userId)
                            ->with('categoria') 
                            ->latest('data')    
                            ->limit(5)          
                            ->get();
    
    // 3. Retorna a View com os dados
    return view('dashboard', [
        'saldo' => $saldo,
        'recentes' => $recentes
    ]);

})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas que exigem login
Route::middleware('auth')->group(function () {
    // Rotas do Perfil (do Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // NOSSAS ROTAS DO PROJETO
    Route::resource('categorias', CategoriaController::class);
    
    Route::resource('transacoes', TransacaoController::class)
         ->parameters(['transacoes' => 'transacao']); // A correção do plural
         
    // !! INÍCIO DO NOVO CÓDIGO DO COFRINHO !!
    Route::resource('cofrinhos', CofrinhoController::class)
         ->parameters(['cofrinhos' => 'cofrinho']);
         
    // Rotas para depositar e retirar
    Route::post('/cofrinhos/{cofrinho}/depositar', [CofrinhoController::class, 'depositar'])->name('cofrinhos.depositar');
    Route::post('/cofrinhos/{cofrinho}/retirar', [CofrinhoController::class, 'retirar'])->name('cofrinhos.retirar');
    // !! FIM DO NOVO CÓDIGO DO COFRINHO !!
});

// Arquivo de rotas de autenticação (login, registro)
require __DIR__.'/auth.php';