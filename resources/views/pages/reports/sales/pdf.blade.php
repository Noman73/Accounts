<!DOCTYPE html>
<html>
<head>
	<title>CWS Report</title>
	<style type="text/css">
		table td{
			border:1px solid black;
			font-size: 10px;
		}
		table th{
			margin-right: 20px;
		}
		table{
			margin-left:80px;
			border-collapse: collapse;
		}
		#name{
			font-size: 18px;
			font-weight: bold;
		}
		#adress{
			font-size:14px;
			text-decoration: underline;
			text-decoration-color:black;
			margin-bottom: 5px;
		}
		#heading{
			text-align: center;
			text-decoration:underline;
			font-size: 18;
			font-weight: bold;
		}
		#grand_total{
			margin-left:70%;
			font-size: 14px;
			font-weight: bold;
		}
		#invoice_id{
			font-size: 14px;
			margin-left:60px;
		}
		#head-tbl th{
			font-size: 16px;
		}
		#date{
			text-align: center;
			margin-bottom: 20px;
			font-size: 14px;
		}
		#fromDate{
			margin-right: 20px;
		}
		#toDate{
			margin-left: 20px;
		}

	</style>
</head>
<body>
<div id='heading'>Customer Wise Sales Report</div>
<div id="date"><span id="fromDate">{{$fromDate}}</span>to<span id="toDate">{{$toDate}}</span></div>
<table id="head-tbl" width="90%">
	<tr>
		<th width="24%">Details</th>
		<th width="22%">Qantity</th>
		<th width="22%">Price</th>
		<th width="22%">Total</th>
	</tr>
</table>
@foreach($get as $row)
<div id="name">{{$row->name}}</div>
<div id="adress">{{$row->adress}}</div>
@if($row->invoice_id!=null)
<div id="invoice_id">INV-{{$row->invoice_id}}</div>
@endif
@if($row->total!=null)
<table width="90%">
	<tr>
		<td height="10" width="24%">{{$row->product_name}}</td>
		<td width="22%">{{$row->qantity}}</td>
		<td width="22%">{{$row->price}}</td>
		<td width="22%">{{$row->total}}</td>
	</tr>
</table>
@endif
@if($row->sub_total!=null)
<div id="grand_total">Grand Total:{{$row->sub_total}}</div>
@endif
	
@endforeach
</body>
</html>