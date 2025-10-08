<div
        x-data="{
            show: false,
            type: '',
            message: '',
            init() {
                // Cek jika ada data notifikasi dari Laravel session
                @if(session('notification'))
                    this.type = '{{ session('notification')['type'] }}';
                    this.message = '{{ addslashes(session('notification')['message']) }}';
                    this.show = true;

                    // Sembunyikan notifikasi setelah 5 detik
                    setTimeout(() => this.show = false, 5000);
                @endif

                // (Opsional) Listener untuk event kustom dari komponen Livewire/JS lain
                window.addEventListener('toast-notification', event => {
                    this.type = event.detail.type;
                    this.message = event.detail.message;
                    this.show = true;
                    setTimeout(() => this.show = false, 5000);
                });
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-4"
        x-cloak
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50 px-6 py-3 rounded-lg shadow-lg text-white text-sm font-semibold"
        :class="{
            'bg-green-500': type === 'success',
            'bg-red-600': type === 'error',
            'bg-blue-500': type === 'info',
            'bg-yellow-500': type === 'warning'
        }"
    >
        <span x-text="message"></span>
    </div>
