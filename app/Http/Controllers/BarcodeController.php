<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DOMPDF;
use DNS2D;
use DNS1D;
use Auth;
class BarcodeController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	return view('pages.barcode.barcode');
    }
    public function Generate(Request $r){
    	$validate=$r->validate([
            'product'=>'required|min:1|max:100|',
            'qantity'=>'required|min:1|max:3|regex:/^([0-9]+)$/',
    		'price'=>'required|min:1|max:12|regex:/^([0-9]+)$/',
    	]);
    	if ($r->product==='0') {
    		$barcode='not found!! give valid code';
    	}else{
            $data=explode('|',$r->product);
            // return json_encode($data);
            for ($i=0; $i <$r->qantity ; $i++){ 
                $barcode[]= DNS1D::getBarcodeSVG(str_pad($data[0],10, "0", STR_PAD_LEFT),'I25');
                $text[]=$data[1];
                $price[]=$r->price;
            }
            
    	}
    	// return view('pages.reports.barcode.pdf',compact('barcode','text','price'));
        $pdf = DOMPDF::loadView('pages.reports.barcode.pdf',compact('barcode','text','price'));
        return $pdf->stream('barcode_'.($data[1]).'.pdf');
    }
}




