<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use App\Invoice;
use App\Sale;
use App\Voucer;
use DataTables;
use URL;
class InvoiceController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function invoiceForm(){
    	return view('pages.invoice.invoice');
    }
    public function insertInvoice(Request $r){
    	$data['product']=array_combine(range(1,count(explode(',',$r->product[0]))),explode(',',$r->product[0]));
    	$data['qantities']=array_combine(range(1,count(explode(',',$r->qantities[0]))),explode(',',$r->qantities[0]));
    	$data['prices']=array_combine(range(1,count(explode(',',$r->prices[0]))),explode(',',$r->prices[0]));
    	$data['customer']=$r->customer;
    	$data['date']=$r->date;
    	$data['total_payable']=$r->total_payable;
    	$data['total_item']=$r->total_item;
    	$data['discount']=$r->discount;
    	$data['vat']=$r->vat;
    	$data['labour']=$r->labour;
        $data['transaction']=$r->transaction;
        $data['payment_method']=$r->payment_method;
        if ($r->payment_method=='null') {
            $data['payment_method']=null;
        }
        $data['pay']=$r->pay;
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
    		'date'=>'required|max:10|date_format:d-m-Y',
    		'total_payable'=>'required|max:10|regex:/^([0-9.]+)$/',
    		'total_item'=>'required|max:10|regex:/^([0-9.]+)$/',
    		'discount'=>'nullable|max:15|regex:/^([0-9.]+)$/',
    		'vat'=>'nullable|max:15|regex:/^([0-9.]+)$/',
    		'labour'=>'nullable|max:15|regex:/^([0-9.]+)$/',
            'total'=>'required|max:15|regex:/^([0-9.]+)$/',
            'payment_method'=>'nullable|max:10|regex:/^([0-9]+)$/',
            'transaction'=>'nullable|max:30|regex:/^([a-zA-Z0-9]+)$/',
    		'pay'=>'nullable|max:18|regex:/^([0-9.]+)$/',
    	]);
    	if ($validator->passes()) {
    		$invoice=new Invoice;
    		$invoice->dates=strtotime(strval($data['date']));
    		$invoice->customer_id=$data['customer'];
    		$invoice->total_item=$data['total_item'];
    		$invoice->discount=$data['discount'];
    		$invoice->vat=$data['vat'];
    		$invoice->labour_cost=$data['labour'];
    		$invoice->total_payable=$data['total_payable'];
            $invoice->total=$data['total'];
    		$invoice->action_id=1;
    		$invoice->user_id=Auth::user()->id;
    		$invoice->save();
    		$inv_id=$invoice->id;
    		$user_id=$invoice->user_id;
	    	if ($invoice=true){
	    			$length=intval($data['total_item'])-1;
    			for ($i=0; $i <=$length;$i++){
	    			$stmt=new Sale();
	                $stmt->invoice_id=$inv_id;
	    			$stmt->dates=strtotime(strval($data['date']));
	    			$stmt->customer_id=$data['customer'];
	    			$stmt->product_id=$data['product'][$i+1];
	    			$stmt->qantity=$data['qantities'][$i+1];
	    			$stmt->price=$data['prices'][$i+1];
                    $stmt->user_id=$user_id;
	    			$stmt->action_id=1;
	    			$stmt->save();
    			}
    			if ($stmt=true){
                    if ($data['payment_method']!=null and $data['pay']!=null) {
                        $voucer=new Voucer();
                        $voucer->bank_id=$data['payment_method'];
                        $voucer->dates=strtotime(strval($data['date']));
                        $voucer->category='customer';
                        $voucer->data_id=$data['customer'];
                        $voucer->debit=$data['pay'];
                        $voucer->invoice_id=$inv_id;
                        $voucer->user_id=Auth::user()->id;
                        $voucer->save();
                        $v_id=$voucer->id;
                        $inv=Invoice::where('id',$inv_id)->update(['payment_id'=>$v_id]);
                    return ['message'=>'Invoice and Payment Added Success','id'=>$inv_id];
                    }
                    return ['message'=>'Invoice Added Success','id'=>$inv_id];
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
                       <a type="button" href="'.URL::to('admin/invoice-update').'/'.$get->id.'" class="btn btn-sm btn-primary rounded mr-1 edit"><i class="fas fa-eye"></i></a>
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
        $data['row']=array_combine(range(1,count(explode(',',$r->row[0]))),explode(',',$r->row[0]));
        $data['product']=array_combine(range(1,count(explode(',',$r->product[0]))),explode(',',$r->product[0]));
        $data['qantities']=array_combine(range(1,count(explode(',',$r->qantities[0]))),explode(',',$r->qantities[0]));
        $data['prices']=array_combine(range(1,count(explode(',',$r->prices[0]))),explode(',',$r->prices[0]));
        $data['customer']=$r->customer;
        $data['date']=$r->date;
        $data['total_payable']=$r->total_payable;
        $data['total_item']=$r->total_item;
        $data['discount']=$r->discount;
        $data['vat']=$r->vat;
        $data['labour']=$r->labour;
        $data['total']=$r->total;
        // return $r->all();
        $validator=Validator::make($data,[
            'row' => 'required|array',
            'row.*' => 'required|distinct|regex:/^([0-9]+)$/',
            'product'=>'required|array',
            'product.*'=>'required|distinct|regex:/^([0-9]+)$/',
            'qantities'=>'required|array',
            'qantities.*'=>'required|regex:/^([0-9.]+)$/',
            'prices'=>'required|array',
            'prices.*'=>'required|regex:/^([0-9.]+)$/',
            'customer'=>'required|regex:/^([0-9]+)$/',
            'date'=>'required|max:10|date_format:d-m-Y',
            'total_payable'=>'required|max:10|regex:/^([0-9.]+)$/',
            'total_item'=>'required|max:10|regex:/^([0-9.]+)$/',
            'discount'=>'nullable|max:15|regex:/^([0-9.]+)$/',
            'vat'=>'nullable|max:15|regex:/^([0-9.]+)$/',
            'labour'=>'nullable|max:15|regex:/^([0-9.]+)$/',
            'total'=>'required|max:15|regex:/^([0-9.]+)$/',
        ]);
        if ($validator->passes()) {
            $invoice=Invoice::find($id);
            $invoice->dates=strtotime(strval($data['date']));
            $invoice->customer_id=$data['customer'];
            $invoice->total_item=$data['total_item'];
            $invoice->discount=$data['discount'];
            $invoice->vat=$data['vat'];
            $invoice->labour_cost=$data['labour'];
            $invoice->total_payable=$data['total_payable'];
            $invoice->total=$data['total'];
            $invoice->user_id=Auth::user()->id;
            $invoice->save();
            $inv_id=$invoice->id;
            $user_id=$invoice->user_id;
            if ($invoice=true) {
                    $length=intval($data['total_item'])-1;
                for ($i=0; $i <=$length; $i++){
                    if ($data['row'][$i+1]!=0){
                        $stmt=Sale::find($data['row'][$i+1]);
                        $stmt->invoice_id=$inv_id;
                        $stmt->dates=strtotime(strval($data['date']));
                        $stmt->customer_id=$r->customer;
                        $stmt->product_id=$data['product'][$i+1];
                        $stmt->qantity=$data['qantities'][$i+1];
                        $stmt->price=$data['prices'][$i+1];
                        $stmt->user_id=$user_id;
                        $stmt->save();
                    }else{
                        $stmt=new Sale();
                        $stmt->invoice_id=$inv_id;
                        $stmt->dates=strtotime(strval($data['date']));
                        $stmt->customer_id=$r->customer;
                        $stmt->product_id=$data['product'][$i+1];
                        $stmt->qantity=$data['qantities'][$i+1];
                        $stmt->price=$data['prices'][$i+1];
                        $stmt->user_id=$user_id;
                        $stmt->increment_id=$this->Increment()+1;
                        $stmt->save();
                    }
                }
                if ($stmt=true) {
                    $increment_id=$this->Increment()+1;
                    $inv=Invoice::where('id',$inv_id)->update(['increment_id'=>$increment_id]);
                    if ($inv=true) {
                        return ['message'=>'success','id'=>$inv_id];
                    }
                }
            }
        }
        return response()->json([$validator->getMessageBag()]);
    }
    public function Increment(){
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
