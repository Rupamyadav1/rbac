@extends('layouts.main')

@section('content')

<div class="bg-gray-100 min-h-screen">

```
<!-- Product Detail Section -->
<div class="max-w-7xl mx-auto px-6 py-12">
    <div class="grid md:grid-cols-2 gap-12 bg-white p-8 rounded-lg shadow">

        <!-- Product Images -->
        <div>
            <!-- Main Image -->
            <img src="{{ asset('uploads/products/' . ($product->images->first()->image ?? 'no-image.png')) }}" 
                 alt="{{ $product->title }}" 
                 class="w-full h-96 object-cover rounded-lg mb-4">

            <!-- Thumbnail Gallery -->
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images->skip(1) as $image)
                    <img src="{{ asset('uploads/products/' . $image->image) }}" 
                         class="h-24 w-full object-cover rounded cursor-pointer hover:opacity-80">
                @endforeach
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->title }}</h1>

            <p class="text-sm text-gray-500 mb-2">
                Category: 
                <span class="text-blue-600 font-medium">{{ $product->category->title ?? 'Uncategorized' }}</span>
            </p>

            <p class="text-sm text-gray-500 mb-4">
                Slug: {{ $product->slug }}
            </p>

            <div class="text-gray-700 mb-6">
                {!! $product->short_description !!}
            </div>

            <p class="text-xl font-semibold text-blue-600 mb-6">
                ₹{{ number_format($product->price, 2) }}
            </p>

           
        </div>
    </div>

    <!-- Full Description -->
    <div class="bg-white mt-10 p-8 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-4">Product Description</h2>
        <div class="text-gray-700 leading-relaxed">
            {!! $product->description !!}
        </div>
    </div>

</div>
```

</div>
@endsection
