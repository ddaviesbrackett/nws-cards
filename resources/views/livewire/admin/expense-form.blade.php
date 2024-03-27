<div>
    <dialog id="{{$dialogId}}">
        <x-validation-errors class="mb-4" />
        <form wire:submit="save" x-data>
            @csrf
            <input type="hidden" id="id" name="id" wire:model="id" />
            <x-label>
                Date:
                <x-input id="date" name="date" type="date" wire:model="date" required autofocus />
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
                <select id="acount" name="account" wire:model="state">
                    @foreach( $schoolclasses as $name => $id)
                    <option value="$id">{{$name}}</option>
                    @endforeach
                </select>
            </x-label>
            <x-button type="submit">
                Submit
            </x-button>
            <x-button type="submit" button value="cancel" formmethod="dialog">
                Cancel
            </x-button>
        </form>
    </dialog>
</div>