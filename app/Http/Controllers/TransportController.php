<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Transport;
use Auth;
class TransportController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function Form(){
        return view('pages.transport.transport');
    }

    public function Create(Request $r){
        $validation=Validator::make($r->all(),[
            'name'         =>'required|max:100',
            'phone'        =>'nullable|max:100',
            'driver_phone' =>'nullable|max:100',
            'adress'       =>'nullable|max:100',
            'type'         =>'nullable|max:100',
            'status'       =>'nullable|max:100',
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
}
