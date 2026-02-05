<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             @if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="mb-4 flex items-start justify-between p-4 rounded bg-green-100 text-green-800 border border-green-300"
    >
        <span>
            {{ session('success') }}
        </span>

        <button
            @click="show = false"
            class="ml-4 text-green-800 hover:text-green-900 font-bold text-lg leading-none"
            aria-label="Dismiss"
        >
            &times;
        </button>
    </div>
@endif




            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-2 gap-4 mt-4">
                    @foreach ($products as $product)
                    <div class="bg-neutral-primary-soft block max-w-sm border rounded shadow">
                        <div class="flex items-center justify-center h-64"><img
                                src="{{ $product->images->first()
                    ? asset('uploads/products/' . $product->images->first()->image)
                    : asset('images/no-image.png') }}"
                                alt="{{ $product->name }}"
                                class="w-48 h-48 object-cover"> </div>
                        <div class="p-6 text-left">
                            <h5 class="mt-3 mb-4 text-xl font-semibold">
                                {{ $product->name }}
                            </h5>
                            <p class="mb-4 text-lg font-bold">
                                ₹{{ $product->price }}
                            </p>


                            <form method="POST" action="{{ route('user.buyNow') }}">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <button type="submit"
        class="mt-4 w-full bg-blue-600 text-white py-2 rounded">
        Buy Now
    </button>
</form>


                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>