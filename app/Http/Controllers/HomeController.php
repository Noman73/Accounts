<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voucer;
use App\Invoice;
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
        $deposit=Voucer::where(['payment_type'=>'Deposit','dates'=>strtotime(date('d-m-Y'))])->sum('ammount');
        $expence=Voucer::where(['payment_type'=>'Expence','dates'=>strtotime(date('d-m-Y'))])->sum('ammount');
        $total_sales_ammount=Invoice::where(['dates'=>strtotime(date('d-m-Y'))])->sum('total_payable');

        return ['deposit'=>$deposit,'expence'=>$expence,'total_sales'=>$total_sales_ammount];
    }
}
