<x-mail::message>
# Nightly processing
<ul>
@foreach ($model as $result)
<li>{{$result}}</li>
@endforeach
</ul>

From<br>
{{ config('app.name') }}
</x-mail::message>
