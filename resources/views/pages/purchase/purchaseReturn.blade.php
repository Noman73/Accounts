@extends('layouts.master')
@section('content')
<div class="container">
  <div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Purchase Return</h5>
     </div>
    <div class="card-body px-3 px-md-5">
    <form>
      <div class="row">
        <div class="col-12 col-md-6"> 
          <div class="form-group">
            <label class="font-weight-bold">Select Supplier</label>
            <select class="select2-container--open form-control" id="supplier">
            </select>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group float-right">
            <label class="font-weight-bold d-block">Return Date:</label>
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
                    <td class="font-weight-bold">Transport:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="transport">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">%</span>
                          </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Labour:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="labour">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">%</span>
                          </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Fine:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="fine">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">%</span>
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
                
<!--               </form> -->
                {{--invoice slip modal here --}}
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog " role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="ModalClose()">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <!--modal body-->
                      <div class="modal-body">
                        <div >
                          <table>
                            <tr>
                              <td><strong>Customer</strong></td>
                              <td><strong id="report_customer"></strong></td>
                            </tr>
                            <tr>
                              <td><strong>Date</strong></td>
                              <td><strong id="report_date"></strong></td>
                            </tr>
                          </table>
                          <br>
                          {{-- product list --}}
                          <table class="table table-sm text-center">
                            <thead>
                              <th>Product</th>
                              <th>Qantity</th>
                              <th>Price</th>
                              <th>Total</th>
                            </thead>
                            <tbody  id="product_list">
                            </tbody>
                          </table>
                          <hr>
                          <div class="float-right mr-4">
                            <table>
                              <tr>
                                <th>Total</th>
                                <td id='report_total'></td>
                              </tr>
                              <tr>
                                <th>fine</th>
                                <td id="report_fine"></td>
                              </tr>
                              <tr>
                                <th>Previous Due</th>
                                <td id="report_previous_due"></td>
                              </tr>
                              <tr>
                                <th >Total Payable</th>
                                <td id="report_total_payable"></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        
                       <!--end 2nd column -->
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="submit()">Confirm</button>
                        <button type="button" class="btn btn-warning"><i class="fas fa-print"></i>Print</button>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- /invoic modal --}}
            </div>
      </div>
      <button class="btn btn-lg btn-warning text-center float-right mb-3 mt-3" type="submit" onclick="showModal()" id="submit">Return</button>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function(){
  $('#supplier').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('admin/search_supplier')}}",
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
  $('#transport').val('');
  $('#labour').val('');
  $('#fine').val('');
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
  transport=parseFloat($('#transport').val());
  labour=parseFloat($('#labour').val());
  fine=parseFloat($('#fine').val());
  if (!isNaN(total_payable)) {
    if (isNaN(transport)){
      transport=0;
    }
    if (isNaN(labour)){
      labour=0;
    }
    if (isNaN(fine)){
      fine=0;
    }
    total_payableX=(total_payable*fine)/100;
    $('#total_payable').val((total_payable-total_payableX)+transport+labour);
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
$(document).on('keyup','#transport',function(){
  totalCalculation()
});
$(document).on('keyup','#labour',function(){
  totalCalculation()
});
$(document).on('keyup','#fine',function(){
  totalCalculation()
});

// show Modal with data

function showModal(){
  isValid=Validate();
  if(isValid==true){
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
      html='';
      console.log(x[0].product[0])
      for (var i=0;i<products.length; i++) {
        html+="<tr>";
        html+="<td>"+x[0]['product'][i]+"</td>";
        html+="<td>"+x[0]['qantities'][i]+"</td>";
        html+="<td>"+x[0]['prices'][i]+"</td>";
        html+="<td>"+x[0]['total'][i]+"</td>";
        html+="</tr>";
      }
      $('#product_list').html(html);
      $('#report_date').text(' :'+$('#date').val());
      $('#report_total').text(' :'+$('#final_total').val());
      $('#report_fine').text(':'+$('#fine').val());
      $('#report_total_payable').text(' :'+$('#total_payable').val());
      $('#report_customer').text(' :'+$('#customer option:selected').text());
      $('#report_previous_due').text(' :');
      $('.modal').modal('show');
    }
  }
// validate all fields
function Validate(){
  let isValid=true;
$('#supplier').removeClass('is-invalid');
console.log($('#supplier').val());
if($('#supplier').val()==null){
  isValid=false
  $('#supplier').addClass('is-invalid');
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
if (isValid==true) {
       qan=document.getElementsByName('qantity[]');
   qantities = $("input[name='qantity[]']")
              .map(function(){return $(this).val();}).get();
   prices = $("input[name='price[]']")
              .map(function(){return $(this).val();}).get();
   items = $("select[name='item[]']")
              .map(function(){return $(this).val();}).get();
   supplier=$('#supplier').val();
   date=$('#date').val();
   total_payable=$('#total_payable').val();
   total_item=$('#total_item').val();
   transport=$('#transport').val();
   labour=$('#labour').val();
   fine=$('#fine').val();
   total=$('#final_total').val();
    formData=new FormData();
    formData.append('qantities[]',qantities);
    formData.append('prices[]',prices);
    formData.append('product[]',items);
    formData.append('supplier',supplier);
    formData.append('date',date);
    formData.append('total_payable',total_payable);
    formData.append('total_item',total_item);
    formData.append('transport',transport);
    formData.append('labour',labour);
    formData.append('fine',fine);
    formData.append('total',total);
    axios.post('admin/purchase_return',formData)
    .then(function(response){
      console.log(response.data);
      if (response.data.message!=='success'){
        length=Object.keys(response.data[0]).length;
        html='';
        for (var i = 0; i<length; i++) {
          html+='*';
          html+=response.data[0]["product."+i+""][0]+'\n';
        }
        alert(html);
      }else if(response.data.message==='success'){
        window.toastr.warning('Return Success');
      }
    })
    .catch(function(error){
      console.log(error.request.response);
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
 </script>
@endsection