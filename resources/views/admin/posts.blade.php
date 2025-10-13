@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">Post Management</h1>


        <div class="bg-netr overflow-hidden overflow-x-auto shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted-background">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caption
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created
                            At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-netr divide-y divide-border">
                    @foreach ($posts as $post)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $post->id }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('post.show', $post) }}" target="_blank">
                                    <img src="{{ $post->image }}" alt="Post image"
                                        class="w-16 ratio-4x3 object-cover rounded-md">
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $post->user->username }}</td>
                            <td class="px-6 py-4 max-w-sm truncate">{{ $post->caption }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $post->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- Karena ini halaman admin, kita bisa langsung tampilkan tombol hapus --}}
                                {{-- Arahkan 'action' ke rute admin yang baru --}}
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                </form>
                            </td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
