<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('category') }}
            </h2>

            <a href="{{ route('category.create') }}"
                class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Create</a>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-message></x-message>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left">#</th>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-left">Status</th>

                        <th class="px-6 py-3 text-left">Created</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if ($categories->isNotEmpty())
                        @foreach ($categories as $category)
                            <tr class="border-b">
                                <td class="px-6 py-3 text-left">{{ $category->id }}</td>
                                <td class="px-6 py-3 text-left">{{ $category->title }}</td>
                                <td class="px-6 py-3 text-left">{{ $category->slug }}</td>
                                <td class="px-6 py-3 text-left">
                                    @if ($category->status == 1)
                                        <span class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded">
                                            Active
                                        </span>
                                    @else
                                        <span class="bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-left">
                                    {{ \Carbon\Carbon::parse($category->created_at)->format('d M, Y') }}</td>
                                <td class="px-9 py-3 text-center">

                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="bg-slate-700 text-sm rounded-md text-white px-3 py-2 hover:bg-slate-600">
                                        Edit
                                    </a>

                                    <button type="button" onclick="deleteCategory({{ $category->id }})"
                                        class="ml-2 bg-red-700 text-sm rounded-md text-white px-3 py-2 hover:bg-red-500">
                                        Delete
                                    </button>

                                </td>
                            </tr>
                        @endforeach

                    @endif

                </tbody>
            </table>
            <div class="bg-white my-3">
                {{ $categories->links() }}
            </div>


        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            function deleteCategory(id) {
                if (!confirm('Are you sure you want to delete this category?')) {
                    return;
                }

                $.ajax({
                    url: `/category/${id}`, // pass id directly in URL
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status) {
                            alert(response.message);
                            window.location.reload();
                        } else {
                            alert(response.error);
                        }
                    },
                    error: function() {
                        alert('Something went wrong!');
                    }
                });
            }
            @endpush
        </script>



    </x-app-layout>
