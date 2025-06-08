@extends('layouts.app')

@section('title')
    @if(isset($rating))
        Ubah Penilaian
    @else
        Tambah Penilaian
    @endif
@endsection

@section('content')
    <div class="flex flex-col max-w-3xl mx-auto bg-white shadow-lg gap-5 rounded-lg my-10 p-5">
        <h1 class="text-2xl font-bold text-center p-5 bg-kuarter">
            {{ isset($rating) ? 'Ubah Penilaian' : 'Tambahkan Penilaian' }}
        </h1>

        <div class="flex gap-5 border-1 border-canceled p-2 rounded">
            <div class="max-w-25">
                <img src="{{ asset('storage/' . $transaksi->lelang->foto_produk)  }}" alt="{{ $transaksi->lelang->nama_produk_lelang }}"
                    class="w-full rounded-lg aspect-square object-cover border-1 shadow-lg border-gray-200"
                >
            </div>
            <div>
                <p>Produk : {{ $transaksi->lelang->katalog->nama_produk }}</p>
                <p class="text-xs text-canceledhov">
                    Lelang yang dinilai : <br>
                    {{ $transaksi->lelang->nama_produk_lelang }} | {{ $transaksi->lelang->kode_lelang }}
                </p>
            </div>
        </div>

        <div class="">
            <form id="ratingForm" method="POST" enctype="multipart/form-data"
                action="{{ isset($rating) ? route('rating.update', $rating->id) : route('rating.store' , $transaksi->id) }}"
                class="space-y-2">
                @csrf
                @if (isset($rating))
                @method('PATCH')
                <input id="ratingId" type="hidden" name="rating_id" value="{{ $rating->id }}">
                @endif
                <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
                <!-- Bintang Rating -->
                <div class="flex flex-col justify-center items-center gap-2">
                    <div>
                        <p>Bagaimana pengalaman anda "ber-lelang" produk ini?</p>
                    </div>
                    <div id="starRating" class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg data-value="{{ $i }}" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"
                                class="star w-10 h-10 cursor-pointer text-canceled"
                                >
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.164c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.371-2.448a1 1 0 00-1.175 0l-3.371 2.448c-.785.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.02 9.384c-.783-.57-.38-1.81.588-1.81h4.165a1 1 0 00.95-.69l1.286-3.957z"/>
                            </svg>
                        @endfor
                    </div>
                    <div>
                        <p id="teksRating" class=""></p>
                    </div>
                    <div>
                        @if (isset($rating))
                        <p class="text-xs text-canceledhov">
                            Rating anda sebelumnya :
                            @for ($i = 1; $i <= $rating->rating; $i++)
                            <span class="text-yellow-400">&#9733;</span>
                            @endfor
                        </p>
                        @endif
                    </div>
                    <input type="hidden" id="ratingInput" name="rating" value="{{ $rating->rating ?? 0 }}">
                </div>
                <!-- Teks Input Ulasan -->
                <div class="space-y-2">
                    <label for="review" class="text-lg font-medium">Ulasan:</label>
                    <textarea id="review" name="review" class="w-full p-3 border rounded-lg" rows="2"placeholder="Tulis ulasan Anda di sini">{{ $rating->ulasan ?? '' }}</textarea>
                </div>
                <!-- Tombol Submit -->
                <button id="btnSubmit" type="button" class="w-full px-6 py-2 bg-sekunderDark text-white font-semibold rounded-lg hover:bg-primer">
                    {{ isset($rating) ? 'Perbarui' : 'Kirim' }}
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('ratingInput');
            const textRating = document.getElementById('teksRating');

            // Function to update star colors and text
            function updateStars(rating) {
                // Update star colors
                stars.forEach(star => {
                    if (parseInt(star.dataset.value) <= rating) {
                        star.classList.add('text-restore');
                        star.classList.remove('text-canceled');
                    } else {
                        star.classList.add('text-canceled');
                        star.classList.remove('text-restore');
                    }
                });

                // Update text based on rating
                switch (rating) {
                    case 1:
                        textRating.innerText = 'Pengalaman kurang menyenangkan';
                        break;
                    case 2:
                        textRating.innerText = 'Bisa lebih baik lagi';
                        break;
                    case 3:
                        textRating.innerText = 'Lumayan, Cukup memuaskan';
                        break;
                    case 4:
                        textRating.innerText = 'Sangat baik!';
                        break;
                    case 5:
                        textRating.innerText = 'Omaygat! Beautiful! Ini yang papa cari!';
                        break;
                    default:
                        textRating.innerText = 'Silahkan pilih rating dari anda!';
                        break;
                }
            }

            // Add click event listeners to stars
            stars.forEach(star => {
                star.addEventListener('click', function () {
                    const rating = parseInt(this.dataset.value);
                    ratingInput.value = rating;
                    updateStars(rating);
                });
            });

            // Initialize stars based on initial value
            const initialRating = parseInt(ratingInput.value) || 0;
            updateStars(initialRating);

            const button = document.getElementById('btnSubmit');
            const form = document.getElementById('ratingForm');

            button.addEventListener('click', function () {
                showAlert({
                    title: 'Apakah anda yakin?',
                    text: 'Apakah Anda yakin memberi penilaian ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal',
                    onConfirm: function () {
                        form.submit(); // Mengirim formulir jika pengguna mengonfirmasi
                    }
                });
            });
        });
    </script>

@endsection
