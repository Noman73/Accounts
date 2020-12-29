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
            'date'=>'required|max:10|min:10|date_format:d-m-Y',
            'category'=>'required|max:100|regex:/^([a-zA-Z0-9., ]+)$/',
            'data'=>'required|max:20|regex:/^([0-9]+)$/',
            'payment_type'=>'required|max:7|regex:/^([a-zA-Z]+)$/',
            'bank'=>'required|max:10|regex:/^([0-9]+)$/',
            'debit'=>'nullable|max:20|regex:/^([0-9]+)$/',
            'credit'=>'nullable|max:20|regex:/^([0-9]+)$/',
            'ammount'=>'required|max:20|min:1|regex:/^([0-9]+)$/',
        ]);
        if ($validator->passes()){
           
            $voucer=new Voucer;
            $voucer->dates=strtotime(strval($r->date));
            $voucer->category=strtolower($r->category);
            $voucer->data_id=$r->data;
            $voucer->bank_id=$r->bank;
            if ($r->payment_type=='Deposit') {
              $voucer->debit=$r->ammount;
              $voucer->credit=0;
            }
            if ($r->payment_type=='Expence') {
              $voucer->credit=$r->ammount;
              $voucer->debit=0;
            }
            $voucer->user_id=Auth::user()->id;
            $voucer->save();
            return ['message'=>'Voucer Added Success'];
        }

        return response()->json($validator->getMessageBag());
    }
}
