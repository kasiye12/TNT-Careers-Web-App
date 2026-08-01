@if(!empty($items))
<nav class="flex items-center gap-2 text-sm text-gray-400 mb-4">
    <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
    @foreach($items as $label => $url)
        <i class="fas fa-chevron-right text-[10px]"></i>
        @if($url)
            <a href="{{ $url }}" class="hover:text-blue-600 transition-colors">{{ $label }}</a>
        @else
            <span class="text-gray-600 font-medium">{{ $label }}</span>
        @endif
    @endforeach
</nav>
@endif
