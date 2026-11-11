@php($b = $buku ?? null)

<div>
    <x-input-label for="kategori_id" value="ID Kategori" />
    <x-text-input id="kategori_id" name="kategori_id" type="number" min="1" class="mt-1 block w-full"
                  :value="old('kategori_id', $b?->kategori_id)" placeholder="Masukkan ID kategori" required />
    <x-input-error :messages="$errors->get('kategori_id')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="judul" value="Judul" />
    <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full"
                  :value="old('judul', $b?->judul)" required />
    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="penulis" value="Penulis" />
    <x-text-input id="penulis" name="penulis" type="text" class="mt-1 block w-full"
                  :value="old('penulis', $b?->penulis)" required />
    <x-input-error :messages="$errors->get('penulis')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="tahun_terbit" value="Tahun Terbit" />
    <x-text-input id="tahun_terbit" name="tahun_terbit" type="number" class="mt-1 block w-full"
                  :value="old('tahun_terbit', $b?->tahun_terbit)" required />
    <x-input-error :messages="$errors->get('tahun_terbit')" class="mt-2" />
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>Simpan</x-primary-button>
    <a href="{{ route('buku.index') }}" class="text-sm text-gray-600 underline">Batal</a>
</div>
