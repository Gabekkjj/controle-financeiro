<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Mostra uma lista de todas as categorias. (READ)
     */
    public function index()
    {
        // A CORREÇÃO ESTÁ AQUI: Trocámos ->get() por ->paginate(10)
        $categorias = Categoria::orderBy('nome')->paginate(10);
        
        // Retorna a "view" (tela) de listagem e passa as categorias para ela
        return view('categorias.index', ['categorias' => $categorias]);
    }

    /**
     * Mostra o formulário para criar uma nova categoria. (CREATE)
     */
    public function create()
    {
        // Apenas mostra o formulário de criação
        return view('categorias.create');
    }

    /**
     * Salva a nova categoria no banco de dados. (CREATE)
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|in:receita,despesa',
        ]);

        // 2. Cria a nova categoria no banco
        Categoria::create([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
        ]);

        // 3. Redireciona de volta para a lista (index) com uma msg de sucesso
        return redirect()->route('categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Mostra uma categoria específica. (READ)
     */
    public function show(Categoria $categoria)
    {
        // Redireciona para a tela de edição
        return redirect()->route('categorias.edit', $categoria);
    }

    /**
     * Mostra o formulário para editar uma categoria. (UPDATE)
     */
    public function edit(Categoria $categoria)
    {
        // Mostra o formulário de edição, passando a categoria específica
        return view('categorias.edit', ['categoria' => $categoria]);
    }

    /**
     * Atualiza a categoria no banco de dados. (UPDATE)
     */
    public function update(Request $request, Categoria $categoria)
    {
        // 1. Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string|in:receita,despesa',
        ]);

        // 2. Atualiza a categoria no banco
        $categoria->update([
            'nome' => $request->nome,
            'tipo' => $request->tipo,
        ]);

        // 3. Redireciona de volta para a lista (index) com msg de sucesso
        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove a categoria do banco de dados. (DELETE)
     */
    public function destroy(Categoria $categoria)
    {
        try {
            // 1. Tenta deletar a categoria
            $categoria->delete();
            
            // 2. Redireciona com msg de sucesso
            return redirect()->route('categorias.index')->with('success', 'Categoria excluída com sucesso.');
        
        } catch (\Illuminate\Database\QueryException $e) {
            // 3. Captura erro se a categoria estiver em uso por uma transação
            if ($e->getCode() == "23503") { // Código de violação de FK no PostgreSQL
                return redirect()->route('categorias.index')->with('error', 'Não é possível excluir esta categoria, pois ela já está sendo usada em transações.');
            }
            
            // Outro erro qualquer
            return redirect()->route('categorias.index')->with('error', 'Ocorreu um erro ao excluir a categoria.');
        }
    }
}