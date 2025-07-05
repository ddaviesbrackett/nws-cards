<div class="px-4 py-2">
    <x-validation-errors class="mb-4" />
    <form wire:submit="save" x-data>
        @csrf
        <input type="hidden" id="id" name="id" wire:model="id" />
        <x-label>
            Name:
            <x-input id="name" name="name" type="text" wire:model="name" required autofocus />
        </x-label>
        <x-label>
            Internal Name:
            <x-input id="bucketname" name="bucketname" type="text" wire:model="bucketname" required />
        </x-label>
        <x-label>
            Display Order:
            <x-input id="displayorder" name="displayorder" type="number" wire:model="displayorder" required />
        </x-label>
        <x-label>
            Current?
            <x-input id="current" name="current" type="checkbox" wire:model="current" />
        </x-label>
        <x-label>
            Enrolment:
            <x-input id="enrolment" name="enrolment" type="number" wire:model="enrolment" required />
        </x-label>
        <x-button type="submit">
            Submit
        </x-button>
        <x-button @click.prevent="document.querySelector('dialog#{{$dialogId}}').close()">{{-- ew. --}}
            Cancel
        </x-button>
    </form>
</div>