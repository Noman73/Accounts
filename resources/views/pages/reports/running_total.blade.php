@extends('layouts.master')
@section('content')
@section('link')
<style>
 .ml{
  margin-left: 1px;
  margin-right: 1px;
 }
 .submit{
  margin:0 auto;
 }
 .buffer{
  height: 30px;
  width: 30px;
 }
 #blnc{
  text-align:right;
 }
</style>
@endsection
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Running Total<img class="float-right buffer d-none" src="{{asset('storage/admin-lte/dist/img/buffer.gif')}}" alt=""></h5>
      
     </div>
    <div class="card-body px-3 px-md-5">
      <form id="myForm">
        <div class="input-group">
         <label class="control-label col-sm-2 text-lg-right" for="category">Category :</label>
         <div class="col-sm-9">
            <select class="form-control form-control-sm" onchange="getName(this)" name="category" id="category">
            <option value="">--select--</option>
              @foreach($categories as $category)
            <option value="{{$category->name}}">{{$category->name}}</option>
            @endforeach
          </select>
             <div id="category_msg" class="invalid-feedback">
             </div>
          </div>
       </div>
        <div class="input-group d-none mt-1 name">
         <label class="control-label col-sm-2 text-lg-right" for="name" id="name_text"></label>
         <div class="col-sm-9">
             <select type="text" class="form-control form-control-sm" id="name" placeholder="Enter Product Name....">
             </select>
             <div id="name_msg" class="invalid-feedback">
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
      <div class=" text-center mt-2">
          <button class="btn btn-sm btn-primary submit"  onclick="ajaxRequest()">Create Report</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="{{asset('js/pdf.js')}}"></script>
<script>

function getName(data){
  selected=data.options[data.selectedIndex].text;
  console.log(data.value);
  $('#name option').remove();
  $('.name').removeClass('d-none');
  if (data.value==''){
  $('#name option').remove();
  return false;
  }
  $('#name_text').text(selected+' :');
  if (selected==='customer'){
    dataURL="{{URL::to('admin/search_customer')}}";
  }else if(selected==='supplier'){
    dataURL="{{URL::to('admin/search_supplier')}}";
  }
  $('#name').select2({
    theme:'bootstrap4',
    placeholder:'select',
    ajax:{
      url:dataURL,
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
}
// request for data
function ajaxRequest(){
  $('.buffer').removeClass('d-none');
  $('.submit').attr('disabled',true);
  let category=$('#category').val();
  let id=$('#name').val();
  let name=$('#name').text();
  let fromDate=$('#fromDate').val();
  let toDate=$('#toDate').val();
  let formData=new FormData();
  formData.append('category',category);
  formData.append('id',id);
  formData.append('name',name);
  formData.append('fromDate',fromDate);
  formData.append('toDate',toDate);
  axios.post('admin/running-total',formData)
        .then(function(res){
            console.log(res)
            data=res.data.get;
  let html='';
       html+='<table>'
       html+="<thead>"
       html+="<tr style='text-align:center;font-size:10px;height:12px;'>"
       html+="<th width='5%'>ID</th>"
       html+="<th width='10%'>DATE</th>"
       html+="<th width='15%'>Details</th>"
       html+="<th width='10%'>V-ID</th>"
       html+="<th width='10%'>Qantity</th>"
       html+="<th width='10%'>Price</th>"
       html+="<th width='10%'>Debit</th>"
       html+="<th width='10%'>Credit</th>"
       html+="<th width='20%'>Balance</th>"
       html+='</tr>'
       html+="</thead>"
       html+="<tbody style='font-size:8px;text-align:center;'>"
      for (var i = 0; i < data.length; i++){
        // $('#test').text(i+=i);
        html+=`<tr style='height:12px;'>
                <td>`+i+`</td>
                <td>`+data[i]['dates']+`</td>
                <td>`+data[i]['product_name']+`</td>
                <td>`+data[i]['voucer_id']+`</td>
                <td>`+data[i]['qantity']+`</td>
                <td>`+data[i]['price']+`</td>
                <td>`+data[i]['debit']+`</td>
                <td>`+data[i]['credit']+`</td>
                <td>`+data[i]['balance']+`</td>
               </tr>`
      }
       html+="</tbody>"
       html+=`<tfoot>
              <tr>
                <th colspan="6"></th>
                <th colspan="3" style='text-align:right;'>Current Balance: `+res.data['current_blnce'][0]['total']+`<span id='curr_blnc'></span></th>
              </tr>
            </tfoot>`;
      header=`<h6 style='text-align:center;margin-top:10px;'>Ledger Sheet</h6>
             <strong style='font-size:10px;text-align:center'>`+dateFormat(new Date(res.data.fromDate*1000))+` to `+dateFormat(new Date(res.data.toDate*1000))+`</strong>
                <div style='text-align:center;font-weight:bold;margin-top:10px;'>`+capitalize(res.data.category)+` : `+res.data.name+`</div>
                <div style='text-align:right;margin-right:30px;font-size:12px;'>Print Date : `+dateFormat(new Date())+` </div>`;
      footer=`<div style='margin-top:50px;'><p style='text-align:center;font-size:10px;color:#808080;'>DevTunes Technology || 01731186740</p></div>`

    var head = HtmlToPdfMake(header);
    var val = HtmlToPdfMake(html,{
              tableAutoSize:true
            });
    var footer = HtmlToPdfMake(footer);
        var dd = {info:{title:res.data.name+(new Date()).getTime()},pageMargins:[20,80,20,40],content:val,header:head,footer:footer};
    MakePdf.createPdf(dd).open();
  $('.buffer').addClass('d-none');
  // document.getElementById('myForm').reset()
  $('.submit').attr('disabled',false);

    // endpdf
        })
        .catch(function(error){
          console.log(error)
        })
}
 $('#fromDate').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        locale: {
            format: 'DD-MM-YYYY',
            separator:' to ',
            customRangeLabel: "Custom",

        },
        minDate: '01-01-1970',
        maxDate: '01/01/2050'
        
  })
 $('#toDate').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        locale: {
            format: 'DD-MM-YYYY',
            separator:' to ',
            customRangeLabel: "Custom",

        },
        minDate: '01-01-1970',
        maxDate: '01/01/2050'
        
  })
 $('#category').select2({
  theme:"bootstrap4",
 })
 function capitalize(s){
    return s[0].toUpperCase() + s.slice(1);
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
