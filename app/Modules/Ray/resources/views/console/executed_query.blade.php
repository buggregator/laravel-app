<code>{{ $query }}</code>

<div class="mt-1">
    <table>
        <thead class="bg-blue" title="Info"></thead>
        <tbody>
        @foreach($data as $title => $value)
            <tr>
                <th>{{ $title }}</th>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
