<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Financeiro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- 1. RESUMO FINANCEIRO (3 CARDS) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Card Receitas (Entradas) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Entradas</p>
                        <p class="text-2xl font-bold text-green-600">+ R$ {{ number_format($totalReceitas, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    </div>
                </div>

                <!-- Card Despesas (Saídas) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Saídas</p>
                        <p class="text-2xl font-bold text-red-600">- R$ {{ number_format($totalDespesas, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                    </div>
                </div>

                <!-- Card Saldo (Total) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-gray-800 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Saldo Atual</p>
                        <p class="text-2xl font-bold {{ $saldo >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($saldo, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-full bg-gray-100 text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                </div>

            </div>

            <!-- 2. AÇÕES E TABELA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                <!-- Ações Rápidas -->
                <div class="lg:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Acesso Rápido</h3>
                    <div class="flex flex-col space-y-3">
                        <a href="{{ route('transacoes.create') }}" class="w-full justify-center inline-flex items-center px-4 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 transition text-center">
                            + Nova Receita
                        </a>
                        <a href="{{ route('transacoes.create') }}" class="w-full justify-center inline-flex items-center px-4 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 transition text-center">
                            - Nova Despesa
                        </a>
                        <a href="{{ route('cofrinhos.index') }}" class="w-full justify-center inline-flex items-center px-4 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition text-center">
                            Meus Cofrinhos
                        </a>
                    </div>
                </div>

                <!-- Tabela Recente -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Últimas Movimentações</h3>
                        <a href="{{ route('transacoes.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">Ver todas &rarr;</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Valor</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($recentes as $transacao)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($transacao->data)->format('d/m') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                            {{ $transacao->descricao ?? $transacao->categoria->nome }}
                                            <div class="text-xs text-gray-400 font-normal">{{ $transacao->categoria->nome }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $transacao->tipo == 'receita' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $transacao->tipo == 'despesa' ? '-' : '+' }}
                                            R$ {{ number_format($transacao->valor, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                            <p>Nenhuma transação recente.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>