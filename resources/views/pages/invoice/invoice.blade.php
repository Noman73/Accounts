@extends('layouts.master')
@section('content')
@section('link')
<style>
  .buffer{
    height: 20px;
    width:20px;
  }
</style>
@endsection
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Sale Invoice <img class='buffer float-right d-none' src="{{asset('storage/admin-lte/dist/img/buffer.gif')}}" alt=""></h5>
     </div>
    <div class="card-body px-3 px-md-5">
    <form>
      <div class="row">
        <div class="col-12 col-md-6"> 
          <div class="form-group">
            <label class="font-weight-bold">Select Customer</label>
            <select class="form-control" id="customer" onchange="getBlnce(this.value)">
            </select>
            <span class="p-1 d-none" id="balance"></span>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group float-right">
            <label class="font-weight-bold d-block">Date:</label>
            <input  class="form-control-sm" id="date">
          </div>
        </div>
      </div>
    </form>
<!--<button class="btn btn-sm btn-primary mb-3" id="add_item">Add Product</button> -->
        <table class="table-sm table-bordered" id="sales-table">
            <thead>
                  <tr>
                        <th class="text-center" width="20%">Product</th>
                        <th class="text-center" width="15%">Avl.Qty</th>
                        <th class="text-center" width="15%">qantity</th>
                        <th class="text-center" width="20%">price</th>
                        <th class="text-center" width="15%">total</th>
                        <th class="text-center" width="15%">Action</th>
                  </tr>
                
            </thead>
        <tbody>
<!--               <form name='invoice[]' id='invoice'>-->
<!--                @csrf -->
        </tbody> 
      </table>
      <button class="btn btn-sm btn-primary mb-3 float-right" id="add_item">+</button>
      <div class="row footer-form">
            <div class="col-12 col-md-4">
                <table>
                  <tr>
                    <td class="font-weight-bold">Total:</td>
                    <td width="50%">
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="final_total" disabled="">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">৳</span>
                          </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Total Item:</td>
                    <td>
                      <input type="text" disabled="" class="form-control-sm form-control" id="total_item">
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Discount:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="discount">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">%</span>
                          </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Vat:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="vat">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">%</span>
                          </div>
                      </div>
                      </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Labour Cost:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="labour">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">৳</span>
                          </div>
                      </div>
                      </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Total Payable:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="total_payable" disabled="">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">৳</span>
                          </div>
                      </div>
                      </td>
                  </tr>
                </table>
                <button class="btn btn-sm btn-primary text-center mb-3 mt-3" type="submit" onclick="submit()" id="submit">submit</button>
<!--               </form> -->
                {{--invoice slip modal here --}}
                {{-- /invoic modal --}}
            </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src='{{asset('js/pdf.js')}}'></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#customer').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('admin/search_customer')}}",
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

function getBlnce(id){
  if (id=='' || id==null || id==NaN) {
      $('#balance').addClass('d-none');
      return false;
    }
  axios.get('admin/customer_balance/'+id)
  .then(function(response){
    console.log(response);
    if (response.data[0].total){
        total=response.data[0].total;
        $('#balance').removeClass('d-none');
        $('#balance').text('Balance:'+total);
        if (total>0){
          $('#balance').removeClass('bg-danger');
          $('#balance').addClass('bg-success');
        }else if(total==null){
          $('#balance').text('Balance:'+0.00);
        }else{
          $('#balance').removeClass('bg-success');
          $('#balance').addClass('bg-danger');
        }
    }
  })
}
 var count=0;
//add item function 
function addItem(){
  count=count+1;
  var html='<tr>';
      html+="<td><select class='form-control form-control-sm item' type='text' name='item[]' id='item"+count+"' data-allow-clear='true'><option value='' selected>Select</option></select></td>";
      html+="<td><input class='form-control form-control-sm text-right qantity'  type='text' placeholder='0.00' name='av_qty[]' disabled id='av_qty"+count+"'></td>";
      html+="<td><input class='form-control form-control-sm text-right qantity'  type='text' placeholder='0.00' name='qantity[]' id='qantity"+count+"'></td>";
      html+="<td><input class='form-control form-control-sm text-right price'  type='text' placeholder='0.00' name='price[]' id='price"+count+"'></td>";
      html+="<td><input class='form-control form-control-sm text-right total'  type='text' placeholder='0.00' name='total[]' id='total"+count+"'></td>";
      html+="<td><button id='remove' class='btn btn-sm btn-danger'>X</button></td>";
      html+='</tr>';
  $('#sales-table tbody').append(html);
  $('#total_item').val(count);
  $('#item'+count).select2({
      theme:"bootstrap4",
      allowClear:true,
      placeholder:'select',
      ajax:{
      url:"{{URL::to('admin/select2')}}",
      type:'post',
      dataType:'json',
      delay:20,
      data:function(params){
        return {
          searchTerm:params.term,
          _token:'{{csrf_token()}}',
          }
      },
      processResults:function(response){
        item=$("select[name='item[]'] option:selected")
                  .map(function(){return $(this).val();}).get();
         res=response.map(function(currentValue, index, arr){
          if (item.includes(currentValue.id)){
            response[index]['disabled']=true;
          }
        });
        return {
          results:response,
        }
      },
      cache:true,
    }
  })

  
}
//............end add item function...........

//............remove item function............
function remove(){
  count=0;
  $('#sales-table tbody').children().remove();
  $('#final_total').val('');
  $('#discount').val('');
  $('#vat').val('');
  $('#labour').val('');
  $('#total_payable').val('');
  addItem();
}
// get category wise product
$('body').on('select2:select',"select[name='category[]']", function (e){
  id=e.params.data.id;
  this_cat=$(this);
  console.log(id);
 axios.get('admin/product_by_cat/'+id)
      .then(function(response){
          html="<option value='' selected>select</option>"
              response.data.forEach(function(data){
                html+="<option value='"+data.id+"'>"+data.product_name+"</option>";
              })
            this_cat.parent().next().children("[name='item[]']").html(html);
          })
          .catch(function(error){
          console.log(error.request);
        })
 })
// get product wise price
$('body').on('select2:select',"select[name='item[]']", function (e){
  id=e.params.data.id;
  this_cat=$(this);
 axios.get('admin/product_price_by_id/'+id)
      .then(function(response){
        console.log(response.data);
            this_cat.parent().next().next().next().children("[name='price[]']").val(response.data);
          })
          .catch(function(error){
          console.log(error.request);
        })
 })
//<=======end category wise product==========>


$(document).ready(function(){
  addItem();
})
$('#add_item').click(function(){
  addItem();
});
$('tbody').on('click','#remove',function(){
  if (parseInt($('#total_item').val())>1) {
  $(this).parent().parent().remove();
  count=count-1;
  $('#total_item').val(count);
  var final_total=parseFloat($("#final_total").val());
  var this_total=$(this).parent().prev().children().val();
  if (isNaN(final_total)){
    final_total=0;
  }else if(isNaN(this_total)){
    this_total=0;
  }else if(isNaN(final_total) && isNaN(this_total)){
    final_total=0;
    this_total=0
  }
  final_total=final_total-this_total
  $("#final_total").val(final_total);
  $("#total_payable").val(final_total);
  }else{
    alert('You cannot remove this item')
  }
  
})

// function discount(discount){
//   total_payable=$('#final_total').val();
//   total_payable=total_payable-(total_payable*discount)/100;
//   $('#total_payable').val(total_payable);
// }
// function vat(vat){
//   total_payable=$('#final_total').val();
//   total_payable=total_payable-(total_payable*vat)/100;
//   $('#total_payable').val(total_payable);
// }
function totalCalculation(){
  total_payable=parseFloat($('#final_total').val());
  discount=parseFloat($('#discount').val());
  vat=parseFloat($('#vat').val());
  labour=parseFloat($('#labour').val());
  if (!isNaN(total_payable)) {
    if (isNaN(discount)) {
      discount=0;
    }
    if (isNaN(vat)) {
      vat=0;
    }
    if (isNaN(labour)) {
      labour=0;
    }
    total_payableX=(total_payable*discount)/100;
    vat=(total_payable*vat)/100;
    $('#total_payable').val(((total_payable-total_payableX)+labour+vat).toFixed(2));
  }
}
function calculation(){
  var total_item=$('#total_item').val();
  var qantity=0;
  var price=0;
  var total=0;
  var final_total=0;
   for (var i = 0; i <= total_item; i++) {
     qantity=$("#qantity"+i).val();
     if (qantity>0) {
      var price=$("#price"+i).val()
      if (price>0) {
        total=qantity*price;
        $("#total"+i).val(total);
        if (total>0) {
          final_total=final_total+parseFloat($("#total"+i).val());
          $('#final_total').val(final_total);
          $("#total_payable").val(final_total);
          totalCalculation();
        }
      }
     }
   }
}



$(document).on('keyup','.qantity',function(){
  calculation();
})
$(document).on('keyup','#discount',function(){
  totalCalculation()
});
$(document).on('keyup','#vat',function(){
  totalCalculation();
});
$(document).on('keyup','#labour',function(){
  totalCalculation();
});

// show Modal with data

function CreatePdf(inv_id){
  isValid=Validate();
  if(isValid){
      products = $("select[name='item[]'] option:selected")
                  .map(function(){return $(this).text();}).get();
      qantities = $("input[name='qantity[]']")
                  .map(function(){return $(this).val();}).get();
      prices = $("input[name='price[]']")
                  .map(function(){return $(this).val();}).get();
      total = $("input[name='total[]']")
                  .map(function(){return $(this).val();}).get();
                  console.log(products.length);
      x=[{product:products,qantities,prices,total}];
      html=`
      <table style='font-weight:bold;'>
        <tr style='border:none;'><td>Invoice ID</td><td> : `+inv_id+`</td></tr>
        <tr style='border:none;'><td>Date</td><td> : `+$('#date').val()+`</td></tr>
        <tr style='border:none;'><td>Customer</td><td> : `+$('#customer').text()+`</td></tr>
      </table>
      <h6 style='text-align:center;font-size:15px'>Product List</h6>
      <table style='font-size:10px;'>
      <tr style='text-align:center;width:25%'>
        <th>Product Name</th>
        <th>Qantity</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
      `;
      console.log(x[0].product[0])
      for (var i=0;i<products.length; i++) {
        html+="<tr style='text-align:center;width:25%'>";
        html+="<td>"+x[0]['product'][i]+"</td>";
        html+="<td>"+x[0]['qantities'][i]+"</td>";
        html+="<td>"+x[0]['prices'][i]+"</td>";
        html+="<td>"+x[0]['total'][i]+"</td>";
        html+="</tr>";
      }
      html+=`</table>
        <table>
            <tr style='border:none;'><td>Total</td><td> : `+$('#final_total').val()+`</td></tr>
            <tr style='border:none;'><td>Total Item</td><td> : `+$('#total_item').val()+`</td></tr>
            <tr style='border:none;'><td>Discount</td><td> : `+$('#discount').val()+`</td></tr>
            <tr style='border:none;'><td>vat</td><td> : `+$('#vat').val()+`</td></tr>
            <tr style='border:none;'><td>Labour Cost</td><td> : `+$('#labour').val()+`</td></tr>
            <tr style='border:none;'><td>Total Payable</td><td> : `+$('#total_payable').val()+`</td></tr>
        </table>

      `;
      header=`<h6 style='text-align:center;margin-top:10px;'>Customer Invoice</h6>
               <div style='text-align:right;margin-right:30px;font-size:12px;'>Print Date : `+dateFormat(new Date())+` 
                </div>`;
      footer=`<div style='margin-top:50px;'><p style='text-align:center;font-size:10px;color:#808080;'>Powered By : DevTunes Technology || 01731186740</p></div>`
       // var head = HtmlToPdfMake(header);
    var val = HtmlToPdfMake(html,{
              tableAutoSize:true
            });
    var header = HtmlToPdfMake(header,{
              // tableAutoSize:true
            });
    var footer = HtmlToPdfMake(footer);
        var dd = {info:{title:'invoice_'+inv_id+(new Date()).getTime()},pageMargins:[20,80,20,40],pageSize:'A5',content:val,header:header,footer:footer};
    MakePdf.createPdf(dd).open();
    }
  }
// validate all fields
function Validate(){
  let isValid=true;
$('#customer').removeClass('is-invalid');
if($('#customer').val()==''){
  isValid=false
  $('#customer').addClass('is-invalid');
}
$("input[name='qantity[]']").each(function(){
  $(this).removeClass('is-invalid');
if ($(this).val()=='') {
  isValid=false;
  
  $(this).addClass('is-invalid');
}
})
$("input[name='price[]']").each(function(){
  $(this).removeClass('is-invalid');
if ($(this).val()=='') {
  isValid=false;
  $(this).addClass('is-invalid');
}
})
$("select[name='item[]']").each(function(){
  $(this).removeClass('is-invalid');
if ($(this).val()=='') {
  isValid=false;
  $(this).addClass('is-invalid');
}
});
return isValid;
}
function submit(){
   isValid=Validate();
   // isValid=true;
   $('.buffer').removeClass('d-none');
if (isValid==true) {
       qan=document.getElementsByName('qantity[]');
   qantities = $("input[name='qantity[]']")
              .map(function(){return $(this).val();}).get();
   prices = $("input[name='price[]']")
              .map(function(){return $(this).val();}).get();
   items = $("select[name='item[]']")
              .map(function(){return $(this).val();}).get();
   customer=$('#customer').val();
   date=$('#date').val();
   total_payable=$('#total_payable').val();
   total_item=$('#total_item').val();
   discount=$('#discount').val();
   vat=$('#vat').val();
   labour=$('#labour').val();
   total=$('#final_total').val();
    formData=new FormData();
    formData.append('qantities[]',qantities);
    formData.append('prices[]',prices);
    formData.append('product[]',items);
    formData.append('customer',customer);
    formData.append('date',date);
    formData.append('total_payable',total_payable);
    formData.append('total_item',total_item);
    formData.append('discount',discount);
    formData.append('vat',vat);
    formData.append('labour',labour);
    formData.append('total',total);
    axios.post('admin/invoice',formData)
    .then(function(response){
      console.log(response.data);
      $('.buffer').addClass('d-none');
      if (!response.data.message){
        keys=Object.keys(response.data[0]);
        html='';
        for (var i = 0; i <keys.length; i++) {
          console.log(keys[i]);
          html+="<p style='color:red;line-height:1px;font-size:12px;'>"+response.data[0][keys[i]][0]+"</p>";
        }
        // alert(html);
        Swal.fire({
          title: 'Error !',
          icon:false,
          html:html,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonText:'Ok',
        })
      }else if(response.data.message==='success'){
        window.toastr.success('Invoice Added Success');
        CreatePdf(response.data.id);
      }
    })
    .catch(function(error){
      console.log(error);
    })
  }
}
//datepicker.................

$('#date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });

function dateFormat(date){
let date_ob = date;
let dates = ("0" + date_ob.getDate()).slice(-2);
let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
let year = date_ob.getFullYear();
return(dates + "-" + month + "-" + year);
}
 </script>
@endsection