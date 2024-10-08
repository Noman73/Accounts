<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Customer;
use DB;
use DataTables;
use App\Notification;
use Illuminate\Support\Facades\Storage; 
class CustomerController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function CustomerForm(){
        return view('pages.customer.customer');
    }
    public function CreateNew(Request $r){
        $array=$r->all();
		if ($array['opening_balance']===null) {
			$array['opening_balance']=0;
		}
		if ($array['maximum_due']===null) {
			$array['maximum_due']=500;
		}
		if ($array['stutus']===null) {
			$array['stutus']=1;
		}
    	$validator = Validator::make($array,[
        'company_name'      => "nullable|max:50|regex:/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
        'name'              => "required|max:50|regex:/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
        'opening_balance'   => 'nullable|max:19|regex:/^([0-9.]+)$/',
        'balance_type'      => 'required|max:1|regex:/^([0-1]+)$/',
        'maximum_due'     	=> 'nullable|max:16|regex:/^([0-9.]+)$/',
        'phone1'            => 'required|regex:/^([0-9]+)$/|max:20|unique:customers,phone1,'.$array['phone1'],
        'phone2'            => 'nullable|max:20|regex:/^([0-9]+)$/|unique:customers,phone2,'.$array['phone2'],
        'email'             => 'nullable|max:100|email|unique:customers,email,'.$array['email'],
        'birth_date'    	=> 'nullable|max:100|date_format:d-m-Y',
        'mariage_date'      => 'nullable|max:100|date_format:d-m-Y',
        'adress'     		=> "nullable|max:100|regex:/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
        'city'     			=> 'nullable|max:50|regex:/^([a-zA-Z0-9]+)$/',
        'postal_code'     	=> 'nullable|max:20|regex:/^([a-zA-Z0-9]+)$/',
        'stutus'     		=> 'nullable|max:1|regex:/^([0-1]+)$/',
        'group_types'     	=> 'nullable|max:50|regex:/^([a-zA-Z0-9]+)$/',
        'photo'     		=> 'nullable|image|mimes:jpeg,png,jpg,svg|max:2024',
        ]);

    //for image
    if ($validator->passes()) {
    	$customer= new Customer;
        $customer->company_name     =$array['company_name'];
        $customer->name      		=$array['name'];
        if(intval($array['balance_type'])===0){
            $customer->opening_balance  =-abs($array['opening_balance']);
        }elseif(intval($array['balance_type'])===1){
            $customer->opening_balance  =abs($array['opening_balance']);
        }
		$customer->maximum_due      =$array['maximum_due'];
		$customer->phone1       	=$array['phone1'];
		$customer->phone2         	=$array['phone2'];
		$customer->email        	=$array['email'];
		$customer->birth_date       =$array['birth_date'];
		$customer->marriage_date    =$array['mariage_date'];
		$customer->adress        	=$array['adress'];
		$customer->city        		=$array['city'];
		$customer->postal_code      =$array['postal_code'];
		$customer->stutus        	=$array['stutus'];
		$customer->group_types      =$array['group_types'];
        $customer->users_id   	    = Auth::user()->id;
        if ($r->hasFile('photo')){
            $ext=$r->photo->getClientOriginalExtension();
            $name=Auth::user()->id.'_'.str_replace(" ","_",$array['name']).'_'.$array['phone1'].'_'.time().'.'.$ext;
            $r->photo->storeAs('public/customer',$name);
            $customer->photo=$name;
        }
        $customer->save();
        return response()->json(['message'=>'Customer Added Success']);
    }
    return response()->json([$validator->getMessageBag()]);
    }

    public function searchCustomer(Request $r){
        if (!preg_match("/[^a-zA-Z0-9. ]/", $r->searchTerm)) {
            $data=DB::select("SELECT id,name,phone1 from customers where name like '%".$r->searchTerm."%' or  phone1 like '%".$r->searchTerm."%' limit 10");
            foreach ($data as $value) {
                $set_data[]=['id'=>$value->id,'text'=>$value->name.'('.$value->phone1.')'];
            }
            return $set_data;
        }
    }

    public function getBalance($id='wrong'){
        if (!preg_match("/[^0-9]/",$id)){
           $get=DB::select("
            SELECT
    cast(((t.Deposit+t.total_payablebacks)-(t.Expence+t.total_payable))+t.op_blnce as decimal(16,2)) as total
from(
    select 
    ifnull(sum(ifnull(debit,0)),0) as Deposit,
    ifnull(sum(ifnull(credit,0)),0) as Expence,
    ifnull((select SUM(total_payable) from invoices where customer_id=:id and (action_id=0 or action_id=3)),0) as total_payable,
    ifnull((select SUM(total_payable) from invoices where customer_id=:id and action_id=2),0) as total_payablebacks,
    (select opening_balance from customers where id=:id) as op_blnce
    from voucers where category='customer' and data_id=:id
    ) t",['id'=>$id]);
           return $get;
        }else{
            return ['data'=>'something wrong here'];
        }
    }
    public function getAll(){
        if (request()->ajax()) {
            $get=DB::select("select id,name,phone1,adress from customers");
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
        return view('pages.customer.all_customer');
    }
    public function Delete($id=null){
        return 'sorry! you dont have to permission for delete';
        $photo=DB::table('customers')->select('photo')->where('id',$id)->first();
        $delete=Customer::where('id',$id)->delete();
        if ($delete) {
            $notification=new Notification;
            $notification->details='customer <strong>'.$customer->name.'('.$id.')</strong>'.' Deleted by <strong>'.Auth::user()->name.'('.Auth::user()->id.')</strong>';
            $notification->action='delete';
            $notification->save();
            if (isset($photo)){
                Storage::delete("public/customer/".$photo->photo);
            }
        return response()->json(['message'=>'success']);
        }
    }
    public function getCustomer($id=null){
        $res = Customer::where('id', $id)->get();
        $res=$res->makeHidden(['created_at','updated_at']);
        return response()->json($res);
    }
    public function Update(Request $r,$id){
        $array=$r->all();
        if ($array['opening_balance']===null) {
            $array['opening_balance']=0;
        }
        if ($array['maximum_due']===null) {
            $array['maximum_due']=500;
        }
        if ($array['stutus']===null) {
            $array['stutus']=1;
        }
        $validator = Validator::make($array,[
        'company_name'      => "nullable|max:50|regex:/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
        'name'              => "required|max:50|regex:/^[a-zA-Z]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
        'opening_balance'   => 'nullable|max:15|regex:/^([0-9.-]+)$/',
        'maximum_due'       => 'nullable|max:15|regex:/^([0-9.]+)$/',
        'phone1'            => 'required|regex:/^([0-9]+)$/|max:20|unique:customers,phone1,'.$id, 
        'phone2'            => 'nullable|max:20|regex:/^([0-9]+)$/|unique:customers,phone2,'.$id,
        'email'             => 'nullable|max:100|email|unique:customers,email,'.$id,
        'birth_date'        => 'nullable|max:100|date_format:d-m-Y',
        'mariage_date'      => 'nullable|max:100|date_format:d-m-Y',
        'adress'            => "nullable|max:100|regex:/^([a-zA-Z0-9, ]+)$/",
        'city'              => 'nullable|max:50|regex:/^([a-zA-Z0-9]+)$/',
        'postal_code'       => 'nullable|max:20|regex:/^([a-zA-Z0-9]+)$/',
        'stutus'            => 'nullable|max:1|regex:/^([0-1]+)$/',
        'group_types'        => 'nullable|max:50|regex:/^([a-zA-Z0-9]+)$/',
        'photo'             => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2024',
        ]);

    //for image
    if ($validator->passes()) {
        $customer=Customer::find($id);
        $customer->company_name     =$array['company_name'];
        $customer->name             =$array['name'];
        if(intval($array['balance_type'])===0){
            $customer->opening_balance  =-abs($array['opening_balance']);
        }elseif(intval($array['balance_type'])===1){
            $customer->opening_balance  =abs($array['opening_balance']);
        }
        $customer->maximum_due      =$array['maximum_due'];
        $customer->phone1           =$array['phone1'];
        $customer->phone2           =$array['phone2'];
        $customer->email            =$array['email'];
        $customer->birth_date       =$array['birth_date'];
        $customer->marriage_date    =$array['mariage_date'];
        $customer->adress           =$array['adress'];
        $customer->city             =$array['city'];
        $customer->postal_code      =$array['postal_code'];
        $customer->stutus           =$array['stutus'];
        $customer->group_types      =$array['group_types'];
        $customer->users_id         = Auth::user()->id;
        if ($r->hasFile('photo')){
            $photo=DB::table('customers')->select('photo')->where('id',$id)->first();
            $ext=$r->photo->getClientOriginalExtension();
            $name=$name=Auth::user()->id.'_'.str_replace(" ","_",$array['name']).'_'.$array['phone1'].'_'.time().'.'.$ext;
            $r->photo->storeAs('public/customer',$name);
            $customer->photo=$name;
        }
        $save=$customer->save();
        if ($save) {
            $notification=new Notification;
            $notification->details='customer <strong>'.$customer->name.'('.$id.')</strong>'.' updated by <strong>'.Auth::user()->name.'('.Auth::user()->id.')</strong>';
            $notification->action='update';
            $notification->save();
            if (isset($photo)) {
            Storage::delete('public/customer/'.$photo->photo);
            }
            return response()->json(['message'=>'Customer Updated Success']);
        }
      }
    return response()->json([$validator->getMessageBag()]);
    }
}
