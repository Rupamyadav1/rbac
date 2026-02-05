<x-app-layout>
   <x-slot name="header">
    <div class="flex flex-wrap items-center justify-between gap-4">
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>

        <div class="flex items-center gap-3">
            <a href="{{ route('product.create') }}"
                class="inline-flex items-center rounded-md bg-slate-700 px-4 py-2 text-sm font-medium text-white
                       hover:bg-slate-800 transition">
                Create
            </a>

            <a href="{{ route('products.export.csv') }}"
                class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white
                       hover:bg-green-700 transition">
                Export CSV
            </a>
        </div>

    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:
        px-6 lg:px-8">
            <x-message></x-message>
            <table class="w-full">

                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Name</th>
                        <th class="px-6 py-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-left">Image</th>
                        <th class="px-6 py-3 text-left">Category</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Short description</th>
                        <th class="px-6 py-3 text-left">Status</td>
                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($products->isNotEmpty())
                        @foreach ($products as $product)
                            <tr class="border-b">
                                <td class="px-6 py-3 text-left">{{ $product->id }}</td>
                                <td class="px-6 py-3 text-left">{{ $product->title }}</td>
                                <td class="px-6 py-3 text-left">{{ $product->slug }}</td>
                                <td class="px-6 py-3 text-left">
                                    @if ($product->images && $product->images->count())
                                        <img src="{{ asset('uploads/products/' . $product->images->first()->image) }}"
                                            height="100" width="100" alt="product-image"
                                            class="object-cover rounded">
                                    @else
                                        <img src="{{ asset('uploads/products/default-image.jpg') }}" height="100"
                                            width="100" alt="default-product-image" class="object-cover rounded">
                                    @endif
                                </td>

                                <td class="px-6 py-3 text-left">{{ $product->category_id }}</td>
                                <td class="px-6 py-3 text-left">₹{{ $product->price }}</td>
                                <td class="px-6 py-3 text-left">{!! $product->short_description !!}</td>
                                <td class="px-6 py-3 text-left">
                                    @if ($product->status == 1)
                                        <span class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded">
                                            Active
                                        </span>
                                    @else
                                        <span class="bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded">
                                            Inactive
                                        </span>
                                    @endif
                                </td>


                                <td class="px-8 py-3 text-left">
                                    {{ \Carbon\Carbon::parse($product->created_at)->format('d M, Y') }}</td>
                                <td class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        @can('edit products')
                                            
                                       
                                        <a href="{{ route('product.edit', $product->id) }}"
                                            class="bg-slate-700 text-sm rounded-md text-white px-3 py-2 hover:bg-slate-600">
                                            Edit
                                        </a>
                                         @endcan
                                         @can('delete products')
                                             
                                        

                                        <button onclick="deleteProduct({{ $product->id }})"
                                            class="bg-red-700 text-sm rounded-md text-white px-3 py-2 hover:bg-red-500">
                                            Delete
                                        </button>
                                         @endcan
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                    @endif

                </tbody>
            </table>
            <div class="bg-white my-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function deleteProduct(id) {
                if (!confirm('Are you sure you want to delete this product?')) {
                    return;
                }

                $.ajax({
                    url: `/product/delete/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.reload();
                    },
                    error: function(xhr) {
                        alert('Delete failed');
                        console.log(xhr.responseText);
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
