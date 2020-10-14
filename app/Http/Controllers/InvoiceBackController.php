<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Invoiceback;
use App\SalesBack;
class InvoiceBackController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function InvoiceBackForm(){
    	return view('pages.invoice.invoiceback');
    }
    public function insert(Request $r){
    	$data['product']=explode(',',$r->product[0]);
    	$data['qantities']=explode(',',$r->qantities[0]);
    	$data['prices']=explode(',',$r->prices[0]);
    	$data['customer']=$r->customer;
    	$data['date']=strtotime(strval($r->date));
    	$data['total_payable']=$r->total_payable;
    	$data['total_item']=$r->total_item;
    	$data['fine']=$r->fine;
    	$data['total']=$r->total;
    	// return $r->all();
    	$validator=Validator::make($data,[
    		'product'=>'required|array',
    		'product.*'=>'required|distinct|regex:/^([0-9]+)$/',
    		'qantities'=>'required|array',
    		'qantities.*'=>'required|regex:/^([0-9]+)$/',
    		'prices'=>'required|array',
    		'prices.*'=>'required|regex:/^([0-9]+)$/',
    		'customer'=>'required|regex:/^([0-9]+)$/',
    		'date'=>'required|max:10',
    		'total_payable'=>'required|max:10',
    		'total_item'=>'required|max:10',
    		'fine'=>'nullable|max:15',
    		'total'=>'required|max:15',
    	]);
    	if ($validator->passes()) {
    		$invoice=new Invoiceback;
    		$invoice->dates=$data['date'];
    		$invoice->customer_id=$data['customer'];
    		$invoice->total_item=$data['total_item'];
    		$invoice->fine=$data['fine'];
    		$invoice->total_payable=$data['total_payable'];
    		$invoice->total=$data['total'];
    		$invoice->increment_id=0;
    		$invoice->user_id=Auth::user()->id;
    		$invoice->save();
    		$inv_id=$invoice->id;
    		$user_id=$invoice->user_id;
	    	if ($invoice=true) {
	    			$length=intval($data['total_item'])-1;
    			for ($i=0; $i <=$length; $i++) {
	    			$stmt=new Salesback();
	                $stmt->invoice_id=$inv_id;
	    			$stmt->dates=$data['date'];
	    			$stmt->customer_id=$r->customer;
	    			$stmt->product_id=$data['product'][$i];
	    			$stmt->qantity=$data['qantities'][$i];
	    			$stmt->price=$data['prices'][$i];
	    			$stmt->user_id=$user_id;
	                $stmt->increment_id=$this->Increment()+1;
	    			$stmt->save();
    			}
    			if ($stmt=true) {
    				$increment_id=$this->Increment()+1;
    				$inv=Invoiceback::where('id',$inv_id)->update(['increment_id'=>$increment_id]);
    				if ($inv=true) {
    					return ['message'=>'success'];
    				}
    			}
	    	}
    	}
    	return response()->json([$validator->getMessageBag()]);
    }
    private function Increment(){
       $data=DB::select("
          SELECT 
              (SELECT max(increment_id) from voucers) as voucer_id,
              (SELECT max(increment_id) from invoices) as invoice_id,
              (SELECT max(increment_id) from sales) as sales_id,
              (SELECT max(increment_id) from invoicebacks) as invoiebacks_id,
              (SELECT max(increment_id) from salesbacks) as salesbacks_id,
              (SELECT max(increment_id) from invpurchases) as invpurchase_id,
              (SELECT max(increment_id) from purchases) as purchase_id,
              (SELECT max(increment_id) from invpurchasebacks) as invpurchasebacks_id,
              (SELECT max(increment_id) from purchasebacks) as purchaseback_id
              ");
        foreach ($data[0] as $key => $value) {
            $arr[]=$value;
        }
        return intval(max($arr));
    }
}
