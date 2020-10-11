<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Category;
use DataTables;
use Auth;
class CategoryController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function ManageCategory(){
    	if (request()->ajax()){
    $get=DB::select("select categories.name,users.name as username from categories inner join users on categories.user_id=users.id");
   return DataTables::of($get)
      ->addIndexColumn()->make(true);
    }
   return view('pages.products.category');
    }
    public function insertCategory(Request $r){
    	$validator = Validator::make($r->all(),[
        'name'       => 'required|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        ]);

    //for image
        if ($validator->passes()) {
        	$category= new Category;
            $category->name       = $r->name;
            $category->user_id   = Auth::user()->id;
            $category->save();
            return response()->json(['message'=>'success']);
        }
        return response()->json([$validator->getMessageBag()]);
    }
    public function getCat(){
        $category=DB::table('categories')->select('id','name')->get();
        return [$category];
    }

}
