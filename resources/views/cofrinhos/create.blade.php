<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Criar Novo Cofrinho') }}
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

                    <form action="{{ route('cofrinhos.store') }}" method="POST">
                        @csrf <div>
                            <x-input-label for="nome" :value="__('Nome do Cofrinho')" />
                            <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome')" required autofocus placeholder="Ex: Viagem, Reserva de Emergência" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="meta" :value="__('Meta de Valor (Opcional)')" />
                            <x-text-input id="meta" class="block mt-1 w-full" type="number" name="meta" :value="old('meta')" step="0.01" min="0.01" placeholder="Ex: 1500,00" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('cofrinhos.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Salvar Cofrinho') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>