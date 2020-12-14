<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use DB;
class InstallmentStatusController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	  if (request()->ajax()) {
            $get=DB::select("
              SELECT invoices.id,invoices.dates,customers.name,cast(invoices.total_payable-(((invoices.total_payable*ifnull(invoices.insmnt_pay_percent,0))/100)+ifnull(sum(voucers.debit),0)) as decimal(20,2)) due,cast(invoices.total_payable-((invoices.total_payable*ifnull(invoices.insmnt_pay_percent,0))/100) as decimal(20,2)) total_payable,ifnull(count(voucers.id),0) paid_inst,cast(invoices.insmnt_total_days-ifnull(count(voucers.id),0) as int) due_inst from invoices
              inner join customers on customers.id=invoices.customer_id
              left join voucers on voucers.invoice_id=invoices.id and voucers.pay_action_id=1
              where invoices.action_id=3 group by invoices.id,voucers.invoice_id
              ");
        return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
          $button  ='<div class="btn-group btn-group-toggle" data-toggle="buttons">
                       <button type="button" class="btn btn-sm btn-primary rounded mr-1 edit" data-id="'.$get->id.'"><i class="fas fa-eye"></i></button>
                       <a class="btn btn-danger btn-sm rounded delete" data-id="'.$get->id.'"><i class="fas fa-trash-alt"></i></a>
                    </div>';
        return $button;
      })
      ->addColumn('date',function($get){
          return date('d-m-Y',$get->dates);
      })
      ->rawColumns(['action'])->make(true);
        }
        return view('pages.installment.installment_status');
    }
    public function getInvoice($id=null){
    	$invoice=DB::select("
SELECT invoices.id,invoices.dates,invoices.issue_dates,customers.name,invoices.total_item,invoices.total_payable,invoices.insmnt_type,invoices.insmnt_total_days,invoices.insmnt_pay_percent,ifnull(sum(voucers.debit),0)+ifnull(sum(voucers2.debit),0) debit,count(voucers2.id) from invoices
inner join customers on invoices.customer_id=customers.id
left join voucers on voucers.invoice_id=invoices.id and voucers.pay_action_id=0
left join voucers as voucers2 on voucers.invoice_id=invoices.id and voucers2.pay_action_id=1 where invoices.id=:id
   		",['id'=>$id]);
       return response()->json($invoice);
    }
  
}
