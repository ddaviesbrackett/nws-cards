<div>
    <x-validation-errors class="mb-4" />
    <form wire:submit="save" x-data>
        @csrf
        <input type="hidden" id="id" name="id" wire:model="id" />
        <x-label>
            Saveon Cheque:
            <x-input id="saveon_cheque_value" name="saveon_cheque_value" type="number" wire:model="saveon_cheque_value" required autofocus />
        </x-label>
        <x-label>
            Saveon Card Value:
            <x-input id="saveon_card_value" name="saveon_card_value" type="number" wire:model="saveon_card_value" required />
        </x-label>
        <x-label>
            Co-op Cheque:
            <x-input id="coop_cheque_value" name="coop_cheque_value" type="number" wire:model="coop_cheque_value" required />
        </x-label>
        <x-label>
            Co-op Card Value:
            <x-input id="coop_card_value" name="coop_card_value" type="number" wire:model="coop_card_value" required />
        </x-label>
        <x-button type="submit">
            Calculate Order Profit
        </x-button>
        <x-button @click.prevent="document.querySelector('dialog#{{$dialogId}}').close()">{{-- ew. --}}
            Cancel
        </x-button>
    </form>
</div>