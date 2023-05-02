<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function CreateTransaction(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:tb_menus,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);

        $employee_id = DB::table('tb_employees')->select('id')->where('user_id', $request->user_id)->first();

        $transaction = DB::table('tb_transactions')->insert([
            'menu_id' => $request->menu_id,
            'employee_id' => $employee_id,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status
        ]);

        if ($transaction) {
            return ResponseFormater::success(
                $transaction,
                'Data transaksi berhasil ditambahkan'
            );
        } else {
            return ResponseFormater::error(
                null,
                'Data transaksi gagal ditambahkan',
                500
            );
        }

    }

    public function SetStatus(Request $request)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $transaction = DB::table('tb_transactions')->where('id', $request->id)->update([
            'status' => $request->status
        ]);

        if ($transaction) {
            return ResponseFormater::success(
                $transaction,
                'Data transaksi berhasil diupdate'
            );
        } else {
            return ResponseFormater::error(
                null,
                'Data transaksi gagal diupdate',
                500
            );
        }

    }

}
