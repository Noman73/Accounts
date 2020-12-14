<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Store;
use Auth;
use DB;
use DataTables;
class StoreController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function Form(){
        if (request()->ajax()) {
            $get=DB::select("
               select id,name,adress,capacity,type,status from stores
                ");
            return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('status',function($get){
                  if ($get->status==1) {
                      $status='Active';
                  }else{
                    $status='Deactive';
                  }
              return $status;
            })->rawColumns(['status'])->make(true);
            }
           return view('pages.store.store');
    }

    public function Create(Request $r){
        $validation=Validator::make($r->all(),[
            'name'=>'required|max:100',
            'adress'=>'nullable|max:100',
            'capacity'=>'nullable|max:100',
            'type'=>'nullable|max:100',
            'status'=>'required|max:100',

        ]);
        if ($validation->passes()) {
            $store=new Store;
            $store->name=$r->name;
            $store->adress=$r->adress;
            $store->capacity=$r->capacity;
            $store->type=$r->type;
            $store->status=$r->status;
            $store->user_id=Auth::user()->id;
            $store->save();
            if ($store) {
                return response()->json(['message'=>'Store Inserted Success']);
            }
        }
    }
    public function getStore(Request $r){
        if (!preg_match("/[^a-zA-Z0-9. ]/", $r->searchTerm)) {
            $data=DB::select("SELECT id,name from stores where name like '%".$r->searchTerm."%' limit 10");
            foreach ($data as $value) {
                $set_data[]=['id'=>$value->id,'text'=>$value->name];
            }
            return $set_data;
        }
    }
}
