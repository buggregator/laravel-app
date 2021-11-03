<div class="mb-1">
    <h1 class="mb-1 font-bold">
        <span class="bg-blue px-1">HTTP</span>
        <span class="px-1 bg-{{ $color }}">{{ $request->method() }}</span>
        <span class="px-1 bg-{{ $responseColor }}">{{ $response->getStatusCode() }}</span>
    </h1>

    <div>
        <table>
            <tbody>
            <tr>
                <th>URL</th>
                <td>{{ $request->fullUrl() }}</td>
            </tr>
            <tr>
                <th>IP</th>
                <td>{{ $request->ip() }}</td>
            </tr>
            <tr>
                <th>Duration</th>
                <td>{{ $duration }} ms</td>
            </tr>
            <tr>
                <th>Memory</th>
                <td>{{ $memory }} mb</td>
            </tr>
            </tbody>
        </table>

        {{--
        <table>
            <tbody title="headers">
            @foreach($request->headers->all() as $key => $values)
                <tr>
                    <th>{{ $key }}</th>
                    <td>
                        @foreach($values as $value)
                            {{ $value }}<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        --}}
    </div>
</div>
