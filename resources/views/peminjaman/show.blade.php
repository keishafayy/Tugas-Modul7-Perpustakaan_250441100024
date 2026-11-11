<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Peminjaman #{{ $peminjaman->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6 text-sm space-y-2">
                <p><strong>Anggota:</strong> {{ $peminjaman->anggota?->nama ?? '-' }}</p>
                <p><strong>Email anggota:</strong> {{ $peminjaman->anggota?->email ?? '-' }}</p>
                <p><strong>Buku:</strong> {{ $peminjaman->buku?->judul ?? '-' }}</p>
                <p><strong>Tanggal pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
                <p><strong>Tanggal kembali:</strong> {{ $peminjaman->tanggal_kembali ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $peminjaman->status }}</p>

                <div class="pt-3">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 underline">&larr; Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
