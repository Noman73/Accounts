<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use DataTables;
use App\Product;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function ManageProduct(){
    	$category=DB::table('categories')->select('id','name')->get();
    	$ptype=DB::table('ptypes')->select('id','name')->get();
    	if (request()->ajax()){
            $get=DB::select("select products.id as p_id,products.product_name as product_name,child_categories.name as name,products.photo as photo from products inner join child_categories on child_categories.id=products.child_category");
        return DataTables::of($get)
              ->addIndexColumn()
              ->addColumn('photo',function($get){
	          $button  ='<img id="p_photo" src="'.asset('storage/product/'.$get->photo).'">';
	          return $button;
      		  })
              ->addColumn('action',function($get){
          $button  ='<div class="btn-group btn-group-toggle" data-toggle="buttons">
                       <button type="button" data-id="'.$get->p_id.'" class="btn btn-sm btn-primary rounded mr-1 edit" data-toggle="modal" data-target=""><i class="fas fa-eye"></i></button>
                       <button class="btn btn-danger btn-sm rounded delete" data-id="'.$get->p_id.'"><i class="fas fa-trash-alt"></i></button>
                    </div>';
        	  return $button; 
      })
      ->rawColumns(['photo','action'])->make(true);
        }
        return view('pages.products.product',compact('category','ptype'));
    }
    public function insertProduct(Request $r){
    	$validator=Validator::make($r->all(),[
    		'product_name'    =>   "required|max:100|regex:/^[a-zA-Z0-9]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
    		'category'        =>   'required|max:10|regex:/^([0-9]+)$/',
    		'child_category'  =>   'required|max:10|regex:/^([0-9]+)$/',
    		'product_code'    =>   'nullable|max:30|regex:/^([0-9]+)$/',
    		'model_no' 		    =>   'nullable|max:10|regex:/^([a-zA-Z0-9]+)$/',
    		'warranty'		    =>   'nullable|max:10|regex:/^([a-zA-Z0-9 ]+)$/',
    		'product_type'	  =>   'nullable|max:10|regex:/^([a-zA-Z0-9]+)$/',
    		'packaging'		    =>   'nullable|max:10|regex:/^([a-zA-Z]+)$/',
    		'price'     	    =>   'required|max:10|regex:/^([0-9.]+)$/',
    		'photo'     	    =>   'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
       	]);

       	if ($validator->passes()) {
       		$product= new Product;
       		$product->product_name		=$r->product_name;
       		$product->category   		=$r->category;
       		$product->child_category	=$r->child_category;
       		$product->product_code		=$r->product_code;
       		$product->model_no			=$r->model_no;
       		$product->warranty			=$r->warranty;
       		$product->product_type		=$r->product_type;
       		$product->packaging			=$r->packaging;
       		$product->price 			=$r->price;
       		$product->user_id=Auth::user()->id;
       		if ($r->hasFile('photo')){
       			$ext=$r->photo->getClientOriginalExtension();
       			$name=Auth::user()->id.str_replace(' ','_',$r->product_name).time().'.'.$ext;
        		$r->photo->storeAs('public/product',$name);
        		$product->photo=$name;
			}else{
				$product->photo='noimage.png';
			}
        	$product->save();
        return response()->json(['message'=>'success']);
       	}
    	return response()->json([$validator->getMessageBag()]);
    }
    public function getProduct($id){
    		$product=DB::table('products')->select('id','product_name')->where('child_category',$id)->get();
    		return $product;
    }
    public function ProductPrice($id=null){
      $price=DB::table('products')->select('price')->where('id',$id)->get();
      return $price[0]->price;
    }
    public function productBarcode(Request $r){
      $data=DB::select("SELECT product_code,product_name from products where product_name like '%".$r->searchTerm."%' order by product_name asc limit 100");
      foreach($data as $value){
        $set_data[]=['id'=> ($value->product_code==null) ? 0 : $value->product_code.'|'.$value->product_name,'text'=>$value->product_name];
      }
      return $set_data;
    }
    public function getProductById($id){
        $res = Product::where('id', $id)->get();
        $res=$res->makeHidden(['created_at','updated_at','user_id']);
        return response()->json($res);
    }
    public function Delete($id=null){
       $photo=DB::table('products')->select('photo')->where('id',$id)->first();
       $delete=Product::where('id',$id)->delete();
       if ($delete) {
        if (strval($photo->photo)!=='noimage.png') {
        $x=Storage::delete('public/product/'.$photo->photo);
        }
         return response()->json(['message'=>'success']);
       }
    }
    public function Update(Request $r,$id){
      $validator=Validator::make($r->all(),[
        'product_name'    =>   "required|max:100|regex:/^[a-zA-Z0-9]+(([',. -][a-zA-Z ])?[a-zA-Z0-9 ]*)*$/",
        'category'        =>   'required|max:10|regex:/^([0-9]+)$/',
        'child_category'  =>   'required|max:10|regex:/^([0-9]+)$/',
        'product_code'    =>   'nullable|max:30|regex:/^([0-9]+)$/',
        'model_no'        =>   'nullable|max:10|regex:/^([a-zA-Z0-9.-]+)$/',
        'warranty'        =>   'nullable|max:10|regex:/^([a-zA-Z0-9 ]+)$/',
        'product_type'    =>   'nullable|max:10|regex:/^([a-zA-Z0-9]+)$/',
        'packaging'       =>   'nullable|max:10|regex:/^([a-zA-Z]+)$/',
        'price'           =>   'required|max:10|regex:/^([0-9.]+)$/',
        'photo'           =>   'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->passes()) {
          $product=Product::find($id);
          $product->product_name    =$r->product_name;
          $product->category      =$r->category;
          $product->child_category  =$r->child_category;
          $product->product_code    =$r->product_code;
          $product->model_no      =$r->model_no;
          $product->warranty      =$r->warranty;
          $product->product_type    =$r->product_type;
          $product->packaging     =$r->packaging;
          $product->price       =$r->price;
          $product->user_id=Auth::user()->id;
          if ($r->hasFile('photo')){
            $photo=DB::table('products')->select('photo')->where('id',$id)->first();
            $ext=$r->photo->getClientOriginalExtension();
            $name=Auth::user()->id.str_replace(' ','_',$r->product_name).time().'.'.$ext;
            $r->photo->storeAs('public/product',$name);
            $product->photo=$name;
          }else{
            $product->photo='noimage.png';
          }
          $save=$product->save();
            if ($save) {
              if (isset($photo)) {
              $x=Storage::delete('public/product/'.$photo->photo);
              }
              return response()->json(['message'=>'success']);
            }
        }
      return response()->json([$validator->getMessageBag()]);
    }

}
