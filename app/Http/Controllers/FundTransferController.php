<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Voucer;
use Auth;
use DB;
use DataTables;
class FundTransferController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function Form(){
        if (request()->ajax()){
            $get=DB::select("
               select voucers.id,banks.name as bank_name,voucers.debit,voucers.credit from voucers 
               inner join banks on banks.id=voucers.bank_id where voucers.category='fund_transfer';
                ");
            return DataTables::of($get)
              ->addIndexColumn()->make(true);
            }
           return view('pages.banks.fund_transfer');
    }
    public function Transfer(Request $r){
        $validation=Validator::make($r->all(),[
            'from'     => 'required|max:20|regex:/^([0-9]+)$/',
            'to'       => 'required|max:20|regex:/^([0-9]+)$/',
            'ammount'  => 'required|max:20|regex:/^([0-9]+)$/',
            'details'  => 'nullable|max:500',
        ]);
      
        if ($validation->passes()) {
            $voucer=new Voucer;
            $voucer->bank_id=$r->from;
            $voucer->category='fund_transfer';
            $voucer->data_id=$r->to;
            $voucer->credit=$r->ammount;
            $voucer->user_id=Auth::user()->id;
            $voucer->save();
            if ($voucer==true) {
                $voucer=new Voucer;
                $voucer->bank_id=$r->to;
                $voucer->category='fund_transfer';
                $voucer->data_id=$r->from;
                $voucer->debit=$r->ammount;
                $voucer->transaction=$r->transaction;
                $voucer->user_id=Auth::user()->id;
                $voucer->save();
                if ($voucer==true) {
                    return response()->json(['message'=>'Banks Transfer Success']);
                }
            }
        }
        return response()->json([$validation->getMessageBag()]);
    }
}
