@extends('layouts.main')

@section('content')
    {{-- ================= HERO SECTION ================= --}}
    <section class="bg-gray-100 py-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

            <div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    Welcome to Company
                </h1>
                <p class="text-gray-600 mb-6">
                    Buy amazing products at the best prices. Quality, trust, and fast delivery.
                </p>
                <a href="{{ route('products') }}"
                    class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Explore Products
                </a>
            </div>

            <div>
                <img src="{{ asset('img/photo-1523275335684-37898b6baf30.jpg') }}" class="rounded-lg shadow" alt="Shopping">
            </div>

        </div>
    </section>

    {{-- ================= FEATURED PRODUCTS ================= --}}
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-10">
                Featured Products
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach ($featuredProducts as $product)
                    <div class="bg-white shadow rounded overflow-hidden">

                        <img src="{{ $product->images->first()
                            ? asset('uploads/products/' . $product->images->first()->image)
                            : asset('uploads/products/default-image.jpg') }}"
                            alt="{{ $product->title }}" class="w-full h-48 object-cover">

                        <div class="p-4">
                            <h3 class="font-semibold text-lg">{{ $product->title }}</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 80) }}
                            </p>
                            <span class="text-blue-600 font-bold"> ₹ {{ number_format($product->price, 2) }}</span>
                        </div>
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
                @endforeach
            </div>

        </div>
    </section>
@endsection
