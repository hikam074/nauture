<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class C_RajaOngkir extends Controller
{
    public function cariDestination(Request $request) {
        Log::info('RAJAONGKIR - mencari destinasi');
        try {
            $response = Http::withHeaders([
                'key' => config('rajaongkir.cost_key')
            ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination', [
                'search' => $request->search ?? '',
                'limit' => $request->limit ?? 100,
                'offset' => $request->offset ?? 0,
            ]);
            // Cek jika respons berhasil
            if ($response->successful() && isset($response->json()['data'])) {
                Log::info('RAJAONGKIR - data didapatkan');
                return response()->json($response->json()['data']);
            }
            return response()->json(['error' => 'Failed to fetch locations'], $response->status());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function hitungOngkir(Request $request) {
        try {
            Log::info('RAJAONGKIR - cek validitas untuk hitung ongkir');
            $validator = Validator::make($request->all(), [
                'destination' => 'required|integer',
                'weight' => 'required|numeric|min:1',
                'courier' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            Log::info('RAJAONGKIR - aman validitas untuk hitung ongkir');
            $response = Http::withHeaders([
                'key' => config('rajaongkir.cost_key')
            ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'origin' => config('rajaongkir.origin'),
                'destination' => $request->destination,
                'weight' => ($request->weight)*1000,
                'courier' => $request->courier,
            ]);
            Log::info('RAJAONGKIR - coba akses api untuk hitung ongkir');
            // Cek jika respons berhasil
            if ($response->successful() && isset($response->json()['data'])) {
                Log::info('RAJAONGKIR - berhasil dapatkan data ongkir');
                return response()->json($response->json()['data']);
            }
            return response()->json(['error' => 'Failed to fetch locations'], $response->status());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
