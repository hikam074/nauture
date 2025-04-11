<x-layout>
    <h3>Lelang Produk Kami</h3>

    <form action="{{ route('lelang.index') }}" method="GET">
        <select name="filter" onchange="this.form.submit()">
            <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Lelang Berlangsung</option>
            <option value="completed" {{ request('filter') == 'completed' ? 'selected' : '' }}>Lelang Selesai</option>
            <option value="deleted" {{ request('filter') == 'deleted' ? 'selected' : '' }}>Lelang Dihapus</option>
            <option value="all" {{ request('filter') == 'all' ? 'selected' : '' }}>Semua Lelang</option>
        </select>
    </form>

    @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
        <a href="{{ route('lelang.add') }}">Tambah Lelang</a>
    @endif

    <ul>
        @foreach($lelangs as $lelang)
        <li>
            <p>{{ $lelang->nama_produk_lelang }}</p>
            <img src="{{ asset('storage/' . $lelang->foto_produk) }}" alt="Foto Produk" style="max-width: 300px;">
            <p>Berat : {{ $lelang->jumlah_kg }} Kg.</p>
            <p>Dibuka Mulai : Rp. {{ $lelang->harga_dibuka }}</p>
            <p>Status: {{ $lelang->trashed() ? 'Selesai' : 'Berlangsung' }}</p>
            <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}">Detail</a>
            @if($lelang->trashed())
                <form action="{{ route('lelang.restore', $lelang->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Restore</button>
                </form>
            @else
                <a href="{{ route('lelang.edit', $lelang->id) }}">Edit</a>
            @endif
        </li>
        @endforeach
    </ul>

    {{ $lelangs->links() }} {{-- Pagination --}}
</x-layout>
