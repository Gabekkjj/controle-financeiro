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
                        <div class="mb-6 p-4 bg-gray-100 border-l-4 border-gray-800 text-gray-700 shadow-sm">
                            <strong>Atenção:</strong> {{ session('error') }}
                        </div>
                    @endif
                    
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-300 text-gray-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('transacoes.update', $transacao) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div>
                                <x-input-label for="tipo" :value="__('Tipo')" />
                                <select name="tipo" id="tipo" class="mt-1 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm" required>
                                    <option value="receita" {{ old('tipo', $transacao->tipo) == 'receita' ? 'selected' : '' }}>Receita</option>
                                    <option value="despesa" {{ old('tipo', $transacao->tipo) == 'despesa' ? 'selected' : '' }}>Despesa</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="valor" :value="__('Valor (R$)')" />
                                <x-text-input id="valor" class="mt-1 block w-full focus:border-gray-500 focus:ring-gray-500" type="number" name="valor" :value="old('valor', $transacao->valor)" step="0.01" required />
                            </div>

                            <div>
                                <x-input-label for="data" :value="__('Data')" />
                                <x-text-input id="data" class="mt-1 block w-full focus:border-gray-500 focus:ring-gray-500" type="date" name="data" :value="old('data', $transacao->data)" required />
                            </div>

                            <div>
                                <x-input-label for="id_categoria" :value="__('Categoria')" />
                                <select name="id_categoria" id="id_categoria" class="mt-1 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm" required>
                                    <optgroup label="Receitas">
                                        @foreach($categorias->where('tipo', 'receita') as $categoria)
                                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $transacao->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                                {{ $categoria->nome }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Despesas">
                                        @foreach($categorias->where('tipo', 'despesa') as $categoria)
                                            <option value="{{ $categoria->id_categoria }}" {{ old('id_categoria', $transacao->id_categoria) == $categoria->id_categoria ? 'selected' : '' }}>
                                                {{ $categoria->nome }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="descricao" :value="__('Descrição (Opcional)')" />
                                <x-text-input id="descricao" class="mt-1 block w-full focus:border-gray-500 focus:ring-gray-500" type="text" name="descricao" :value="old('descricao', $transacao->descricao)" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-6 pt-4 border-t border-gray-100">
                            <a href="{{ route('transacoes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 font-medium">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Salvar Alterações
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>