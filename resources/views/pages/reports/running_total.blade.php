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
</style>
@endsection
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Running Total <img class="float-right buffer d-none" src="{{asset('storage/admin-lte/dist/img/buffer.gif')}}" alt=""></h5>
      
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
  let fromDate=$('#fromDate').val();
  let toDate=$('#toDate').val();
  let formData=new FormData();
  formData.append('category',category);
  formData.append('id',id);
  formData.append('fromDate',fromDate);
  formData.append('toDate',toDate);
  axios.post('admin/running-total',formData)
        .then(function(res){
            console.log(res)
            data=res.data.get;
  let html="<h1 style='text-align:center'>header hello world</h1>"
       html+='<table>'
       html+="<thead>"
       html+="<tr style='background-color:red;text-align:center'>"
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
       html+="<tbody>"
      for (var i = 0; i < data.length; i++){
        (data[i]['product_name']!=null) ? console.log(data[i]['product_name']) : '';
        html+=`<tr style='height:10px;'>
                <td style='font-size:8px;'>`+i+`</td>
                <td style='font-size:8px;'>`+data[i]['dates']+`</td>
                <td style='font-size:8px;'>`+(data[i]['product_name']!=null ? data[i]['product_name'] : '')+`</td>
                <td style='font-size:8px;'>`+(data[i]['voucer_id']!=null ? data[i]['voucer_id'] : '')+`</td>
                <td style='font-size:8px;'>`+(data[i]['qantity']!=null ? data[i]['qantity'] : '')+`</td>
                <td style='font-size:8px;'>`+(data[i]['price']!=null ? data[i]['price'] : '')+`</td>
                <td style='font-size:8px;'>`+(data[i]['debit']!=null ? data[i]['debit'] : '')+`</td>
                <td style='font-size:8px;'>`+(data[i]['credit']!=null ? data[i]['credit'] : '')+`</td>
                <td style='font-size:8px;'>`+data[i]['balance']+`</td>
               </tr>`
      }
       html+="</tbody>"
    var val = HtmlToPdfMake(html,{
  tableAutoSize:true
});
    var dd = {content:val};
    MakePdf.createPdf(dd).open();
  $('.buffer').addClass('d-none');
  document.getElementById('myForm').reset()
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
</script>
@endsection
