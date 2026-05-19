<x-app-layout>
    <h1>Order list</h1>
    <dialog id="profit-dialog" class="border-solid border-2 border-gray-700 dark:border-gray-300 bg-gray-100 dark:bg-gray-900 text-gray-700 dark:text-gray-300 shadow">
        <livewire:admin.profit-form dialog-id="profit-dialog" />
    </dialog>
    <table class='table'>
        <tr>
            <th class="px-4 text-left"></th>
            <th class="px-4 text-left">Delivery Date</th>
            <th class="px-4 text-left"># Orders</th>
            <th class="px-4 text-left">Save-On Cards</th>
            <th class="px-4 text-left">Co-op Cards</th>
            <th class="px-4 text-left">Save-On Profit</th>
            <th class="px-4 text-left">Co-op Profit</th>
        </tr>
        @foreach($model as $order)
        <tr>
            <td>
                <x-link href="{{URL::route('admin-order', ['id' => $order['id']])}}">Order Sheet</x-link> &middot;
                <x-link href="{{URL::route('admin-caft', ['id' => $order['id']])}}">CAFT</x-link> &middot;
                <x-link href="{{URL::route('admin-cc', ['id' => $order['id']])}}">CC</x-link>
            </td>
            <td class="px-4">{{{$order['delivery']}}}</td>
            <td class="px-4">{{{$order['orders']}}}</td>
            <td class="px-4">{{{$order['saveon']}}}</td>
            <td class="px-4">{{{$order['coop']}}}</td>
            <td class="px-4"><x-button name="edit" data-id="{{$order['id']}}">{{$order['saveon_profit']}}%</x-button></td>
            <td class="px-4"><x-button name="edit" data-id="{{$order['id']}}">{{$order['coop_profit']}}%</x-button></td>
        </tr>
        @endforeach
    </table>
    @push('latescripts')
    <script>
        let dialog = document.querySelector('dialog#profit-dialog');
        document.querySelectorAll('button[name="edit"]').forEach(function(btn) {
            btn.addEventListener('click', function(el, ev) {
                Livewire.dispatch('populate', {
                    dateforprofit: btn.dataset.id
                });
                dialog.showModal();
            });
        });
    </script>
    @endpush
</x-app-layout>