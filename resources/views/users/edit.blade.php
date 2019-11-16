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
            <form class="form-horizontal" method="post" action="{{url('update-user/')}}/{{$allData->id}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="uname" value="{{$allData->name}}" class="form-control" placeholder="Enter Name">
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="email" name="email" value="{{$allData->email}}" class="form-control" placeholder="Enter Email" readonly>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">User Type</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="userType" class="form-control select2">
	              				<option value="" selected disabled>Please Select</option>
	              				@if($allUserType)
	              					@foreach($allUserType as $uItem)
	              					<option value="{{$uItem->id}}" {{ $allData->user_type == $uItem->id ? 'selected="selected"' : '' }}>{{$uItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Mobile</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="number" name="mobile" value="{{$allData->mobile}}" class="form-control" placeholder="Enter Mobile" readonly>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Gender</label>
	              		<div class="col-md-3 font-normal">	              			
	              			<input type="radio" name="gender" value="male" {{ $allData->gender == 'male' ? 'checked' : '' }}><label>Male</label>
		                  	<input type="radio" name="gender" value="female" {{ $allData->gender == 'female' ? 'checked' : '' }}><label>Female</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<select name="status" class="form-control select2">
	              				<option selected disabled>Please Select</option>
	              				<option value="active" {{ $allData->status == 'active' ? 'selected="selected"' : '' }}>Active</option>
	              				<option value="inactive" {{ $allData->status == 'inactive' ? 'selected="selected"' : '' }}>Inactive</option>
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">State</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control state" id="val-skill" name="state" data-url="{{url('/myform/ajax/')}}">
	              				<option selected disabled>Please select</option>
	              				@foreach($states as $state)
	              				<option value="{{$state->id}}" {{$allData->state == $state->id ? 'selected="selected"' : ''}}>{{$state->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">City</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control" name="city">
	              				<option selected disabled>Please Select</option>
	              				@foreach($cities as $city)
	              				<option value="{{$city->id}}" {{$allData->city == $city->id ? 'selected="selected"' : ''}}>{{$city->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control" name="branch" required>
	              				<option selected disabled>Please Select</option>
	              				@if($branch)
	              					@foreach($branch as $bItem)
	              					<option value="{{$bItem->id}}" {{$allData->branch == $bItem->id ? 'selected="selected"' : ''}}>{{$bItem->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Timing</label>
	              		<div class="col-md-3 font-normal">
	              			<input type="text" name="timing" value="{{$allData->timing}}" class="form-control" placeholder="10:00 AM to 6:00 PM">
	              		</div>
	              	</div>
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