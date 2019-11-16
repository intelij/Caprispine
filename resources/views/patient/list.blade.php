@extends('layouts.apps')
@section('content')

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <!-- Horizontal Form -->
      <div class="box box-info">
        <div class="box-header with-border">
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal formloc" id="" method="post" action="{{url('search-patients')}}"  enctype="multipart/form-data">
        {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Patient Name</label>
              <div class="col-md-3">
                <select class="form-control select2" name="patientName">
                  <option value="" selected disabled>Please Select</option>
                  @if($allPatient)
                    @foreach($allPatient as $allpat)
                    <option value="{{$allpat->id}}">{{$allpat->name}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">Therapist Name</label>
              <div class="col-md-3">
                <select class="form-control select2" name="therapistName">
                  <option value="" selected disabled>Please Select</option>
                  @if($therapistData)
                    @foreach($therapistData as $allTh)
                    <option value="{{$allTh->id}}">{{$allTh->name}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">From Date</label>
              <div class="col-md-3">
                 <input type="date" class="form-control" name="to_date" value="" placeholder="">
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">To Date</label>
              <div class="col-md-3">
                 <input type="date" class="form-control" name="from_date" value="" placeholder="">
              </div>
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
      <!-- /.box -->
      <div class="box">
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>Registration Id</th>
                  <th>User Name</th>
                  <th>User Type</th>
                  @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
	                <th>Email Id</th>
	                <th>Password</th>
	                <th>Phone No</th>
                  @endif
                  <th>Branch</th>
                  <th>Therapist</th>
                  <th>Status</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($allData)
                	@foreach($allData as $item)
                		<tr>
                			<td>{{$item->registration_no}}</td>
                			<td>{{$item->name}}</td>
                			<td>{{userTypeDetails($item->user_type)->name}}</td>
                			@if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
                			<td>{{$item->email}}</td>
                			<td>{{$item->confirmpassword}}</td>
                			<td>{{$item->mobile}}</td>
                			@endif
                			<td>@if($item->branch) {{branchDetails($item->branch)->name}} @endif</td>
                			<td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                			<td>{{ucfirst($item->status)}}</td>
                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                			<td>
                				@if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
	                				<a title="View" href="{{url('view-patient/')}}/{{$item->id}}" class="btn bg-maroon"><i class="fa fa-eye" aria-hidden="true"></i></a>
	                				<a title="Edit" href="{{url('edit-patient/')}}/{{$item->id}}" class="btn btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
	                			@endif
	                			@if($item->status == 'active')
	                				<a title="Therapist Assignment" data-toggle="modal" data-target="#modal-assignment" data-id="{{$item->id}}" href="#" class="btn bg-purple therapistAssigned"><i class="fa fa-tasks"></i></a>
	                			@endif
	                			@if($item->flag == 'true')
	                				<a title="All Visit Report" href="{{url('visit-details-report')}}/{{$item->id}}" class="btn btn-success" target="_blank">Visit Report</a>
	                			@endif
                			</td>
                		</tr>
                	@endforeach
                @endif
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>

<!-- Modal -->
<div class="modal fade" id="modal-assignment">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Therapist Assignment</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('assign-therapist')}}" enctype="multipart/form-data">
    	{{ csrf_field() }}
          <div class="box-body">
          	<div class="form-group">
          		<input type="text" name="userId" class="userId" value="" style="display: none;">
          		<label for="inputEmail3" class="col-md-5 control-label">Therapist (Doctors) </label>
          		<div class="col-md-5">
          			<select name="therapistId" class="form-control select2" id="assignselectedTherapist" data-url="{{url('check-attendance-of-therapist')}}" required>
          				<option disabled selected>Please Select</option>
          				@if($therapistData)
          					@foreach($therapistData as $thData)
          					<option value="{{$thData->id}}">{{$thData->name}} @if($thData->branch) ( {{branchDetails($thData->branch)->name}} ) @endif</option>
          					@endforeach
          				@endif
          			</select>
          		</div>
          	</div>
          </div>
          <!-- /.box-body -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success pull-left">Assign</button>
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
<!-- /.modal -->
@endsection