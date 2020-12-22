<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Validator;
use App\Invpurchase;
use App\Purchase;
use App\Voucer;
class PurchaseController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function ManagePurchase(){
        return view('pages.purchase.purchase');
    }
    public function insertPurchase(Request $r){
    	$data['product']=explode(',',$r->product[0]);
        $data['qantities']=explode(',',$r->qantities[0]);
        $data['prices']=explode(',',$r->prices[0]);
        $data['store']=explode(',',$r->store[0]);
        $data['supplier']=$r->supplier;
        $data['date']=strtotime(strval($r->date));
        $data['total_payable']=$r->total_payable;
        $data['total_item']=$r->total_item;
        $data['transport']=$r->transport;
        $data['transport_cost']=$r->transport_cost;
        $data['purchase_type']=$r->purchase_type;
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
            'qantities.*'=>'required|regex:/^([0-9]+)$/',
            'prices'=>'required|array',
            'prices.*'=>'required|regex:/^([0-9]+)$/',
            'purchase_type'=>'required|regex:/^([0-2]+)$/',
            'supplier'=>'required|regex:/^([0-9]+)$/',
            'date'=>'required|max:10',
            'total_payable'=>'required|max:10',
            'total_item'=>'required|max:10',
            'transport'=>'nullable|max:15',
            'payment_method'=>'nullable|max:15|regex:/^([0-9]+)$/',
            'transaction'=>'nullable|max:15|regex:/^([a-zA-Z0-9]+)$/',
            'pay'=>'nullable|max:15|regex:/^([0-9.]+)$/',
            'transport_cost'=>'nullable|max:15',
            'labour'=>'nullable|max:15',
            'total'=>'required|max:15',
        ]);
        if ($validator->passes()) {
            $invoice=new Invpurchase;
            $invoice->dates=$data['date'];
            $invoice->supplier_id=$data['supplier'];
            $invoice->total_item=$data['total_item'];
            $invoice->transport=$data['transport_cost'];
            $invoice->transport_id=$data['transport'];
            $invoice->labour_cost=$data['labour'];
            $invoice->total_payable=$data['total_payable'];
            $invoice->total=$data['total'];
            $invoice->action_id=$data['purchase_type'];
            $invoice->user_id=Auth::user()->id;
            $invoice->save();
            $inv_id=$invoice->id;
            $user_id=$invoice->user_id;
            if ($invoice=true){
                    $length=intval($data['total_item'])-1;
                for ($i=0; $i <=$length; $i++){
                    $stmt=new Purchase();
                    $stmt->invoice_id=$inv_id;
                    $stmt->dates=$data['date'];
                    $stmt->supplier_id=$r->supplier;
                    $stmt->product_id=$data['product'][$i];
                    if ($data['purchase_type']==0 or $data['purchase_type']==1){
                       $stmt->deb_qantity=$data['qantities'][$i];
                    }elseif($data['purchase_type']==2){
                        $stmt->cred_qantity=$data['qantities'][$i];
                    }
                    $stmt->price=$data['prices'][$i];
                    $stmt->store_id=$data['store'][$i];
                    $stmt->user_id=$user_id;
                    $stmt->action_id=$data['purchase_type'];
                    $stmt->save();
                }
                if ($stmt=true){
                     if ($data['payment_method']!=null and $data['pay']!=null) {
                        $voucer=new Voucer();
                        $voucer->bank_id=$data['payment_method'];
                        $voucer->dates=$data['date'];
                        $voucer->category='supplier';
                        $voucer->data_id=$data['supplier'];
                        if ($data['purchase_type']==2) {
                            $voucer->debit=$data['pay'];
                        }else{
                            $voucer->credit=$data['pay'];
                        }
                        $voucer->user_id=Auth::user()->id;
                        $voucer->save();
                        $v_id=$voucer->id;
                        $inv=Invpurchase::where('id',$inv_id)->update(['payment_id'=>$v_id]);
                    return ['message'=>'Purchase Invoice and Payment Added Success','id'=>$inv_id];
                    }
                    return ['message'=>'Purchase Invoice Added Success'];
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
