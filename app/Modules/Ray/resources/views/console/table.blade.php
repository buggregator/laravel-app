<div>
    <table>
        <thead class="bg-blue font-bold" title="{{ $label }}">
        <tr>
            <th>Key</th>
            <td>Value</td>
        </tr>
        </thead>
        @foreach($rows as $title => $value)
            <tr>
                <th>{!! $title !!}</th>
                <td>{!! $value !!}</td>
            </tr>
        @endforeach
    </table>
</div>
