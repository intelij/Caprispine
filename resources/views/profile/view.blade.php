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
              <form class="form-horizontal" method="post" action="{{url('update-profile')}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name: </label>
	              		<div class="col-md-3">
	              			<label class="control-label">{{Auth::user()->name}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email: </label>
	              		<div class="col-md-3">
	              			<label class="control-label">{{Auth::user()->email}}</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">User Type: </label>
	              		<div class="col-md-3">
	              			<label class="control-label">@if(Auth::user()->user_type == 'superadmin') Superadmin @else {{userTypeDetails(Auth::user()->user_type)->name}} @endif</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch: </label>
	              		<div class="col-md-3">
	              			<label class="control-label">{{branchDetails(Auth::user()->branch)->name}}</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">State</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control state" id="val-skill" name="state" data-url="{{url('/myform/ajax/')}}" required>
	              				<option selected disabled>Please select</option>
	              				@foreach($states as $state)
	              				<option value="{{$state->id}}" {{Auth::user()->state == $state->id ? 'selected="selected"' : ''}}>{{$state->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">City</label>
	              		<div class="col-md-3 font-normal">
	              			<select class="form-control" name="city">
	              				<option selected disabled>Please Select</option>
	              				@foreach($cities as $city)
	              				<option value="{{$city->id}}" {{Auth::user()->city == $city->id ? 'selected="selected"' : ''}}>{{$city->name}}</option>
	              				@endforeach
	              			</select>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Profile Pic</label>
	              		<div class="col-md-3">
	              			<input type="file" name="profile_pic" id="imageFile" class="form-control">
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Mobile No: </label>
	              		<div class="col-md-3">
	              			<input type="number" class="form-control" name="mobile" value="{{Auth::user()->mobile}}">
	              		</div>
	              	</div>
	              	<div class="form-group row">
		              	@if(!empty(Auth::user()->profile_pic))
		              		<img src="{{PROFILE_PIC.Auth::user()->profile_pic}}" id="profile_picture" alt="" width="100" height="100" style="border-radius: 10px;padding-left: 6px;">
		              	@else
		              		<img src="{{DEFAULT_PROFILE}}" alt="" width="100" id="profile_picture" height="100" style="border-radius: 10px;padding-left: 6px;">
		              	@endif
		            </div>
	              	<!-- /.box-body -->
		              <div class="box-footer">
		              	<button type="submit" class="btn btn-primary">Update</button>
	                    <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
		              </div>
		            <!-- /.box-footer -->
	              </div>
	          </form>
          	</div>
      	</div>
 	</div>
</section>

@endsection