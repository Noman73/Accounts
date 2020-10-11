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
</style>
@endsection

<div class="container">
	<div class="card m-0">
    <div class="card-header pt-3  flex-row align-items-center justify-content-between">
      <h5 class="m-0 font-weight-bold">Customer Sales Report</h5>
     </div>
    <div class="card-body px-3 px-md-5">
      <form action="{{URL::to('admin/sales_report')}}" method="POST" target="_blank">
        @csrf
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
        <div class="col-md-2" id="submit">
          <button class="btn btn-sm btn-primary" type="submit">Create Report</button>
        </div>
        </form>
    </div>
  </div>
</div>
@endsection
@section('script')
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
</script>
@endsection
