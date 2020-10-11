<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
class StockController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Stock(){
    	if (request()->ajax()) {
            $total_bal=500;
            $get=DB::select("
            	select product_name,(ifnull((select sum(qantity) from purchases where products.id=purchases.product_id),0))-ifnull((select sum(qantity) from sales where products.id=sales.product_id),0) as total from products
            	");
        return DataTables::of($get)->addIndexColumn()->make(true);
        }
        return view('pages.stock.stock');
    }
}
