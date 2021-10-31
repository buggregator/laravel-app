<div class="mb-2">
    <table>
        <tr>
            <th>date</th>
            <td>{{ $date }}</td>
        </tr>
        <tr>
            <th>source</th>
            <td>{{ $source }}</td>
        </tr>
        <tr>
            <th>file</th>
            <td>{{ $file }}</td>
        </tr>
    </table>

    <h1 class="font-bold text-white mb-1">
        <span class="bg-blue px-1">RAY</span>
        <span class="px-1 bg-{{ $color }}">{{ $type }}</span>
        @if($label)
            <span class="bg-yellow px-1">{{ $label }}</span>
        @endif
    </h1>

    {!! $content !!}
</div>
