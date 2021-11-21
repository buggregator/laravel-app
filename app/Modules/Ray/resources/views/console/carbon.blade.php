<table class="mt-1">
    @foreach($data as $title => $value)
        <tr>
            <th>{{ $title }}</th>
            <td>{{ $value }}</td>
        </tr>
    @endforeach
</table>
