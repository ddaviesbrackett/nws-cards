<x-app-layout>
    <h1>CAFT entry form</h1>
    @php
        $totalRows = 0;
    @endphp
    @foreach($model as $name => $bucket)
        @php
            $totalRows += count($bucket);
        @endphp
        <h2>{{{$name}}}</h2>
        <table>
            <tr>
                <th>Account</th>
                <th>Institution</th>
                <th>Transit</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Frequency</th>
                <th>Has One-Time Order?</th>
            </tr>
            @foreach($bucket as $info)
                <tr>
                    <td>{{{$info['acct']}}}</td>
                    <td>{{{$info['institution']}}}</td>
                    <td>{{{$info['transit']}}}</td>
                    <td>{{{$info['order']->user->name}}}</td>
                    <td>${{{$info['order']->totalCards()}}}00</td>
                    <td>{{{$info['order']->user->schedule}}}</td>
                    <td>{{{$info['order']->hasOnetime()?'YES':''}}}</td>
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
    <h3>Download CAFT file for upload to C1</h3>
    <p>
    <form method="GET" action="/admin/caftfile/{{$cutoff}}">
        <label>
            File Number
            <x-input name="filenum" type="text" />
        </label>
        <x-button type='submit' class='btn btn-danger btn-lg'>
            Get CAFT File
        </x-button>
    </form>
    </p>
</x-app-layout>