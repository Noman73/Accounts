<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use DataTables;
use App\Voucer;
class VoucerController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function ManageVoucer(){
      $names=DB::table('names')->select('id','name')->where('stutus',1)->get();
    	$banks=DB::table('banks')->select('id','name')->get();
    	if (request()->ajax()) {
        $get=DB::select("select voucers.dates,voucers.category,voucers.data_id,banks.name as bank_name,voucers.debit,voucers.credit from voucers inner join banks on banks.id=voucers.bank_id");
          return DataTables::of($get)
          ->addIndexColumn()
          ->addColumn('dat',function($get){
          $dates  = date('d-m-Y',$get->dates);
          return $dates;
          })
          ->addColumn('categories',function($get){
          $categories =ucwords($get->category).'(ID-'.$get->data_id.')';
          return $categories;
          })
          ->rawColumns(['dat','categories'])->make(true);
        }
        return view('pages.voucer.voucers',compact('names','banks'));
    }
    public function getNameData($data=null){
      $data=trim($data);
      $data=ucwords($data);
        if ($data=='Customer' or $data=='Supplier'){
            $passdata=DB::table(strtolower($data).'s')->select('id','name')->get();
            return $passdata;
        }else{
            $passdata=DB::table('names')
                      ->join('namerelations','names.id','=','namerelations.name_id')
                      ->select('namerelations.id','namerelations.rel_name as name')
                      ->where('names.name',$data)
                      ->get();
             return $passdata;
        }
    }
    public function insertVoucer(Request $r){
        $validator=Validator::make($r->all(),[
            'date'=>'required|max:10|min:10',
            'category'=>'required|max:100',
            'data'=>'required|max:20',
            'payment_type'=>'required|max:7',
            'bank'=>'required|max:10',
            'debit'=>'nullable|max:20|',
            'credit'=>'nullable|max:20|',
        ]);
        if ($validator->passes()){
           
            $voucer=new Voucer;
            $voucer->dates=strtotime(strval($r->date));
            $voucer->category=strtolower($r->category);
            $voucer->data_id=$r->data;
            $voucer->bank_id=$r->bank;
            if ($r->payment_type=='Deposit') {
              $voucer->debit=$r->ammount;
            }
            if ($r->payment_type=='Expence') {
              $voucer->credit=$r->ammount;
            }
            $voucer->increment_id=$this->Increment()+1;
            $voucer->user_id=Auth::user()->id;
            $voucer->save();
            return ['message'=>'success'];
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
