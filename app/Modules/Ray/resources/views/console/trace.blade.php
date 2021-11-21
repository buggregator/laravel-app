@if($frame)
    <h2 class="font-bold">{{ $frame['class'] }}:{{ $frame['method'] }} on line <span
            class="text-yellow">{{ $frame['line'] }}</span></h2>
    <p class="text-gray">{{ $frame['file'] }}</p>
@endif

<div class="mt-1">
    @foreach($trace as $pos => $line)
        @if(is_array($line))
            <div>
                <span class="text-gray ml-{{ 3 - strlen($pos) }}">{{ $pos }}</span>.
                <span class="font-bold">{{ $line['file'] }}</span>:<span
                    class="text-yellow font-bold">{{ $line['line'] }}
            </div>
            <div>
                <span class="text-gray ml-5">{{ $line['class'] }}{{ $line['function'] }}</span>
            </div>
        @else
            <div class="mt-1">
                <span class="font-bold text-blue ml-5">{{ $line }}</span>
            </div>
        @endif
    @endforeach
</div>
