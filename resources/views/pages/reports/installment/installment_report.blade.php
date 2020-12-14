@extends('layouts.master')
@section('content')
@section('link')
<style>
  /*#date{
    margin:0 auto;
  }*/
  .ml{
  margin-left:1px;
  margin-right:1px;
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
      <h5 class="m-0 font-weight-bold">Installment Report</h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <form>
        <div class="input-group">
           <label class="control-label col-sm-2 text-lg-right" for="type">Customer:</label>
           <div class="col-sm-9">
              <select class="form-control form-control-sm" name="type" id="customer">
              </select>
               <div id="report_name_msg" class="invalid-feedback">
               </div>
            </div>
        </div>
      </form>
        <div class="col-md-2" id="submit">
          <button class="btn btn-sm btn-primary" onclick="Request()">Submit</button>
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
 $(document).ready(function(){
  
    $('#customer').select2({
      theme:'bootstrap4',
      placeholder:'select',
      allowClear:true,
      ajax:{
        url:"{{URL::to('admin/get_ins_invoice')}}",
        type:'post',
        dataType:'json',
        delay:20,
        data:function(params){
          return {
            searchTerm:params.term,
            _token:"{{csrf_token()}}",
            }
        },
        processResults:function(response){
          return {
            results:response,
          }
        },
        cache:true,
      }
    })
 })
 function getInvoice(this_val){
    axios.get('admin/get_installment_report/'+this_val.value)
    .then((res)=>{
      console.log(res);
      total_ins=parseFloat(res.data.invoice[0].insmnt_total_days)
      total_payable=parseFloat(res.data.invoice[0].total_payable)
      type=parseFloat(res.data.invoice[0].insmnt_type);
      dates=res.data.invoice[0].dates;
      issue_dates=parseFloat(res.data.invoice[0].issue_dates);
      var dates=new Date(dates*1000);


      console.log()
      console.log(dateFormat(dates))
      var x=0;
      for (var i = 1; i <=total_ins; i++) {
          console.log(dateFormat(new Date(dates.setMonth((dates.getMonth()+1)))));
      }
    })
 }
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
  id=$('#customer').val()
  axios.get('/admin/get_installment_report/'+id)
  .then((res)=>{
    console.log(res)
    // if (res.data.error){
    //   console.log(res);
    //   var keys=Object.keys(res.data.error);
    // for(var i=0; i<keys.length;i++){
    //     $('#'+keys[i]+'_msg').html(res.data.error[keys[i]][0]);
    //     $('#'+keys[i]).css('border','1px solid red');
    //     $('#'+keys[i]+'_msg').show();
    //   }
    //   return false;
    // }
    invoice=res.data.invoice[0];
    voucer=res.data.voucers;
  

    issue_dates=new Date(invoice.issue_dates*1000)
    console.log(issue_dates)
    let html='';
       html+='<table>'
       html+="<thead>"
       html+="<tr style='text-align:center;font-size:12px;height:12px;'>"
       html+="<th width='10%'>No</th>"
       html+="<th width='10%'>Date</th>"
       html+="<th width='10%'>Paid Date</th>"
       html+="<th width='10%'>INV-ID</th>"
       html+="<th width='25%'>Name</th>"
       html+="<th width='10%'>Status</th>"
       html+="<th width='10%'>Paid</th>"
       html+="<th width='15%'>Payable</th>"
       html+='</tr>'
       html+="</thead>"
       html+="<tbody style='font-size:12px;text-align:center;'>"
       debit=0;
       credit=0;
       switch(true){
        case invoice.insmnt_type==1:
          for (var i = 1; i <= invoice.insmnt_total_days; i++){
              html+=`<tr style='height:12px;'>
                      <td>`+i+`</td>
                      <td>`+dateFormat(new Date(issue_dates.setMonth((issue_dates.getMonth()+1))))+`</td>
                      <td>`+((voucer[i-1]) ? dateFormat(new Date(voucer[i-1].dates*1000)) : 'Not Pay')+`</td>
                      <td>INV-`+invoice['id']+`</td>
                      <td>`+invoice['name']+`</td>
                      <td>`+((voucer[i-1]) ? 'Paid' : 'Not Pay')+`</td>
                      <td>`+((voucer[i-1]) ?  voucer[i-1]['debit'] : '0.00')+`</td>
                      <td>`+(parseInt((parseFloat(invoice.total_payable)-parseFloat((invoice.total_payable*invoice.insmnt_pay_percent)/100))/parseFloat(invoice.insmnt_total_days)).toFixed(2))+`</td>
                     </tr>`
            }
          break;
          case invoice.insmnt_type==0:
          for (var i = 1; i <= invoice.insmnt_total_days; i++){
              html+=`<tr style='height:12px;'>
                      <td>`+i+`</td>
                      <td>`+dateFormat(new Date(issue_dates.setDate((issue_dates.getDate()+7))))+`</td>
                      <td>`+((voucer[i-1]) ? dateFormat(new Date(voucer[i-1].dates*1000)) : 'Not Pay')+`</td>
                      <td>INV-`+invoice['id']+`</td>
                      <td>`+invoice['name']+`</td>
                      <td>`+((voucer[i-1]) ? 'Paid' : 'Not Pay')+`</td>
                      <td>`+((voucer[i-1]) ?  voucer[i-1]['debit'] : '0.00')+`</td>
                      <td>`+(parseInt((parseFloat(invoice.total_payable)-parseFloat((invoice.total_payable*invoice.insmnt_pay_percent)/100))/parseFloat(invoice.insmnt_total_days)).toFixed(2))+`</td>
                     </tr>`
              }
              break;
       }
      
       html+="</tbody>"
      header=`<h6 style='text-align:center;margin-top:25px;'>Installment Pay Status<br>`+$('#customer option:selected').text()+`</h6>
             <div style='text-align:right;margin-right:30px;font-size:12px;'>Print Date: `+dateFormat(new Date())+` </div>`;
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
