<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;
use PDF;
class TestController extends Controller
{
    public function test(Request $r){
    	$fromDate=strtotime(strval($r->fromDate));
        $toDate=strtotime(strval($r->toDate));
        return $get=DB::select("
                    SELECT dates,
                           name,
                           name_data_id,
                           payment_type,
                           ammount
                     from voucers
                    ");

        $pdf=PDF::loadView('pages.testfile.test_pdf',compact('get','fromDate','toDate'))->setPaper('a4','portrait');
        return $pdf->stream('invoice.pdf');
    }
    public function page(){
    	return view('pages.testfile.test');
    }
    public function array(){
        return [
            ['id'=>1,'text'=>'text1'],
            ['id'=>2,'text'=>'text2'],
            ['id'=>2,'text'=>'text3'],
        ];
    }

    public function select2(Request $r){
      $data=DB::select("SELECT id,product_name from products where product_name like '%".$r->searchTerm."%' order by product_name asc limit 100");
      foreach($data as $value){
        $set_data[]=['id'=> $value->id,'text'=>$value->product_name];
      }
      return $set_data;
    }
}
