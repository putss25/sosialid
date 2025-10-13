@extends('layouts.admin') {{-- Atau layout lain jika Anda punya layout khusus admin --}}

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 lg:px-8">
        <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

        {{-- Grid untuk Kartu Statistik --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-netr p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Users</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
            </div>

            <div class="bg-netr p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">New Users Today</h3>
                <p class="text-3xl font-bold mt-2">{{ $newUsersToday }}</p>
            </div>

            <div class="bg-netr p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Posts</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalPosts }}</p>
            </div>

            <div class="bg-netr p-6 rounded-lg shadow-lg">
                <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">New Posts Today</h3>
                <p class="text-3xl font-bold mt-2">{{ $newPostsToday }}</p>
            </div>

        </div>

        {{-- Grid untuk Laporan Analitik BARU --}}
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-netr dark:bg-[--color-surface] p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold ">Most Popular Posts</h3>
                <div class="mt-4 space-y-4">
                    @forelse($popularPosts as $post)
                        <a href="{{ route('post.show', $post) }}" target="_blank" class="flex rounded-md items-center space-x-4 hover:bg-accent p-3 hover:text-netbg-netr group">
                            <img src="{{ $post->image }}" class="w-12 ratio-4x3  object-cover">
                            <div class="flex-grow">
                                <p class="text-md font-semibold">by {{ $post->user->username }}</p>
                                <p class="text-sm  truncate">{{ $post->caption ?: 'No caption' }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="font-bold text-lg ">{{ $post->likes_count }}</p>
                                <p class="text-xs text-secondary">Likes</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-secondary">Not enough data yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-netr dark:bg-[--color-surface] p-6 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold ">Most Active Users</h3>
                <div class="mt-4 space-y-4">
                    @forelse($activeUsers as $user)
                        <a href="{{ route('profile.show', $user) }}" target="_blank" class="flex items-center space-x-4 hover:bg-primary p-3 rounded-md hover:text-netbg-netr">
                                <img src="{{ $user->avatar }}" class="w-12 h-12 rounded-full object-cover">
                            <div class="flex-grow">
                                <p class="font-semibold text-sm ">{{ $user->username }}</p>
                                <p class="text-xs text-secondary">{{ $user->email }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="font-bold text-lg ">{{ $user->posts_count }}</p>
                                <p class="text-xs text-secondary">Posts</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-secondary">Not enough data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{--  Chart --}}
        <div class="mt-8 bg-netr p-6 rounded-lg shadow-lg">
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

        // Data dari controller tetap sama ($userGrowthData atau $chartData)
        const chartData = @json($userGrowthData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'New Users',
                    data: chartData.data ?? chartData.userData, // Sesuaikan dengan nama variabel Anda

                    // --- PERUBAHAN WARNA HANYA DI SINI ---
                    borderColor: 'rgb(59, 130, 246)', // Warna Garis: Biru
                    backgroundColor: 'rgba(59, 130, 246)', // Warna Area di Bawah Garis: Biru Transparan

                    borderWidth: 2,
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
