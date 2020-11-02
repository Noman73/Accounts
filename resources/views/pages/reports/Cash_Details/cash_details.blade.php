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
  .img-buffer{
  height: 30px;
  width: 30px;
 }
</style>
@endsection

<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Cash Details<img class="float-right buffer img-buffer d-none" src="{{asset('storage/admin-lte/dist/img/buffer.gif')}}" alt=""></h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <h2 class="buffer d-none">Please Wait Data Processing....</h2>
      <div class="p-2">
      <button class="btn btn-primary" onclick="Request()">Create Report</button>
      </div>
    </div>
    
  </div>
</div>
@endsection
@section('script')
<script src="{{asset('js/pdf.js')}}"></script>

<script>
  // $(document).ready(()=>{
  //   Request();
  // })
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
  $('.buffer').removeClass('d-none');
  fromDate=$('#fromDate').val()
  toDate=$('#toDate').val()
  axios.get('/admin/cash_details')
  .then((res)=>{
    console.log(res)
    data=res.data.get;
    let html='';
       html+='<table>'
       html+="<thead>"
       html+="<tr style='text-align:center;font-size:12px;height:12px;'>"
       html+="<th width='33%'>No</th>"
       html+="<th width='33%'>Name</th>"
       html+="<th width='33%'>Balance</th>"
       html+='</tr>'
       html+="</thead>"
       html+="<tbody style='font-size:12px;text-align:center;'>"
       let total=0;
       x=1;
      for (var i = 0; i < data.length; i++){
        total+=parseFloat(data[i]['total'])
        html+=`<tr style='height:12px;'>
                <td>`+(x++)+`</td>
                <td>`+data[i]['name']+`</td>
                <td>`+data[i]['total']+`</td>
               </tr>`;
               
      }
       html+="</tbody>"
       html+=`<tfoot>
              <tr>
                <th></th>
                <th></th>
                <th style='text-align:right;font-size:12px;'>Total: `+(total.toFixed(2))+`</th>
              </tr>
            </tfoot>`;
      header=`<h6 style='text-align:center;margin-top:30px;'>Cash Details</h6>
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
