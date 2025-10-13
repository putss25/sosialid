@extends('layouts.admin') {{-- Atau layout lain jika Anda punya layout khusus admin --}}

@section('content')
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

        {{-- Grid untuk Kartu Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Users</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">New Users Today</h3>
                <p class="text-3xl font-bold mt-2">{{ $newUsersToday }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Posts</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalPosts }}</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">New Posts Today</h3>
                <p class="text-3xl font-bold mt-2">{{ $newPostsToday }}</p>
            </div>

        </div>

        {{-- Grid untuk Laporan Analitik BARU --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white dark:bg-[--color-surface] p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-[--color-text]">Most Popular Posts</h3>
                <div class="mt-4 space-y-4">
                    @forelse($popularPosts as $post)
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('post.show', $post) }}" target="_blank">
                                <img src="{{ $post->image }}" class="w-12 h-12 rounded-md object-cover">
                            </a>
                            <div class="flex-grow">
                                <p class="text-sm text-[--color-text] truncate">{{ $post->caption ?: 'No caption' }}</p>
                                <p class="text-xs text-[--color-text-secondary]">by {{ $post->user->username }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="font-bold text-lg text-[--color-text]">{{ $post->likes_count }}</p>
                                <p class="text-xs text-[--color-text-secondary]">Likes</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[--color-text-secondary]">Not enough data yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-[--color-surface] p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-[--color-text]">Most Active Users</h3>
                <div class="mt-4 space-y-4">
                    @forelse($activeUsers as $user)
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('profile.show', $user) }}" target="_blank">
                                <img src="{{ $user->avatar }}" class="w-12 h-12 rounded-full object-cover">
                            </a>
                            <div class="flex-grow">
                                <p class="font-semibold text-sm text-[--color-text]">{{ $user->username }}</p>
                                <p class="text-xs text-[--color-text-secondary]">{{ $user->email }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="font-bold text-lg text-[--color-text]">{{ $user->posts_count }}</p>
                                <p class="text-xs text-[--color-text-secondary]">Posts</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[--color-text-secondary]">Not enough data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{--  Chart --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold">User Growth (Last 7 Days)</h3>
            <div class="mt-4">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('userGrowthChart').getContext('2d');

        // Ambil data dari controller Laravel dan ubah menjadi objek JavaScript
        const userGrowthData = @json($userGrowthData);

        // Buat chart baru
        new Chart(ctx, {
            type: 'line', // Tipe chart: garis
            data: {
                labels: userGrowthData.labels, // Label untuk sumbu X (tanggal)
                datasets: [{
                    label: 'New Users',
                    data: userGrowthData.data, // Data untuk sumbu Y (jumlah)
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Memastikan sumbu Y hanya menampilkan angka bulat (1, 2, 3, bukan 1.5)
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Sembunyikan legenda 'New Users'
                    }
                }
            }
        });
    </script>
@endpush
