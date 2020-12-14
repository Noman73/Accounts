<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Name;
use Validator;
use DB;
class CustomReportController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	$name=Name::select('id','name')->where('stutus',0)->get();
    	return view('pages.reports.custom_report.custom_report',compact('name'));
    }
    public function Report(Request $r){
    	$validator=Validator::make($r->all(),[
    		'report_name'	=>'required|max:100|min:1',
    		'sub_name'		=>'required|max:15|min:1',
    		'fromDate'		=>'required|max:10|date_format:d-m-Y',
    		'toDate'		=>'required|max:10|date_format:d-m-Y'
    	]);
	   if ($validator->passes()) {
	   	$fromDate=strtotime(strval($r->fromDate));
	   	$toDate=strtotime(strval($r->toDate));
	   		
	   		  	$get=DB::select("
	SELECT voucers.id,voucers.dates,namerelations.rel_name,voucers.debit,voucers.credit from voucers
	inner join namerelations on voucers.data_id=namerelations.id where voucers.category=:report_name and voucers.data_id=:sub_name and voucers.dates>=:fromDate and voucers.dates<=:toDate;
	    ",['report_name'=>$r->report_name,'sub_name'=>$r->sub_name,'fromDate'=>$fromDate,'toDate'=>$toDate]);
	    		return response()->json(['get'=>$get,'fromDate'=>$fromDate,'toDate'=>$toDate]);
	   }
    	return response()->json(['error'=>$validator->getMessageBag(),'all'=>$r->all()]);
    }
}
