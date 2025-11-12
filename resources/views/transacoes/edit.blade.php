<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Transação') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('transacoes.update', $transacao) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mt-4">
                            <x-input-label for="tipo" :value="__('Tipo da Transação')" />
                            <select name="tipo" id="tipo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um tipo</option>
                                <option value="receita" {{ old('tipo', $transacao->tipo) == 'receita' ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ old('tipo', $transacao->tipo) == 'despesa' ? 'selected' : '' }}>Despesa</option>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="valor" :value="__('Valor (R$)')" />
                            <x-text-input id="valor" class="block mt-1 w-full" type="number" name="valor" :value="old('valor', $transacao->valor)" required step="0.01" min="0.01" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="data" :value="__('Data')" />
                            <x-text-input id="data" class="block mt-1 w-full" type="date" name="data" :value="old('data', $transacao->data)" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="id_categoria" :value="__('Categoria')" />
                            <select name="id_categoria" id="id_categoria" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione uma categoria</option>
                                <optgroup label="Receitas">
                                    @foreach ($categorias->where('tipo', 'receita') as $categoria)
                                        <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $transacao->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Despesas">
                                    @foreach ($categorias->where('tipo', 'despesa') as $categoria)
                                        <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $transacao->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="descricao" :value="__('Descrição (Opcional)')" />
                            <x-text-input id="descricao" class="block mt-1 w-full" type="text" name="descricao" :value="old('descricao', $transacao->descricao)" />
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('transacoes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Salvar Alterações') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>