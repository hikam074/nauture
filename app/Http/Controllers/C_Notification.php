<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OneSignalService;

class C_Notification
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sendNotification($judul, $isiPesan, $url = null)
    {
        if ($url == null) {$url = config('onesignal.this_app_url');}
        
        $oneSignal = new OneSignalService();
        $response = $oneSignal->sendNotification(
            $judul, // Judul
            $isiPesan, // pesan
            $url // Opsional, URL tujuan.
        );
        if (isset($response['error'])) {
            return response()->json(['status' => 'failed', 'message' => $response['error']], 500);
        }
        return response()->json(['status' => 'success', 'data' => $response]);
    }
}
