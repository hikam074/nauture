@extends('layouts.app')

@section('title', 'Notifikasi')

@section('show-sidebar')
@endsection

@section('hide-footer')
@endsection

@section('content')
<div class="mb-5 flex flex-col gap-10 w-full">
    <div>
        <h1 class="font-bold text-4xl">Notifikasi</h1>
        <p class="font-thin text-sm">Semua notifikasi aplikasi</p>
    </div>
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b-1 border-primer">
                <th>Diumumkan</th>
                <th>Judul</th>
                <th>Deskripsi</th>
                <th>Link Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($notifs as $index => $notif)
            <tr class="border-b-1 border-bsoft
                animasi-slide-kekanan
                ">
                <!--TANGGAL-->
                <td>{{ $notif->created_at }}</td>
                <!--TITLE-->
                <td>{{ $notif->title_notif }}</td>
                <!--BODY-->
                <td>{{ $notif->body_notif }}</td>
                <!--LINK-->
                <td><a href="{{ $notif->link_click_action }}">{{ $notif->link_click_action }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
