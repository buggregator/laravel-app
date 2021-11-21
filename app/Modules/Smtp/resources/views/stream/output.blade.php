<div class="mt-2">
    <table>
        <tr>
            <th>date</th>
            <td>{{ $date }}</td>
        </tr>
        <tr>
            <th>id</th>
            <td>{{ $id }}</td>
        </tr>
    </table>

    <h1 class="font-bold bg-blue text-white my-1 px-1">SMTP</h1>
    <h2 class="font-bold mb-2">{{ $subject }}</h2>

    <div class="mb-2">
        @if($addresses !== [])
            <table>
                <thead title="Addresses"></thead>
                @php($i = 1)
                @foreach($addresses as $type => $users)
                    <tbody>
                    <tr>
                        <th colspan="2" align="center">{{ $type }}</th>
                    </tr>

                    @foreach($users as $user)
                        <tr @if($loop->last) border="1" @endif>
                            <th>{{ $i++ }}.</th>
                            <td>
                                @if(!empty($user['name']))
                                    {{ $user['name'] }} [{{ $user['email'] }}]
                                @else
                                    {{ $user['email'] }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endforeach
            </table>
        @endif
    </div>

    <code>{{ $body }}</code>
</div>
