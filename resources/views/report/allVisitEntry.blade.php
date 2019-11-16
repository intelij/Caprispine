@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
        	<div class="box box-info">
        		<div class="box-header with-border">
	              <h3 class="box-title">Total Amount : <b>{{$allAmount}}</b>, Extra Amount : <b>{{$extraAmt}}</b>, Total AV : <b>{{$totalAV}}</b>, Total AW : <b>{{$totalAW}}</b></h3>
	              <!-- <a href="{{ URL::previous() }}" class="btn btn-info" style="float: right;">Go Back</a> -->
	            </div>
        		<div class="box-body">
	              <table id="example1" class="table table-bordered table-striped dataTable">
	                <thead>
		                <tr>
		                  <th>S.No</th>
		                  <th>Patient Name</th>
		                  <th>Therapist Name</th>
		                  <th>Package Type</th>
		                  <th>Booked Date</th>
		                  <th>Booked Time</th>
		                  <th>In Time</th>
		                  <th>Out Time</th>
		                  <th>Difference</th>
		                  <th>Visit Type</th>
		                  <th>Amount</th>
	                      <th>Extra Amount</th>
	                      <th>Total Seats</th>
	                      <th>No of Seats</th>
		                  <th>Remaining Seats</th>
		                  <th>Status</th>
		                </tr>
	                </thead>
	                <tbody>
	                	@if($allData)
		                	@foreach($allData as $item)
			                  <tr>
			                	<td>{{$no++}}</td>
	                			<td>@if($item->appointment_id) {{userDetails(appointmentDetails($item->appointment_id)->user_id)->name}} @endif</td>
	                			<td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
	                			<td>
	                				@if($item->type == 1)
	                					Per day visit
	                				@else
	                					Package wise visit
	                				@endif
	                			</td>
	                			<td>{{date("d-M-Y", strtotime($item->app_booked_date))}}</td>
	                			<td>{{$item->app_booked_time}}</td>
	                			<td>{{$item->in_time}}</td>
	                			<td>{{$item->out_time}}</td>
		                        <td>
		                          @if(!empty($item->in_time) && !empty($item->out_time))
		                            @php
		                              $startTime = new DateTime($item->in_time);
		                              $endTime = new DateTime($item->out_time);
		                              $getDiff = $startTime->diff($endTime);
		                            @endphp
		                            {{$getDiff->format("%H:%I:%S")}}
		                          @else
		                            -
		                          @endif
		                        </td>
		                        <td>{{$item->visit_type}}</td>
			                			<td>@if($item->amount) {{$item->amount}}/- @endif</td>
		                        <td>@if($item->extra_amount) {{$item->extra_amount}}/- @endif</td>
		                        <td>@if($item->total_seats) {{$item->total_seats}} @endif</td>
		                        <td>@if($item->no_of_seats) {{$item->no_of_seats}} @endif</td>
	                			<td>@if($item->due_days) {{$item->due_days}} @else 0 @endif</td>
	                			<td>
	                				@if($item->rating != '')
			                          <small class="label label-danger"><i class="fa fa-clock-o"></i> out</small>
			                        @elseif($item->in_time != '')
			                          <small class="label label-success"><i class="fa fa-clock-o"></i> come</small>
			                        @else
			                          <small class="label label-warning"><i class="fa fa-clock-o"></i> waiting</small>
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