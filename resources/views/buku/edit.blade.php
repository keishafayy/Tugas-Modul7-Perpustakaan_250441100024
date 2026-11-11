<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Buku</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('buku.update', $buku->id) }}">
                    @csrf
                    @method('PUT')
                    @include('buku._form', ['buku' => $buku])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
