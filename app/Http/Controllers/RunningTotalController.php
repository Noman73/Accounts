<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class RunningTotalController extends Controller
{
    public function __construct(){
    	$this->middleware('auth');
    }
    public function Form(){
    	$categories=DB::table('names')->select('name')->where('stutus',1)->get();
    	return view('pages.reports.running_total',compact('categories'));
    }
    public function CreateRunningTotal(Request $r){

 		$fromDate=strtotime(strval($r->fromDate));
 		$toDate=strtotime(strval($r->toDate));
        	if($r->category){
        switch ($r->category){
          case 'customer':
       $previous=DB::select("
           SELECT
    t.Deposit,t.Expence,t.salePrice,t.invoice,cast(((t.Deposit+t.rtrnPrice)-(t.Expence+t.rtrnInvoice))-(t.salePrice+t.invoice) as decimal(16,2)) as total
from(
    select 
    sum(IF(payment_type='Deposit',ammount,0)) as Deposit,
    sum(IF(payment_type='Expence',ammount,0)) as Expence,        
    ifnull((select SUM(qantity*price) from sales where customer_id=:id and dates<:fromDate),0) as salePrice,
    (select (SUM((((total*ifnull(vat,0))/100)))+SUM(ifnull(labour_cost,0)))-sum(total*ifnull(discount,0)/100) from invoices where customer_id=:id and dates<:fromDate) as invoice,
    ifnull((select SUM(qantity*price) from salesbacks where customer_id=:id and dates<:fromDate),0) as rtrnPrice,
    ifnull((select SUM(total*ifnull(fine,0))/100 from invoicebacks where customer_id=:id and dates<:fromDate),0) as rtrnInvoice
    from voucers where name='customer' and name_data_id=:id and dates<:fromDate
    ) t",['id'=>$r->id,'fromDate'=>$fromDate]);
     $current_blnce=DB::select("
            SELECT
    t.Deposit,t.Expence,t.salePrice,t.invoice,cast(((t.Deposit+t.rtrnPrice)-(t.Expence+t.rtrnInvoice))-(t.salePrice+t.invoice) as decimal(16,2)) as total
from(
    select 
    sum(IF(payment_type='Deposit',ammount,0)) as Deposit,
    sum(IF(payment_type='Expence',ammount,0)) as Expence,        
    ifnull((select SUM(qantity*price) from sales where customer_id=:id),0) as salePrice,
    (select (SUM((((total*ifnull(vat,0))/100)))+SUM(ifnull(labour_cost,0)))-sum(total*ifnull(discount,0)/100) from invoices where customer_id=:id) as invoice,
    ifnull((select SUM(qantity*price) from salesbacks where customer_id=:id),0) as rtrnPrice,
    ifnull((select SUM(total*ifnull(fine,0))/100 from invoicebacks where customer_id=:id),0) as rtrnInvoice
    from voucers where name='customer' and name_data_id=:id
    ) t",['id'=>$r->id]);
          if ($previous[0]->total>0) {
            $dabit=abs($previous[0]->total);
            $credit=0;
          }else{
            
            $credit=abs($previous[0]->total);
            $dabit=0;
          }
            $get=DB::select("
              select
                     t1.dates,
                     t1.datetime,
                     t1.product_name,
                     t1.voucer_id,
                     t1.qantity,
                     t1.price,
                     t1.debit,
                     t1.credit,
                    
                     SUM(cast(COALESCE(t1.debit,0)-COALESCE(t1.credit,0) as decimal(12,2))) 
                     over(order by t1.dates,t1.datetime) balance
              FROM(
              select 
                     sales.dates,
                     sales.micro_time as datetime,
                     products.product_name,
                     '' as voucer_id,
                     sales.qantity,
                     sales.price,
                     0 as debit,
                     (cast(sales.qantity*sales.price as decimal(12,2))) as credit 
                        FROM sales
                        inner join products 
                        ON products.id=sales.product_id
                          WHERE sales.customer_id=:id
                          and sales.dates>=:fromDate and sales.dates<=:toDate
                            UNION ALL
              SELECT 
                     dates,
                     micro_time datetime,
                     '',
                     id,
                     '',
                     '',
                     (case when payment_type='Deposit' then ammount else 0 end) as debit,
                     (case when payment_type='Expence' then ammount else 0 end) as credit
                      FROM voucers 
                        WHERE name_data_id=:id and name='customer'
                          and dates>=:fromDate and dates<=:toDate
                          UNION ALL
              SELECT '',
                     '',
                     'Prev-B',
                     '',
                     '',
                     '',
                     '".$dabit."',
                     '".$credit."'
                          UNION ALL
              SELECT 
                      dates,
                      micro_time,
                      '(vat+lbr)-dis',
                      '',
                      '',
                      '',
                      cast(ifnull((total*discount)/100,0) as decimal(16,2)) as debit,
                      cast(ifnull((total*vat)/100,0)+ifnull(labour_cost,0) as decimal(14,2)) as credit
                  from invoices where customer_id=:id 
                    and  dates>=:fromDate and dates<=:toDate
                    UNION ALL
              SELECT 
                    dates,
                    micro_time,
                    products.product_name,
                    '',
                    salesbacks.qantity,
                    salesbacks.price,
                    (cast(salesbacks.qantity*salesbacks.price as decimal(12,2))) as dabit,
                    0 as credit
                    from salesbacks inner join products on products.id=salesbacks.product_id
                    where salesbacks.customer_id=:id and dates >=:fromDate and dates<=:toDate
                    UNION ALL
              SELECT 
                    dates,
                    micro_time,
                    'fine',
                    '',
                    '',
                    '',
                    0 as credit,
                    ifnull(total*fine/100,0) as dabit
                    from invoicebacks where customer_id=:id and dates>=:fromDate and dates<=:toDate
              ) t1 order by t1.dates,t1.datetime",['id'=>$r->id,'fromDate'=>$fromDate,'toDate'=>$toDate]);
            break;
            case 'supplier':
           $previous=DB::select("
              select 
t.purchase,t.invoice,t.purchasebacks,t.invoicebacks,t.Deposit,t.Expence,((t.purchase+t.invoice)-(t.purchasebacks+t.invoicebacks))-(t.Deposit-t.Expence) as total
from(
    select ifnull((select sum(qantity*price) from purchases where supplier_id=:id and dates<:fromDate),0) as purchase,
    ifnull((select sum(ifnull(transport,0)+ifnull(labour_cost,0)) from invpurchases where supplier_id=:id and dates<:fromDate),0) as invoice,
    ifnull((select sum(qantity*price) from purchasebacks where supplier_id=:id and dates<:fromDate),0) as purchasebacks,
    ifnull((select sum(ifnull(transport,0)+ifnull(labour_cost,0))-(total*ifnull(fine,0))/100 from invpurchasebacks where supplier_id=:id and dates<:fromDate),0) as invoicebacks,
    ifnull(sum(IF(payment_type='Deposit',ammount,0)),0) as Deposit,
    ifnull(sum(IF(payment_type='Expence',ammount,0)),0) as Expence
    from voucers where name='supplier' and name_data_id=:id and dates<:fromDate
) t",['id'=>$r->id,'fromDate'=>$fromDate]);
      $current_blnce=DB::select("
              select 
t.purchase,t.invoice,t.purchasebacks,t.invoicebacks,t.Deposit,t.Expence,((t.purchase+t.invoice)-(t.purchasebacks+t.invoicebacks))-(t.Deposit-t.Expence) as total
from(
    select ifnull((select sum(qantity*price) from purchases where supplier_id=:id),0) as purchase,
    ifnull((select sum(ifnull(transport,0)+ifnull(labour_cost,0)) from invpurchases where supplier_id=:id),0) as invoice,
    ifnull((select sum(qantity*price) from purchasebacks where supplier_id=:id),0) as purchasebacks,
    ifnull((select sum(ifnull(transport,0)+ifnull(labour_cost,0))-(total*ifnull(fine,0))/100 from invpurchasebacks where supplier_id=:id),0) as invoicebacks,
    ifnull(sum(IF(payment_type='Deposit',ammount,0)),0) as Deposit,
    ifnull(sum(IF(payment_type='Expence',ammount,0)),0) as Expence
    from voucers where name='supplier' and name_data_id=:id
) t",['id'=>$r->id]);
            if ($previous[0]->total<0) {
              $credit=abs($previous[0]->total);
              $dabit=0;
            }else{
              $dabit=abs($previous[0]->total);
              $credit=0;
            }

             $get=DB::select("
              select t1.dates,
                     t1.micro_time,
                     t1.product_name,
                     t1.voucer_id,
                     t1.qantity,
                     t1.price,
                     t1.debit,
                     t1.credit,
                     SUM(cast(COALESCE(t1.debit,0)-COALESCE(t1.credit,0) as decimal(12,2)))
                     over(order by t1.micro_time)as balance
              FROM(
              select purchases.dates,
                     purchases.micro_time,
                     products.product_name,
                     null as voucer_id,
                     purchases.qantity,
                     purchases.price,
                     (cast(purchases.qantity*purchases.price as decimal(12,2))) as debit,
                     0 as credit 
                        FROM purchases
                        INNER JOIN products
                        ON products.id=purchases.product_id
                          WHERE supplier_id=:id
                          and dates>=:fromDate and dates<=:toDate
                            UNION ALL
              SELECT dates,
                     micro_time,
                     't-port/lebour',
                     null,
                     null,
                     null,
                     transport+labour_cost as dabit,
                     0 as credit
                     from invpurchases 
                     where supplier_id=:id and dates>=:fromDate and dates<=:toDate
                     UNION ALL
               select purchasebacks.dates,
               purchasebacks.micro_time,
               products.product_name,
               null as voucer_id,
               purchasebacks.qantity,
               purchasebacks.price,
               0 as debit,
               (cast(purchasebacks.qantity*purchasebacks.price as decimal(12,2))) as credit 
                  FROM purchasebacks
                  INNER JOIN products
                  ON products.id=purchasebacks.product_id
                    WHERE purchasebacks.supplier_id=:id
                    and purchasebacks.dates>=:fromDate and purchasebacks.dates<=:toDate
                      UNION ALL
                SELECT dates,
                 micro_time,
                 '(t-port+lebour)-fine',
                 null,
                 null,
                 null,
                 cast(ifnull(total*fine/100,0) as decimal(16,2)) as dabit,
                 transport+labour_cost  as credit
                 from invpurchasebacks
                 where supplier_id=:id and dates>=:fromDate and dates<=:toDate
                 UNION ALL
              SELECT dates,
                     micro_time,
                     null,
                     id,
                     null,
                     null,
                     (case when payment_type='Expence' then ammount else 0 end) as debit,
                     (case when payment_type='Deposit' then ammount else 0 end) as credit
                      FROM voucers 
                        WHERE name_data_id=:id and name='supplier'
                        and dates>=:fromDate and dates<=:toDate
                      UNION ALL
              SELECT 0,
                     null,
                     'Prev-B',
                     null,
                     null,
                     null,
                     '".$dabit."',
                     '".$credit."'
              ) t1 order by t1.micro_time",['id'=>$r->id,'fromDate'=>$fromDate,'toDate'=>$toDate]);
        }
      }
      return response()->json(['get'=>$get,'current_blnce'=>$current_blnce,'fromDate'=>$fromDate,'toDate'=>$toDate,'name'=>$r->name,'category'=>$r->category]);
    }
}
