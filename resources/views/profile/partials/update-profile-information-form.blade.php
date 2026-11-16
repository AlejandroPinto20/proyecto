<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Información del Perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Actualiza la información de tu perfil y dirección de correo electrónico.
        </p>
    </header>



    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" value="Foto de Perfil" />
            <div class="mt-2 flex items-center space-x-4">
                @if($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="h-16 w-16 rounded-full object-cover">
                @else
                    <div class="h-16 w-16 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 font-medium">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
                <input id="profile_photo" name="profile_photo" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" accept="image/*">
            </div>
            <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF hasta 2MB</p>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Correo Electrónico" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />


        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" value="Teléfono" />
            <div class="flex mt-1">
                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg">
                    🇧🇴 +591
                </span>
                @php
                    $phoneNumber = $user->phone;
                    if (str_starts_with($phoneNumber, '+591')) {
                        $phoneNumber = substr($phoneNumber, 4);
                    }
                @endphp
                <x-text-input id="phone" name="phone" type="tel" class="flex-1 rounded-none rounded-r-lg" :value="old('phone', $phoneNumber)" maxlength="8" pattern="[0-9]{8}" placeholder="12345678" oninput="this.value = this.value.replace(/[^0-9]/g, '')" autocomplete="tel" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            <p class="text-xs text-gray-500 mt-1">Ingresa tu número de teléfono (8 dígitos, solo números)</p>
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" value="Biografía" />
            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="off">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Guardar</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Guardado.</p>
            @endif
        </div>
    </form>
</section>
