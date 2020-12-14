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
      <h5 class="m-0 font-weight-bold">Custom Report</h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <form>
        <div class="input-group">
           <label class="control-label col-sm-2 text-lg-right" for="type">Report Name:</label>
           <div class="col-sm-9">
              <select class="form-control form-control-sm" name="type" id="report_name" onchange='getSubName(this)'>
                <option value=""></option>
                @foreach($name as $names)
                <option value="{{$names->id}}">{{$names->name}}</option>
                @endforeach
              </select>
               <div id="report_name_msg" class="invalid-feedback">
               </div>
            </div>
        </div>
        <div class="input-group">
           <label class="control-label col-sm-2 text-lg-right" for="type">Sub Name :</label>
           <div class="col-sm-9">
              <select class="form-control form-control-sm" name="sub_name" id="sub_name">
                <option value=""></option>
              </select>
               <div id="sub_name_msg" class="invalid-feedback">
               </div>
            </div>
        </div>
       <div class="row ml mt-1">       
        <label class="col-sm-2 text-lg-right">Date :</label>
        <div class="input-group input-group-sm col-sm-9" id="date">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">from :</span>
            </div>
            <input class="form-control form-control-sm" name="fromDate" id="fromDate">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">To :</span>
            </div>
            <input class="form-control form-control-sm" name="toDate" id="toDate">
        </div>
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
 $(document).ready(function(){
  $('#report_name').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
  })
  $('#sub_name').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
  })
  $('#report_type').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
  })
 })
 function getSubName(this_val){
    $('#sub_name').select2({
      theme:'bootstrap4',
      placeholder:'select',
      allowClear:true,
      ajax:{
        url:"{{URL::to('admin/relation_search')}}"+'/'+this_val.value,
        type:'get',
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
  fromDate=$('#fromDate').val()
  toDate=$('#toDate').val()
  report_name=$('#report_name option:selected').text()
  sub_name=$('#sub_name').val()
  formData=new FormData;
  formData.append('fromDate',fromDate);
  formData.append('toDate',toDate);
  formData.append('report_name',report_name);
  formData.append('sub_name',sub_name);
  axios.post('/admin/custom_report',formData)
  .then((res)=>{
    if (res.data.error){
      console.log(res);
      var keys=Object.keys(res.data.error);
    for(var i=0; i<keys.length;i++){
        $('#'+keys[i]+'_msg').html(res.data.error[keys[i]][0]);
        $('#'+keys[i]).css('border','1px solid red');
        $('#'+keys[i]+'_msg').show();
      }
      return false;
    }
    data=res.data.get;
    let html='';
       html+='<table>'
       html+="<thead>"
       html+="<tr style='text-align:center;font-size:12px;height:12px;'>"
       html+="<th width='10%'>No</th>"
       html+="<th width='10%'>Date</th>"
       html+="<th width='20%'>V-ID</th>"
       html+="<th width='30%'>Name</th>"
       html+="<th width='15%'>debit</th>"
       html+="<th width='15%'>credit</th>"
       html+='</tr>'
       html+="</thead>"
       html+="<tbody style='font-size:12px;text-align:center;'>"
       debit=0;
       credit=0;
      for (var i = 0; i < data.length; i++){
        html+=`<tr style='height:12px;'>
                <td>`+i+`</td>
                <td>`+dateFormat(new Date(data[i]['dates']*1000))+`</td>
                <td>V-`+data[i]['id']+`</td>
                <td>`+data[i]['rel_name']+`</td>
                <td>`+data[i]['debit']+`</td>
                <td>`+data[i]['credit']+`</td>
               </tr>`
        debit+=parseFloat(data[i]['debit']);
        debit+=parseFloat(data[i]['credit']);
      }
       html+="</tbody>"
       html+=`<tfoot>
                <tr>
                  <th colspan="4"></th>
                  <th style='text-align:right'>Total: `+debit.toFixed(2)+`</th>
                  <th style='text-align:right'>Total: `+credit.toFixed(2)+`</th>
                </tr>
              </tfoot>`;
      header=`<h6 style='text-align:center;margin-top:10px;'>`+$('#sub_name option:selected').text()+`Sheet</h6>
              <strong style='font-size:10px;text-align:center'>`+dateFormat(new Date(res.data.fromDate*1000))+` to `+dateFormat(new Date(res.data.toDate*1000))+`</strong>
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
