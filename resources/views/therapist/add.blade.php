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
            <form class="form-horizontal" method="post" role="form" action="{{url('save-therapist')}}" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="uname" class="form-control" placeholder="Enter Name" required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="email" name="email" class="form-control" placeholder="Enter Email" required>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Mobile</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="number" name="mobile" class="form-control patientMobileNo" data-url="{{url('check-duplicateNo')}}" placeholder="Enter Mobile" required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Gender</label>
	              		<div class="col-md-3 font-normal">	              			
	              			<input type="radio" name="gender" value="male" checked><label>Male</label>
		                  	<input type="radio" name="gender" value="female" ><label>Female</label>
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
	              		<label for="inputEmail3" class="col-md-3 control-label">Service</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control therapistService select2" id="val-skill" name="service">
	              				<option value="" selected disabled>Please select</option>
	              				@foreach($service as $srv)
	              				<option value="{{$srv->id}}">{{$srv->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              		<div class="textBox1" style="display: none;">
	              			<label for="inputEmail3" class="col-md-3 control-label">Areas of Home Visit</label>
		              		<div class="col-md-3 font-normal">
		              			<input type="text" name="areaHomeVisit" value="" class="form-control" placeholder="Enter Areas of Home Visit">
		              		</div>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="status" class="form-control select2">
	              				<option value="" selected disabled>Please Select</option>
	              				<option value="active">Active</option>
	              				<option value="inactive">Inactive</option>
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Timing</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="toTime" class="form-control timepicker" require>To
	              			<input type="text" name="fromTime" class="form-control timepicker" require>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="branch" class="form-control select2">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($branch)
	              					@foreach($branch as $bItem)
	              					<option value="{{$bItem->id}}">{{$bItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Password</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="password" name="password" class="form-control" placeholder="Enter Password">
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Base Amount</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="base_commision" class="form-control" placeholder="Enter Base Amount %">
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Date of Birth</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="date" name="dob" class="form-control" required>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Aadhar Card</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="file" name="doc1" id="docfile1" class="form-control" required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Degree</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="file" name="doc2" id="docfile2" class="form-control">
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Profile Picture</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="file" name="profile_pic" id="imageFile" class="form-control" required>
	              		</div>
	              	</div>
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	              	<button type="submit" class="btn btn-primary">Submit</button>
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
		if(document.myForm.branch.value == ''){
			alert('Branch field is mandatory!!');
			document.myForm.branch.focus();
			return false;
		}else if(document.myForm.status.value == ''){
			alert('Status field is mandatory!!');
			document.myForm.status.focus();
			return false;
		}else if(document.myForm.base_commision.value == ''){
			alert('Base Commission field is mandatory!!');
			document.myForm.base_commision.focus();
			return false;
		}else if(document.myForm.password.value == ''){
			alert('Password field is mandatory!!');
			document.myForm.password.focus();
			return false;
		}else if(document.myForm.service.value == ''){
			alert('Service field is mandatory!!');
			document.myForm.service.focus();
			return false;
		}else{
			return true;
		}
	}
</script>
@endsection