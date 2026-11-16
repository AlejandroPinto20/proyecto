@props(['title', 'subtitle' => null, 'actions' => null])

<div class="bg-white shadow-sm border-b border-gray-200 mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
                @if($subtitle)
                <p class="mt-2 text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
            
            @if($actions)
            <div class="flex space-x-3">
                {{ $actions }}
            </div>
            @endif
        </div>
    </div>
</div>