<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransacaoController extends Controller
{
    /**
     * Mostra uma lista das transações DO UTILIZADOR LOGADO.
     */
    public function index()
    {
        $userId = Auth::id();

        $transacoes = Transacao::where('id_usuario', $userId)
                                ->with('categoria')
                                ->latest('data')
                                ->paginate(10); 

        $saldo = $this->calcularSaldo($userId);

        return view('transacoes.index', [
            'transacoes' => $transacoes,
            'saldo' => $saldo
        ]);
    }

    /**
     * Mostra o formulário para criar uma nova transação.
     */
    public function create()
    {
        $categorias = Categoria::orderBy('nome')->get();
        return view('transacoes.create', ['categorias' => $categorias]);
    }

    /**
     * Salva a nova transação no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|in:receita,despesa',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
            'descricao' => 'nullable|string|max:255',
        ]);

        $categoria = Categoria::find($request->id_categoria);
        if ($categoria->tipo != $request->tipo) {
            return back()->withInput()->with('error', 'O tipo da transação não corresponde ao tipo da categoria selecionada.');
        }

        Transacao::create([
            'id_usuario' => Auth::id(), // Envia o ID do utilizador
            'id_categoria' => $request->id_categoria,
            'valor' => $request->valor,
            'data' => $request->data,
            'descricao' => $request->descricao,
            'tipo' => $request->tipo,
        ]);

        return redirect()->route('transacoes.index')->with('success', 'Transação registrada com sucesso.');
    }

    /**
     * Mostra o formulário para editar uma transação.
     */
    public function edit(Transacao $transacao)
    {
        // !! VERIFICAÇÃO DE SEGURANÇA DESATIVADA !!
        // if ($transacao->id_usuario != Auth::id()) {
        //     abort(403, 'ACESSO NÃO AUTORIZADO.');
        // }

        $categorias = Categoria::orderBy('nome')->get();
        
        return view('transacoes.edit', [
            'transacao' => $transacao,
            'categorias' => $categorias
        ]);
    }

    /**
     * Atualiza a transação no banco de dados.
     */
    public function update(Request $request, Transacao $transacao)
    {
        // !! VERIFICAÇÃO DE SEGURANÇA DESATIVADA !!
        // if ($transacao->id_usuario != Auth::id()) {
        //     abort(403, 'ACESSO NÃO AUTORIZADO.');
        // }

        $request->validate([
            'tipo' => 'required|string|in:receita,despesa',
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
            'id_categoria' => 'required|integer|exists:categorias,id_categoria',
            'descricao' => 'nullable|string|max:255',
        ]);

        $categoria = Categoria::find($request->id_categoria);
        if ($categoria->tipo != $request->tipo) {
            return back()->withInput()->with('error', 'O tipo da transação não corresponde ao tipo da categoria selecionada.');
        }

        $transacao->update($request->all());

        return redirect()->route('transacoes.index')->with('success', 'Transação atualizada com sucesso.');
    }

    /**
     * Remove a transação do banco de dados.
     */
    public function destroy(Transacao $transacao)
    {
        // !! VERIFICAÇÃO DE SEGURANÇA DESATIVADA !!
        // if ($transacao->id_usuario != Auth::id()) {
        //     abort(403, 'ACESSO NÃO AUTORIZADO.');
        // }

        $transacao->delete();

        return redirect()->route('transacoes.index')->with('success', 'Transação excluída com sucesso.');
    }

    /**
     * Método privado para calcular o saldo total.
     */
    private function calcularSaldo($userId)
    {
        $receitas = Transacao::where('id_usuario', $userId)->where('tipo', 'receita')->sum('valor');
        $despesas = Transacao::where('id_usuario', $userId)->where('tipo', 'despesa')->sum('valor');
        return $receitas - $despesas;
    }
}