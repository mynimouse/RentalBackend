<?php

namespace App\Http\Controllers;

use App\Models\Rent;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $rent = Rent::paginate(10);
        $rent = Rent::with('tenant')->paginate();

        return response()->json($rent);
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
        $validatedData = Validator::make($request->all(), [
            "tenant" => "nullable|exists:users,id",
            "no_car" => "required|unique:rents,no_car",
            "date_borrow" => "required|date",
            "date_return" => "required|date|after:date_borrow",
            "down_payment" => "required|integer",
            'total' => 'required|integer',
            'discount' => 'integer',
        ]);
        if ($validatedData->fails()) {
            return response()->json([
                'msg' => 'Kesalahan input',
                'error' => $validatedData->errors()
            ]);
        }

        // Hitung total pembayaran setelah diskon
        $total = $request->input('total');
        $discount = $request->input('discount');
        $down_payment = $request->input('down_payment');
        $totalAfterDiscount = $total - $discount - $down_payment;
        $discount = !empty($discount) ? $discount : 0;
        // Simpan transaksi
        // $tenant = $request->tenant = auth()->id();
        $rent = Rent::create([
            'tenant' => $request->tenant,
            'no_car' => $request->no_car,
            'date_borrow' => $request->date_borrow,
            'date_return' => $request->date_return,
            'down_payment' => $down_payment,
            'discount' => $discount,
            'total' => $totalAfterDiscount,
        ]);

        $totalTanpaDiscount = $request->input('total');
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $rent,
            'total' => $totalTanpaDiscount
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $rent = Rent::with('tenant')->find($id);
        return response()->json($rent);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rent $rent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            "tenant" => "nullable",
            "no_car" => "required",
            "date_borrow" => "required|date",
            "date_return" => "required|date|after:date_borrow",
            "down_payment" => "required|integer",
            'total' => 'required|integer',
            'discount' => 'required|integer',
        ]);

        // Hitung total pembayaran setelah diskon
        $total = $request->input('total');
        $discount = $request->input('discount');
        $down_payment = $request->input('down_payment');
        $totalAfterDiscount = $total - $discount - $down_payment;

        // Ubah transaksi

        $rent = Rent::find($id);
        $rent->tenant = $request->input('tenant');
        $rent->no_car = $validatedData['no_car'];
        $rent->date_borrow = $validatedData['date_borrow'];
        $rent->date_return = $validatedData['date_return'];
        $rent->down_payment = $down_payment;
        $rent->discount = $discount;
        $rent->total = $totalAfterDiscount;
        $rent->save();


        return response()->json([
            'msg' => 'Berhasil',
            'data' => $rent
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $rent = Rent::find($id);
        if ($rent == null) {
            return response()->json([
                'msg' => 'Data Tidak Ada'
            ], 404);
        }
        $rent->delete();
        return response()->json($rent, 201);
    }
}
