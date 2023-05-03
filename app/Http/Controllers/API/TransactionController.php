<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function all(Request $request)
    {
        $status = $request->input('status');

        $transactions = DB::table('tb_transactions')
            ->join('tb_menus', 'tb_transactions.menu_id', '=', 'tb_menus.id')
            ->join('tb_employees', 'tb_transactions.employee_id', '=', 'tb_employees.id')
            ->join('users', 'tb_employees.user_id', '=', 'users.id')
            ->select('tb_transactions.*', 'tb_menus.name as menu_name', 'users.name as employee_name');
        if ($status) {
            $transactions->where('tb_transactions.status', '=', $status);
        }

        if ($transactions) {
            return ResponseFormater::success(
                $transactions->paginate(10),
                'Data list transaksi berhasil diambil'
            );
        } else {
            return ResponseFormater::error(
                null,
                'Data list transaksi gagal diambil',
                500
            );
        }

    }

    public function CreateTransaction(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:tb_menus,id',
            'user_id' => 'required|exists:users,id',
            'price' => 'required',
            'quantity' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);

        $employee_id = DB::table('tb_employees')->select('id')->where('user_id', $request->user_id)->first();

        // dd($employee_id->id);

        $transaction = DB::table('tb_transactions')->insert([
            'menu_id' => $request->menu_id,
            'employee_id' => $employee_id->id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'created_by' => $request->user_id,
            'created_at' => date('Y-m-d H:i:s')
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
            'id' => 'required|exists:tb_transactions,id',
            'status' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        $transaction = DB::table('tb_transactions')->where('id', $request->id)->update([
            'status' => $request->status,
            'updated_by' => $request->user_id,
            'updated_at' => date('Y-m-d H:i:s')

        ]);

        $transaction_update = DB::table('tb_transactions')->where('id', $request->id)->first();

        // dd($transaction_update);

        if ($transaction_update) {
            return ResponseFormater::success(
                $transaction_update,
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
