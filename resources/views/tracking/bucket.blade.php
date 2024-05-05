<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Leaderboard
        </h2>
    </x-slot>
    <h1>{{$sum}} raised for {{$name}} so far</h1>
    <h3>{{$name}} has {{$supporters}} supporters</h3>
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