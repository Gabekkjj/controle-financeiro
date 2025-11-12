<?php

namespace App\Http\Controllers;

use App\Models\Cofrinho;
use App\Models\Transacao; // Precisamos criar transações normais
use App\Models\Categoria; // Precisamos da categoria "Transferência"
use App\Models\MovimentacaoCofrinho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Para fazer transações de BD seguras

class CofrinhoController extends Controller
{
    /**
     * Mostra uma lista de todos os cofrinhos do utilizador.
     */
    public function index()
    {
        $cofrinhos = Auth::user()->cofrinhos()->get();

        // Vamos calcular o total guardado em todos os cofrinhos
        $totalGuardado = 0;
        foreach ($cofrinhos as $cofrinho) {
            $totalGuardado += $cofrinho->saldo_atual; // Usamos a função 'getSaldoAtualAttribute' do Model
        }

        return view('cofrinhos.index', [
            'cofrinhos' => $cofrinhos,
            'totalGuardado' => $totalGuardado
        ]);
    }

    /**
     * Mostra o formulário para criar um novo cofrinho.
     */
    public function create()
    {
        return view('cofrinhos.create');
    }

    /**
     * Salva o novo cofrinho na base de dados.
     */
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

    /**
     * Mostra os detalhes e o histórico de um cofrinho.
     */
    public function show(Cofrinho $cofrinho)
    {
        // Segurança: Garante que o cofrinho pertence ao utilizador logado
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        // Carrega as movimentações ordenadas
        $movimentacoes = $cofrinho->movimentacoes()->latest('data')->paginate(10);

        return view('cofrinhos.show', [
            'cofrinho' => $cofrinho,
            'movimentacoes' => $movimentacoes
        ]);
    }

    /**
     * Mostra o formulário para editar o cofrinho (nome/meta).
     */
    public function edit(Cofrinho $cofrinho)
    {
        // Segurança
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        return view('cofrinhos.edit', ['cofrinho' => $cofrinho]);
    }

    /**
     * Atualiza o cofrinho na base de dados.
     */
    public function update(Request $request, Cofrinho $cofrinho)
    {
        // Segurança
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
     * Apaga um cofrinho (e todo o dinheiro dele é perdido!).
     */
    public function destroy(Cofrinho $cofrinho)
    {
        // Segurança
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        // (Nota: Numa app real, perguntaríamos se o utilizador quer mover o saldo de volta)
        $cofrinho->delete();

        return redirect()->route('cofrinhos.index')->with('success', 'Cofrinho excluído.');
    }


    // --- MÉTODOS ESPECIAIS (DEPOSITAR / RETIRAR) ---

    /**
     * Adiciona dinheiro ao cofrinho (e remove do saldo principal).
     */
    public function depositar(Request $request, Cofrinho $cofrinho)
    {
        // Segurança
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
        ]);

        $valor = $request->valor;

        // 1. Encontrar a categoria "Transferência" (ou criar se não existir)
        // Esta é a categoria que será usada no seu extrato principal
        $categoriaTransferencia = Categoria::firstOrCreate(
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'despesa'],
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'despesa']
        );

        // Usamos uma Transação de Base de Dados para garantir que as duas operações ocorrem
        DB::transaction(function () use ($cofrinho, $categoriaTransferencia, $valor, $request) {
            
            // 2. Cria a movimentação de DEPÓSITO no cofrinho
            $cofrinho->movimentacoes()->create([
                'tipo' => 'deposito',
                'valor' => $valor,
                'data' => $request->data,
            ]);

            // 3. Cria a transação de DESPESA no saldo principal
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

    /**
     * Retira dinheiro do cofrinho (e adiciona ao saldo principal).
     */
    public function retirar(Request $request, Cofrinho $cofrinho)
    {
        // Segurança
        if ($cofrinho->id_usuario != Auth::id()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $request->validate([
            'valor' => 'required|numeric|min:0.01',
            'data' => 'required|date',
        ]);

        $valor = $request->valor;

        // Validação extra: não pode retirar mais do que tem
        if ($valor > $cofrinho->saldo_atual) {
            return back()->with('error', 'Você não pode retirar mais dinheiro do que possui no cofrinho.');
        }

        // 1. Encontrar a categoria "Transferência" (Receita)
        $categoriaTransferencia = Categoria::firstOrCreate(
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'receita'],
            ['nome' => 'Transferência Cofrinho', 'tipo' => 'receita']
        );

        // Transação de Base de Dados
        DB::transaction(function () use ($cofrinho, $categoriaTransferencia, $valor, $request) {
            
            // 2. Cria a movimentação de RETIRADA no cofrinho
            $cofrinho->movimentacoes()->create([
                'tipo' => 'retirada',
                'valor' => $valor,
                'data' => $request->data,
            ]);

            // 3. Cria a transação de RECEITA no saldo principal
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