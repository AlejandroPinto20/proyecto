<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Solicitar Desactivación de Cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Si deseas desactivar tu cuenta, puedes enviar una solicitud a los administradores. Tu cuenta será revisada y desactivada si es apropiado. Los administradores tienen control total sobre la eliminación y desactivación de cuentas.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'request-deactivation')"
    >Solicitar Desactivación</x-danger-button>

    <x-modal name="request-deactivation" :show="$errors->deactivationRequest->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.request-deactivation') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                Solicitar Desactivación de Cuenta
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Por favor, explica la razón por la que deseas desactivar tu cuenta. Un administrador revisará tu solicitud.
            </p>

            <div class="mt-6">
                <x-input-label for="reason" value="Razón de la solicitud" />
                <textarea
                    id="reason"
                    name="reason"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    rows="4"
                    placeholder="Explica por qué deseas desactivar tu cuenta..."
                    required
                ></textarea>
                <x-input-error :messages="$errors->get('reason')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancelar
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    Enviar Solicitud
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
