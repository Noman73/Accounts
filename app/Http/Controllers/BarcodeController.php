<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PDF;
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
    		'product'=>'required|min:1|max:20|',
    	]);
    	if ($r->product==='0') {
    		$barcode='not found!! give valid code';
    	}else{
    		$barcode= DNS1D::getBarcodeSVG(str_pad($r->product,10, "0", STR_PAD_LEFT), 'I25')."<br>";
    	}
    	return redirect()->back()->with('data',$barcode);
    }
}




