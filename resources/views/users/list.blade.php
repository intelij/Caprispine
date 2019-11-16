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
		                  <th>Registration Id</th>
		                  <th>User Name</th>
		                  <th>User Type</th>
		                  @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
		                  <th>Email Id</th>
		                  <th>Password</th>
		                  <th>Phone No</th>
		                  @endif
		                  <th>Branch</th>
		                  <th>Timing</th>
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
		                			<td>{{$item->timing}}</td>
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
			                					<a title="View" href="{{url('view-user/')}}/{{$item->id}}" class="btn bg-maroon"><i class="fa fa-eye" aria-hidden="true"></i></a>
			                				@endif
			                				<a title="Edit" href="{{url('edit-user/')}}/{{$item->id}}" class="btn btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
			                			@endif
		                			@php
		                              $attendanceData = checkAttendance($item->id);
		                            @endphp
		                            @if($item->status == 'active')
			                            @if(empty($attendanceData))
			                				<a title="Mark Attandance" href="{{url('mark-attandance-staff')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to mark attendance?')" class="btn btn-info"><i class="fa fa-clock-o" aria-hidden="true"></i></a>
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

@endsection