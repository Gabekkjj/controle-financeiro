<div class="p-4">
    <a href="{{ route('dashboard') }}" class="flex items-center justify-center mb-6">
        <x-application-logo class="block h-9 w-auto fill-current text-white" />
    </a>

    <nav class="space-y-2">
        <x-nav-link-sidebar :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link-sidebar>
        
        <x-nav-link-sidebar :href="route('transacoes.index')" :active="request()->routeIs('transacoes.*')">
            {{ __('Transações') }}
        </x-nav-link-sidebar>
        
        <x-nav-link-sidebar :href="route('cofrinhos.index')" :active="request()->routeIs('cofrinhos.*')">
            {{ __('Cofrinhos') }}
        </x-nav-link-sidebar>

        <x-nav-link-sidebar :href="route('categorias.index')" :active="request()->routeIs('categorias.*')">
            {{ __('Categorias') }}
        </x-nav-link-sidebar>
    </nav>
    
    <div class="absolute bottom-0 left-0 w-full p-4">
        <div class="font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
        <div class="font-medium text-sm text-gray-400 mb-2">{{ Auth::user()->email }}</div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="text-sm text-gray-400 hover:text-white">
                {{ __('Log Out') }}
            </a>
        </form>
    </div>
</div>