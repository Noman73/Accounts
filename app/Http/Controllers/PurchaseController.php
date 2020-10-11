<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Validator;
use App\Invpurchase;
use App\Purchase;
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
        $data['supplier']=$r->supplier;
        $data['date']=strtotime(strval($r->date));
        $data['total_payable']=$r->total_payable;
        $data['total_item']=$r->total_item;
        $data['transport']=$r->transport;
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
            'supplier'=>'required|regex:/^([0-9]+)$/',
            'date'=>'required|max:10',
            'total_payable'=>'required|max:10',
            'total_item'=>'required|max:10',
            'transport'=>'nullable|max:15',
            'labour'=>'nullable|max:15',
            'total'=>'required|max:15',
        ]);
        if ($validator->passes()) {
            $invoice=new Invpurchase;
            $microtime=explode(' ', microtime());
            $invoice->dates=$data['date'];
            $invoice->supplier_id=$data['supplier'];
            $invoice->total_item=$data['total_item'];
            $invoice->transport=$data['transport'];
            $invoice->labour_cost=$data['labour'];
            $invoice->total_payable=$data['total_payable'];
            $invoice->total=$data['total'];
            $invoice->micro_time=$microtime[1];
            $invoice->user_id=Auth::user()->id;
            $invoice->save();
            $inv_id=$invoice->id;
            $user_id=$invoice->user_id;
            if ($invoice=true) {
                    $length=intval($data['total_item'])-1;
                for ($i=0; $i <=$length; $i++) {
                    $microtime=explode(' ', microtime());
                    $stmt=new Purchase();
                    $stmt->invoice_id=$inv_id;
                    $stmt->dates=$data['date'];
                    $stmt->supplier_id=$r->supplier;
                    $stmt->product_id=$data['product'][$i];
                    $stmt->qantity=$data['qantities'][$i];
                    $stmt->price=$data['prices'][$i];
                    $stmt->user_id=$user_id;
                    $stmt->micro_time=$microtime[1].'.'.(int)round($microtime[0]*1000)+$i;
                    $stmt->save();
                }
                if ($stmt=true) {
                    $micro_time=$microtime[1].'.'.(int)round($microtime[0]*1000)+($length+2);
                    $inv=Invpurchase::where('id',$inv_id)->update(['micro_time'=>$micro_time]);
                    if ($inv=true) {
                        return ['message'=>'success'];
                    }
                }
            }
        }
        return response()->json([$validator->getMessageBag()]);
    }
}
