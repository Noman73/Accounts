<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
class DailyStatementController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function Form(){
    	return view('pages.reports.dailyStatement.daily_statement_form');
    }
    public function Report(Request $r){
    	$fromDate=strtotime(strval($r->fromDate));
    	$toDate=strtotime(strval($r->toDate));
        $opening_balance=DB::table('banks')->selectRaw('sum(opening_balance) as opening_balance')->first();
        $expence=DB::table('voucers')->selectRaw('sum(ammount) as ammount')->where('payment_type','Expence')->first();
        $deposit=DB::table('voucers')->selectRaw('sum(ammount) as ammount')->where('payment_type','Deposit')->first();
        $total=($deposit->ammount-$expence->ammount)+$opening_balance->opening_balance;
    	$get=DB::select("
    	    		SELECT dates,
    	    		       name as category,
    	    	CASE WHEN 
    	    			  voucers.name='customer' THEN  (select name from customers where id=voucers.name_data_id)
    	          	 WHEN 
    	          		  voucers.name='supplier' THEN  (select name from suppliers where id=voucers.name_data_id)
    	          	 ELSE
    	              	 (select rel_name from namerelations where id=name_data_id) end as name,
    		   IF(payment_type='Deposit',ammount,0) as Deposit,
    	       IF(payment_type='Expence',ammount,0) as Expence
    	             from voucers where dates>=:fromDate and dates<=:toDate
    	    		",['fromDate'=>$fromDate,'toDate'=>$toDate]);

    	return response()->json(['get'=>$get,'fromDate'=>$fromDate,'toDate'=>$toDate,'total'=>$total]);
    }
}
