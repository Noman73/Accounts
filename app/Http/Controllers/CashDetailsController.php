<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class CashDetailsController extends Controller
{
    public function __contruct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	return view('pages.reports.Cash_Details.cash_details');
    }
    public function cashDetails(){
    	$get=DB::select("
           select id,name,branch,number,(opening_balance+ifnull((select sum(ifnull(debit,0))-sum(ifnull(credit,0)) from voucers where banks.id=voucers.bank_id),0)) as total from banks
            ");

      return ['get'=>$get];
    }
}
