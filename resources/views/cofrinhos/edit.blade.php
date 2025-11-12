<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cofrinho') }}: {{ $cofrinho->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-sm text-gray-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('cofrinhos.update', $cofrinho) }}" method="POST">
                        @csrf @method('PUT') <div>
                            <x-input-label for="nome" :value="__('Nome do Cofrinho')" />
                            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $cofrinho->nome)" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="meta" :value="__('Meta de Valor (Opcional)')" />
                            <x-text-input id="meta" class="block mt-1 w-full" type="number" name="meta" :value="old('meta', $cofrinho->meta)" step="0.01" min="0.01" />
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <form action="{{ route('cofrinhos.destroy', $cofrinho) }}" method="POST" data-confirm-delete="true">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                    Excluir Cofrinho
                                </button>
                            </form>
                            
                            <div>
                                <a href="{{ route('cofrinhos.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                    Cancelar
                                </a>
                                <x-primary-button>
                                    {{ __('Salvar Alterações') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>