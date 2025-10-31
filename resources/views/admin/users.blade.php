@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">User Management</h1>

        {{-- FORM PENCARIAN BARU --}}
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="relative">
                <input type="text" name="q" placeholder="Search by name, username, or email..."
                    value="{{ request('q') }}" class="w-full pl-10 pr-4 py-2 border rounded-lg">
                <div class="absolute top-0 left-0 inline-flex items-center p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-search-icon lucide-search">
                        <path d="m21 21-4.34-4.34" />
                        <circle cx="11" cy="11" r="8" />
                    </svg>
                </div>
            </div>
        </form>

        <div class="bg-netr overflow-hidden  overflow-x-auto shadow-xl sm:rounded-lg">
            <table class="min-w-full divide-y divide-border">
                <thead class="bg-muted-background">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified
                        </th>
                        
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-netr divide-y divide-border">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            
                            {{-- Ini adalah 'cell' untuk VERIFIED (Sudah ada) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if ($user->is_verified)
                                    {{-- Jika sudah terverifikasi, tampilkan tombol Unverify --}}
                                    <form action="{{ route('admin.users.unverify', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="flex items-center text-yellow-600 hover:text-yellow-800"
                                            title="Unverify User">
                                            {{-- Ini adalah ikon 'Perisai Coret' dari Lucide --}}
                                            <x-lucide-shield-off class="w-5 h-5 mr-1" />
                                            <span>Unverify</span>
                                        </button>
                                    </form>
                                @else
                                    {{-- Jika belum terverifikasi, tampilkan tombol Verify --}}
                                    <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="flex items-center text-indigo-600 hover:text-indigo-800"
                                            title="Verify User">
                                            {{-- Ini adalah ikon 'Perisai Centang' dari Lucide --}}
                                            <x-lucide-shield-check class="w-5 h-5 mr-1" />
                                            <span>Verify</span>
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- Cek apakah yang login adalah SUPER ADMIN (ID 1) --}}
                                @if (auth()->id() === 1)
                                    
                                    {{-- Jika user di baris ini adalah SUPER ADMIN (ID 1) --}}
                                    @if ($user->id === 1)
                                        <span class="flex items-center text-blue-600 font-bold" title="Super Admin">
                                            <x-lucide-award class="w-5 h-5 mr-1" />
                                            <span>Super Admin</span>
                                        </span>
                                    
                                    {{-- Jika user di baris ini adalah ADMIN BIASA --}}
                                    @elseif ($user->is_admin)
                                        <form action="{{ route('admin.users.revoke-admin', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="flex items-center text-yellow-600 hover:text-yellow-800" title="Revoke Admin Status">
                                                <x-lucide-user-minus class="w-5 h-5 mr-1" />
                                                <span>Revoke Admin</span>
                                            </button>
                                        </form>

                                    {{-- Jika user di baris ini adalah USER BIASA --}}
                                    @else
                                        <form action="{{ route('admin.users.make-admin', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="flex items-center text-green-600 hover:text-green-800" title="Make Admin">
                                                <x-lucide-user-plus class="w-5 h-5 mr-1" />
                                                <span>Make Admin</span>
                                            </button>
                                        </form>
                                    @endif

                                {{-- Jika yang login BUKAN SUPER ADMIN (tapi admin biasa) --}}
                                @else

            {{-- V V V TAMBAHKAN LOGIKA INI V V V --}}
            {{-- Cek dulu, apakah user di baris ini adalah Super Admin (ID 1)? --}}
            @if ($user->id === 1)
                <span class="flex items-center text-blue-600 font-bold" title="Super Admin">
                    <x-lucide-award class="w-5 h-5 mr-1" />
                    <span>Super Admin</span>
                </span>

            {{-- Jika bukan ID 1, baru cek apakah dia admin biasa? --}}
            @elseif ($user->is_admin)
            {{-- ^ ^ ^ BATAS PENAMBAHAN ^ ^ ^ --}}

                <span class="flex items-center text-gray-500" title="Admin">
                    <x-lucide-user-check class="w-5 h-5 mr-1" />
                    <span>Admin</span>
                </span>
            @else
                <span class="flex items-center text-gray-400">
                    <x-lucide-user class="w-5 h-5 mr-1" />
                    <span>User</span>
                </span>
            @endif
        @endif
                            </td>
                            {{-- Ini adalah 'cell' untuk ACTIONS (Sudah ada) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                {{-- Logikanya: JIKA BUKAN ADMIN... --}}
                                @if (!$user->is_admin && $user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center text-red-600 hover:text-red-800"
                                            title="Delete User"
                                            onclick="return confirm('Are you sure you want to delete this user and all their data?')">
                                            <x-lucide-trash-2 class="w-5 h-5 mr-1" />
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                @else
                                    {{-- INI TANDA BARUNYA: JIKA DIA ADMIN --}}
                                    <span class="flex items-center text-gray-400 cursor-not-allowed"
                                        title="Admin accounts cannot be deleted">

                                        {{-- Ikon gembok dari Lucide --}}
                                        <x-lucide-lock class="w-5 h-5 mr-1" />
                                        <span>Admin</span>
                                    </span>
                                @endif
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