<x-app-layout>
	<h1>Expenses</h1>
	<livewire:admin.expense-form dialog-id="expense-dialog" />
	<x-button id="add">Add Expense</x-button>
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
	@push('latescripts')
		<script>
			let expenseDialog = document.querySelector('dialog#expense-dialog');
			document.querySelector('button#add').addEventListener('click', function(el, ev) {
				expenseDialog.showModal();
			});
		</script>
	@endpush
</x-app-layout>