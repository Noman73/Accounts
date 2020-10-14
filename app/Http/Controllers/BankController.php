<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Bank;
use Auth;
use DB;
use DNS1D;
use DNS2D;
use TPDF;
class BankController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function bankForm(){
    	return view('pages.banks.bank');
    }
    public function insertBank(Request $r){
    	$validator = Validator::make($r->all(),[
        'name'       => 'required|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        'number'     => 'nullable|max:30|regex:/^([0-9]+)$/',
        'branch'     => 'nullable|max:50|regex:/^([a-zA-Z0-9., ]+)$/',
        'balance'    => 'nullable|max:14|regex:/^[0-9]+(\\.[0-9]+)?$/'
        ]);
        if ($r->balance==null) {
            $r->balance=0;
        }
    //for image
    if ($validator->passes()) {
    	$bank= new Bank;
        $bank->name              = $r->name;
        $bank->number            = $r->number;
        $bank->branch            = $r->branch;
        $bank->opening_balance   = $r->balance;
        $bank->users_id   = Auth::user()->id;
        $bank->save();
        return response()->json(['message'=>'success']);
    }
    return response()->json([$validator->getMessageBag()]);
    }
    public function allBanks(){
        if (request()->ajax()) {
        $total_bal=500;
        $get=DB::select("
            select id,name,branch,number,(opening_balance+ifnull((select sum(ammount) from voucers where payment_type='Deposit' and banks.id=voucers.bank_id),0))-ifnull((select sum(ammount) from voucers where payment_type='Expence' and banks.id=voucers.bank_id),0) as total from banks

            ");
        return DataTables::of($get)
          ->addIndexColumn()->make(true);
        }
       return view('pages.banks.bank');
    }
    public function test(){
        // echo DNS1D::getBarcodeSVG('4445645656', 'C39')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C39+')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C39E')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C39E+')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C93')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'S25')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'S25+')."<br>";
        // echo DNS1D::getBarcodeSVG('0000000001', 'I25')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'I25+')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C128')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C128A')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C128B')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'C128C')."<br>";
        // echo DNS1D::getBarcodeSVG('44455656', 'EAN2')."<br>";
        // echo DNS1D::getBarcodeSVG('4445656', 'EAN5')."<br>";
        // echo DNS1D::getBarcodeSVG('4445', 'EAN8')."<br> EAN 13";
        // echo DNS1D::getBarcodeSVG('1234567891231', 'EAN13')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'UPCA')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'UPCE')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'MSI')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'MSI+')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'POSTNET')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'PLANET')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'RMS4CC')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'KIX')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'IMB')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'CODABAR')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'CODE11')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA')."<br>";
        // echo DNS1D::getBarcodeSVG('4445645656', 'PHARMA2T')."<br>";
        // echo DNS2D::getBarcodePNGPath('4445645656', 'PDF417')."<>";
return $data=DB::select("
    SELECT name,phone,(ifnull((select sum(debit-credit) from voucers where category='customer' and data_id=customers.id),0)+ifnull((select sum(total_payable) from invoicebacks where customer_id=customers.id),0))-ifnull((select sum(total_payable) from invoices where customer_id=customers.id),0) as balance from customers
              ");
// return json_encode($data);
    }
}
