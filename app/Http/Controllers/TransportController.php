<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Transport;
use Auth;
use DB;
use DataTables;
class TransportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function Form(){
        if (request()->ajax()) {
            $total_bal=500;
            $get=DB::select("select id,name,phone,adress,driver_phone,type,status from transports");
        return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('status',function($get){
                  if ($get->status==1) {
                      $status='Active';
                  }elseif($get->status==0){
                    $status='Deactive';
                  }
        return $status;
      })
        ->addColumn('action',function($get){
          $button  ='<div class="btn-group btn-group-toggle" data-toggle="buttons">
                       <button type="button" data-id="'.$get->id.'" class="btn btn-sm btn-primary rounded mr-1 edit" data-toggle="modal" data-target=""><i class="fas fa-eye"></i></button>
                       <button class="btn btn-danger btn-sm rounded delete" data-id="'.$get->id.'"><i class="fas fa-trash-alt"></i></button>
                    </div>';
        return $button;
      })
      ->rawColumns(['action'])->make(true);
        }
        return view('pages.transport.transport');
    }

    public function Create(Request $r){
        $validation=Validator::make($r->all(),[
            'name'         =>"required|max:100|regex:/^([a-zA-Z0-9., ]+)$/",
            'phone'        =>'nullable|max:100|regex:/^([0-9]+)$/',
            'driver_phone' =>'nullable|max:100|regex:/^([0-9]+)$/',
            'adress'       =>'nullable|max:100|regex:/^([a-zA-Z0-9., ]+)$/',
            'type'         =>'required|max:100|regex:/^([a-zA-Z0-9., ]+)$/',
            'status'       =>'nullable|max:100|regex:/^([a-zA-Z0-9]+)$/',
        ]);
        if ($validation->passes()) {
            $transport=new Transport;
            $transport->name=$r->name;
            $transport->phone=$r->phone;
            $transport->driver_phone=$r->driver_phone;
            $transport->adress=$r->adress;
            $transport->type=$r->type;
            $transport->status=$r->status;
            $transport->user_id=Auth::user()->id;
            $transport->save();
            if ($transport==true) {
                return  response()->json(['message'=>'transport added success']);
            }
        }
        return response()->json([$validation->getMessageBag()]);
    }
    public function Update(Request $r,$id){
        $validation=Validator::make($r->all(),[
            'name'         =>'required|max:100|regex:/^([a-zA-Z0-9., ]+)$/',
            'phone'        =>'nullable|max:100|regex:/^([0-9]+)$/|unique:transports,phone,'.$r->phone,
            'driver_phone' =>'nullable|max:100|regex:/^([0-9]+)$/|unique:transports,driver_phone,'.$r->driver_phone,
            'adress'       =>'nullable|max:100|regex:/^([a-zA-Z0-9., ]+)$/',
            'type'         =>'required|max:100|regex:/^([a-zA-Z0-9., ]+)$/',
            'status'       =>'nullable|max:100|regex:/^([0-9]+)$/',
        ]);
        if ($validation->passes()) {
            $transport=Transport::find($id);
            $transport->name=$r->name;
            $transport->phone=$r->phone;
            $transport->driver_phone=$r->driver_phone;
            $transport->adress=$r->adress;
            $transport->type=$r->type;
            $transport->status=$r->status;
            $transport->user_id=Auth::user()->id;
            $transport->save();
            if ($transport==true) {
                return  response()->json(['message'=>'transport Updated success']);
            }
        }
        return response()->json([$validation->getMessageBag()]);
    }
    public function Data($id=null){
        $data=Transport::find($id);
        if ($data) {
            return response()->json($data);
        }
    }
    public function getTransport(Request $r){
        if (!preg_match("/[^a-zA-Z0-9. ]/", $r->searchTerm)) {
            $data=DB::select("SELECT id,name,phone from transports where name like '%".$r->searchTerm."%' or  phone like '%".$r->searchTerm."%' limit 10");
            foreach ($data as $value) {
                $set_data[]=['id'=>$value->id,'text'=>$value->name.'('.$value->phone.')'];
            }
            return $set_data;
        }
    }
}
