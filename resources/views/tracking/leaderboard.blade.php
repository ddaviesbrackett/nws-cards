<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Leaderboard
        </h2>
    </x-slot>
    <h1>We've raised {{$total}} so far</h1>
    <table class='table'>
        <tr>
            <th></th>
            <th>Amount Raised</th>
            <th>Supporters</th>
            <th>Expenses</th>
            <th>Funds Available</th>
        </tr>
        @foreach($buckets as $name => $vals)
            <tr>
                <td><a href="/tracking/{{$name}}">{{$vals['nm']}}</a></td>
                <td>{{$vals['raised']}}</td>
                <td>{{$vals['count']}}</td>
                <td>{{$vals['spent']}}</td>
                <td>{{$vals['available']}}</td>
            </tr>
        @endforeach
    </table>
</x-guest-layout>