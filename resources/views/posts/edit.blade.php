@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-[--color-text] mb-8">Edit Post</h1>

        <div class="bg-white dark:bg-[--color-surface] p-6 rounded-lg shadow-md">
            <form action="{{ route('posts.update', $post) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="flex flex-col md:flex-row md:space-x-6">
                    <div class="md:w-1/2 mb-4 md:mb-0">
                        <img src="{{ $post->image }}" alt="Post image" class="rounded-lg w-full object-cover">
                    </div>

                    <div class="md:w-1/2">
                        <label for="caption"
                            class="block text-sm font-medium text-[--color-text-secondary]">Caption</label>
                        <textarea name="caption" id="caption" rows="8"
                            class="mt-1 block w-full border rounded-md shadow-sm bg-[--color-input-background]  p-1">{{ old('caption', $post->caption) }}</textarea>

                        @error('caption')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('post.show', $post) }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
