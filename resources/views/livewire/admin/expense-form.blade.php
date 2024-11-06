<div class="px-4 py-2">
    <x-validation-errors class="mb-4" />
    <form wire:submit="save" x-data>
        @csrf
        <input type="hidden" id="id" name="id" wire:model="id" />
        <x-label>
            Date:
            <x-input id="expense_date" name="expense_date" type="date" wire:model="expense_date" required autofocus />
        </x-label>
        <x-label>
            Description:
            <x-input id="description" name="description" type="text" wire:model="description" required />
        </x-label>
        <x-label>
            Amount:
            <x-input id="amount" name="amount" type="text" wire:model="amount" required />
        </x-label>
        <x-label>
            Account:
            <select id="class_id" name="class_id" wire:model="class_id" value="1" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                @foreach( $schoolclasses as $name => $id)
                <option value="{{$id}}">{{$name}}</option>
                @endforeach
            </select>
        </x-label>
        <x-button type="submit">
            Submit
        </x-button>
        <x-button @click.prevent="document.querySelector('dialog#{{$dialogId}}').close()">{{-- ew. --}}
            Cancel
        </x-button>
    </form>
</div>