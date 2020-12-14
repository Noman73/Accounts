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
      <h5 class="m-0 font-weight-bold">Sale Summery</h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <form>
        <div class="input-group">
         <label class="control-label col-sm-2 text-lg-right" for="type">Type :</label>
         <div class="col-sm-9">
            <select class="form-control form-control-sm" name="type" id="type">
            <option value="0">Normal Sale</option>
            <option value="1">Advance Sale</option>
            <option value="2">Sales Return</option>
            <option value="3">Installment</option>
              
          </select>
             <div id="type_msg" class="invalid-feedback">
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
  type=$('#type').val()
  axios.post('/admin/sales_summery',{fromDate:fromDate,toDate:toDate,type:type})
  .then((res)=>{
    console.log(res)
    data=res.data.get;
    let html='';
       html+='<table>'
       html+="<thead>"
       html+="<tr style='text-align:center;font-size:12px;height:12px;'>"
       html+="<th width='10%'>No</th>"
       html+="<th width='10%'>Date</th>"
       html+="<th width='20%'>INV-ID</th>"
       html+="<th width='20%'>Product Name</th>"
       html+="<th width='10%'>Qantity</th>"
       html+="<th width='10%'>Price</th>"
       html+="<th width='20%'>Total</th>"
   
       html+='</tr>'
       html+="</thead>"
       html+="<tbody style='font-size:12px;text-align:center;'>"
       total=0;
      for (var i = 0; i < data.length; i++){
        html+=`<tr style='height:12px;'>
                <td>`+i+`</td>
                <td>`+dateFormat(new Date(data[i]['dates']*1000))+`</td>
                <td>`+data[i]['invoice_id']+`</td>
                <td>`+data[i]['product_name']+`</td>
                <td>`+data[i]['qantity']+`</td>
                <td>`+data[i]['price']+`</td>
                <td>`+data[i]['total']+`</td>
               </tr>`
        total+=parseFloat(data[i]['total']);
      }
       html+="</tbody>"
       html+=`<tfoot>
                <tr>
                  <th colspan="5"></th>
                  <th colspan="2" style='text-align:right'>Total: `+total.toFixed(2)+`</th>
                </tr>
              </tfoot>`;
      header=`<h5 style='text-align:center;margin-top:30px;'>Sale Summery Sheet</h5>
               <h6 style='text-align:center;'>`+$('#type option:selected').text()+`</h6>
              <strong style='font-size:10px;text-align:center'>`+dateFormat(new Date(res.data.fromDate*1000))+` to `+dateFormat(new Date(res.data.toDate*1000))+`</strong>
             <div style='text-align:right;margin-right:30px;font-size:12px;'>Print Date: `+dateFormat(new Date())+` </div>`;
      footer=`<div><p style='text-align:center;font-size:10px;'>DevTunes Technology || 01731186740</p></div>`
    var head = HtmlToPdfMake(header);
    var val = HtmlToPdfMake(html,{
              tableAutoSize:true
            });
    var footer = HtmlToPdfMake(footer);
        var dd = {pageMargins:[20,100,20,40],content:val,header:head,footer:footer};
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
