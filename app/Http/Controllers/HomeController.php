<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voucer;
use App\Invoice;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dashboard(){
        $deposit=Voucer::where('dates',strtotime(date('d-m-Y')))->sum('debit');
        $expence=Voucer::where('dates',strtotime(date('d-m-Y')))->sum('credit');
        $total_sales_ammount=Invoice::where('dates',strtotime(date('d-m-Y')))->sum('total_payable');
        return ['deposit'=>$deposit,'expence'=>$expence,'total_sales'=>$total_sales_ammount];
    }

    public function getVoucerFormData(){
        $banks=DB::table('banks')->select('id','name')->get();
        $category=DB::table('names')->select('id','name')->get();
        return ['category'=>$category,'banks'=>$banks];
    }
}
