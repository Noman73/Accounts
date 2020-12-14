<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use Validator;
use Auth;
use App\Namerelation;
class NameRelationController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }

    public function ManageNameRelation(){
    	$names=DB::table('names')->select('id','name')->where('stutus',0)->get();
    	if (request()->ajax()) {
        $get=DB::select("select namerelations.id,namerelations.rel_name as rel_name,users.id as user_id,users.name as username,names.name as name from namerelations left join users on users.id=namerelations.user_id 
            inner join names on names.id=namerelations.name_id");
          return DataTables::of($get)
          ->addIndexColumn()
          ->addColumn('username',function($get){
          	$username=$get->username.'('.$get->user_id.')';
          	return $username;
          })
          ->rawColumns(['username'])->make(true);
        }
        return view('pages.names.namerelation',compact('names'));
    }
    public function insertNameRelation(Request $r){
    	$validator=Validator::make($r->all(),[
    		'rel_name'=>'required|max:100',
    		'name'=>'required|max:10',
    	]);

    	if ($validator->passes()) {
    		$nameRelation=new Namerelation;
    		$nameRelation->rel_name=ucwords($r->rel_name);
    		$nameRelation->name_id=$r->name;
    		$nameRelation->user_id=Auth::user()->id;
    		$nameRelation->save();
    		return ['message'=>'success'];
    	}
    	return [$validator->getMessageBag()];
    }

    public function getRelationById($id=null){
      if (!preg_match("/[^a-zA-Z0-9. ]/", $id)) {
      $data=DB::table('namerelations')->select('id','rel_name')->where('name_id',$id)->get();
            foreach ($data as $value){
                  $set_data[]=['id'=>$value->id,'text'=>$value->rel_name];
             }
             if (isset($set_data)) {
               return $set_data;
             }
         }
      }
}
