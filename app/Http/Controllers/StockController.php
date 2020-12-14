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
            $get=DB::select("
SELECT stores.name store,products.product_name,ifnull(sum(purchases.deb_qantity-purchases.cred_qantity),0)-ifnull(sales1.deb_qantity,0) qantity from
products 
left join purchases on purchases.product_id=products.id
left join (
select product_id,store_id,ifnull(sum(deb_qantity)-sum(cred_qantity),0) deb_qantity from sales where action_id=0 or action_id=2 or action_id=3 group by product_id,store_id
) as sales1 on (sales1.product_id=products.id and purchases.store_id=sales1.store_id)
left join stores on (sales1.store_id=stores.id or purchases.store_id=stores.id) group by products.id,sales1.store_id,purchases.store_id,purchases.product_id,sales1.product_id order by products.id
            	");
        return DataTables::of($get)->addIndexColumn()->make(true);
        }
        return view('pages.stock.stock');
    }
}
