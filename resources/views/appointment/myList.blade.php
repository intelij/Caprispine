@extends('layouts.apps')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
    	<div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
	                <tr>
	                  <th>Patient Name</th>
	                  <th>Phone No</th>
	                  <!-- <th>Appointment Type</th> -->
	                  <th>Status</th>
	                  <th>Created At</th>
	                  <th>Action</th>
	                </tr>
                </thead>
                <tbody>
	                @if($allData)
	                	@foreach($allData as $item)
	                		<tr>
	                			<td>{{userDetails($item->user_id)->name}}</td>
	                			<td>{{userDetails($item->user_id)->mobile}}</td>
	                			<!-- <td>{{$item->appointment_type}}</td> -->
	                			<td>
                				@if($item->status == 'approved')
                					<p style="color: green;font-weight: bold;">{{ucfirst($item->status)}}</p>
								@elseif($item->status == 'cancel')
									<p style="color: red;font-weight: bold;">{{ucfirst($item->status)}}</p>
								@elseif($item->status == 'completed')
									<p style="color: #46b8da;font-weight: bold;">{{ucfirst($item->status)}}</p>
								@else
									<p style="font-weight: bold;">{{ucfirst($item->status)}}</p>
								@endif		                			
	                			</td>
	                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
	                			<td>
	                				<a title="View Appointment" href="{{url('view-appointment/')}}/{{$item->id}}" class="btn bg-maroon"><i class="fa fa-eye" aria-hidden="true"></i></a>
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