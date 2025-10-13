@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">User Management</h1>

        {{-- FORM PENCARIAN BARU --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="relative">
                <input type="text" name="q" placeholder="Search by name, username, or email..."
                    value="{{ request('q') }}" class="w-full pl-10 pr-4 py-2 border rounded-lg">
                <div class="absolute top-0 left-0 inline-flex items-center p-2">
                    <svg class="w-6 h-6 text-gray-400" ...></svg> {{-- ikon search --}}
                </div>
            </div>
        </form>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            {{-- resources/views/admin/users.blade.php --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center space-x-4">
                                @if ($user->is_verified)
                                    {{-- Jika sudah terverifikasi, tampilkan tombol Unverify --}}
                                    <form action="{{ route('admin.users.unverify', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-yellow-600 hover:text-yellow-900">Unverify</button>
                                    </form>
                                @else
                                    {{-- Jika belum terverifikasi, tampilkan tombol Verify --}}
                                    <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900">Verify</button>
                                    </form>
                                @endif

                                {{-- Tombol Delete (tetap sama) --}}
                                @if (!$user->is_admin && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- Kolom Aksi akan kita isi di bawah --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
