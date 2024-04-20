<x-app-layout>
    <h1>Order list</h1>
    <dialog id="profit-dialog">
        <livewire:admin.profit-form dialog-id="profit-dialog" />
    </dialog>
    <table class='table'>
        <tr>
            <th></th>
            <th>Delivery Date</th>
            <th># Orders</th>
            <th>Save-On Cards</th>
            <th>Co-op Cards</th>
            <th>Save-On Profit</th>
            <th>Co-op Profit</th>
        </tr>
        @foreach($model as $order)
        <tr>
            <td>
                <a href="{{URL::route('admin-order', ['id' => $order['id']])}}">Order Sheet</a> &middot;
                <a href="{{URL::route('admin-caft', ['id' => $order['id']])}}">CAFT</a>
            </td>
            <td>{{{$order['delivery']}}}</td>
            <td>{{{$order['orders']}}}</td>
            <td>{{{$order['saveon']}}}</td>
            <td>{{{$order['coop']}}}</td>
            <td><x-button name="edit" data-id="{{$order['id']}}">{{$order['saveon_profit']}}%</x-button></td>
            <td><x-button name="edit" data-id="{{$order['id']}}">{{$order['coop_profit']}}%</x-button></td>
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