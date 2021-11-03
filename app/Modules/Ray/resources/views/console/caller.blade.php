<div>
    @if($class)
    <span class="text-gray">{{ $class }}:</span>
    @endif

    <span class="font-bold">{{ $method }}</span>
    on line <span class="text-yellow">{{ $line }}</span>
</div>
