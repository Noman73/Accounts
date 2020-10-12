@extends('layouts.master')
@section('content')
@section('link')
<style>
  #date{
    margin:0 auto;
  }
  #submit{
    margin:0 auto;
    margin-top: 20px;
  }
  #blnc{
    float:right;
  }
</style>
@endsection

<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Invoice Summery</h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <form>
        <div class="input-group input-group-sm col-md-4 col-12" id="date">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-sm">from</span>
          </div>
          <input class="form-control" name="fromDate" id="fromDate">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
          </div>
          <input class="form-control" name="toDate" id="toDate">
        </div>
      </form>
        <div class="col-md-2" id="submit">
          <button class="btn btn-sm btn-primary" onclick="Request()">Create Report</button>
        </div>
            </div>
  </div>
</div>
@endsection
@section('script')
<script src="{{asset('js/pdf.js')}}"></script>
<script>
 // $('#date').daterangepicker({
 //        showDropdowns: true,
 //        locale: {
 //            format: 'DD-MM-YYYY',
 //            separator:' to ',
 //            customRangeLabel: "Custom",
 //        },
 //        minDate: '01-01-1970',
 //        maxDate: '01/01/2050'
 //  })
$('#fromDate').daterangepicker({
  showDropdowns:true,
 singleDatePicker: true,
 locale: {
    format: 'DD-MM-YYYY',
  },
 minDate: '01-01-1970',
 maxDate: '01-01-2050'
});
$('#toDate').daterangepicker({
 showDropdowns:true,
 singleDatePicker: true,
 locale: {
    format: 'DD-MM-YYYY',
  },
  minDate: '01-01-1950',
  maxDate: '01-01-2050'
});
function Request(){
  fromDate=$('#fromDate').val()
  toDate=$('#toDate').val()
  axios.post('/admin/invoice_summery',{fromDate:fromDate,toDate:toDate})
  .then((res)=>{
    console.log(res)
    data=res.data.get;
    let html='';
       html+='<table>'
       html+="<thead>"
       html+="<tr style='text-align:center;font-size:10px;height:12px;'>"
       html+="<th width='10%'>ID</th>"
       html+="<th width='20%'>Date</th>"
       html+="<th width='10%'>Inv-ID</th>"
       html+="<th width='20%'>Name</th>"
       html+="<th width='20%'>Total</th>"
       html+="<th width='20%'>Total Payable</th>"
       html+='</tr>'
       html+="</thead>"
       html+="<tbody style='font-size:8px;text-align:center;'>"
       total=0;
      for (var i = 0; i < data.length; i++){
        html+=`<tr style='height:12px;'>
                <td>`+i+`</td>
                <td>`+dateFormat(new Date(data[i]['dates']*1000))+`</td>
                <td>`+data[i]['invoice_id']+`</td>
                <td>`+data[i]['name']+`</td>
                <td>`+data[i]['total']+`</td>
                <td>`+data[i]['total_payable']+`</td>
               </tr>`
        total+=parseFloat(data[i]['total_payable']);
      }
       html+="</tbody>"
       html+=`<tfoot>
              <tr>
                <th colspan="4"></th>
                <th colspan="2" style='text-align:right'>Total: `+total.toFixed(2)+`</th>
              </tr>
            </tfoot>`;
      header=`<h6 style='text-align:center;margin-top:10px;'>Invoice Summery Sheet</h6>
             <strong style='font-size:10px;text-align:center'>`+dateFormat(new Date(res.data.fromDate*1000))+` to `+dateFormat(new Date(res.data.toDate*1000))+`</strong>
             <div style='text-align:right;margin-right:30px;font-size:12px;'>Print Date : `+dateFormat(new Date())+` </div>`;
      footer=`<div><p style='text-align:center;font-size:10px;'>DevTunes Technology || 01731186740</p></div>`

    var head = HtmlToPdfMake(header);
    var val = HtmlToPdfMake(html,{
              tableAutoSize:true
            });
    var footer = HtmlToPdfMake(footer);
        var dd = {pageMargins:[20,80,20,40],content:val,header:head,footer:footer};
    MakePdf.createPdf(dd).open();
  $('.buffer').addClass('d-none');
  // document.getElementById('myForm').reset()
  $('.submit').attr('disabled',false);
  })
  .catch((error)=>{
    console.log(error)
  })
}

function dateFormat(date){
let date_ob = date;
let dates = ("0" + date_ob.getDate()).slice(-2);
let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
let year = date_ob.getFullYear();
return(dates + "-" + month + "-" + year);
}
</script>
@endsection
