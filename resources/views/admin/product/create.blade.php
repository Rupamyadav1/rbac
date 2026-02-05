<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Product / Create
            </h2>
            <a href="{{ route('product.index') }}"
               class="bg-slate-700 text-sm rounded-md text-white px-4 py-2">
                Back
            </a>
        </div>
    </x-slot>

    {{-- Dropzone CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('product.store') }}" method="POST">
                        @csrf

                        {{-- hidden images --}}
                        <input type="hidden" name="images" id="images">

                        {{-- Title & Slug --}}
                        <div class="flex gap-12 mb-6">
                            <div class="w-1/2">
                                <label class="text-lg font-medium">Title</label>
                                <input type="text" name="title" id="title"
                                       value="{{ old('title') }}"
                                       class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                @error('title')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-1/2">
                                <label class="text-lg font-medium">Slug</label>
                                <input type="text" name="slug" id="slug"
                                       value="{{ old('slug') }}"
                                       class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                @error('slug')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Category & Price --}}
                        <div class="flex gap-12 mb-6">
                            <div class="w-1/2">
                                <label class="text-lg font-medium">Category</label>
                                <select name="category_id"
                                        class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">
                                            {{ $cat->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-1/2">
                                <label class="text-lg font-medium">Price</label>
                                <input type="text" name="price"
                                       value="{{ old('price') }}"
                                       class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                @error('price')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Short Description --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium">Short Description</label>
                            <textarea name="short_description" id="short_description"
                                      class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                {{ old('short_description') }}
                            </textarea>
                        </div>

                        {{-- Full Description --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium">Full Description</label>
                            <textarea name="description" id="description"
                                      class="border-gray-300 shadow-sm w-full rounded-lg mt-2 p-2">
                                {{ old('description') }}
                            </textarea>
                        </div>

                        {{-- Featured --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium block mb-2">Is Featured</label>
                            <input type="checkbox" name="is_featured" value="1">
                        </div>

                        {{-- Status --}}
                        <div class="mb-6">
                            <label class="text-lg font-medium">Status</label>
                            <select name="status"
                                    class="border-gray-300 shadow-sm w-1/2 rounded-lg mt-2 p-2">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        {{-- Dropzone --}}
                        <div class="mt-10 mb-12">
                            <h2 class="text-lg font-semibold mb-3">Product Images</h2>
                            <div id="image"
                                 class="dropzone w-1/2 min-h-[160px]
                                        border-2 border-dashed border-gray-400
                                        rounded-lg bg-gray-50 hover:bg-gray-100">
                            </div>
                        </div>

                        <div class="mt-12 flex justify-end">
                            <button type="submit"
                                    class="bg-black text-white text-sm rounded-md px-6 py-3">
                                Submit
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- CKEditor --}}
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('short_description', { height: 150 });
        CKEDITOR.replace('description', { height: 300 });
    </script>

    {{-- Auto Slug --}}
    <script>
        document.getElementById('title').addEventListener('keyup', function () {
            let slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
            document.getElementById('slug').value = slug;
        });
    </script>

    {{-- Dropzone --}}
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;

        let uploadedImages = [];

        const dropzone = new Dropzone("#image", {
            url: "{{ route('product.upload.temp') }}",
            paramName: "image",
            maxFiles: 10,
            addRemoveLinks: true,
            acceptedFiles: "image/*",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            success: function (file, response) {
                uploadedImages.push(response.name);
                document.getElementById('images').value = uploadedImages.join(',');
                file.serverName = response.name;
            },
            removedfile: function (file) {
                uploadedImages = uploadedImages.filter(img => img !== file.serverName);
                document.getElementById('images').value = uploadedImages.join(',');
                file.previewElement.remove();
            }
        });
    </script>

</x-app-layout>
