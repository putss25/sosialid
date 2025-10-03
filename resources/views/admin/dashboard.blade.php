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
