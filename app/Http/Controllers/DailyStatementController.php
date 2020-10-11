<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
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

    	$pdf=PDF::loadView('pages.reports.dailyStatement.daily_statement_pdf',compact('get','fromDate','toDate'))->setPaper('a4','portrait');
        return $pdf->stream('invoice.pdf');
    }
}
