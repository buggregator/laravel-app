<div class="mt-2">
    <table>
        <tr>
            <th>date</th>
            <td>{{ $date }}</td>
        </tr>
        <tr>
            <th>channel</th>
            <td>{{ $channel }}</td>
        </tr>
    </table>

    <h1 class="font-bold text-color-white mt-1 mb-2">
        <span class="bg-blue px-1">MONOLOG</span>
        <span class="px-1 {{ $levelColor }}">{{ $level }}</span>
    </h1>

    <div class="mb-1">
        @foreach($messages as $message)
            <p class="font-bold">{{ $message }}</p>
        @endforeach
    </div>
</div>
