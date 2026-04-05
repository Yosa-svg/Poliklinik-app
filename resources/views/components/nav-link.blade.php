@props([
    'href' => '#',
    'active' => false,
    'icon' => 'fas fa-circle',
])

<a href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200
    {{ $active
        ? 'bg-white/20 text-white border-l-4 border-white pl-2.5 shadow-sm'
        : 'text-white/75 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
    <i class="{{ $icon }} w-4 text-center {{ $active ? 'text-white' : 'text-white/60' }}"></i>
    <span>{{ $slot }}</span>
</a>
