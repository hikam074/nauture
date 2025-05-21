@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="d-flex justify-content-center">
        <div class="card">
            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
                Anda akan melakukan pembelian produk <strong>{{ $transaksi['order_id'] }}</strong> dengan harga
                <strong>Rp{{ number_format($transaksi['gross_amount'], 0, ',', '.') }}</strong>
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
