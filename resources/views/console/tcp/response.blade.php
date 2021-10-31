<div class="mb-1">
    <h1 class="mb-1 font-bold">
        <span class="bg-green-700 px-1">← TCP</span>
        <span class="bg-yellow text-black px-1">{{ $response->getContext() }}</span>
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
                <th>Duration</th>
                <td>{{ $duration }} ms</td>
            </tr>
            <tr>
                <th>Memory</th>
                <td>{{ $memory }} mb</td>
            </tr>
        </table>
    </div>

    @if(trim($response->getBody()))
    <h1 class="mb-1 font-bold">
        <span class="bg-magenta px-1">Response body</span>
    </h1>
    <code>{{ trim($response->getBody()) }}</code>
    @endif
</div>
