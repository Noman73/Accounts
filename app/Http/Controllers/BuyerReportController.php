<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class BuyerReportController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	return view('pages.reports.buyer.buyerlist');
    }
    public function BuyerList(){
    	$data=DB::table('customers')->selectRaw("id,name,phone1,ifnull(adress,'not inserted') as adress,stutus")->get();

    	return response()->json(['get'=>$data]);
    }
    public function BuyerBalanceSheet(){
     $data=DB::select("
SELECT id,name,phone1,adress,((ifnull((select sum(debit-credit) from voucers where category='customer' and data_id=customers.id),0)+ifnull((select sum(total_payable) from invoicebacks where customer_id=customers.id),0))-ifnull((select sum(total_payable) from invoices where customer_id=customers.id),0))-previous_due as balance from customers
    ");
     return response()->json(['get'=>$data]);
    }
    public function BuyerBlnceForm(){
        return view('pages.reports.buyer.buyerbalancesheet');
    }
}
