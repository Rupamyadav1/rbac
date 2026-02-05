<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sections / Edit
            </h2>
            <a href="{{ route('section.index') }}"
               class="bg-slate-700 text-sm rounded-md text-white px-4 py-2">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('section.update', $section->id) }}" method="POST">
                        @csrf
                       

                        <div class="flex gap-12 mb-6">
                            <div class="w-1/2">
                                <label class="text-lg font-medium">Title</label>
                                <input type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title', $section->title) }}"
                                       class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                @error('title')
                                    <p class="text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-1/2">
                                <label class="text-lg font-medium">Slug</label>
                                <input type="text"
                                       name="slug"
                                       id="slug"
                                       value="{{ old('slug', $section->slug) }}"
                                       class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                @error('slug')
                                    <p class="text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="text-lg font-medium">Position</label>
                            <input type="number"
                                   name="position"
                                   value="{{ old('position', $section->position) }}"
                                   class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                        </div>
                                  <div class="mb-6">
    <label class="text-lg font-medium">Redirect To (Route / URL)</label>
    <input type="text"
           name="redirect_to"
           value="{{ old('redirect_to', $section->redirect_to ?? '') }}"
           placeholder="Redirect to"
           class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
</div>

                        <div class="mb-6">
                            <label class="text-lg font-medium">Status</label>
                            <select name="status"
                                    class="border-gray-300 shadow-sm w-1/2 rounded-lg mt-2 p-2">
                                <option value="1" {{ $section->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $section->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="mt-12 flex justify-end">
                            <button type="submit"
                                    class="bg-black text-white text-sm rounded-md px-6 py-3 hover:bg-gray-800 transition">
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

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
