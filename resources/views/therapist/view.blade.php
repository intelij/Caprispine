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
              			<label class="">{{$allData->email}}</label>
              		</div>
              	</div>
              	<div class="form-group row">
              		<label for="inputEmail3" class="col-md-3 control-label">Mobile</label>
              		<div class="col-md-3 font-normal">
              			<label class="">{{$allData->mobile}}</label>
              		</div>
              		<label for="inputEmail3" class="col-md-3 control-label">Gender</label>
              		<div class="col-md-3 font-normal">	              			
              			<label class="">{{$allData->gender}}</label>
              		</div>
              	</div>
              	<div class="form-group row">
              		<label for="inputEmail3" class="col-md-3 control-label">State</label>
              		<div class="col-md-3 font-normal">
              			<label class="">@if($allData->state) {{getState($allData->state)->name}} @endif</label>
              		</div>
              		<label for="inputEmail3" class="col-md-3 control-label">City</label>
              		<div class="col-md-3 font-normal">
              			<label class="">@if($allData->city) {{getCity($allData->city)->name}} @endif</label>
              		</div>
              	</div>
              	<div class="form-group row">
              		<label for="inputEmail3" class="col-md-3 control-label">Status</label>
              		<div class="col-md-3 font-normal">
              			<label class="">{{$allData->status}}</label>
              		</div>
              		<label for="inputEmail3" class="col-md-3 control-label">Timing</label>
              		<div class="col-md-3 font-normal">
              			<label class="">{{$allData->timing}}</label>
              		</div>
              	</div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-3 control-label">Branch</label>
                  <div class="col-md-3 font-normal">
                    <label class="">{{branchDetails($allData->branch)->name}}</label>
                  </div>
                  <label for="inputEmail3" class="col-md-3 control-label">Password</label>
                  <div class="col-md-3 font-normal">
                    <label class="">{{$allData->confirmpassword}}</label>
                  </div>
                </div>
              	<div class="form-group row">
              		<label for="inputEmail3" class="col-md-3 control-label">Aadhar Card</label>
              		<div class="col-md-3 font-normal">
              			@if($allData->adhar_card != '')
                    	<a href="{{THERAPIST_DOC.$allData->adhar_card}}" target="_blank">Download</a>
                    @endif
              		</div>
              		<label for="inputEmail3" class="col-md-3 control-label">Degree</label>
              		<div class="col-md-3 font-normal">
              			@if($allData->degree != '')
                    	<a href="{{THERAPIST_DOC.$allData->degree}}" target="_blank">Download</a>
                    @endif
              		</div>
              	</div>
                <div class="form-group row">
                  <label class="col-md-3 control-label">Penalty</label>
                  <div class="col-md-3 font-normal">
                    <label>@if(totalDailyPenalty($allData->id)) {{totalDailyPenalty($allData->id)}} Rs @endif</label>
                  </div>
                  <label for="inputEmail3" class="col-md-3 control-label">Date of Birth</label>
                  <div class="col-md-3 font-normal">
                    <label class="">{{$allData->dob}}</label>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-3 control-label">Service</label>
                  <div class="col-md-3 font-normal">
                    <label class="">@if($allData->service_type) {{serviceDetails($allData->service_type)->name}} @endif</label>
                  </div>
                </div>
              	<div class="form-group row">
              		<label for="inputEmail3" class="col-md-3 control-label">Profile Picture</label>
              		<div class="col-md-3 font-normal">
	              		@if($allData->profile_pic != '')
	                    	<img alt="" src="{{PROFILE_PIC.$allData->profile_pic}}" width="50" height="50">
	                    @endif
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