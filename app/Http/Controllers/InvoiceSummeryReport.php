<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DOMPDF;
class InvoiceSummeryReport extends Controller
{
    Public function __construct(){
    	$this->middleware('auth');
    }

    public function Form(){
    	return view('pages.reports.invoice.invoice_summery');
    }
    public function Report(Request $r){
    	$fromDate=strtotime(strval(trim($r->fromDate)));
    	$toDate=strtotime(strval(trim($r->toDate)));
    	$get=DB::select("
    		SELECT invoices.id as invoice_id,invoices.dates,IFNULL(customers.name,'NOT AVAILABLE') as name,invoices.total,invoices.total_payable from invoices
    			 LEFT JOIN customers ON customers.id=invoices.customer_id 
    			 where invoices.dates>=:fromDate and invoices.dates<=:toDate
    		",['fromDate'=>$fromDate,'toDate'=>$toDate]);
    	$pdf=DOMPDF::loadView('pages.reports.invoice.invoice_summery_pdf',compact('get','current_blnce','fromDate','toDate'))->setPaper('a4','portrait');
        return $pdf->stream('invoice_summery.pdf');
    }
}
