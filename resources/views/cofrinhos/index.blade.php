<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meus Cofrinhos') }}
            </h2>
            <a href="{{ route('cofrinhos.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Criar Novo Cofrinho
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Total Guardado</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900">
                        R$ {{ number_format($totalGuardado, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($cofrinhos as $cofrinho)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 flex flex-col justify-between h-full">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $cofrinho->nome }}</h3>
                                
                                <p class="mt-2 text-2xl text-gray-800">
                                    R$ {{ number_format($cofrinho->saldo_atual, 2, ',', '.') }}
                                </p>
                                
                                @if ($cofrinho->meta > 0)
                                    @php
                                        $progresso = ($cofrinho->saldo_atual / $cofrinho->meta) * 100;
                                        if ($progresso > 100) $progresso = 100;
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                        <div class="bg-gray-800 h-2.5 rounded-full" style="width: {{ $progresso }}%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Meta: R$ {{ number_format($cofrinho->meta, 2, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('cofrinhos.show', $cofrinho) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Gerir
                                </a>
                                <a href="{{ route('cofrinhos.edit', $cofrinho) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 text-center text-gray-500">
                        Você ainda não criou nenhum cofrinho.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>