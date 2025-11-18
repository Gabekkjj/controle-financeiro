<?php

namespace App\Http\Controllers;

use App\Models\Transacao;
use App\Models\Categoria;
use App\Models\MovimentacaoCofrinho; // <-- IMPORTANTE: Importar este Model
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
            'id_usuario' => Auth::id(),
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
        // Segurança: Verifica se pertence ao utilizador (opcional, pois já removemos o 403 restrito)
        if ($transacao->id_usuario != Auth::id()) {
             // abort(403); // Mantemos desativado se preferir evitar o erro antigo
        }

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
     * !! A CORREÇÃO DO BUG DO COFRINHO ESTÁ AQUI !!
     */
    public function destroy(Transacao $transacao)
    {
        // 1. Verifica se a transação é de um Cofrinho (pela descrição automática)
        if (str_contains($transacao->descricao, 'Depósito para o cofrinho:') || str_contains($transacao->descricao, 'Retirada do cofrinho:')) {
            
            // Tenta encontrar a movimentação correspondente no cofrinho
            // Procuramos uma movimentação com o mesmo VALOR e mesma DATA criada +/- na mesma hora
            $movimentacao = MovimentacaoCofrinho::where('valor', $transacao->valor)
                ->where('data', $transacao->data)
                ->where('created_at', '>=', $transacao->created_at->subSeconds(10)) // Margem de 10 segundos
                ->where('created_at', '<=', $transacao->created_at->addSeconds(10))
                ->first();

            // Se encontrar, apaga a movimentação do cofrinho também
            if ($movimentacao) {
                $movimentacao->delete();
            }
        }

        // 2. Deleta a transação principal
        $transacao->delete();

        return redirect()->route('transacoes.index')->with('success', 'Transação excluída (e saldo atualizado se era de cofrinho).');
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