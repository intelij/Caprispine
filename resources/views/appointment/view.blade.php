@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Appointment</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Appointment Type</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{$allData->appointment_type}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Patient Type</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{ucfirst($allData->patient_type)}} </label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Patient Name</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{userDetails($allData->user_id)->name}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Patient Mobile</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{userDetails($allData->user_id)->mobile}}</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">{{ucfirst($allData->status)}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Appointment Service Type</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if($allData->app_service_type) {{ucfirst(serviceDetails($allData->app_service_type)->name)}} @endif</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Therapist (Doctor)</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if(userDetails($allData->user_id)->therapist_id) {{userDetails(userDetails($allData->user_id)->therapist_id)->name}} @endif</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Branch</label>
	              		<div class="col-md-3 font-normal">
	              			<label class="">@if(userDetails($allData->user_id)->branch) {{branchDetails(userDetails($allData->user_id)->branch)->name}} @endif</label>
	              		</div>
	              	</div>
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	              	{{-- @if($allData->status != 'complete')
	              		<!-- <a href="{{url('complete-appointment/')}}/{{$allData->id}}" class="btn btn-primary">Complete</a> -->
	              	@endif --}}
                        <!-- <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a> -->
	              </div>
	              <!-- /.box-footer -->
          	</div>
      	</div>
 	</div>
</section>
@endsection