<x-layout>

    <h3>Produk-Produk Kami</h3>

    <form action="{{ route('katalog.index') }}" method="GET">
        <select name="filter" onchange="this.form.submit()">
            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Produk Tersedia</option>
            <option value="deleted" {{ request('filter') == 'deleted' ? 'selected' : '' }}>Produk Dihapus</option>
            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Semua Produk</option>
        </select>
    </form>

    @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
        <a href="{{ route('katalog.add') }}">Tambah Produk</a>
    @endif

    <ul>
        @foreach($katalogs as $katalog)
        <li>
            <p>{{ $katalog['nama_produk'] }}</p>
            <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="Foto Produk" style="max-width: 300px;">
            <p>Rp. {{ $katalog['harga_perkilo'] }} / Kg</p>
            <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}">Detail</a>
            @if($katalog->trashed())
                <form action="{{ route('katalog.restore', $katalog->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Restore</button>
                </form>
            @else
                <a href="{{ route('katalog.edit', $katalog->id) }}">Edit</a>
            @endif
        </li>
        @endforeach
    </ul>

    {{ $katalogs->links() }} {{-- Pagination --}}

</x-layout>
