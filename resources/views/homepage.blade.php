<x-layout>
    <section>
        @if ($user)
        <p>Selamat datang, {{ $user->name }}!</p>
        @else
        <p>Selamat datang, Pengunjung!</p>
        @endif
    </section>

    <section class="katalog">
        <div class="katalog-head">
            <h2>Katalog Produk</h2>
            @if (Auth::check() && Auth::user()->role->nama_role == 'pegawai')
                <a href="{{ route('katalog.add') }}">Tambah Produk</a>
            @endif
        </div>
        <div class="katalog-carousel">
            @foreach($katalogs->take(5) as $katalog)
            <div class="katalog-item">
                <img src="{{ asset('storage/' . $katalog->foto_produk) }}" alt="{{ $katalog->nama_produk }}" width="150">
                <p>{{ $katalog->nama_produk }}</p>
                <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}" class="btn-detail">Detail</a>
            </div>
            @endforeach
            <a href="{{ route('katalog.index') }}">Lihat Lebih Banyak...</a>
        </div>
    </section>

    <section class="lelang">
        <div class="lelang-head">
            <h2>Produk Lelang</h2>
        </div>
        <div class="lelang-carousel">
            @foreach($lelangs->take(5) as $lelang)
            <div class="lelang-item">
                <img src="{{ asset('storage/' . $lelang->katalog->foto_produk) }}" alt="{{ $lelang->katalog->nama_produk }}" width="150">
                <p>{{ $lelang->nama_produk_lelang }}</p>
                <a href="{{ route('lelang.show', ['id' => $lelang->id]) }}" class="btn-detail">Detail</a>
            </div>
            @endforeach
            <a href="{{ route('lelang.index') }}">Lihat Lebih Banyak...</a>
        </div>
    </section>
</x-layout>
