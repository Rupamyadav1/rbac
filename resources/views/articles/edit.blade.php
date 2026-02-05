<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Articles / Edit
        </h2>
        <a href="{{ route('permissions.index') }}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3">Back</a>
    </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('article.update',$article->id) }}" method="post">
                        @csrf
                        <div>
                            <label for="" class="text-lg font-medium">Title</label>
                            <div class="my-3">
                                <input type="text" name="title" id="title" placeholder="title" class="border-gray-300 
                                shadow-sm w-1/2 rounded-lg" value={{ old('title',$article->title) }}>
                                @error('title')
                                <p class="text-red-400">{{$message}}</p>
                                    
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Content</label>
                            <div class="my-3">
                                <textarea name="text" placeholder="content" id="text"  class="border-gray-300 shadow-sm w-1/2 rounded-lg" >{{ old('text',$article->text) }}</textarea>


                                @error('content')
                                <p class="text-red-400">{{$message}}</p>
                                    
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Author</label>
                            <div class="my-3">
                                <input type="text" name="author" value="{{ old('author',$article->author) }}"
                                 id="author" placeholder="author" class="border-gray-300 
                                shadow-sm w-1/2 rounded-lg" >
                                @error('author')
                                <p class="text-red-400">{{$message}}</p>
                                    
                                @enderror
                            </div>


                            <button class="bg-slate-700 text-sm rounded-md text-white px-5 py-3">Submit</button>
                            
                        </div>
                    </form>
                   
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
