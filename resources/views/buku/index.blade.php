@extends('layouts.perpus')

@section('title', 'Daftar Buku')

@section('content')
<style>
    .daftar-buku .card {
        background: #fff;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        overflow-x: auto;
    }

    .daftar-buku table {
        width: 100%;
        border-collapse: collapse;
    }

    .daftar-buku th,
    .daftar-buku td {
        padding: 8px 10px;
        border-bottom: 1px solid #eadcc8;
        text-align: left;
        font-size: 14px;
    }

    .daftar-buku th {
        background: #f5ecdf;
    }

    .daftar-buku .alert {
        padding: 10px 14px;
        border-radius: 4px;
        margin-bottom: 16px;
    }

    .daftar-buku .alert.sukses {
        background: #e6f6ea;
        color: #1e7b34;
    }

    .daftar-buku .alert.error {
        background: #fdecea;
        color: #b3261e;
    }

    .daftar-buku .tombol,
    .daftar-buku button {
        padding: 6px 12px;
        border: 0;
        border-radius: 4px;
        cursor: pointer;
        color: #fff;
        background: #8a7762;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
        font-family: inherit;
    }

    .daftar-buku .tombol.utama {
        background: #6d4c2f;
    }

    .daftar-buku .tombol.ubah {
        background: #b9772e;
    }

    .daftar-buku .tombol.hapus,
    .daftar-buku button.hapus {
        background: #c0392b;
    }

    .daftar-buku .aksi {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .daftar-buku .aksi form {
        margin: 0;
    }

    .daftar-buku .aksi-atas {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }
</style>

<div class="daftar-buku">
    <h2 class="page-title">Daftar Buku</h2>

    @if (session('sukses'))
    <div class="alert sukses">{{ session('sukses') }}</div>
    @endif
    @if (session('error'))
    <div class="alert error">{{ session('error') }}</div>
    @endif

    <div class="aksi-atas">
        <a href="{{ route('dashboard') }}" class="tombol">&larr; Dashboard</a>
        @can('kelola-data-buku')
        <a href="{{ route('buku.create') }}" class="tombol utama">+ Tambah Buku</a>
        @endcan
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($daftarBuku as $b)
                <tr>
                    <td>{{ $daftarBuku->firstItem() + $loop->index }}</td>
                    <td>{{ $b->kategori?->nama }}</td>
                    {{-- A1 (CELAH XSS, sengaja untuk Soal 1): sintaks tanpa escaping --}}
                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->penulis }}</td>
                    <td>{{ $b->tahun_terbit }}</td>
                    <td>
                        <div class="aksi">
                            <a href="{{ route('buku.show', $b->id) }}" class="tombol utama">Detail</a>
                            @can('kelola-data-buku')
                            <a href="{{ route('buku.edit', $b->id) }}" class="tombol ubah">Ubah</a>
                            {{-- A3 (CELAH CSRF, sengaja untuk Soal 1): hapus lewat tautan GET --}}
                            <form method="POST" action="{{ route('buku.destroy', $b->id) }}"
                                onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="hapus">Hapus</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">Belum ada data buku.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 12px;">{{ $daftarBuku->links() }}</div>
    </div>
</div>
@endsection