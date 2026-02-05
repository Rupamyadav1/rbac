<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Category / Create
            </h2>
            <a href="{{ route('category.index') }}"
                class="bg-slate-700 text-sm rounded-md text-white px-3 py-2">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf

                        {{-- Title --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium">Title</label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title') }}"
                                placeholder="Enter category title"
                                class="border-gray-300 shadow-sm w-1/2 rounded-lg mt-2 p-2">

                            @error('title')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium">Slug</label>
                            <input type="text" name="slug" id="slug"
                                value="{{ old('slug') }}"
                                placeholder="Slug"
                                class="border-gray-300 shadow-sm w-1/2 rounded-lg mt-2 p-2">

                            @error('slug')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium">Status</label>
                            <select name="status"
                                class="border-gray-300 shadow-sm w-1/2 rounded-lg mt-2 p-2">
                                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>

                            @error('status')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="mt-6">
                            <button
                                class="bg-slate-700 text-sm rounded-md text-white px-6 py-3 hover:bg-slate-600">
                                Submit
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- Auto Slug Script --}}
    <script>
        document.getElementById('title').addEventListener('keyup', function () {
            let slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');

            document.getElementById('slug').value = slug;
        });
    </script>

</x-app-layout>
