<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        
        $menus = DB::table('tb_menus');
        if ($id) {
            $menus->where('id', '=', $id);
        }

        if ($name) {
            $menus->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormater::success(
            $menus->paginate(10),
            'Data list menu berhasil diambil'
        );
    }
}
