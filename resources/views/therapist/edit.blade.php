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
            <form class="form-horizontal" method="post" action="{{url('update-therapist/')}}/{{$allData->id}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="uname" class="form-control" value="{{$allData->name}}" placeholder="Enter Name" required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="email" name="email" class="form-control" value="{{$allData->email}}" placeholder="Enter Email" readonly required>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Mobile</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="number" name="mobile" class="form-control" value="{{$allData->mobile}}" placeholder="Enter Mobile" readonly required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Gender</label>
	              		<div class="col-md-3 font-normal">	              			
	              			<input type="radio" name="gender" value="male" {{ $allData->gender == 'male' ? 'checked' : '' }}><label>Male</label>
		                  	<input type="radio" name="gender" value="female" {{ $allData->gender == 'female' ? 'checked' : '' }}><label>Female</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">State</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control state" id="val-skill" name="state" data-url="{{url('/myform/ajax/')}}" required>
	              				<option selected disabled>Please select</option>
	              				@foreach($states as $state)
	              				<option value="{{$state->id}}" {{ $state->id == $allData->state ? 'selected="selected"' : '' }}>{{$state->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">City</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control" name="city">
	              				<option selected disabled>Please Select</option>
	              				@foreach($cities as $city)
	              				<option value="{{$city->id}}" {{ $city->id == $allData->city ? 'selected="selected"' : '' }}>{{$city->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="status" class="form-control select2" required>
	              				<option selected disabled>Please Select</option>
	              				<option value="active" {{ $allData->status == 'active' ? 'selected="selected"' : '' }}>Active</option>
	              				<option value="inactive" {{ $allData->status == 'inactive' ? 'selected="selected"' : '' }}>Inactive</option>
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Timing</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="timing" value="{{$allData->timing}}" class="form-control">
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="branch" class="form-control select2" required>
	              				<option selected disabled>Please Select</option>
	              				@if($branch)
	              					@foreach($branch as $bItem)
	              					<option value="{{$bItem->id}}" {{ $allData->branch == $bItem->id ? 'selected="selected"' : '' }}>{{$bItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Date of Birth</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="date" name="dob" class="form-control" value="{{$allData->dob}}" required>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Base Amount</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="base_commision" class="form-control" value="{{$allData->base_commision}}" placeholder="Enter Base Amount %" required>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">User Type</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control select2" name="userType">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($allUserType)
		              				@foreach($allUserType as $userVal)
		              					<option value="{{$userVal->id}}" {{ $allData->user_type == $userVal->id ? 'selected="selected"' : '' }}>{{$userVal->name}}</option>
		              				@endforeach
		              			@endif
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Service</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control select2" name="serviceType">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($allService)
	              					@foreach($allService as $service)
	              					<option value="{{$service->id}}" {{ $allData->service_type == $service->id ? 'selected="selected"' : '' }}>{{$service->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Aadhar Card</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="file" name="doc1" id="docfile1" class="form-control">
	              		</div>
	              		@if($allData->adhar_card != '')
	                    	<a href="{{THERAPIST_DOC.$allData->adhar_card}}" target="_blank">Download</a>
	                    	<input type="hidden" name="old_adhar_card" value="{{$allData->adhar_card}}">
	                    @endif
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Degree</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="file" name="doc2" id="docfile2" class="form-control">
	              		</div>
	              		@if($allData->degree != '')
	                    	<a href="{{THERAPIST_DOC.$allData->degree}}" target="_blank">Download</a>
	                    	<input type="hidden" name="old_degree" value="{{$allData->degree}}">
	                    @endif
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Profile Picture</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="file" name="profile_pic" id="imageFile" class="form-control">
	              			<input type="hidden" value="{{$allData->profile_pic}}" name="old_Profile_pic">
	              		</div>
	              	</div>
	              		<img src="{{PROFILE_PIC.$allData->profile_pic}}" id="profile_picture" alt="" width="90" height="90" style="border-radius: 10px;">
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	              	<button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
	              </div>
	              <!-- /.box-footer -->
	          </form>
          	</div>
      	</div>
 	</div>
</section>

@endsection