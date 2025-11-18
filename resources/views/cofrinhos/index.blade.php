<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meus Cofrinhos') }}
            </h2>
            <a href="{{ route('cofrinhos.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                Criar Novo Cofrinho
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 border-b-4 border-gray-800">
                <div class="p-6 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Guardado</p>
                        <p class="text-3xl font-bold text-gray-900">
                            R$ {{ number_format($totalGuardado, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($cofrinhos as $cofrinho)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 hover:shadow-md transition">
                        <div class="p-6 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $cofrinho->nome }}</h3>
                                    <div class="p-2 bg-gray-50 rounded-full text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </div>
                                </div>
                                
                                <p class="text-2xl font-bold text-gray-900 mb-4">
                                    R$ {{ number_format($cofrinho->saldo_atual, 2, ',', '.') }}
                                </p>
                                
                                @if ($cofrinho->meta > 0)
                                    @php
                                        $progresso = ($cofrinho->saldo_atual / $cofrinho->meta) * 100;
                                        if ($progresso > 100) $progresso = 100;
                                    @endphp
                                    <div class="w-full bg-gray-100 rounded-full h-2 mb-1">
                                        <div class="bg-gray-800 h-2 rounded-full" style="width: {{ $progresso }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 text-right">
                                        {{ number_format($progresso, 0) }}% da meta de R$ {{ number_format($cofrinho->meta, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <a href="{{ route('cofrinhos.show', $cofrinho) }}" class="text-sm font-bold text-gray-800 hover:text-gray-600">
                                    GERIR SALDO &rarr;
                                </a>
                                <a href="{{ route('cofrinhos.edit', $cofrinho) }}" class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-3 py-12 text-center text-gray-400 bg-white rounded-lg border border-dashed border-gray-300">
                        <p>Você ainda não criou nenhum cofrinho.</p>
                        <a href="{{ route('cofrinhos.create') }}" class="text-gray-800 font-bold hover:underline mt-2 inline-block">Criar o primeiro</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>