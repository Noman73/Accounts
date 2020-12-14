<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
class SaleSummeryController extends Controller
{
    Public function __construct(){
    	$this->middleware('auth');
    }

    public function Form(){
    	return view('pages.reports.sales.sales');
    }
    public function Report(Request $r){
        $validator=Validator::make($r->all(),[
            'type'=>'required|max:15',
            'fromDate'=>'required|max:10|min:10|date_format:d-m-Y',
            'toDate'=>'required|max:10|min:10|date_format:d-m-Y',
        ]);
        if ($validator->passes()) {
            $type=$r->type;
            $fromDate=strtotime(strval(trim($r->fromDate)));
            $toDate=strtotime(strval(trim($r->toDate)));
            $get=DB::select("
                SELECT sales.dates,sales.invoice_id,products.product_name,sum(sales.deb_qantity)-sum(sales.cred_qantity) qantity,sales.price,(sum(sales.deb_qantity)-sum(sales.cred_qantity))*sales.price as total from sales
                inner join products on products.id=sales.product_id where sales.dates>=:fromDate and sales.dates <=:toDate and action_id=:type group by sales.product_id,sales.dates
                ",['fromDate'=>$fromDate,'toDate'=>$toDate,'type'=>$type]);
            return ['get'=>$get,'fromDate'=>$fromDate,'toDate'=>$toDate];
        }
    }
}
