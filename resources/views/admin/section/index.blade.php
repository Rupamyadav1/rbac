<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('section.create') }}"
                class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Create</a>
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
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-left">Position</th>
                       
                        <th class="px-6 py-3 text-left">Status</td>
                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($sections->isNotEmpty())
                        @foreach ($sections as $section)
                            <tr class="border-b">
                                <td class="px-6 py-3 text-left">{{ $section->id }}</td>
                                <td class="px-6 py-3 text-left">{{ $section->title }}</td>
                                <td class="px-6 py-3 text-left">{{ $section->slug }}</td>
                               

                                <td class="px-6 py-3 text-left">{{ $section->position }}</td>
                               
                                <td class="px-6 py-3 text-left">
                                    @if ($section->status == 1)
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
                                    {{ \Carbon\Carbon::parse($section->created_at)->format('d M, Y') }}</td>
                                <td class="px-6 py-3 text-left">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('section.edit', $section->id) }}"
                                            class="bg-slate-700 text-sm rounded-md text-white px-3 py-2 hover:bg-slate-600">
                                            Edit
                                        </a>

                                      
                                    </div>
                                </td>

                            </tr>
                        @endforeach

                    @endif

                </tbody>
            </table>
            <div class="bg-white my-3">
                {{ $sections->links() }}
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
