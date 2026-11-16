<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Sistema de Voluntariado UNIFRANZ') }}
    </div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('Únete a nuestra comunidad de voluntarios') }}
    </div>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('dashboard') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Continuar al Dashboard') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
