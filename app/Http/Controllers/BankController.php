<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Bank;
use App\Invoice;
use App\Voucer;
use App\Keycheck;
use Auth;
use DB;
use Hash;
class BankController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function bankForm(){
    	return view('pages.banks.bank');
    }
    public function insertBank(Request $r){
    	$validator = Validator::make($r->all(),[
        'name'       => 'required|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        'number'     => 'nullable|max:30|regex:/^([0-9]+)$/',
        'branch'     => 'nullable|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        'balance'    => 'nullable|max:14|regex:/^[0-9]+(\\.[0-9]+)?$/'
        ]);
        if ($r->balance==null) {
            $r->balance=0;
        }
    //for image
    if ($validator->passes()) {
    	$bank= new Bank;
        $bank->name              = $r->name;
        $bank->number            = $r->number;
        $bank->branch            = $r->branch;
        $bank->opening_balance   = $r->balance;
        $bank->users_id   = Auth::user()->id;
        $bank->save();
        return response()->json(['message'=>'Bank Added Success']);
    }
    return response()->json([$validator->getMessageBag()]);
    }
    public function allBanks(){
        if (request()->ajax()) {
        $total_bal=500;
        $get=DB::select("
           select id,name,branch,number,(opening_balance+ifnull((select sum(ifnull(debit,0))-sum(ifnull(credit,0)) from voucers where banks.id=voucers.bank_id),0)) as total from banks
            ");
        return DataTables::of($get)
          ->addIndexColumn()
          ->addColumn('action',function($get){
          $button  ='<div class="btn-group btn-group-toggle" data-toggle="buttons">
                       <button type="button" data-id="'.$get->id.'" class="btn btn-sm btn-primary rounded mr-1 edit" data-toggle="modal" data-target=""><i class="fas fa-eye"></i></button>
                       <button class="btn btn-danger btn-sm rounded delete" data-id="'.$get->id.'"><i class="fas fa-trash-alt"></i></button>
                    </div>';
        return $button;
      })
      ->rawColumns(['action'])->make(true);
        }
       return view('pages.banks.bank');
    }
    public function getAccount(){
        $all=Bank::select('id','name')->get();
        return response()->json($all);
    }
    public function getBanks(Request $r){
        if (!preg_match("/[^a-zA-Z0-9. ]/", $r->searchTerm)) {
            $data=DB::select("SELECT id,name from banks where name like '%".$r->searchTerm."%' limit 10");
            foreach ($data as $value){
                $set_data[]=['id'=>$value->id,'text'=>$value->name];
            }
            return $set_data;
        }
    }
    public function getBanksById($id){
        $data=Bank::select('name','number','branch')->where('id',$id)->first();
        return response()->json($data);
    }
    public function Update(Request $r,$id){
        $validator = Validator::make($r->all(),[
        'name'       => 'required|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        'number'     => 'nullable|max:30|regex:/^([0-9]+)$/',
        'branch'     => 'nullable|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        ]);
    //for image
    if ($validator->passes()) {
        $bank= Bank::find($id);
        $bank->name              = $r->name;
        $bank->number            = $r->number;
        $bank->branch            = $r->branch;
        $bank->users_id   = Auth::user()->id;
        $bank->save();
        return response()->json(['message'=>'Bank Updated Success']);
    }
    return response()->json([$validator->getMessageBag()]);
    }
    public function getBalanceById($id=1){
        $opening_balance=Bank::where('id',$id)->select('opening_balance')->first();
        $balance=$opening_balance->opening_balance;
        $debit=Voucer::where('bank_id',$id)->sum('debit');
        $credit=Voucer::where('bank_id',$id)->sum('credit');
        $total=number_format(($balance+$debit)-$credit,2,'.','');
        return response()->json(['total'=>$total]);
    }
    public function test(){
      $keycheck=Keycheck::select('todate')->first();
      // return $keycheck->todate;
      // return Hash::make(12345678);
      // return strtotime('05-12-2020');
       return redirect()->route('md5');
    }
}
