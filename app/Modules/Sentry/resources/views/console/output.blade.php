<div class="mt-2">
    <table>
        <tr>
            <th>date</th>
            <td>{{ $date }}</td>
        </tr>
        <tr>
            <th>type</th>
            <td>{{ $type }}</td>
        </tr>
    </table>

    <h1 class="font-bold text-color-white my-1 bg-green px-1">Sentry</h1>
    <h2 class="font-bold">{{ $type }}</h2>
    <p class="font-bold mb-2">{{ $message }}</p>

    <code line="{{ $line }}">{{ $codeSnippet }}</code>
</div>
