SELECT t1.id,t1.invoice_id,t1.qantity,t1.price from 
( 
   SELECT dates,id,invoice_id,qantity,price from sales 
    UNION ALL 
   SELECT sales.dates,sales.id,sales.invoice_id+0.1,concat('dis-',invoices.discount),concat('dis-',invoices.total_payable) 
    from sales INNER JOIN invoices ON sales.invoice_id=invoices.id 
    group by sales.invoice_id 
) t1 order by t1.dates,t1.invoice_id 



-- sql balance sheet logic
SUM(cast(COALESCE(t1.debit,0)-COALESCE(t1.credit,0) as decimal(12,2))) over(order by t1.dates) balance



debit qantity credit 

-- stock manage
select t1.store_id,t1.product_id,t1.sales_store,t1.sales_prod,ifnull(t1.qantity,0)-ifnull(t1.sales_qan,0) total FROM
(
SELECT purchases.store_id,purchases.product_id,sum(purchases.deb_qantity) qantity,sales.store_id sales_store,sales.product_id sales_prod,sum(sales.deb_qantity) sales_qan from purchases
    left join sales ON sales.product_id=purchases.product_id group by sales.store_id,purchases.store_id,sales.product_id,purchases.product_id
    UNION ALL
select purchases.store_id ,purchases.product_id,sum(purchases.deb_qantity) qantity,sales.store_id sales_store,sales.product_id sales_prod,sum(sales.deb_qantity) sales_qan from purchases 
    right join sales ON sales.product_id=purchases.product_id group by sales.store_id,purchases.store_id,sales.product_id,purchases.product_id
) t1 


0177205978

-- testing purpass

SELECT stores.name store,products.product_name,ifnull(sum(purchases.deb_qantity),0),sales.deb_qantity from 
products 
left join purchases on purchases.product_id=products.id
left join (
select product_id,store_id,sum(deb_qantity) deb_qantity from sales group by product_id,store_id
) as sales on sales.product_id=products.id and  purchases.store_id=sales.store_id
left join stores on sales.store_id=stores.id or purchases.store_id=stores.id group by sales.store_id,purchases.store_id,purchases.product_id,sales.product_id
-- final output stock
sales as (
select product_id,store_id,sum(deb_qantity) deb_qantity from sales group by product_id,store_id
)
SELECT stores.name store,products.product_name,ifnull(sum(purchases.deb_qantity),0),sales.deb_qantity from 
products 
left join purchases on purchases.product_id=products.id
left join sales on sales.product_id=products.id and  purchases.store_id=sales.store_id
left join stores on sales.store_id=stores.id or purchases.store_id=stores.id group by sales.store_id,purchases.store_id,purchases.product_id,sales.product_id



-- DailyStatement
SELECT voucers.dates,
   banks.name bank_name,
   voucers.category,
   concat(ifnull(suppliers.name,''),ifnull(customers.name,''),ifnull(banks2.name,''),ifnull(namerelations.rel_name,'')) as name,
ifnull(voucers.debit,0) as Deposit,
ifnull(voucers.credit,0) as Expence
     from voucers
        left join banks on voucers.bank_id=banks.id
        left join suppliers on voucers.category='supplier' and voucers.data_id=suppliers.id
        left join customers on voucers.category='customer' and voucers.data_id=customers.id
        left join banks as banks2 on voucers.category='fund_transfer' and voucers.data_id=banks2.id
        left join names on voucers.category=names.name and names.stutus=0
        left join namerelations on names.id=namerelations.name_id 
      where voucers.dates>=:fromDate and voucers.dates<=:toDate order by voucers.dates,voucers.id