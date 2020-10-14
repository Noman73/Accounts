<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use App\Invoice;
use App\Sale;
use DataTables;
class InvoiceController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function invoiceForm(){
    	return view('pages.invoice.invoice');
    }
    public function insertInvoice(Request $r){
    	$data['product']=explode(',',$r->product[0]);
    	$data['qantities']=explode(',',$r->qantities[0]);
    	$data['prices']=explode(',',$r->prices[0]);
    	$data['customer']=$r->customer;
    	$data['date']=strtotime(strval($r->date));
    	$data['total_payable']=$r->total_payable;
    	$data['total_item']=$r->total_item;
    	$data['discount']=$r->discount;
    	$data['vat']=$r->vat;
    	$data['labour']=$r->labour;
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
    		'discount'=>'nullable|max:15',
    		'vat'=>'nullable|max:15',
    		'labour'=>'nullable|max:15',
    		'total'=>'required|max:15',
    	]);
    	if ($validator->passes()) {
    		$invoice=new Invoice;
    		$invoice->dates=$data['date'];
    		$invoice->customer_id=$data['customer'];
    		$invoice->total_item=$data['total_item'];
    		$invoice->discount=$data['discount'];
    		$invoice->vat=$data['vat'];
    		$invoice->labour_cost=$data['labour'];
    		$invoice->total_payable=$data['total_payable'];
    		$invoice->total=$data['total'];
    		$invoice->increment_id=$this->Increment();
    		$invoice->user_id=Auth::user()->id;
    		$invoice->save();
    		$inv_id=$invoice->id;
    		$user_id=$invoice->user_id;
	    	if ($invoice=true) {
	    			$length=intval($data['total_item'])-1;
    			for ($i=0; $i <=$length; $i++) {
	    			$stmt=new Sale();
	                $stmt->invoice_id=$inv_id;
	    			$stmt->dates=$data['date'];
	    			$stmt->customer_id=$r->customer;
	    			$stmt->product_id=$data['product'][$i];
	    			$stmt->qantity=$data['qantities'][$i];
	    			$stmt->price=$data['prices'][$i];
	    			$stmt->user_id=$user_id;
	                $stmt->increment_id=$this->Increment($i)+1;
	    			$stmt->save();
    			}
    			if ($stmt=true) {
    				$increment_id=$this->Increment()+1;
    				$inv=Invoice::where('id',$inv_id)->update(['increment_id'=>$increment_id]);
    				if ($inv=true) {
    					return ['message'=>'success'];
    				}
    			}
	    	}
    	}
    	return response()->json([$validator->getMessageBag()]);
    }

    public function getChildCat($id=null){
    	$data=DB::table('child_categories')->select('id','name')->where('cat_id',$id)->get();
    	return [$data];
    }
    public function allInvoices(){
        if (request()->ajax()) {
            $total_bal=500;
            $get=DB::table('invoices')
                     ->join('customers','customers.id','=','invoices.customer_id')
                     ->select('invoices.id','invoices.dates','customers.name','invoices.total_item','invoices.total_payable','invoices.total')
                     ->get();
        return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('action',function($get){
          $button  ='<div class="btn-group btn-group-toggle" data-toggle="buttons">
                       <a type="button" href="'.URL::to('admin/invoice/').$get->id.'" class="btn btn-sm btn-primary rounded mr-1 edit" data-toggle="modal" data-target=""><i class="fas fa-eye"></i></a>
                       <a class="btn btn-danger btn-sm rounded delete" data-id="'.$get->id.'"><i class="fas fa-trash-alt"></i></a>
                    </div>';
        return $button;
      })
      ->rawColumns(['action'])->make(true);
        }
        return view('pages.invoice.all_invoices');
    }
    public function UpdateForm($id){
        $invoice=DB::table('invoices')
                ->join('customers','customers.id','=','invoices.customer_id')
                ->selectRaw('invoices.id,invoices.customer_id,customers.name,customers.phone1,invoices.discount,invoices.vat,invoices.labour_cost,invoices.total_item,invoices.total_payable,invoices.total')
                ->where('invoices.id',$id)
                ->first();               
        $sales=DB::select("select sales.id,sales.product_id,sales.invoice_id,products.product_name,sales.qantity,sales.price from sales inner join products on products.id=sales.product_id where sales.invoice_id=:id order by sales.id asc",['id'=>$id]);
        $invoice=json_encode($invoice);
        $sales=json_encode($sales);
        return view('pages.invoice.invoice-update',compact('invoice','sales'));
    }
    public function Update(Request $r,$id){
        $data['product']=explode(',',$r->product[0]);
        $data['row']=explode(',',$r->row[0]);
        $data['qantities']=explode(',',$r->qantities[0]);
        $data['prices']=explode(',',$r->prices[0]);
        $data['customer']=$r->customer;
        $data['date']=strtotime(strval($r->date));
        $data['total_payable']=$r->total_payable;
        $data['total_item']=$r->total_item;
        $data['discount']=$r->discount;
        $data['vat']=$r->vat;
        $data['labour']=$r->labour;
        $data['total']=$r->total;
        // return $r->all();
        $validator=Validator::make($data,[
            'product'=>'required|array',
            'product.*'=>'required|distinct|regex:/^([0-9]+)$/',
            'qantities'=>'required|array',
            'qantities.*'=>'required|regex:/^([0-9.]+)$/',
            'prices'=>'required|array',
            'prices.*'=>'required|regex:/^([0-9.]+)$/',
            'customer'=>'required|regex:/^([0-9]+)$/',
            'date'=>'required|max:10',
            'total_payable'=>'required|max:10',
            'total_item'=>'required|max:10',
            'discount'=>'nullable|max:15',
            'vat'=>'nullable|max:15',
            'labour'=>'nullable|max:15',
            'total'=>'required|max:15',
        ]);
        if ($validator->passes()) {
            $invoice=Invoice::find($id);
            $invoice->dates=$data['date'];
            $invoice->customer_id=$data['customer'];
            $invoice->total_item=$data['total_item'];
            $invoice->discount=$data['discount'];
            $invoice->vat=$data['vat'];
            $invoice->labour_cost=$data['labour'];
            $invoice->total_payable=$data['total_payable'];
            $invoice->total=$data['total'];
            $invoice->micro_time=$this->Increment();
            $invoice->user_id=Auth::user()->id;
            $invoice->save();
            $inv_id=$invoice->id;
            $user_id=$invoice->user_id;
            if ($invoice=true) {
                    $length=intval($data['total_item'])-1;
                for ($i=0; $i <=$length; $i++){
                    if ($data['row'][$i]!=0) {
                        $stmt=Sale::find($data['row'][$i]);
                        $stmt->invoice_id=$inv_id;
                        $stmt->dates=$data['date'];
                        $stmt->customer_id=$r->customer;
                        $stmt->product_id=$data['product'][$i];
                        $stmt->qantity=$data['qantities'][$i];
                        $stmt->price=$data['prices'][$i];
                        $stmt->user_id=$user_id;
                        $stmt->increment_id=$this->Increment()+1;
                        $stmt->save();
                    }else{
                        $stmt=new Sale();
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
                }
                if ($stmt=true) {
                    $increment_id=$this->Increment()+1;
                    $inv=Invoice::where('id',$inv_id)->update(['increment_id'=>$increment_id]);
                    if ($inv=true) {
                        return ['message'=>'success'];
                    }
                }
            }
        }
        return response()->json([$validator->getMessageBag()]);
    }
    public function Increment($data=null){
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
