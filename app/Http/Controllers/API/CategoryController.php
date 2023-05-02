<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        
        $categories = DB::table('tb_categories')->join('tb_menus', 'tb_menus.category_id', '=', 'tb_categories.id')
        ->select('tb_menus.id as menu_id', 'tb_menus.name as menu_name', 'tb_menus.description as menu_description', 'tb_categories.*');

        if ($id) {
            $categories->where('tb_categories.id', '=', $id);
        }

        if ($name) {
            $categories->where('tb_categories.name', 'like', '%' . $name . '%');
        }

        return ResponseFormater::success(
            $categories->paginate(10),
            'Data list category berhasil diambil'
        );
    }

}
