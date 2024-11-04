<x-guest-layout>
    <x-slot name="header">
        {{$sum}} raised for {{$name}} so far
    </x-slot>
    <h4>Funds Raised</h4>
    <table class='table'>
        <tr>
            <th>Order</th>
            <th>Amount Raised</th>
        </tr>
        @if (! empty($byCutoff))
        @foreach($byCutoff as $co)
            <tr>
                <td>{{$co['date']->format('F jS Y')}}</td>
                <td>{{$co['profit']}}</td>
            </tr>
        @endforeach
    @endif
    </table>
</x-guest-layout>