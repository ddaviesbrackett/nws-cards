<x-app-layout>
	<h1>Expenses</h1>
	<dialog id="expense-dialog">
		<livewire:admin.expense-form dialog-id="expense-dialog" />
	</dialog>
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
			<td><x-button name="edit" data-id="{{$exp->id}}">Edit</x-button></td>
			<td><x-button name="delete" data-id="{{$exp->id}}">Delete</x-button></td>
		</tr>
		@endforeach
	</table>
	@push('latescripts')
	<script>
		let expenseDialog = document.querySelector('dialog#expense-dialog');
		document.querySelector('button#add').addEventListener('click', function(el, ev) {
			Livewire.dispatch('populate', {id: null});
			expenseDialog.showModal();
		});
		document.querySelectorAll('button[name="edit"]').forEach(function(btn) {
			btn.addEventListener('click', function(el, ev) {
				Livewire.dispatch('populate', {id: btn.dataset.id});
				expenseDialog.showModal();
			});
		});
		document.querySelectorAll('button[name="delete"]').forEach(function(btn) {
			btn.addEventListener('click', function(el, ev) {
				Livewire.dispatch('delete', {id: btn.dataset.id});
				expenseDialog.showModal();
			});
		});
	</script>
	@endpush
</x-app-layout>