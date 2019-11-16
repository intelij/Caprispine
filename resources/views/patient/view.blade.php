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
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{$allData->name}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{$allData->email}} </label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">User Type</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{userTypeDetails($allData->user_type)->name}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Mobile</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{$allData->mobile}}</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Gender</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{ucfirst($allData->gender)}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{ucfirst($allData->status)}}</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">State</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if($allData->state){{getState($allData->state)->name}}@endif</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">City</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if($allData->city){{getCity($allData->city)->name}}@endif</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Password</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{$allData->confirmpassword}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if($allData->branch) {{branchDetails($allData->branch)->name}} @endif</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Therapist (Doctor)</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if($allData->therapist_id) {{userDetails($allData->therapist_id)->name}} @endif</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Date of Birth</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{$allData->dob}}</label>
	              		</div>
	              	</div>
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
	              </div>
	              <!-- /.box-footer -->
          	</div>
      	</div>
 	</div>
</section>

@endsection