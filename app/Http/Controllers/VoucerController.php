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
      $names=DB::table('names')->select('id','name')->get();
    	$banks=DB::table('banks')->select('id','name')->get();
    	if (request()->ajax()) {
        $get=DB::select("select voucers.dates,voucers.name,banks.name as bank_name,voucers.ammount,voucers.payment_type from voucers inner join banks on banks.id=voucers.bank_id");
          return DataTables::of($get)
          ->addIndexColumn()
          ->addColumn('dat',function($get){
          $dates  = date('d-m-Y',$get->dates);
          return $dates;
      })
      ->rawColumns(['dat'])->make(true);
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
            'data'=>'required|max:10',
            'payment_type'=>'required|max:100',
            'bank'=>'required|max:10',
            'ammount'=>'required|max:20|',
        ]);
        if ($validator->passes()) {
            $microtime=explode(' ',microtime());
            $voucer=new Voucer;
            $voucer->dates=strtotime(strval($r->date));
            $voucer->name=strtolower($r->category);
            $voucer->name_data_id=$r->data;
            $voucer->payment_type=$r->payment_type;
            $voucer->bank_id=$r->bank;
            $voucer->ammount=$r->ammount;
            $voucer->micro_time=$microtime[1].'.'.(int)round($microtime[0]*1000);
            $voucer->user_id=Auth::user()->id;
            $voucer->save();
            return ['message'=>'success'];
        }

        return response()->json([$validator->getMessageBag()]);
    }
}
