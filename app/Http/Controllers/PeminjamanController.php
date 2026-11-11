<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/** Pengelolaan peminjaman oleh admin dan petugas. */
class PeminjamanController extends Controller
{
    public function index(): View
    {
        $daftar = Peminjaman::with(['anggota', 'buku'])
            ->latest('id')
            ->paginate(15);

        return view('peminjaman.index', compact('daftar'));
    }

    /**
     * A4 (diperbaiki, Soal 4): akses diperiksa lewat PeminjamanPolicy@view.
     * Admin dan petugas boleh melihat semua, siswa hanya miliknya sendiri.
     */
    public function show($id): View
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }

    public function setujui(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang menunggu yang bisa disetujui.');
        }

        $peminjaman->update([
            'status' => Peminjaman::DIPINJAM,
            'tanggal_pinjam' => now()->toDateString(),
        ]);

        return back()->with('sukses', 'Pengajuan peminjaman disetujui.');
    }

    public function tolak(Peminjaman $peminjaman): RedirectResponse
    {
        if ($peminjaman->status !== Peminjaman::DIAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang menunggu yang bisa ditolak.');
        }

        $peminjaman->update(['status' => Peminjaman::DITOLAK]);

        return back()->with('sukses', 'Pengajuan peminjaman ditolak.');
    }
}
