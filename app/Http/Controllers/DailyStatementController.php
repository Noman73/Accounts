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
        $voucer=DB::table('voucers')->selectRaw('sum(debit) as debit,sum(credit) as credit')->first();
        $total=($voucer->debit-$voucer->credit)+$opening_balance->opening_balance;
    	$get=DB::select("
    	    		SELECT dates,
    	    		      category,
    	    	CASE WHEN 
    	    			  voucers.category='customer' THEN  (select name from customers where id=voucers.data_id)
    	          	 WHEN 
    	          		  voucers.category='supplier' THEN  (select name from suppliers where id=voucers.data_id)
    	          	 ELSE
    	              	 (select rel_name from namerelations where id=voucers.data_id) end as name,
    		   ifnull(debit,0) as Deposit,
    	       ifnull(credit,0) as Expence
    	             from voucers where dates>=:fromDate and dates<=:toDate
    	    		",['fromDate'=>$fromDate,'toDate'=>$toDate]);

    	return response()->json(['get'=>$get,'fromDate'=>$fromDate,'toDate'=>$toDate,'total'=>$total]);
    }
}
