@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Appointment</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            	<form class="form-horizontal" method="post" role="form" action="{{url('save-appointment')}}" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">Patient Type</label>
	              		<div class="col-md-3">
	              			<select class="form-control patientType select2" id="patienttype" name="patientType" required >
	              				<option selected disabled value="">Please Select</option>
	              				<option value="old">Old Patient</option>
	              				<option value="new">New Patient</option>
	              			</select>
	              		</div>
	              		<div class="box1" style="display: none;">
		              		<label for="inputEmail3" class="col-md-2 control-label">All Patient</label>
		              		<div class="col-md-3">
		              			<select class="form-control patientSelected select2" id="allpatient" data-url="{{url('therapist-patient-wise')}}" name="patientId">
		              				<option selected disabled>Please Select</option>
		              				@if($allPatient)
		              					@foreach($allPatient as $patItem)
		              						<option value="{{$patItem->id}}">{{$patItem->name}} ({{$patItem->mobile}})</option>
		              					@endforeach
		              				@endif
		              			</select>
		              		</div>
		              	</div>
	              	</div>
	              	<div class="box2" style="display:none;">
              			<div class="form-group row">
	                		<label for="inputEmail3" class="col-md-2 control-label">Patient Name</label>
							<div class="col-md-3">
		                  		<input type="text" name="patientName" class="form-control" id="patientname" placeholder="Enter Patient Name">
		                  	</div>
		                  	<label for="inputEmail3" class="col-md-2 control-label">Patient Mobile No</label>
							<div class="col-md-3">
		                  		<input type="number" name="patientMobile" id="patientno" class="form-control patientMobileNo" data-url="{{url('check-duplicateNo')}}" placeholder="Enter Patient Mobile No">
		                  	</div>
		                </div>
		                <div class="form-group row">
		                  	<label class="col-md-2 control-label">Email</label>
		                  	<div class="col-md-3">
		                  		<input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id">
		                  	</div>
		                  	<label class="col-md-2 control-label">Password</label>
							<div class="col-md-3">
		                  		<input type="password" name="password"  id="password" value="" class="form-control" placeholder="Enter Patient Password">
		                  	</div>
		                </div>
		                <div class="form-group row">
		                	<label for="inputEmail3" class="col-md-2 control-label">Address</label>
		              		<div class="col-md-3 font-normal">
		              			<textarea class="form-control" name="address" id="address" placeholder="Enter Address"></textarea>
		              		</div>
		              		<label for="inputEmail3" class="col-md-2 control-label">Date Of Birth</label>
		              		<div class="col-md-3 font-normal">
		              			<input type="date" name="dob" id="dob" class="form-control">
		              		</div>
		                </div>
		                <div class="form-group row">
		                	<label for="inputEmail3" class="col-md-2 control-label">Marital Status</label>
		              		<div class="col-md-3 font-normal">
		                  		<input type="radio" name="maritalStatus" value="Single" checked><label>Single</label>
		              			<input type="radio" name="maritalStatus" value="Married"><label>Married</label>
		              		</div>
		              		<label for="inputEmail3" class="col-md-2 control-label">Veg/Non Veg</label>
		              		<div class="col-md-3 font-normal">
		              			<input type="radio" name="vegNonVeg" value="Veg" checked><label>Veg</label>
		                  		<input type="radio" name="vegNonVeg" value="Non-Veg"><label>Non Veg</label>
		              		</div>
		                </div>
		                <div class="form-group row">
		                	<label for="inputEmail3" class="col-md-2 control-label">Gender</label>
							<div class="col-md-2">
		                  		<input type="radio" name="gender" value="male" checked><label>Male</label>
		                  		<input type="radio" name="gender" value="female"><label>Female</label>
		                  	</div>
		                	<label for="inputEmail3" class="col-md-3 control-label">Patient Service Type</label>
		              		<div class="col-md-3 font-normal">
		              			<select class="form-control select2 service_type" id="service_type" name="service_type">
		              				<option value="" selected disabled>Please Select</option>
		              				@if($patientService)
		              					@foreach($patientService as $pItem)
		              					<option value="{{$pItem->id}}">{{$pItem->name}}</option>
		              					@endforeach
		              				@endif
		              			</select>
		              		</div>
		                </div>
		                <div class="box3" style="display:none;">
		                	<div class="form-group row">
		                		<label for="inputEmail3" class="col-md-2 control-label">IPD No</label>
								<div class="col-md-3">
			                  		<input type="text" name="ipd_no" class="form-control" id="ipd_no" placeholder="Enter IPD No">
			                  	</div>
			                  	<label for="inputEmail3" class="col-md-2 control-label">IPMR No</label>
								<div class="col-md-3">
			                  		<input type="number" name="ipmr_no" id="ipmr_no" class="form-control" placeholder="Enter IPMR No">
			                  	</div>
			                </div>
			                <div class="form-group row">
			                	<label for="inputEmail3" class="col-md-2 control-label">Room No</label>
								<div class="col-md-3">
			                  		<input type="text" name="room_no" class="form-control" id="room_no" placeholder="Enter Room No">
			                  	</div>
			                  	<label for="inputEmail3" class="col-md-2 control-label">IPD Case</label>
								<div class="col-md-3">
			                  		<input type="text" name="ipdcase" class="form-control" id="case" placeholder="Enter IPD Case">
			                  	</div>
			                </div>
			                <div class="form-group row">
			                	<label for="inputEmail3" class="col-md-2 control-label">Consultant</label>
								<div class="col-md-3">
			                  		<input type="text" name="consultant" class="form-control" id="consultant" placeholder="Enter Consultant">
			                  	</div>
			                  	<label for="inputEmail3" class="col-md-2 control-label">Day of Surgery</label>
								<div class="col-md-3">
			                  		<input type="text" name="surgery_day" class="form-control" id="surgery_day" placeholder="Enter Surgery Day">
			                  	</div>
			                </div>
		                </div>
		                <div class="form-group row">
		                	<label for="inputEmail3" class="col-md-2 control-label">Assign Therapist</label>
		              		<div class="col-md-3">
		              			<select class="form-control select2" id="assignTherapist" name="assignTherapist">
		              				<option value="" selected disabled>Please Select</option>
		              				@if($allTherapist)
		              					@foreach($allTherapist as $thItem)
		              					<option value="{{$thItem->id}}">{{$thItem->name}}</option>
		              					@endforeach
		              				@endif
		              			</select>
		              		</div>
		              		<div class="homeCareTest" style="display: none;">
		              			<label for="inputEmail3" class="col-md-2 control-label">Branch</label>
			              		<div class="col-md-3">
			              			<select class="form-control select2" id="branch" name="branch">
			              				<option value="" selected disabled>Please Select</option>
			              				@if($allBranch)
			              					@foreach($allBranch as $brItem)
			              					<option value="{{$brItem->id}}">{{$brItem->name}}</option>
			              					@endforeach
			              				@endif
			              			</select>
			              		</div>
		              		</div>
		                </div>
	              	</div>
	                <div class="form-group row">
	                  <label for="inputEmail3" class="col-md-2 control-label">Reference Type</label>
	                  <div class="col-md-3">
	                    <select class="form-control select2 reference_type" name="reference_type">
	                    	<option value="" selected disabled>Please Select</option>
	                    	@if($referenceType)
	                    		@foreach($referenceType as $refItem)
	                    		<option value="{{$refItem->id}}">{{$refItem->name}}</option>
	                    		@endforeach
	                    	@endif
	                    </select>
	                  </div>
	                  <label class="col-md-2 control-label">Joints</label>
	                  <div class="col-md-3">
	                  	<select class="form-control select2 jointSelect" name="joints">
	                  		<option value="" selected disabled>Please Select</option>
	                  		<option value="one_joint">One Joint</option>
	          				<option value="two_joint">Two Joint</option>
	          				<option value="three_joint">Three Joint</option>
	          				<option value="neuro">Neuro</option>
	                  	</select>
	                  </div>
	                </div>
	                <!-- <div class="test4" style="display: block;"> -->
	              	<div class="form-group row">
                		<label for="inputEmail3" class="col-md-2 control-label">Appointment Date</label>
						<div class="col-md-2">
	                  		<input type="date" name="appDate" class="form-control datevalidate" required>
	                  	</div>
	                  	<label for="inputEmail3" class="col-md-2 control-label">Appointment Time</label>
						<div class="col-md-2">
	                  		<select class="form-control select2" name="appTime">
	                  			<option disabled selected value="">Please Select</option>
	                  			@if($timeSlot)
	                  				@foreach($timeSlot as $time)
	                  					<option value="{{$time->id}}">{{$time->time}}</option>
	                  				@endforeach
	                  			@endif
	                  		</select>
	                  	</div>
	                  	<label for="inputEmail3" class="col-md-2 control-label">Payment Method</label>
						<div class="col-md-2">
	                  		<select class="form-control select2" name="payment_method">
	                  			<option value="" selected disabled>Please Select</option>
	                  			<option value="package_wise">Package Wise Payment</option>
	                  			<option value="per_day_visit">Per Day Visit Payment</option>
	                  			<option value="complimentary">Complimentary</option>
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
									<option value="{{$allAmt->amount}}">{{$allAmt->amount}}</option>
									@endforeach
								@endif
							</select>
	                  	</div>
	                  	<label class="col-md-2 control-label">Consultation Name</label>
						<div class="col-md-3">
							<select class="form-control select2" name="consultation_name">
								<option disabled selected>Please Select</option>
								@if($allTherapist)
									@foreach($allTherapist as $allThe)
									<option value="{{$allThe->id}}">{{$allThe->name}}</option>
									@endforeach
								@endif
							</select>
	                  	</div>
	                </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	                <button type="submit" class="btn btn-info">Submit</button>
	                <button type="submit" class="btn btn-default">Cancel</button>
	              </div>
	              <!-- /.box-footer -->
	            </form>
          	</div>
      	</div>
 	</div>
</section>

<script type="text/javascript">
	function validate(){
		var type = $('#patienttype').val();
		var allpatient = $('#allpatient').val();
		var patientname = $('#patientname').val();
		var patientno = $('#patientno').val();
		var patientpass = $('#password').val();
		var patientemail = $('#email').val();
		var service_type = $('#service_type').val();
		var assignTherapist = $('#assignTherapist').val();
		var address = $('#address').val();
		var dob = $('#dob').val();

		if(type == 'old'){
			if(!allpatient){
				alert('Please select all patient field!!');
				$('#allpatient').focus();
				return false;
			}
		}else if(type == 'new'){
			if(!patientname){
				alert('Patient Name field is mandatory!!');
			    $("#patientname").focus();
			    return false;
			}else if(!patientno){
				alert('Patient no. field is mandatory!!');
			    $("#patientno").focus();
			    return false;
			}else if(!patientpass){
				alert('Patient password field is mandatory!!');
			    $("#password").focus();
			    return false;
			}else if(!patientemail){
				alert('Patient email field is mandatory!!');
			    $("#email").focus();
				return false;
			}else if(!service_type){
				alert('Patient Service Type is mandatory!!');
				$("service_type").focus();
				return false;
			}else if(!assignTherapist){
				alert('Assign Therapist field is mandatory!!');
				$("assignTherapist").focus();
				return false;
			}else if(!address){
				alert('Address field is mandatory!!');
				$("address").focus();
				return false;
			}else if(!dob){
				alert('DOB field is mandatory!!');
				$("dob").focus();
				return false;
			}
		}

		if(document.myForm.reference_type.value == ''){
			alert('Reference type field is mandatory!!');
			$('.reference_type').focus();
			return false;
		}else if(document.myForm.joints.value == ''){
			alert('Joint field is mandatory!!');
			$('.jointSelect').focus();
			return false;
		}else if(document.myForm.appTime.value == ''){
			alert('Appointment time is mandatory!!');
			document.myForm.appTime.focus();
			return false;
		}else if(document.myForm.payment_method.value == ''){
			alert('Payment method field is mandatory!!');
			document.myForm.payment_method.focus();
			return false;
		}else if(type == 'old'){
			if(allpatient == ''){
				alert('Please select all patient field!!');
				$('#allpatient').focus();
				return false;
			}
		}else{
			return true;
		}
	}
</script>

@endsection