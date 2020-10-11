<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		table thead th{
			background: red;
			border: 1px solid black;
		}
		table tbody td{
			font-size:12px;
			border:1px solid black;
		}
		table tfoot{
			border-top:2px solid black;
		}
		table{
			border-collapse: collapse;
		}
		#curr_blnc{
			/*text-align: right;*/
			margin-left: 10px;
		}
		#blnc{
			text-align: right;
			margin-right: 20px; 
		}
		#heading{
			font-size: 22px;
			font-weight: bold;
			text-align: center;
		}
		#date{
			text-align: center;
			font-size: 14px;
			font-weight: bold;
			margin-bottom: 20px;
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
	<div id="heading">Customer Invoice Summmery</div>
	<div id="date"><span id="fromDate">{{date('d-m-Y',$fromDate)}}</span>to<span id="toDate">{{date('d-m-Y',$toDate)}}</span></div>
<table width="100%">
	<thead>
		<tr>
			<th width="15%">INV-ID</th>
			<th width="15%">Date</th>
			<th width="25%">CUSTOMER NAME</th>
			<th width="20%">TOTAL</th>
			<th width="25%">TOTAL PAYABLE</th>
		</tr>
	</thead>
	<tbody>
		@php
		$i=1;
		@endphp
		@foreach($get as $row)
		<tr>
			<td>{{$row->invoice_id}}</td>
			<td>{{date('d-m-Y',$row->dates)}}</td>
			<td>{{$row->name}}</td>
			<td>{{$row->total}}</td>
			<td>{{$row->total_payable}}</td>
		</tr>
		@endforeach
		
	</tbody>
	<tfoot>
		<tr>
			<th colspan="4"></th>
			<th id="blnc">Grand Total:
				<span id='curr_blnc'>
					@php
					echo $value = number_format(array_sum(array_column($get,'total_payable')),2,'.','');
					@endphp
				</span>
			</th>
		</tr>
	</tfoot>
</table>
</body>
</html>