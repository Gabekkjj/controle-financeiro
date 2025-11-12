<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Mostra o nome da categoria que estamos editando --}}
            {{ __('Editar Categoria') }}: {{ $categoria->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                        @csrf @method('PUT') <div>
                            <x-input-label for="nome" :value="__('Nome da Categoria')" />
                            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $categoria->nome)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="tipo" :value="__('Tipo')" />
                            <select name="tipo" id="tipo" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione um tipo</option>
                                
                                <option value="receita" {{ old('tipo', $categoria->tipo) == 'receita' ? 'selected' : '' }}>Receita</option>
                                <option value="despesa" {{ old('tipo', $categoria->tipo) == 'despesa' ? 'selected' : '' }}>Despesa</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('categorias.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
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