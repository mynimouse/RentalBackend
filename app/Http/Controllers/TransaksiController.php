<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaksi = Transaksi::with('rent')->paginate(10);
        return response()->json($transaksi);
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
        $validate = Validator::make($request->all(), [
            'rent' => 'required|exists:rents,id|unique:transaksis,id',
            'jenis' => 'required|in:dana,cash',
            'pembayaran' => 'required',
            'description' => 'nullable',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }

        $transaksi = Transaksi::create($request->all());
        return response()->json($transaksi);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::with('rent')->find($id);
        return response()->json($transaksi);
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
        $validate = Validator::make($request->all(), [
            'rent' => 'required|exists:rents,id',
            'jenis' => 'required|in:dana,cash',
            'pembayaran' => 'required',
            'description' => 'nullable',
        ]);
        if ($validate->fails()) {
            return response()->json($validate->errors());
        }
        $transaksi = Transaksi::find($id);
        $transaksi->update($request->all());
        return response()->json($transaksi);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaksi = Transaksi::find($id);
        if ($transaksi == null) {
            return response()->json([
                'msg' => 'data tidak ditemukan'
            ]);
        }
        $transaksi->delete();
        return response()->json($transaksi);
    }
}
