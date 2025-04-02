<x-layout>

    <h3>Produk-Produk Kami</h3>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @elseif (session('error'))
        <script>
            alert('{{ session('error') }}');
        </script>
    @endif

    <ul>
        @foreach($katalogs as $katalog)
        <li>
            <p>{{ $katalog['nama_produk'] }}</p>
            <a href="{{ route('katalog.show', ['id' => $katalog->id]) }}">Detail</a>
        </li>
        @endforeach
    </ul>

</x-layout>
