<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Models\Buku;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    public function index()
    {
        $daftarBuku = Buku::with('kategori')
            ->latest()
            ->latest('id')
            ->paginate(10);

        return view('buku.index', compact('daftarBuku'));
    }

    public function show($id)
    {
        $data = Buku::with('kategori')->find((int) $id);
        $buku = $data ? $this->format($data) : null;

        return view('buku.show', compact('buku'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(StoreBukuRequest $request)
    {
        Buku::create($request->validated());

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.edit', compact('buku'));
    }

    public function update(UpdateBukuRequest $request, Buku $buku)
    {
        $buku->update($request->validated());

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        try {
            $buku->delete();
        } catch (QueryException $e) {
            return redirect()->route('buku.index')
                ->with('error', 'Buku tidak dapat dihapus karena masih memiliki data peminjaman.');
        }

        return redirect()->route('buku.index')->with('sukses', 'Buku berhasil dihapus.');
    }

    /**
     * A2 (CELAH SQL INJECTION, sengaja untuk Soal 1).
     * Nilai judul digabungkan langsung ke string SQL.
     */
    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select("SELECT * FROM bukus WHERE judul = '$judul'");

        return response()->json($hasil);
    }

    private function format(Buku $b): array
    {
        return [
            'id'       => $b->id,
            'judul'    => $b->judul,
            'penulis'  => $b->penulis,
            'tahun'    => $b->tahun_terbit,
            'kategori' => $b->kategori->nama,
        ];
    }
}
