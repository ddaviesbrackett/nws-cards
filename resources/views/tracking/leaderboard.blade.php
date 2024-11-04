<x-guest-layout>
    <x-slot name="header">
    We've raised {{$total}} so far
    </x-slot>
    <table class="table">
        <tr>
            <th></th>
            <th>Amount Raised</th>
            <th>Expenses</th>
            <th>Funds Available</th>
        </tr>
        @foreach($buckets as $name => $vals)
            <tr>
                <td><x-link href="/tracking/{{$name}}">{{$vals['nm']}}</x-link></td>
                <td>{{$vals['raised']}}</td>
                <td>{{$vals['spent']}}</td>
                <td>{{$vals['available']}}</td>
            </tr>
        @endforeach
    </table>
</x-guest-layout>