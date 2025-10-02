@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
        <h1 class="text-2xl font-bold mb-6 text-center">Create New Post</h1>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Image Upload --}}
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                <input id="image" name="image" type="file" required
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Caption --}}
            <div class="mb-6">
                <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                <textarea id="caption" name="caption" rows="4"
                    class="w-full px-3 py-2 mt-1 text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Write a caption...">{{ old('caption') }}</textarea>
                @error('caption')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Share
                </button>
            </div>
        </form>
    </div>
@endsection
