<x-app-layout>
    <h1>CC entry form</h1>
    @php
        $totalRows = 0;
    @endphp
    @foreach($model as $name => $bucket)
        @php
            $totalRows += count($bucket);
        @endphp
        <h2 class="pt-8">{{{$name}}}</h2>
        <table class="table">
            <tr>
                <th class="px-4 text-left">Name</th>
                <th class="px-4 text-left">Amount</th>
                <th class="px-4 text-left">Has One-Time Order?</th>
            </tr>
            @foreach($bucket as $info)
                <tr>
                    <td class="px-4">{{{$info['order']->user->name}}}</td>
                    <td class="px-4">${{{$info['order']->totalCards()}}}00</td>
                    <td class="px-4">{{{$info['order']->hasOnetime()?'YES':''}}}</td>
                </tr>
            @endforeach
        </table>
    @endforeach
    <p>
        Total rows: {{{$totalRows}}}
    </p>
    <p>
        Total amount: {{{$total}}}00
    </p>
</x-app-layout>