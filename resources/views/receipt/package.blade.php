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
	            <form class="form-horizontal formloc" method="post" action="{{url('save-package-invoice')}}">
	            	{{ csrf_field() }}
	              <div class="box-body">
	                <div class="form-group row">
	                  <label for="inputEmail3" class="col-md-2 control-label">Treatment Type</label>
		                <div class="col-md-3">
		                   <select class="form-control treatmentType" data-url="{{url('user-details-traetment-wise')}}" name="type" required>
		                   	<option disabled selected>Please Select</option>
		                   	<option value="package">Package wise</option>
		                   	<option value="perday">Per Day wise</option>
		                   </select>
		                </div>
		                <label for="inputEmail3" class="col-md-2 control-label">Registration No</label>
		                <div class="col-md-3">
		                    <select class="form-control select2" name="registration_no" required>
		                    	<option selected disabled>Please Select</option>
		                    	{{-- @if($allReg)
		                    		@foreach($allReg as $item)
		                    		<option value="{{$item->id}}">{{$item->registration_no}} ({{$item->name}})</option>
		                    		@endforeach
		                    	@endif --}}
		                    </select>
		                </div>
	                </div>
	                <div class="form-group row">
	                	<label class="col-md-2 control-label">Joints</label>
		              	<div class="col-md-3">
		              		<select class="form-control select2" name="joints" required>
			              		<option selected disabled>Please Select</option>
			              		<option value="one_joint">One Joint</option>
			      				<option value="two_joint">Two Joint</option>
			      				<option value="three_joint">Three Joint</option>
			      				<option value="neuro">Neuro</option>
			              	</select>
		              	</div>
		              	<label class="col-md-2 control-label">Treatment Days</label>
		              	<div class="col-md-3">
		              		<input type="number" name="treatment_days" class="form-control" placeholder="Enter Treatment Days" required>
		              	</div>
	                </div>
	                <div class="form-group row">
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
	                	<label for="inputEmail3" class="col-md-2 control-label">Amount</label>
		                <div class="col-md-3">
		                   <input type="number" class="form-control" name="amount" value="" placeholder="Enter Amount" required>
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
	                <!-- <a href="{{url('download-pdf')}}" target="_blank" class="btn btn-primary" style="float: right;">Print</a> -->
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
              	  <th>Refund Amount</th>
              	  <th>Treatment Days</th>
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
@php
$refundAmount = refundAmount($invoiceItem->id);
if($refundAmount > 0){
	$refundAmt = $refundAmount;
}else{
	$refundAmt = '0';
}
@endphp


                			<td>{{$refundAmount}}</td>
                			<td>@if($invoiceItem->treatment_days) {{$invoiceItem->treatment_days}} days @endif</td>
                			<td>{{date("d-M-Y", strtotime($invoiceItem->created_at))}}</td>
                			<td>
                				@php
                					$userServiceType = registrationWiseUserDetails($invoiceItem->registration_no)->service_type;
                				@endphp
                				@if(($userServiceType == 9) || ($userServiceType == 8) || $userServiceType == 1)
                					<a title="Invoice Print" class="form-control btn btn-primary" target="_blank" href="{{url('invoice-view')}}/{{$invoiceItem->id}}">Invoice</a>
                				@else
                					<a title="Invoice Print" class="form-control btn btn-primary" target="_blank" href="{{url('invoice-normal-view')}}/{{$invoiceItem->id}}">Invoice</a>
                				@endif
                				@if(($invoiceItem->amountType == 'package') && ($refundAmt == 0))
                					<a title="Refund Generate" data-toggle="modal" data-target="#modal-refund" href="#" class="form-control btn btn-info refundAmount" data-joint="{{$invoiceItem->joint}}" data-url="{{url('refund-amount-details')}}" data-id="{{$invoiceItem->id}}">Refund</a>
                				@endif
                				@if($refundAmt != 0)
                					<a title="Refund Certificate" class="form-control btn btn-warning" target="_blank" href="{{url('refund-view')}}/{{$invoiceItem->id}}">Refund</a>
                				@endif
                				<a title="Cancel" href="{{url('cancel-invoice')}}/{{$invoiceItem->id}}" onclick="return confirm('Are you sure you want to cancel Invoice?');" class="btn btn-danger">Cancel</a>
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
<!-- Refund Modal -->
<div class="modal fade" id="modal-refund">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Refund Amount</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('save-refund-amount')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
          	<div class="form-group row">
          		<label class="col-md-2 control-label">Total Amount</label>
	          	<div class="col-md-4">
	          		<label for="totalPackageAmount"></label>
	          	</div>
	          	<label class="col-md-2 control-label">Paid Amount</label>
	          	<div class="col-md-4">
	          		<label for="paidPackageAmount"></label>
	          	</div>
	          	<!-- <label class="col-md-2 control-label">Total Amount</label>
	          	<div class="col-md-2">
	          		<label for="totalPackageAmount"></label>
	          	</div> -->
          	</div>
            <div class="form-group row">
            	<input type="hidden" name="invoiceId" class="invoiceId" value="">
	            <label class="col-md-2 control-label">Joints</label>
	          	<div class="col-md-4">
	          		<input type="text" name="joint" class="form-control refundJoint" value="" readonly>
	          	</div>
              	<label class="col-md-2 control-label">Refund Amount</label>
              	<div class="col-md-4">
                	<input type="number" name="refund_amount" class="form-control" placeholder="Enter Amount" required>
              	</div>
            </div>
            <div class="form-group row">
            	<label class="col-md-2 control-label">Remark</label>
              	<div class="col-md-4">
                	<textarea name="remark" class="form-control" placeholder="Enter Remark" required></textarea>
              	</div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success pull-left">Submit</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection