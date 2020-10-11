<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\ChildCategory;
use DataTables;
use Auth;
class ChildCategoryController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function ManageCategory(){
        $category=DB::select('select id,name from categories');
    	if (request()->ajax()){
    $get=DB::select("SELECT categories.name as cat_name,child_categories.name as childname,users.name as username from child_categories 
inner join categories on categories.id=child_categories.cat_id
inner join users on users.id=child_categories.user_id");
   return DataTables::of($get)
      ->addIndexColumn()->make(true);
    }
   return view('pages.products.child_category',compact('category'));
    }
    public function insertCategory(Request $r){
    	$validator = Validator::make($r->all(),[
        'child_category'     => 'required|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        'category' => 'required|max:10|regex:/^([0-9]+)$/',
        ]);

    //for image
    if ($validator->passes()) {
        $category= new ChildCategory;
        $category->cat_id    = $r->category;
        $category->name      = $r->child_category;
        $category->user_id   = Auth::user()->id;
        $category->save();
        return response()->json(['message'=>'success']);
    }
    return response()->json([$validator->getMessageBag()]);
    }
    public function getChildCat($id){
        $validator=Validator::make(['cateogory_id'=>$id],[
            'cateogory_id'=>'required|max:10|min:1'
        ]);

        if ($validator->passes()) {
            return $cat=DB::table('child_categories')->select('id','name')->where('cat_id',$id)->get();
        }
        return response()->json(['message'=>$validator->getMessageBag()]);
    }
    public function allChildCat(){
        $allCat=DB::table('child_categories')->select('id','name')->get();
        return [$allCat];
    }
}
