@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
        	<!-- Horizontal Form -->
	        <div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">{{$title}}</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            <form class="form-horizontal formloc" method="post" action="{{url('save-general-invoice')}}">
	            	{{ csrf_field() }}
	              <div class="box-body">
	                <div class="form-group row">
	                  <label for="inputEmail3" class="col-md-2 control-label">Registration No</label>
	                  <div class="col-md-3">
	                    <!-- <input type="text" class="form-control" name="registration_no" value="" placeholder="Enter Name"> -->
	                    <select class="form-control select2" name="registration_no" required>
	                    	<option selected disabled>Please Select</option>
	                    	@if($allReg)
	                    		@foreach($allReg as $item)
	                    		<option value="{{$item->registration_no}}">{{$item->registration_no}} ({{$item->name}})</option>
	                    		@endforeach
	                    	@endif
	                    </select>
	                  </div>
	                  <label for="inputEmail3" class="col-md-2 control-label">Payment Type</label>
		                <div class="col-md-3">
		                   <select class="form-control selectedPaymentType" name="paymentType">
		                   	<option disabled selected>Please Select</option>
		                   	<option value="cash">Cash</option>
		                   	<option value="paytm">Paytm</option>
		                   	<option value="sph">SPH</option>
		                   	<option value="credit">Credit</option>
		                   	<option value="debit">Debit</option>
		                   </select>
		                </div>
	                </div>
	                <div class="form-group row">
	                	<label for="inputEmail3" class="col-md-2 control-label">Amount</label>
		                <div class="col-md-3">
		                   <input type="number" class="form-control" name="amount" value="" placeholder="Enter Amount" required>
		                </div>
		                <label class="col-md-2 control-label">Treatment days</label>
			            <div class="col-md-3">
			            	<input type="number" name="days" class="form-control" placeholder="Treatment Days" required>
			            </div>
	                </div>
	                <div class="reference_check" style="display: none;">
		                <div class="form-group row">
		                	<label for="inputEmail3" class="col-md-2 control-label">Reference No</label>
			                <div class="col-md-3">
			                   <input type="text" class="form-control packageReferenceNo" data-url="{{url('reference-duplicate')}}" name="reference_no" value="" placeholder="Enter Reference No.">
			                </div>
			                <label for="inputEmail3" class="col-md-2 control-label">Bank Name</label>
			                <div class="col-md-3">
			                   <input type="text" class="form-control" name="bank" value="" placeholder="Enter Bank">
			                </div>
		                </div>
		            </div>
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	                <button type="submit" class="btn btn-info">Submit</button>
	                <button type="submit" class="btn btn-default">Cancel</button>
	                <a href="{{url('download-pdf')}}" target="_blank" class="btn btn-primary" style="float: right;">Print</a>
	              </div>
	              <!-- /.box-footer -->
	            </form>
	        </div>
	          <!-- /.box -->
	    </div>
	</div>

	<div class="box">
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>Registration No</th>
                  <th>Name</th>
                  <th>Branch</th>
                  <th>Payment Type</th>
                  <th>Amount</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($allInvoice)
                	@foreach($allInvoice as $invoiceItem)
                		<tr>
                			<td>{{$invoiceItem->registration_no}}</td>
                			<td>{{$invoiceItem->name}}</td>
                			<td>@if($invoiceItem->branch_id) {{branchDetails($invoiceItem->branch_id)->name}} @endif</td>
                			<td>{{ucfirst($invoiceItem->payment_type)}}</td>
                			<td>{{$invoiceItem->amount}}</td>
                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                			<td>
                				<a class="form-control btn btn-primary" target="_blank" href="{{url('invoice-view')}}/{{$invoiceItem->id}}">View</a>
                			</td>
                		</tr>
                	@endforeach
                @endif
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
</section>

@endsection