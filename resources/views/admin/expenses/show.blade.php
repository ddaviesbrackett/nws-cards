<x-app-layout>
    <h1>Expenses</h1>
    <h3>Record a new expense</h3>
    <x-validation-errors class="mb-4"/>
    <form method="POST" action="{{ route('admin-postExpense') }}" x-data>
        @csrf
        <x-label>
            Date:
            <x-input id="date" name="date" type="date" :value="old('date')" required autofocus />
        </x-label>
        <x-label>
            Description:
            <x-input id="description" name="description" type="text" :value="old('description')" required />
        </x-label>
        <x-label>
            Amount:
            <x-input id="amount" name="amount" type="text" :value="old('amount')" required />
        </x-label>
        <x-label>
            Account:
            <select id="acount" name="account">
                @foreach( $schoolclasses as $name => $id)
                    <option value="$id">{{$name}}</option>
                @endforeach
            </select>
        </x-label>
        <x-button type="submit">
            Add Expense
        </x-button>
    </form>
    <h3>Expense History</h3>
<table>
	<tr>
		<th>Date</th>
		<th>Description</th>
		<th>Amount</th>
		<th>Account</th>
		<th></th>
        <th></th>
	</tr>
	@foreach($model as $exp)
		<tr>
			<td>{{{$exp->expense_date->toDateString()}}}</td>
			<td>{{{$exp->description}}}</td>
			<td>{{{$exp->amount}}}</td>
			<td>{{{$exp->schoolclass->name}}}</td>
			<td><a href="{{route('admin-editexpense', ['expense' => $exp->id])}}">Edit</a></td>
			<td><a href="{{route('admin-deleteexpense', ['expense' => $exp->id])}}">Delete</a></td>
		</tr>
	@endforeach
</table>
</x-app-layout>