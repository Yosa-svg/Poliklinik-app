@if (session('message'))
    @php
        $type = session('type', 'success');
        $colors = [
            'success' => 'bg-green-50 border-green-500 text-green-700',
            'danger'  => 'bg-red-50 border-red-500 text-red-700',
            'warning' => 'bg-amber-50 border-amber-500 text-amber-700',
            'info'    => 'bg-blue-50 border-blue-500 text-blue-700',
        ];
        $icons = [
            'success' => 'fas fa-check-circle',
            'danger'  => 'fas fa-exclamation-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info'    => 'fas fa-info-circle',
        ];
        $cls  = $colors[$type]  ?? $colors['success'];
        $icon = $icons[$type]   ?? $icons['success'];
    @endphp

    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="flex items-center gap-3 border-l-4 rounded-lg px-4 py-3 mb-4 text-sm font-medium {{ $cls }}">
        <i class="{{ $icon }}"></i>
        <span class="flex-1">{{ session('message') }}</span>
        <button @click="show = false" class="opacity-50 hover:opacity-100 transition-opacity">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
