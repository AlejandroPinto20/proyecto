@props(['title' => null, 'subtitle' => null])

<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
    @if($title)
    <div class="bg-gradient-to-r from-unifranz-orange to-unifranz-orange-dark px-6 py-4 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-white">{{ $title }}</h2>
        @if($subtitle)
        <p class="text-unifranz-orange-light text-sm mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
</div>