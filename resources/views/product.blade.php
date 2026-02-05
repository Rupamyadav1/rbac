@extends('layouts.main')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Page Title --}}
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">
                Our Products
            </h1>

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                @forelse ($products as $product)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden flex flex-col">

                        {{-- Product Image --}}
                        <img
                            src="{{ $product->images->first()
                                ? asset('uploads/products/' . $product->images->first()->image)
                                : asset('uploads/products/default-image.jpg') }}"
                            alt="{{ $product->title }}"
                            class="w-full h-48 object-cover">

                        <div class="p-4 flex flex-col flex-1">

                            {{-- Product Title --}}
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">
                                {{ $product->title }}
                            </h2>

                            {{-- Price --}}
                            <p class="text-md font-bold text-green-700 mb-2">
                                ₹ {{ number_format($product->price, 2) }}
                            </p>

                            {{-- Short Description --}}
                            <p class="text-sm text-gray-600 mb-4">
                                {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 80) }}
                            </p>

                            {{-- Action --}}
                           <div class="mt-auto">
                            <a href="{{ route('product-detail',$product->slug) }}"
                                class="inline-flex items-center justify-center w-full text-blue-600 font-semibold py-2 border border-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                                Read More
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>


                        </div>
                    </div>
                @empty
                    <p class="text-center col-span-3 text-gray-500">
                        No products found.
                    </p>
                @endforelse

            </div>
        </div>
    </div>

@endsection
