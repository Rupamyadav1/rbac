<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orderItems)
            @foreach ($orderItems as $orderItem)

            <div class="mb-6 p-4 border rounded shadow-sm bg-white">
                <h3 class="text-lg font-semibold mb-2">#{{ $orderItem->orders->order_number }}</h3>
                <p><strong>Date:</strong> {{ $orderItem->orders->order_date }}</p>

                <table class="w-full mt-3 border border-collapse text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 text-left">Product</th>
                            <th class="border px-2 py-1 text-right">Price</th>
                            <th class="border px-2 py-1 text-right">Qty</th>
                            <th class="border px-2 py-1 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1">{{ $orderItem->products->title }}</td>
                            <td class="border px-2 py-1 text-right">{{ $orderItem->unit_price }}</td>
                            <td class="border px-2 py-1 text-right">
                                ₹{{ ($orderItem->unit_price * $orderItem->quantity) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="text-right mt-3 font-bold">
                    Grand Total:
                    ₹{{ ($orderItem->unit_price * $orderItem->quantity) }}
                </div>


            </div>
            @endforeach
            @endif

        </div>
    </div>
</x-app-layout>