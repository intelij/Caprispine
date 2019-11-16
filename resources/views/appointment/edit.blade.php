@extends('layouts.apps')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}</h3>
              @if(!empty($getData->payment_method) && ($getData->payment_method == 'package_wise') && (empty($invoiceData)))
          		<a title="Generate Invoice" data-id="{{$getData->id}}" data-package="" class="btn btn-danger packageInvoice" data-toggle="modal" data-target="#modal-invoice" style="float: right; display: block;">Generate Invoice</a>
          	  @elseif(!empty($invoiceData))
          	  	<a title="Generate Receipt" href="{{url('generate-receipt-package/')}}/{{$getData->id}}" onclick="return confirm('Are you sure, you want to generate receipt?')" target="_blank" class="btn btn-default" style="background: #999966;color: #ffffff;float: right;">Invoice</a>
          	  @endif
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" id="form" role="form" method="post" action="{{url('update-appointment/')}}/{{$getData->id}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">Patient Name</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="uname" class="form-control" value="{{userDetails($getData->user_id)->name}}" placeholder="Enter Name" readonly>
	              		</div>
	              		<input type="hidden" value="{{$getData->user_id}}" name="patientId">
	              		<label for="inputEmail3" class="col-md-2 control-label">Email</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="email" name="email" class="form-control" value="{{userDetails($getData->user_id)->email}}" placeholder="Enter Email" readonly>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	                  	<label class="col-md-2 control-label">Therapist</label>
	                  	<div class="col-md-3">
	                  		<select class="form-control select2 appTherapistId" name="therapistId">
	                  			<option disabled selected>Please Select</option>
	                  			@if($allTherapists)
	                  				@foreach($allTherapists as $theItem)
	                  				<option value="{{$theItem->id}}" @if(!empty(userDetails($getData->user_id)->therapist_id)) {{ userDetails($getData->user_id)->therapist_id == $theItem->id ? 'selected="selected"' : '' }} @endif>{{$theItem->name}} @if($theItem->branch) ( {{branchDetails($theItem->branch)->name}} ) @endif</option>
	                  				@endforeach
	                  			@endif
	                  		</select>
	                  	</div>
	                  	<label for="inputEmail3" class="col-md-2 control-label">Appointment Time</label>
						<div class="col-md-3">
	                  		<select class="form-control checkAppointmentTime" id="appointmentTimeSlot" data-url="{{url('check-appointment-book')}}" name="appTime">
	                  			<option disabled selected value="">Please Select</option>
	                  			@if($timeSlot)
	                  				@foreach($timeSlot as $time)
	                  					<option value="{{$time->id}}" {{ $getData->appointment_time == $time->id ? 'selected="selected"' : '' }}>{{$time->time}}</option>
	                  				@endforeach
	                  			@endif
	                  		</select>
	                  	</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control select2" name="status">
	              				<option selected disabled>Please Select</option>
	              				<option value="approved" {{ $getData->status == 'approved' ? 'selected="selected"' : '' }}>Approved</option>
	              				<option value="inactive" {{ $getData->status == 'inactive' ? 'selected="selected"' : '' }}>Inactive</option>
	              			</select>
	              		</div>
	              		<label class="col-md-2 control-label">Joints</label>
	                  	<div class="col-md-3">
		                  	<select class="form-control jointSelected select2" data-url="{{url('package-wise-joint/')}}" name="joints">
		                  		<option selected disabled>Please Select</option>
		                  		<option value="one_joint" {{ $getData->joints == 'one_joint' ? 'selected="selected"' : '' }}>One Joint</option>
		          				<option value="two_joint" {{ $getData->joints == 'two_joint' ? 'selected="selected"' : '' }}>Two Joint</option>
		          				<option value="three_joint" {{ $getData->joints == 'three_joint' ? 'selected="selected"' : '' }}>Three Joint</option>
		          				<option value="neuro" {{ $getData->joints == 'neuro' ? 'selected="selected"' : '' }}>Neuro</option>
		                  	</select>
	                  	</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">Payment Type</label>
						<div class="col-md-3 font-normal">
	                  		<select class="form-control payment_type" data-sid="{{$getData->app_service_type}}" data-id="{{$getData->id}}" data-url="{{url('packages-list')}}" data-urls="{{url('check-therapist-attendance')}}" name="payment_method">
	                  			<option selected disabled>Please Select</option>
	                  			<option value="package_wise" {{ $getData->payment_method == 'package_wise' ? 'selected="selected"' : '' }}>Package Wise Payment</option>
	                  			<option value="per_day_visit" {{ $getData->payment_method == 'per_day_visit' ? 'selected="selected"' : '' }}>Per Day Visit Payment</option>
	                  			<option value="complimentary" {{ $getData->payment_method == 'complimentary' ? 'selected="selected"' : '' }}>Complimentary</option>
	                  		</select>
	                  	</div>
	                {{-- @if($getData->app_service_type != 9) --}}
	                  @if($getData->payment_method == 'package_wise')
	                  <div class="per_day_visit_name" style="display: block;">
	                  	<label for="inputEmail3" class="col-md-2 control-label">Packages</label>
						<div class="col-md-3 font-normal">
	                  		<select class="form-control package_type_check_condition select2" data-id="{{$getData->id}}" data-url="{{url('check-therapist-attendance')}}" data-urls="{{url('check-payment-status')}}" name="package_type">
	                  			<option selected disabled>Please Select</option>
	                  			@if($allPackage)
	                  				@foreach($allPackage as $item)
	                  				<option value="{{$item->id}}" {{ $getData->package_type == $item->id? 'selected="selected"' : '' }}>{{$item->name}} ( {{$item->package_amount}}/- with {{$item->days}} days for {{$item->joints}} )</option>
	                  				@endforeach
	                  			@endif
	                  		</select>
	                  	</div>
	                  </div>
	                  @else
	                  <div class="per_day_visit_name" style="display: block;">
	                  	<label for="inputEmail3" class="col-md-2 control-label">Packages</label>
						<div class="col-md-3 font-normal">
	                  		<select class="form-control select2" name="package_type">
	                  			<option value="" selected disabled>Please Select</option>
	                  		</select>
	                  	</div>
	                  </div>
	                  @endif
	                {{-- @endif --}}
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">Service Type</label>
						<div class="col-md-3 font-normal">
	                  		<select class="form-control select2" name="service_type">
	                  			<option value="" selected disabled>Please Select</option>
	                  			@if($serviceType)
	                  				@foreach($serviceType as $sItem)
	                  					<option value="{{$sItem->id}}" {{ $getData->app_service_type == $sItem->id? 'selected="selected"' : '' }}>{{$sItem->name}}</option>
	                  				@endforeach
	                  			@endif
	                  		</select>
	                  	</div>
	              	</div>
	              	<div class="form-group row">
	                	<label class="col-md-2 control-label">Consultation Fees</label>
						<div class="col-md-3">
							<select class="form-control select2" name="consultation_fees">
								<option value="" disabled selected>Please Select</option>
								@if($allAmount)
									@foreach($allAmount as $allAmt)
									<option value="{{$allAmt->amount}}" {{ $getData->consultation_fees == $allAmt->amount ? 'selected="selected"' : '' }}>{{$allAmt->amount}}</option>
									@endforeach
								@endif
							</select>
	                  	</div>
	                  	<label class="col-md-2 control-label">Consultation Name</label>
						<div class="col-md-3">
							<select class="form-control" name="consultation_name">
								<option disabled selected>Please Select</option>
								@if($consultationName)
									@foreach($consultationName as $consultationN)
									<option value="{{$consultationN->id}}" {{ $getData->consultation_name == $consultationN->id ? 'selected="selected"' : '' }}>{{$consultationN->name}}</option>
									@endforeach
								@endif
							</select>
	                  	</div>
	                </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	              	<button type="submit" class="btn btn-primary">Update</button>
                    <!-- <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a> -->
	              </div>
	              <!-- /.box-footer -->
	          </form>
          	</div>
      	</div>
 	</div>
</section>

<!-- Generate Invoice bill modal -->
<div class="modal fade" id="modal-invoice">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Generate Invoice</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="{{url('save-invoice-details-for-package')}}" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Payment Type</label>
              <div class="col-md-3">
                 <select class="form-control selectedPaymentType select2" id="paymentType" name="paymentType" required>
                  <option disabled selected>Please Select</option>
                  <option value="cash">Cash</option>
                  <option value="paytm">Paytm</option>
                  <option value="sph">SPH</option>
                  <option value="credit">Credit</option>
                  <option value="debit">Debit</option>
                 </select>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Package</label>
              <div class="col-md-2">
                 <select class="form-control select2" id="packages" name="packages">
          			<option selected disabled>Please Select</option>
          			@if($allPackage)
          				@foreach($allPackage as $item)
          				<option value="{{$item->id}}">{{$item->name}} ({{$item->package_amount}}/- {{$item->joints}})</option>
          				@endforeach
          			@endif
          		</select>
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
            <input type="hidden" name="appId" value="" class="appId">
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
<!-- /. end modal -->

<script>
	function validate(){
	    var paymentType = $('#paymentType').val();
	    var packages = $('#packages').val();
	    if(!paymentType){
	      alert('Payment Type field is mandatory!!');
	      $("#paymentType").focus();
	      return false;
	    }else if(!packages){
	      alert('Package field is mandatory!!');
	      $("#packages").focus();
	      return false;
	    }else{
	      return true;
	    }
	}

</script>
@endsection