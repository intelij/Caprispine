@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-12">
        	<div class="box">
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped">
	                <thead>
		                <tr>
                      <th>Picture</th>
                      <th>Registration Id</th>
		                  <th>Name</th>
		                  <th>Date of Birth</th>
                      @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
		                  <th>Email Id</th>
		                  <th>Password</th>
		                  <th>Phone No</th>
                      @endif
		                  <th>Timing</th>
		                  <th>Branch</th>
		                  <th>Service</th>
		                  <th>Base Amount %</th>
		                  <!-- <th>Penalty</th> -->
		                  <th>Status</th>
		                  <th>Created At</th>
		                  <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
		                @if($allData)
		                	@foreach($allData as $item)
		                		<tr>
                          <th>
                            @if($item->profile_pic != '')
                              <img src="{{PROFILE_PIC.$item->profile_pic}}" alt="" width="50" height="50" style="border-radius: 100px;">
                            @else
                              <img src="{{DEFAULT_PROFILE}}" alt="" width="50" height="50" style="border-radius: 100px;">
                            @endif
                          </th>
                          <td>{{$item->registration_no}}</td>
		                			<td>{{$item->name}}</td>
		                			<td>{{date("d-M-Y", strtotime($item->dob))}}</td>
                          @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
		                			<td>{{$item->email}}</td>
		                			<td>{{$item->confirmpassword}}</td>
		                			<td>{{$item->mobile}}</td>
                          @endif
		                			<td>{{$item->timing}}</td>
		                			<td>@if($item->branch) {{branchDetails($item->branch)->name}} @endif</td>
		                			<td>@if($item->service_type) {{serviceDetails($item->service_type)->name}} @endif</td>
		                			<td>@if($item->base_commision) {{$item->base_commision}} % @endif</td>
		                			<!-- <td>@if(totalDailyPenalty($item->id)) {{totalDailyPenalty($item->id)}} Rs @endif</td> -->
		                			<td>
                            @if($item->status == 'active')
                              <small class="label label-success"> {{ucfirst($item->status)}}</small>
                            @elseif($item->status == 'inactive')
                              <small class="label label-danger"> {{ucfirst($item->status)}}</small>
                            @endif
                            
                          </td>
		                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
		                			<td>
                            @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
                              @if($item->status == 'active')
  		                				  <a title="View" href="{{url('view-therapist/')}}/{{$item->id}}" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i></a>
                              @endif
  		                				<a title="Edit" href="{{url('edit-therapist/')}}/{{$item->id}}" class="btn btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            @endif
                            @if($item->status == 'active')
  		                				<a title="Add Penalty" href="#" data-toggle="modal" data-target="#modal-penalty" data-id="{{$item->id}}" class="btn btn-danger therapistPenalty"><i class="fa fa-ban"></i></a>
  		                				<a title="All Penalty" href="{{url('all-penalty/')}}/{{$item->id}}" class="btn btn-danger"><i class="fa fa-history"></i></a>
                              @php
                                $attendanceData = checkAttendance($item->id);
                              @endphp
                              @if(empty($attendanceData))
                                <a title="Mark Attandance" href="{{url('mark-attandance')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to mark attendance?')" class="btn btn-info"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
                              @endif
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
    </div>
</section>

<!-- Modal Per Day Visit -->
<div class="modal fade" id="modal-penalty">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Penalty</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('therapist-penalty')}}" enctype="multipart/form-data">
    	    {{ csrf_field() }}
    	    <input type="hidden" name="therapistId" class="therapistId" value="">
          <div class="box-body">
          	<div class="form-group row">
          		<label for="inputEmail3" class="col-md-3 control-label">Penalty</label>
          		<div class="col-md-3">
          			<select class="form-control penaltyId select2" data-url="{{url('penalty-amount')}}" name="penaltyId">
          				<option selected disabled>Please Select</option>
      				@if($penalty)
      					@foreach($penalty as $pItem)
      					<option value="{{$pItem->id}}">{{$pItem->name}}</option>
      					@endforeach
      				@endif
          			</select>
          		</div>
          		<div class="condition1" style="display: none;">
          			<label class="col-md-3 control-label">Late Minutes</label>
          			<div class="col-md-3">
          				<input type="number" name="late_minutes" class="form-control late_coming" placeholder="Enter Minutes">
          			</div>
          		</div>
          	</div>
          	<div class="form-group row">
	      		<label for="inputEmail3" class="col-md-3 control-label">Amount</label>
	      		<div class="col-md-3">
	            	<input type="text" name="amount" class="form-control penaltyAmt" placeholder="Penalty Amount" readonly="">
	      		</div>
          	</div>
          </div>
          <!-- /.box-body -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success pull-left">Submit</button>
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

<!-- Modal Mark Attandance -->
<!-- <div class="modal fade" id="modal-attandance">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Mark Attandance</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('mark-attandance')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Date</label>
              <div class="col-md-4">
                <input type="date" name="date" class="form-control calendarDate datevalidate" placeholder="Enter Date" required>
                <input type="text" name="therapistId" class="therapistId" value="" style="display: none;">
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Present/Apsent</label>
              <div class="col-md-4">
                  <select class="form-control getAttandance" data-url="{{url('getCheckAttendance')}}" name="attandance" required>
                    <option selected disabled>Please Select</option>
                    <option value="present">Present</option>
                    <option value="apsent">Apsent</option>
                  </select>
              </div>
            </div>
          </div>
           /.box-body -->
          <!-- <div class="modal-footer">
            <button type="submit" class="btn btn-success pull-left">Submit</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div> -->
          <!-- /.box-footer -->
        <!-- </form>
      </div>
    </div> -->
    <!-- /.modal-content -->
  <!-- </div> -->
  <!-- /.modal-dialog -->
<!-- </div> -->
<!-- /.modal -->

@endsection