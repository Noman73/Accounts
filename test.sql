SELECT t1.id,t1.invoice_id,t1.qantity,t1.price from 
( 
   SELECT dates,id,invoice_id,qantity,price from sales 
    UNION ALL 
   SELECT sales.dates,sales.id,sales.invoice_id+0.1,concat('dis-',invoices.discount),concat('dis-',invoices.total_payable) 
    from sales INNER JOIN invoices ON sales.invoice_id=invoices.id 
    group by sales.invoice_id 
) t1 order by t1.dates,t1.invoice_id 



-- sql balance sheet logic
SUM(cast(COALESCE(t1.debit,0)-COALESCE(t1.credit,0) as decimal(12,2))) 
                     over(order by t1.dates) balance