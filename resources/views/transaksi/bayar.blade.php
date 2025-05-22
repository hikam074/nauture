@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="flex flex-col justify-center items-center gap-5 min-h-100 p-5 text-primer">
        <div class="flex flex-col gap-5">
            <p class="text-center">Anda akan melakukan pembayaran lelang
                <strong>{{ $transaksi['order_id'] }}</strong>
                dengan harga
                <strong>Rp. {{ number_format($transaksi['gross_amount'], 0, ',', '.') }}</strong>
            </p>
            <div class="flex flex-col justify-center gap-5 p-5 rounded-lg border-1 border-canceledhov">
                <h3 class="text-center font-bold border-b-1">Detail Checkout :</h3>
                <div class="flex flex-col gap-5 max-w-200
                    sm:flex-row"
                    >
                    <div class="flex justify-center
                        sm:inline">
                        <img src="{{ asset('storage/' . $transaksi->lelang->foto_produk) }}" alt="[{{ $transaksi->lelang->nama_produk }}]"
                            class="w-40 h-40 object-cover rounded-md">
                    </div>
                    <table class="w-full">
                        <tbody>
                            <tr>
                                <td><p>Judul Lelang</p></td>
                                <td><p class="font-bold">{{ $transaksi->lelang->nama_produk_lelang }}</p></td>
                            </tr>
                            <tr>
                                <td><p>Kode Transaksi</p></td>
                                <td><p class="font-bold">{{ $transaksi['order_id'] }}</p></td>
                            </tr>
                            <tr>
                                <td><p>Kode Lelang</p></td>
                                <td><p class="font-bold">{{ $transaksi->lelang->kode_lelang }}</p></td>
                            </tr>
                            <tr>
                                <td><p>Total biaya</p></td>
                                <td><p class="font-bold">Rp. {{ number_format($transaksi['gross_amount'], 0, ',', '.') }}</p></td>
                            </tr>
                            <tr>
                                <td><p>Alamat tujuan</p></td>
                                <td><p class="font-bold">{{ $transaksi['alamat'] }}</p></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="p-4 bg-kuarter" id="pay-button">
                    Bayar Sekarang
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
      document.getElementById('pay-button').onclick = function(){
        // SnapToken acquired from previous step
        snap.pay('{{ $transaksi->snap_token }}', {
          // Optional
          onSuccess: function(result){
            window.location.href = '{{ route('transaksi.success', ['id' => $transaksi->id]) }}'
          },
          // Optional
          onPending: function(result){
            window.location.href = '{{ route('transaksi.index') }}';
          },
          // Optional
          onError: function(result){
            window.location.href = '{{ route('profil.index') }}';
          },
          onClose: function() {
            alert('Anda telah menutup popup pembayaran. Anda dapat melanjutkan pembayaran nanti.');
            window.location.href = '{{ route('transaksi.index') }}';
          }
        });
      };
    </script>
@endsection
