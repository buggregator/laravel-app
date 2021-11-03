<div class="mb-1">
    <h1 class="mb-1 font-bold">
        <span class="bg-blue px-1">→ TCP</span>
        <span class="bg-yellow text-black px-1">{{ $request->event }}</span>
    </h1>

    <div>
        <table>
            <tr>
                <th>Server</th>
                <td>{{ $request->server }}</td>
            </tr>
            <tr>
                <th>Connection UUID</th>
                <td>{{ $request->connectionUuid }}</td>
            </tr>
            <tr>
                <th>Memory</th>
                <td>{{ $memory }} mb</td>
            </tr>
        </table>
    </div>

    @if(trim($request->body))
    <h1 class="mb-1 font-bold">
        <span class="bg-magenta px-1">Request body</span>
    </h1>
    <code>{{ trim($request->body) }}</code>
    @endif
</div>
