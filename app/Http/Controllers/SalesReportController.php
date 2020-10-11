<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
class SalesReportController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	return view('pages.reports.sales.sales_report_form');
    }

    public function salesReport(Request $r){
    	$fromDate=strtotime(strval($r->fromDate));
    	$toDate=strtotime(strval($r->toDate));
    	$get=DB::select("
SELECT
		t1.name,
		t1.adress,
		t1.micro_time,
		t1.sub_total,
		t1.invoice_id,
		t1.product_name,
		t1.price,
		t1.qantity,
		t1.total
from
(
select sales.customer_id,
	   customers.name,
	   customers.adress,
	   sales.micro_time-0.000002 as micro_time,
	   null as sub_total,
	   null as invoice_id,
	   null as product_name,
	   null as price,
	   null as qantity,
	   null	as total
		   from sales
		   	inner join customers on customers.id=sales.customer_id
		   	 where sales.dates>=:fromDate and sales.dates<=:toDate group by sales.customer_id
		   union all
 select null,
 		null,
 		null,
 		sales.micro_time,
 		null,
 		null,
 		products.product_name,
    	sales.price,
    	sales.qantity,
    	(sales.price*sales.qantity) as total
    	   from sales
    	     inner join products on products.id=sales.product_id
    	     	 where dates>=:fromDate and dates<=:toDate
    	   union all

 select null,
    	null,
    	null,
    	micro_time+0.000001,
    	null,
    	null,
    	'Discount',
    	null,
    	null,
    	concat('-',cast(((total*discount)/100) as decimal(14,2)))
    	   from invoices where dates>=:fromDate and dates<=:toDate
    	   union all

 select null,
    	null,
    	null,
    	micro_time+0.000002,
    	null,
    	null,
    	'Vat',
    	null,
    	null,
    	cast(((total*vat)/100) as decimal(14,2))
    	   from invoices where dates>=:fromDate and dates<=:toDate
    	   union all

 select null,
    	null,
    	null,
    	micro_time+0.000003,
    	null,
    	null,
    	'Labour Cost',
    	null,
    	null,
    	labour_cost
    	   from invoices where dates>=:fromDate and dates<=:toDate
 union All
 select null,
    	null,
    	null,
    	micro_time+0.000005,
    	total_payable,
    	null,
    	null,
    	null,
    	null,
    	null
    	   from invoices where dates>=:fromDate and dates<=:toDate
  union All
 select null,
    	null,
    	null,
    	micro_time-0.000001,
    	null,
    	invoice_id,
    	null,
    	null,
    	null,
    	null
    	   from sales where dates>=:fromDate and dates<=:toDate group by invoice_id,customer_id

)t1 order by t1.micro_time,t1.name,t1.invoice_id",['fromDate'=>$fromDate,'toDate'=>$toDate]);
    	$pdf=PDF::loadView('pages.reports.sales.pdf',compact('get','fromDate','toDate'))->setPaper('a4','portrait');
        return $pdf->stream('invoice.pdf');
    }
}
