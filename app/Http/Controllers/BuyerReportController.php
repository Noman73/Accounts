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
    	return $data=DB::select("
SELECT id,name,phone1,((select ifnull(sum(ammount),0) from voucers where payment_type='Deposit' and name_data_id=customers.id and name='customer')-((select ifnull(sum(ammount),0) from voucers where payment_type='Expence' and name_data_id=customers.id and name='customer')+previous_due+ifnull((select sum(total_payable) from invoicebacks where customer_id=customers.id),0)))-ifnull((select sum(total_payable) from invoices where customer_id=customers.id),0) as balance from customers
    ");
    }
}
