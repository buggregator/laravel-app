@if($isNew)
<h1 class="text-blue font-bold">Start measuring performance...</h1>
@else
<div>
    <table>
        <thead class="bg-gray font-bold" title="{{ $name }}"></thead>
        <tbody>
        <tr>
            <th>Total time</th>
            <td>{{ $totalTime }} s</td>
        </tr>
        <tr>
            <th>Time since last call</th>
            <td>{{ $timeSinceLastCall }} s</td>
        </tr>
        <tr>
            <th>Maximum memory usage</th>
            <td>{{ $memoryUsage }}</td>
        </tr>
        <tr>
            <th>Maximum memory usage since last call</th>
            <td>{{ $memoryUsageSinceLastCall }}</td>
        </tr>
        </tbody>
    </table>
</div>
@endif
