<?php

namespace App\Http\Controllers;

use App\Models\Cofrinho;
use App\Models\Transacao; // Importante
use App\Models\Categoria; 
use App\Models\MovimentacaoCofrinho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

class CofrinhoController extends Controller
{
    public function index()
    {
        $cofrinhos = Auth::user()->cofrinhos()->get();

        $totalGuardado = 0;
        foreach ($cofrinhos as $cofrinho) {
            $totalGuardado += $cofrinho->saldo_atual;
        }

        return view('cofrinhos.index', [
            'cofrinhos' => $cofrinhos,
            'totalGuardado' => $totalGuardado
        ]);
    }

    public function create()
    {
        return view('cofrinhos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'meta' => 'nullable|numeric|min:0.01',
        ]);

        Auth::user()->cofrinhos()->create([
            'nome' => $request->nome,
            'meta' => $request->meta,
        ]);

        return redirect()->route('cofrinhos.index')->with('success', 'Cofrinho criado com sucesso!');
    }

    public function show(Cofrinho $cofrinho)
    {
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $movimentacoes = $cofrinho->movimentacoes()->latest('data')->paginate(10);

        return view('cofrinhos.show', [
            'cofrinho' => $cofrinho,
            'movimentacoes' => $movimentacoes
        ]);
    }

    public function edit(Cofrinho $cofrinho)
    {
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        return view('cofrinhos.edit', ['cofrinho' => $cofrinho]);
    }

    public function update(Request $request, Cofrinho $cofrinho)
    {
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'meta' => 'nullable|numeric|min:0.01',
        ]);

        $cofrinho->update([
            'nome' => $request->nome,
            'meta' => $request->meta,
        ]);

        return redirect()->route('cofrinhos.index')->with('success', 'Cofrinho atualizado com sucesso.');
    }

    /**
     * Apaga um cofrinho E remove as transações associadas do extrato.
     * (A CORREÇÃO DO BUG ESTÁ AQUI)
     */
    public function destroy(Cofrinho $cofrinho)
    {
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        // 1. Apagar as transações do extrato principal ligadas a este cofrinho
        // Procuramos por transações que tenham a descrição exata gerada pelo sistema
        $nome = $cofrinho->nome;
        
        Transacao::where('id_usuario', Auth::id())
            ->where(function($query) use ($nome) {
                $query->where('descricao', 'Depósito para o cofrinho: ' . $nome)
                      ->orWhere('descricao', 'Retirada do cofrinho: ' . $nome);
            })
            ->delete();

        // 2. Apagar o cofrinho (o banco apaga as movimentações internas automaticamente)
        $cofrinho->delete();

        return redirect()->route('cofrinhos.index')->with('success', 'Cofrinho excluído e saldo principal ajustado.');
    }

    // --- MÉTODOS ESPECIAIS (DEPOSITAR / RETIRAR) ---

    public function depositar(Request $request, Cofrinho $cofrinho)
    {
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
        ]);

        $valor = $request->valor;

        $categoriaTransferencia = Categoria::firstOrCreate(
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'despesa'],
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'despesa']
        );

        DB::transaction(function () use ($cofrinho, $categoriaTransferencia, $valor, $request) {
            
            $cofrinho->movimentacoes()->create([
                'tipo' => 'deposito',
                'valor' => $valor,
                'data' => $request->data,
            ]);

            Transacao::create([
                'id_usuario' => Auth::id(),
                'id_categoria' => $categoriaTransferencia->id_categoria,
                'tipo' => 'despesa',
                'valor' => $valor,
                'data' => $request->data,
                'descricao' => 'Depósito para o cofrinho: ' . $cofrinho->nome,
            ]);
        });

        return redirect()->route('cofrinhos.show', $cofrinho)->with('success', 'Dinheiro depositado!');
    }

    public function retirar(Request $request, Cofrinho $cofrinho)
    {
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
        ]);

        $valor = $request->valor;

        if ($valor > $cofrinho->saldo_atual) {
            return back()->with('error', 'Você não pode retirar mais dinheiro do que possui no cofrinho.');
        }

        $categoriaTransferencia = Categoria::firstOrCreate(
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'receita'],
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'receita']
        );

        DB::transaction(function () use ($cofrinho, $categoriaTransferencia, $valor, $request) {
            
            $cofrinho->movimentacoes()->create([
                'tipo' => 'retirada',
                'valor' => $valor,
                'data' => $request->data,
            ]);

            Transacao::create([
                'id_usuario' => Auth::id(),
                'id_categoria' => $categoriaTransferencia->id_categoria,
                'tipo' => 'receita',
                'valor' => $valor,
                'data' => $request->data,
                'descricao' => 'Retirada do cofrinho: ' . $cofrinho->nome,
            ]);
        });

        return redirect()->route('cofrinhos.show', $cofrinho)->with('success', 'Dinheiro retirado!');
    }
}