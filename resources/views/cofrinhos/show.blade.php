<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gerir Cofrinho: {{ $cofrinho->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-gray-100 border border-gray-300 text-gray-700 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div class="md:col-span-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900">Saldo Atual</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">
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
                </div>

                <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Movimentar Dinheiro</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <form action="{{ route('cofrinhos.depositar', $cofrinho) }}" method="POST">
                                @csrf
                                <h4 class="font-semibold text-gray-800">Depositar</h4>
                                <p class="text-sm text-gray-600 mb-2">Move dinheiro do seu Saldo Principal para este cofrinho.</p>
                                
                                <x-input-label for="valor_deposito" :value="__('Valor (R$)')" />
                                <x-text-input id="valor_deposito" class="block mt-1 w-full" type="number" name="valor" required step="0.01" min="0.01" />
                                
                                <x-input-label for="data_deposito" :value="__('Data')" class="mt-2"/>
                                <x-text-input id="data_deposito" class="block mt-1 w-full" type="date" name="data" :value="date('Y-m-d')" required />
                                
                                <x-primary-button class="mt-4">
                                    Depositar
                                </x-primary-button>
                            </form>

                            <form action="{{ route('cofrinhos.retirar', $cofrinho) }}" method="POST">
                                @csrf
                                <h4 class="font-semibold text-gray-800">Retirar</h4>
                                <p class="text-sm text-gray-600 mb-2">Move dinheiro deste cofrinho de volta para o seu Saldo Principal.</p>
                                
                                <x-input-label for="valor_retirada" :value="__('Valor (R$)')" />
                                <x-text-input id="valor_retirada" class="block mt-1 w-full" type="number" name="valor" required step="0.01" min="0.01" />
                                
                                <x-input-label for="data_retirada" :value="__('Data')" class="mt-2"/>
                                <x-text-input id="data_retirada" class="block mt-1 w-full" type="date" name="data" :value="date('Y-m-d')" required />
                                
                                <x-primary-button class="mt-4 bg-gray-500 hover:bg-gray-600 focus:ring-gray-500">
                                    Retirar
                                </x-primary-button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Histórico do Cofrinho</h3>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor (R$)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($movimentacoes as $movimentacao)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($movimentacao->data)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $movimentacao->tipo == 'deposito' ? 'text-gray-900' : 'text-gray-500' }}">
                                        {{ ucfirst($movimentacao->tipo) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $movimentacao->tipo == 'deposito' ? 'text-gray-900' : 'text-gray-500' }}">
                                        {{ $movimentacao->tipo == 'retirada' ? '-' : '+' }}
                                        {{ number_format($movimentacao->valor, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhuma movimentação registrada neste cofrinho.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $movimentacoes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>