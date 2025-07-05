<x-app-layout>
    <h1>Classes</h1>
    <dialog id="class-dialog" class="border-solid border-2 border-gray-700 dark:border-gray-300 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 shadow">
        <livewire:admin.classes-form dialog-id="class-dialog" />
    </dialog>
    <x-button id="add">Add Class</x-button>
    <h3>Classes</h3>
    <table>
        <tr>
            <th class='px-4'>Name</th>
            <th class='px-4'>Internal Name</th>
            <th class='px-4'>Current?</th>
            <th class='px-4'>Enrolment</th>
            <th></th>
        </tr>
        @foreach($model as $class)
        <tr>
            <td>{{{$class->name}}}</td>
            <td>{{{$class->bucketname}}}</td>
            <td>{{{$class->current?'yes':'no'}}}</td>
            <td>{{{$class->enrolment}}}</td>
            <td><x-button name="edit" data-id="{{$class->id}}">Edit</x-button></td>
        </tr>
        @endforeach
    </table>
    @push('latescripts')
    <script>
        let classDialog = document.querySelector('dialog#class-dialog');
        document.querySelector('button#add').addEventListener('click', function(el, ev) {
            Livewire.dispatch('populate', {id: null});
            classDialog.showModal();
        });
        document.querySelectorAll('button[name="edit"]').forEach(function(btn) {
            btn.addEventListener('click', function(el, ev) {
                Livewire.dispatch('populate', {id: btn.dataset.id});
                classDialog.showModal();
            });
        });
    </script>
    @endpush
</x-app-layout>