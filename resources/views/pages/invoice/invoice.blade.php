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
@php
  $info=DB::table('information')->select('company_name','logo','phone','adress')->get()->first();
  $path = asset('storage/logo/'.$info->logo);
  $type = pathinfo($path, PATHINFO_EXTENSION);
  $data = file_get_contents($path);
  $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
@endphp
<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Sale Invoice <img class='buffer float-right d-none' src="{{asset('storage/admin-lte/dist/img/buffer.gif')}}" alt=""></h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <div class="row">
        <div class="col-12 col-md-3"> 
          <div class="form-group">
            <label class="font-weight-bold">Select Customer</label>
            <select class="form-control" id="customer" onchange="getBlnce(this.value)">
            </select>
            <span class="p-1 d-none" id="balance">Balance:<span id='c_bal'></span></span>
          </div>
        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label class="font-weight-bold">Select Tranport</label>
            <select class="form-control" id="transport">
            </select>
          </div>
        </div>
        <div class="col-12 col-md-2">
          <div class="form-group">
            <label class="font-weight-bold">Sales Type</label>
            <select class="form-control" id="sales_type">
              <option value="0">Normal Sale</option>
              <option value="1">Advance Sale</option>
              <option value="2">Sales Return</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-md-2">
          <div class="form-group d-none">
            <label class="font-weight-bold">Issue Date:</label>
            <input disabled="" class="form-control form-control-sm" id="issue_date">
          </div>
        </div>
        <div class="col-12 col-md-2">
          <div class="form-group">
            <label class="font-weight-bold">Date:</label>
            <input  class="form-control form-control-sm" id="date">
          </div>
        </div>
      </div>
<!--<button class="btn btn-sm btn-primary mb-3" id="add_item">Add Product</button> -->
        <table class="table-sm table-bordered" id="sales-table">
            <thead>
                  <tr>
                        <th class="text-center" width="20%">Product</th>
                        <th class="text-center" width="15%">Store</th>
                        <th class="text-center" width="10%">Avl.Qty</th>
                        <th class="text-center" width="10%">qantity</th>
                        <th class="text-center" width="15%">price</th>
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
      <div class="row footer-form mt-5">
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
                      <div class="input-group input-group-sm">
                          <input type="text" disabled="" class="form-control-sm form-control" id="total_item">
                      </div>
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
                    <td class="font-weight-bold">Transport Cost:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="transport_cost">
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
                  <tr>
                    <td class="font-weight-bold">Payment Method:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <select type="text" class="form-control form-control-sm" id="payment_method">
                            <option value="">--SELECT--</option>
                          </select>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Transaction:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="transaction_id" placeholder="X33KDLDFXFKJ">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="font-weight-bold">Payment Ammount:</td>
                    <td>
                      <div class="input-group input-group-sm">
                          <input type="text" class="form-control form-control-sm" id="pay">
                          <div class="input-group-append">
                            <span class="input-group-text" id="inputGroupPrepend">৳</span>
                          </div>
                      </div>
                    </td>
                  </tr>
                </table>
                <button class="btn btn-sm btn-primary text-center mb-3 mt-3 submit" type="submit" onclick="submit()" id="submit">submit</button>
                <button class="btn btn-sm btn-secondary text-center mb-3 mt-3" onclick="remove()" id="submit">Reset</button>
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
  $('#transport').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('admin/get_transport')}}",
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
  $('#payment_method').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('admin/get_banks')}}",
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
   $('#sales_type').select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
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
    total=response.data[0].total;
     switch(true){
        case total>=0:
        $('#c_bal').text(total);
        $('#balance').removeClass('d-none');
        $('#balance').removeClass('bg-danger');
        $('#balance').addClass('bg-success');
        break;
        case total<0:
        $('#c_bal').text(total);
        $('#balance').removeClass('d-none');
        $('#balance').removeClass('bg-success');
        $('#balance').addClass('bg-danger');
        break;
     }
  })
}
 let count=0;
  let i=0;
//add item function
function addItem(){
  count=count+1;
  i=i+1;
  var html='<tr>';
      html+="<td><select class='form-control form-control-sm item' type='text' name='item[]' id='item"+i+"' data-allow-clear='true'><option value='' selected>Select</option></select></td>";
      html+="<td><select class='form-control form-control-sm store' type='text' name='store[]' id='store"+i+"' data-allow-clear='true'><option value='' selected>Select</option></select></td>";
      html+="<td><input class='form-control form-control-sm text-right av_qty'  type='text' placeholder='0.00' name='av_qty[]' disabled id='av_qty"+i+"'></td>";
      html+="<td><input class='form-control form-control-sm text-right qantity'  type='number' placeholder='0.00' name='qantity[]' id='qantity"+i+"' value='1'></td>";
      html+="<td><input class='form-control form-control-sm text-right price'  type='number' placeholder='0.00' name='price[]' id='price"+i+"'></td>";
      html+="<td><input class='form-control form-control-sm text-right total'  type='text' placeholder='0.00' name='total[]' id='total"+i+"'></td>";
      html+="<td><button id='remove' class='btn btn-sm btn-danger'>X</button></td>";
      html+='</tr>';
  $('#sales-table tbody').append(html);
  $('#total_item').val(count);
  $('#item'+i).select2({
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
  $('#store'+i).select2({
      theme:"bootstrap4",
      allowClear:true,
      placeholder:'select',
      ajax:{
      url:"{{URL::to('admin/get_store')}}",
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
  $('.card-body input').val('');
  $(".card-body select").val(null).change();
  $(".card-body select option[value='']").attr('selected',true);
  $('#date,#issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
  addItem();
}

// get product wise price
$('body').on('select2:select',"select[name='item[]']", function (e){
  id=e.params.data.id;
  this_cat=$(this);
 axios.get('admin/product_price_by_id/'+id)
      .then(function(response){
        console.log(response.data);
            this_cat.parent().next().next().next().next().children("[name='price[]']").val(response.data);
            calculation();
          })
          .catch(function(error){
          console.log(error.request);
        })
 })
// when select store
$('body').on('select2:select',"select[name='store[]']", function (e){
  store_id=e.params.data.id;
  this_cat=$(this);
  product_id=this_cat.parent().prev().children("[name='item[]']").val();
  if (store_id=='' || product_id=='') {
    return false;
  }
 axios.get('admin/product_qantity/'+product_id+'/'+store_id)
      .then(function(response){
        console.log(response.data);
            this_cat.parent().next().children("[name='av_qty[]']").val(response.data[0].total);
          })
          .catch(function(error){
          console.log(error.request);
        })
 })
$('body').on('select2:select',"select[name='item[]']", function (e){
  store_id=$(this).parent().next().children("[name='store[]']").val();
  this_cat=$(this);
  product_id=this_cat.val();
  if (store_id=='' || product_id=='') {
    return false;
  }
 axios.get('admin/product_qantity/'+product_id+'/'+store_id)
      .then(function(response){
            this_cat.parent().next().next().children("[name='av_qty[]']").val(response.data[0].total);
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
  calculation();
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
  transport_cost=parseFloat($('#transport_cost').val());
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
    if (isNaN(transport_cost)) {
      transport_cost=0;
    }
    total_payableX=(total_payable*discount)/100;
    vat=(total_payable*vat)/100;
    $('#total_payable').val(((total_payable-total_payableX)+labour+vat+transport_cost).toFixed(2));
  }
}
function calculation(){
  let x=0;
  let totalcal=0;
  var total_item=$('#total_item').val();
  var qantity=$("input[name='qantity[]']")
              .map(function(){return (($(this).val()=='')? 0:$(this).val());}).get();
 $("input[name='price[]']")
  .map(function(){
      price=(($(this).val()=='')? 0:$(this).val())
      total=parseFloat(price)*parseFloat(qantity[x]);
      if (!isNaN(total)) {
      $(this).parent().next().children("input[name='total[]']").val(total)
      totalcal+=total;
      $('#final_total').val(totalcal);
      $('#total_payable').val(totalcal);
      totalCalculation();
      }
    x=x+1;
  }).get();
}
$(document).on('keyup change','.qantity,.price',function(){
  calculation();
})

$(document).on('keyup change','#discount,#vat,#labour,#transport_cost',function(){
  totalCalculation()
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
      totalx=parseFloat($('#final_total').val());
      total_item=parseFloat($('#total_item').val());
      discount=parseFloat($('#discount').val());
      vat=parseFloat($('#vat').val());
      labour=parseFloat($('#labour').val());
      transport=parseFloat($('#transport_cost').val());
      total_payable=parseFloat($('#total_payable').val());
      pay=parseFloat($('#pay').val());
      s_type=parseFloat($('#sales_type').val());
      console.log(pay+'first')
      if (isNaN(pay)){
          pay=parseFloat(0);
          console.log(pay+'pay')
      }
      x=[{product:products,qantities,prices,total}];
      html=`
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
        html+="<td>"+(parseFloat(x[0]['qantities'][i])).toFixed(2)+"</td>";
        html+="<td>"+(parseFloat(x[0]['prices'][i])).toFixed(2)+"</td>";
        html+="<td>"+(parseFloat(x[0]['total'][i])).toFixed(2)+"</td>";
        html+="</tr>";
      }
      html+=`</table>
        <table style='margin-left:16.8rem'>
            <tr style='border:none;'><td>Total</td><td style='background-color:blue;border:1px solid white;' width="53%"> `+totalx.toFixed(2)+`</td></tr>
            <tr style='border:none;'><td>Total Item</td><td style='background-color:blue;border:1px solid white;'> `+total_item+`</td></tr>
            <tr style='border:none;'><td>Discount</td><td  style='background-color:blue;border:1px solid white;'> `+(discount ? ((discount*totalx)/100).toFixed(2) :0.00 )+`</td></tr>
            <tr style='border:none;'><td>vat</td><td  style='background-color:blue;border:1px solid white;'> `+(vat ? ((vat*totalx)/100).toFixed(2) : 0.00)+`</td></tr>
            <tr style='border:none;'><td>Labour Cost</td><td  style='background-color:blue;border:1px solid white;'> `+(labour ? labour.toFixed(2) :0.00)+`</td></tr>
            <tr style='border:none;'><td>Transport Cost</td><td  style='background-color:blue;border:1px solid white;'> `+(transport ? transport.toFixed(2) :0.00)+`</td></tr>
            <tr style='border:none;'><td>Total Payable</td><td  style='background-color:blue;border:1px solid white;'> `+(total_payable ? total_payable.toFixed(2) : 0.00)+`</td></tr>
            <tr style='border:none;'><td>Payment</td><td  style='background-color:blue;border:1px solid white;'> `+(pay ? (pay).toFixed(2) : 0.00)+`</td></tr>
        </table>
        <div style='background-color:black;color:white;text-align:center;padding:10px;line-height:1.0;'>
        `+PaymentCheck(total_payable,pay)+`<br>
        Balance :`+(parseFloat($('#c_bal').text())-(parseFloat(total_payable)-parseFloat(pay)))+`
        </div>
      `;
      header=`<img style='width:100px;height:70px;margin-top:30px;margin-left:25px;' src='{{$base64}}'/>
              <span style='margin-left:30px;font-size:22px;'>INVOICE-`+inv_id+`</span>
              <span style='margin-left:30px;font-size:18px;'>{{$info->company_name}}</span>
              <span style='margin-left:30px;font-size:12px;'>{{$info->adress}}</span>
              <span style='margin-left:30px;font-size:12px;margin-bottom:15px;'>{{$info->phone}}</span>
              <span style='font-weight:bold;font-size:14px;text-align:center;'>`+$('#customer option:selected').text()+`</span>
              <span style='font-size:12px;text-align:center;'>`+$('#date').val()+`</span>
              <span style='font-size:12px;text-align:center;'>`+((s_type==2) ? 'Sales Return':'')+`</span>`;

      footer=`<div style='margin-top:50px;'>
        <div style='text-align:right;margin-right:30px;font-size:8px;'>Print Date : `+dateFormat(new Date())+`
               </div>
      <p style='text-align:center;font-size:10px;color:#808080;'>Powered By : DevTunes Technology || 01731186740</p></div>`
       // var head = HtmlToPdfMake(header);
    var val = HtmlToPdfMake(html,{
              tableAutoSize:true
            });
    console.log(val);
    setColor=val[1].table.body;
    for (var i = 0; i < setColor.length; i++) {
      setColor[i][1].fillOpacity=0.1;
    }
    // val[1]._layout.hLineColor:function(i, node) {
    //   return (i === 0 || i === node.table.body.length) ? 'red' : 'red';
    // }
    var header = HtmlToPdfMake(header,{
              // tableAutoSize:true
            });
    header[0].alignment="center";
  console.log(header);
    var footer = HtmlToPdfMake(footer);
        var dd = {info:{title:'invoice_'+inv_id+(new Date()).getTime()},pageMargins:[20,170,20,40],pageSize:'A5',content:val,header:header,footer:footer};
    MakePdf.createPdf(dd).open();
    remove();
    $('.submit').attr('disabled',false);
    }
    function PaymentCheck(payable,pay=0){
      payablex=parseInt(payable)
      payx=parseInt(pay)
      switch(true){
        case payablex===payx:
        return 'Paid';
        break;
        case payablex<payx:
        return 'Over Paid';
        break;
        case payablex>payx:
        t=(parseFloat(payable)-parseFloat(pay)).toFixed(2)
        ta=t.toString().split('.');
        return 'Due:'+t+'/= ('+n2words(ta[0])+' point '+n2words(ta[1])+')';
        break;
      }
    }
    // function WordConv(num){
    //   num=num.toString().split('.');
    //   return (n2words(num[0]))+" point "+(n2words(num[1]))
    // }
  }
// validate all fields
function Validate(){
  let isValid=true;
  let i=0;
$('#customer').removeClass('is-invalid');
$('#transport').removeClass('is-invalid');
if($('#customer').val()==null){
  isValid=false
  $('#customer').addClass('is-invalid');
}
if($('#transport').val()==null){
  isValid=false
  $('#transport').addClass('is-invalid');
}
av_qty = $("input[name='av_qty[]']")
       .map(function(){  
        if($(this).val()==''){
          return 0;
        }else{
          return $(this).val();
        }
      }).get();
$("input[name='qantity[]']").each(function(){
  $(this).removeClass('is-invalid');
if ($(this).val()=='' || parseFloat(av_qty[i])<parseFloat($(this).val())){
  isValid=false;
  $(this).addClass('is-invalid');
}else{
  i=i+1;
}
})
$("select[name='store[]']").each(function(){
  $(this).removeClass('is-invalid');
  console.log($(this).val())
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
if ($(this).val()==''){
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
  $('.submit').attr('disabled',true);
       qan=document.getElementsByName('qantity[]');
   qantities = $("input[name='qantity[]']")
              .map(function(){return $(this).val();}).get();
   prices = $("input[name='price[]']")
              .map(function(){return $(this).val();}).get();
   items = $("select[name='item[]']")
              .map(function(){return $(this).val();}).get();
   store = $("select[name='store[]']")
              .map(function(){return $(this).val();}).get();
   customer=$('#customer').val();
   date=$('#date').val();
   issue_date=$('#issue_date').val();
   total_payable=$('#total_payable').val();
   total_item=$('#total_item').val();
   discount=$('#discount').val();
   vat=$('#vat').val();
   labour=$('#labour').val();
   transport_cost=$('#transport_cost').val();
   transport=$('#transport').val();
   sales_type=$('#sales_type').val();
   total=$('#final_total').val();
   payment_method=$('#payment_method').val();
   transaction=$('#transaction_id').val();
   pay=$('#pay').val();
    formData=new FormData();
    formData.append('qantities[]',qantities);
    formData.append('prices[]',prices);
    formData.append('product[]',items);
    formData.append('store[]',store);
    formData.append('customer',customer);
    formData.append('issue_date',issue_date);
    formData.append('date',date);
    formData.append('total_payable',total_payable);
    formData.append('total_item',total_item);
    formData.append('discount',discount);
    formData.append('vat',vat);
    formData.append('labour',labour);
    formData.append('transport_cost',transport_cost);
    formData.append('transport',transport);
    formData.append('sales_type',sales_type);
    formData.append('total',total);
    formData.append('payment_method',payment_method);
    formData.append('transaction',transaction);
    formData.append('pay',pay);
    axios.post('admin/invoice',formData)
    .then(function(response){
      console.log(response.data);
      $('.buffer').addClass('d-none');
      $('.submit').attr('disabled',true);
      if (!response.data.message){
        keys=Object.keys(response.data[0]);
        html='';
        for (var i = 0; i <keys.length; i++) {
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
      }else if(response.data.message){
        window.toastr.success(response.data.message);
        CreatePdf(response.data.id);
        $('.buffer').addClass('d-none');
      }
    })
    .catch(function(error){
      console.log(error);
    })
  }else{
    $('.buffer').addClass('d-none');
  }
}
//datepicker.................

$('#date,#issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
$('#sales_type').on("select2:select", function(e){
  console.log(e.params.data.id);
  if (e.params.data.id==1) {
    $('#issue_date').parent().removeClass('d-none');
    $('#issue_date').attr('disabled',false)
  }else{
    $('#issue_date').parent().addClass('d-none');
    $('#issue_date').attr('disabled',true);
  }
})
function dateFormat(date){
let date_ob = date;
let dates = ("0" + date_ob.getDate()).slice(-2);
let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
let year = date_ob.getFullYear();
return(dates + "-" + month + "-" + year);
}
 </script>
@endsection