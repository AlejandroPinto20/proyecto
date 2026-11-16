<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Certificado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('certificates.update', $certificate) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="user_id" :value="__('Usuario')" />
                            <select name="user_id" id="user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $certificate->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="activity_title" :value="__('Título de Actividad')" />
                            <x-text-input id="activity_title" class="block mt-1 w-full" type="text" name="activity_title" :value="old('activity_title', $certificate->activity_title)" required autofocus />
                            <x-input-error :messages="$errors->get('activity_title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="hours_volunteered" :value="__('Horas de Voluntariado')" />
                            <x-text-input id="hours_volunteered" class="block mt-1 w-full" type="number" name="hours_volunteered" :value="old('hours_volunteered', $certificate->hours_volunteered ?? $certificate->total_hours)" required />
                            <x-input-error :messages="$errors->get('hours_volunteered')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="issue_date" :value="__('Fecha de Emisión')" />
                            <x-text-input id="issue_date" class="block mt-1 w-full" type="date" name="issue_date" :value="old('issue_date', $certificate->issue_date->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('issue_date')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Estado')" />
                            <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="active" {{ $certificate->status == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="revoked" {{ $certificate->status == 'revoked' ? 'selected' : '' }}>Revocado</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Actualizar Certificado') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
