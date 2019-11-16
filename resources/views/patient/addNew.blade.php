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
            <form class="form-horizontal" method="post" role="form" action="{{url('save-patient')}}" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="uname" class="form-control" placeholder="Enter Name" required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="email" name="uemail" class="form-control" placeholder="Enter Email" required>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">User Type</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="userType" class="form-control select2">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($allUserType)
	              					@foreach($allUserType as $uItem)
	              					<option value="{{$uItem->id}}">{{$uItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Mobile</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="number" name="mobile" class="form-control patientMobileNo" data-url="{{url('check-duplicateNo')}}" placeholder="Enter Mobile" required>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Gender</label>
	              		<div class="col-md-3 font-normal">	              			
	              			<input type="radio" name="gender" value="male" checked><label>Male</label>
		                  	<input type="radio" name="gender" value="female" ><label>Female</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="status" class="form-control select2">
	              				<option value="" selected disabled>Please Select</option>
	              				<option value="active">Active</option>
	              				<option value="inactive">Inactive</option>
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">State</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control state" id="val-skill" name="state" data-url="{{url('/myform/ajax/')}}" required>
	              				<option selected disabled>Please select</option>
	              				@foreach($states as $state)
	              				<option value="{{$state->id}}">{{$state->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">City</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control" name="city">
	              				<option selected disabled>Please Select</option>
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Address</label>
	              		<div class="col-md-3 font-normal">
	              			<textarea class="form-control" name="address" required></textarea>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Patient Type</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control select2" name="patient_type">
	              				<option value="" disabled selected>Please Select</option>
	              				<option value="complimantory">Complimantory Patient</option>
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Patient Service Type</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control select2" name="service_type">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($patientService)
	              					@foreach($patientService as $pItem)
	              					<option value="{{$pItem->id}}">{{$pItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control select2" name="branch">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($branch)
	              					@foreach($branch as $bItem)
	              					<option value="{{$bItem->id}}">{{$bItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              	</div>
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	              	<button type="submit" class="btn btn-primary">Add Patient</button>
                    <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
	              </div>
	              <!-- /.box-footer -->
	          </form>
          	</div>
      	</div>
 	</div>
</section>
<script type="text/javascript">
	function validate(){
		if(document.myForm.userType.value == ''){
			alert('User type field is mandatory!!');
			document.myForm.userType.focus();
			return false;
		}else if(document.myForm.status.value == ''){
			alert('Status field is mandatory!!');
			document.myForm.status.focus();
			return false;
		}else if(document.myForm.service_type.value == ''){
			alert('Service type field is mandatory!!');
			document.myForm.service_type.focus();
			return false;
		}else if(document.myForm.branch.value == ''){
			alert('Branch field is mandatory!!');
			document.myForm.branch.focus();
			return false;
		}else{
			return true;
		}
	}
</script>
@endsection