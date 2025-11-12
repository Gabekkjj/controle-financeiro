<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="flex h-screen bg-gray-100">
            
            <aside class="w-64 bg-gray-800 text-white flex-shrink-0">
                @include('layouts.navigation')
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden">
                
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Intercepta todos os cliques em botões de "Excluir"
            document.addEventListener('DOMContentLoaded', function () {
                // Procura por todos os formulários que tenham 'data-confirm-delete'
                const deleteForms = document.querySelectorAll('form[data-confirm-delete]');

                deleteForms.forEach(form => {
                    form.addEventListener('submit', function (event) {
                        // Para o envio do formulário
                        event.preventDefault(); 
                        
                        Swal.fire({
                            title: 'Tem a certeza?',
                            text: "Esta ação não pode ser revertida!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#1f2937', // Cor cinza-escuro (nosso estilo)
                            cancelButtonColor: '#6b7280', // Cor cinza-claro
                            confirmButtonText: 'Sim, excluir!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Se o utilizador confirmar, envia o formulário
                                event.target.submit(); 
                            }
                        });
                    });
                });
            });
        </script>
        </body>
</html>