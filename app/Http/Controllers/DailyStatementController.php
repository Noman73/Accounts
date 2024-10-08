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
        $previous=DB::table('voucers')->selectRaw('sum(debit) debit,sum(credit) as credit')->whereRaw("dates<?",[$fromDate])->first();
        $previous_debit=$previous->debit+$opening_balance->opening_balance;
            $deb=abs($previous_debit);
            $cred=abs($previous->credit);

        $voucer=DB::table('voucers')->selectRaw('sum(debit) as debit,sum(credit) as credit')->first();
        $total=($voucer->debit-$voucer->credit)+$opening_balance->opening_balance;
    	$get=DB::select("
  SELECT t.id,t.dates,t.bank_name,t.category,t.name,t.Deposit,t.Expence
from (SELECT 
             voucers.id,
             voucers.dates,
             banks.name bank_name,
             voucers.category,
             concat(ifnull(suppliers.name,''),ifnull(customers.name,''),ifnull(banks2.name,''),ifnull(namerelations.rel_name,'')) as name,
  ifnull(voucers.debit,0) as Deposit,
  ifnull(voucers.credit,0) as Expence
       from voucers
          left join banks on voucers.bank_id=banks.id
          left join suppliers on voucers.category='supplier' and voucers.data_id=suppliers.id
          left join customers on voucers.category='customer' and voucers.data_id=customers.id
          left join banks as banks2 on voucers.category='fund_transfer' and voucers.data_id=banks2.id
          left join names on voucers.category=names.name and names.stutus=0
          left join namerelations on names.id=namerelations.name_id and voucers.data_id=namerelations.id
        where voucers.dates>=:fromDate and voucers.dates<=:toDate 
        UNION ALL
  SELECT '','','','','Previous Balance','".$deb."','".$cred."') t
order by t.dates,t.id
    	    		",['fromDate'=>$fromDate,'toDate'=>$toDate]);

    	return response()->json(['get'=>$get,'fromDate'=>$fromDate,'toDate'=>$toDate,'total'=>$total]);
    }
}
