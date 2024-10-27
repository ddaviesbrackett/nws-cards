<x-app-layout>
    <h1>Expenses</h1>
    <dialog id="expense-dialog" class="border-solid border-2 border-gray-700 dark:border-gray-300 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 shadow">
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
            <td>
            <form method="POST" action="{{ route('admin-deleteexpense', [$exp->id]) }}" x-data>
                    @csrf
                    @method('DELETE')
                <x-button name="delete" type="submit" onclick="return confirm('really delete this expense?');">Delete</x-button>
            </form>
            </td>
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
    </script>
    @endpush
</x-app-layout>