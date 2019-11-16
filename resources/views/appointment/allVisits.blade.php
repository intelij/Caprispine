@extends('layouts.apps')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
	<!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Search Visits</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal formloc" method="post" action="{{url('search-all-visits')}}">
            	{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Therapist Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="therapistName">
                    	<option value="" selected disabled>Please Select</option>
                    	@if($allTherapist)
                    		@foreach($allTherapist as $allTh)
                    		<option value="{{$allTh->id}}">{{$allTh->name}}</option>
                    		@endforeach
                    	@endif
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Patient Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="patientName">
                    	<option value="" selected disabled>Please Select</option>
                    	@if($allPatient)
                    		@foreach($allPatient as $allPa)
                    		<option value="{{$allPa->id}}">{{$allPa->name}}</option>
                    		@endforeach
                    	@endif
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Month</label>
                  <div class="col-md-3">
                     <select class="form-control select2" name="monthName">
                       <option value="" selected disabled>Please Select</option>
                       <option value="1">January</option>
                       <option value="2">February</option>
                       <option value="3">March</option>
                       <option value="4">April</option>
                       <option value="5">May</option>
                       <option value="6">June</option>
                       <option value="7">July</option>
                       <option value="8">August</option>
                       <option value="9">September</option>
                       <option value="10">October</option>
                       <option value="11">November</option>
                       <option value="12">December</option>
                     </select>
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Year</label>
                  <div class="col-md-3">
                     <select name="yearName" class="form-control select2">
                       <option value="" selected disabled>Please Select</option>
                       @php
                        $earliest_year = 2019;
                        $latest_year = date('Y');
                        $matchYear = range($earliest_year, $latest_year);
                        foreach($matchYear as $year){ @endphp
                        <option value="@php $year @endphp">{{$year}}</option>
                        @php }
                       @endphp
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
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Appointment Type</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="appointmentType">
                      <option value="" disabled selected>Please Select</option>
                      <option value="2">Package Wise</option>
                      <option value="1">Per day Wise</option>
                      <option value="3">Complimentary</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Service Type</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="serviceType">
                      <option value="" disabled selected>Please Select</option>
                      @if($allServiceType)
                        @foreach($allServiceType as $services)
                          <option value="{{$services->id}}">{{$services->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Submit</button>
                <button type="submit" class="btn btn-default">Cancel</button>
                <!-- /<h3 class="box-title" style="float: right;"><a title="Download Report" href="{{url('daily-visit-list-export')}}" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Export</a></h3> -->
              </div>
              <!-- /.box-footer -->
            </form>
        </div>
          <!-- /.box -->
    	  <div class="box box-info">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped example2">
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
	                  <th>Amount</th>
                    <th>Visit Type</th>
                    <th>Extra Amount</th>
                    <th>Total Seats</th>
                    <th>No of Seats</th>
	                  <th>Remaining Seats</th>
	                  <th>Status</th>
	                  <th>Action</th>
	                </tr>
                </thead>

                <tbody>
	                @if($allData)
	                	@foreach($allData as $item)
	                		<tr>
                        <td>{{$no++}}</td>
	                			<td>
                                  @php
                                    $appDataServiceType = appointmentDetails($item->appointment_id)->app_service_type;
                                  @endphp
                                  @if($item->appointment_id) {{userDetails(appointmentDetails($item->appointment_id)->user_id)->name}} @endif
                                  @if(!empty($appDataServiceType) && ($appDataServiceType == 7))
                                    <img src="{{asset('public/upload/images/ipd2gif.gif')}}" alt="" width="60" height="30">
                                  @elseif(!empty($appDataServiceType) && (($appDataServiceType == 9) || ($appDataServiceType == 8) || ($appDataServiceType == 1)))
                                    <img src="{{asset('public/upload/images/homeCare1.gif')}}" alt="" width="100" height="50">
                                  @endif
                                  @if($item->type == '3')
                                    <img title="Complimentary Visit" src="{{asset('public/upload/images/comp.gif')}}" alt="" width="70" height="60">
                                  @endif
                                </td>
	                			<td>@if($item->therapistId) {{userDetails($item->therapistId)->name}} @endif</td>
	                			<td>
	                				@if($item->type == 1)
	                					Per day visit
	                				@elseif($item->type == 2)
	                					Package wise visit
                                    @elseif($item->type == 3)
                                        Complimentary Visit
            	                	@endif
	                			</td>
	                			<td>@if($item->app_booked_date) {{date("d-M-Y", strtotime($item->app_booked_date))}} @endif</td>
	                			<td>@if($item->app_booked_time) {{$item->app_booked_time}} @endif</td>
	                			<td>@if($item->in_time) {{$item->in_time}} @endif</td>
	                			<td>@if($item->out_time) {{$item->out_time}} @endif</td>
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
	                			<td>@if($item->amount) {{$item->amount}}/- @endif</td>
                        <td>@if($item->visit_type) {{$item->visit_type}} @endif</td>
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
	                			<td>
                          @if($item->status == 'approval_pending')
                            <a title="Approved Pending Visit" href="{{url('approved-patient-visit')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to approve this pending visit?')" class="btn bg-maroon">Approved</a>
                          @endif
	                				@if($item->type == 1)
        										<!-- perday action -->
        										<a title="Edit Entry" href="#" data-toggle="modal" data-target="#modal-edit-per-day-visit" data-id="{{$item->id}}" data-ids="{{$item->appointment_id}}" data-url="{{url('getPerDayEntryDetails')}}" class="btn btn-warning perDayEditId"><i class="fa fa-edit"></i></a>
                                        @php
                                          $createDate = $item->app_booked_date;
                                          $cdate = explode(' ',$createDate);
                                          $oldCreateDate = $cdate[0];
                                          $currentDate = date('Y-m-d');
                                        @endphp
                                        @if(Auth::user()->user_type == 'superadmin')
        			                            @if(($item->in_time == '') && ($item->amount != '') && ($currentDate == $oldCreateDate))
        			                              <a title="Come Patient" href="{{url('update-time-daily-entry/')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to Handshake it?')" class="btn btn-success"><i class="fa fa-handshake-o"></i></a>
        			                            @endif
        			                            @if($item->rating == '' && $item->in_time != '')
                                                  <a title="Rating" href="#" class="btn btn-success packageRating" data-toggle="modal" data-target="#modal-success" data-id="{{$item->id}}" data-url="{{url('rating')}}"><i class="fa fa-star" aria-hidden="true"></i></a>
                                                @endif
                                        @endif
	                				@elseif($item->type == 2)
        										<!-- packagewise action -->
        										<a title="Edit Entry" href="#" data-toggle="modal" data-target="#modal-edit-package-wise-visit" data-id="{{$item->id}}" data-url="{{url('getPackageWiseEntryDetails')}}" class="btn btn-warning packageEditId"><i class="fa fa-edit"></i></a>
                                      @if(Auth::user()->user_type == 'superadmin')
                                        @php
                                          $createDate = $item->app_booked_date;
                                          $cdate = explode(' ',$createDate);
                                          $oldCreateDate = $cdate[0];
                                          $currentDate = date('Y-m-d');
                                        @endphp
        				                        @if(($item->in_time == '') && ($currentDate == $oldCreateDate))
        				                          <a title="Come Patient" href="{{url('update-time-daily-entry-for-package/')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to Handshake it?')" class="btn btn-success"><i class="fa fa-handshake-o"></i></a>
        				                        @endif
        				                        @if($item->rating == '' && $item->in_time != '')
                                          <a title="Rating" href="#" class="btn btn-success packageRating" data-toggle="modal" data-target="#modal-success" data-id="{{$item->id}}" data-url="{{url('rating')}}"><i class="fa fa-star" aria-hidden="true"></i></a>
                                        @endif
                                      @endif
                                    @elseif($item->type == 3)
                                        <!-- perday action -->
                                        <a title="Edit Entry" href="#" data-toggle="modal" data-target="#modal-edit-complimentary-visit" data-id="{{$item->id}}" data-ids="{{$item->appointment_id}}" data-url="{{url('getPerDayEntryDetails')}}" class="btn btn-warning perDayEditId"><i class="fa fa-edit"></i></a>
                                          @php
                                            $createDate = $item->app_booked_date;
                                            $cdate = explode(' ',$createDate);
                                            $oldCreateDate = $cdate[0];
                                            $currentDate = date('Y-m-d');
                                          @endphp
                                          @if(Auth::user()->user_type == 'superadmin')
                                            @if(($item->in_time == '') && ($item->type == 3) && (($item->amount == '') || empty($item->amount) || ($item->amount == 0)) && ($currentDate == $oldCreateDate))
                                              <a title="Come Patient" href="{{url('update-time-daily-entry-for-complimentary')}}/{{$item->id}}" data-id="{{$item->id}}" data-url="{{url('check-therapist-attendance')}}" onclick="return confirm('Are you sure, you want to Handshake it?')" class="btn btn-success"><i class="fa fa-handshake-o"></i></a>
                                            @endif
                                            @if($item->rating == '' && $item->in_time != '')
                                              <a title="Rating" href="#" class="btn btn-success packageRating" data-toggle="modal" data-target="#modal-success" data-id="{{$item->id}}" data-url="{{url('rating')}}"><i class="fa fa-star" aria-hidden="true"></i></a>
                                            @endif
                                          @endif
	                				@endif
                          <!-- Cancel Visit -->
                          @if(Auth::User()->user_type == 'superadmin')
                            <a title="Cancel Visit" href="{{url('cancel-perday-visit/')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to delete this?')" class="btn btn-danger"><i class="fa fa-times"></i></a>
                          @endif
                          {{-- @if((Auth::User()->user_type == 'superadmin') || (Auth::User()->user_type == '6'))
                            <a title="Approve Report" href="{{url('approved-report-for-patient')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to approved this?')" class="btn btn-info" style="background-color: #761e8c;border: #761e8c;">Approve Report</a>
                          @endif --}}
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

<!-- Per day Modals -->
<!-- Modal Edit Per Day Visit -->
<div class="modal fade" id="modal-edit-per-day-visit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Per Day Daily Entry</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('edit-per-day-entry')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <input type="text" name="flag" value="allVisit" style="display: none;">
              <label for="inputEmail3" class="col-md-2 control-label">Booked Date</label>
              <div class="col-md-4">
                <input type="date" name="booked_date" class="form-control booked_date" readonly>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Booked Time</label>
              <div class="col-md-4">
                <input type="text" name="booked_time" class="form-control booked_time checkBookedAppointmentTime" readonly>
              </div>
            </div>
            <input type="text" name="perDayId" value="" class="perDayId" style="display: none;">
            <input type="text" name="appointmentId" value="" class="appointmentId" style="display: none;">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Therapist</label>
              <div class="col-md-4">
                @php
                  $therapistData = therapistList(Auth::user()->id);
                @endphp
                <select class="form-control select2 therapist_id" name="therapist_id">
                  <option selected disabled>Please Select</option>
                  @if($therapistData)
                    @foreach($therapistData as $theItem)
                    <option value="{{$theItem->id}}">{{$theItem->name}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="amountCheck" style="display: block;">
                <label for="inputEmail3" class="col-md-2 control-label">Amount</label>
                <div class="col-md-4">
                  <select class="form-control select2 amount" id="amount" name="amount">
                    <option selected disabled>Please Select</option>
                    @if($allAmt)
                      @foreach($allAmt as $amt)
                        <option value="{{$amt->amount}}">{{$amt->amount}}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
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

<!-- Rating Modal -->
<div class="modal modal-success fade" id="modal-success">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Treatment Rating</h4>
      </div>
      <form class="form-horizontal" method="post" action="{{url('per-day-rating')}}">
        {{ csrf_field() }}
        <div class="modal-body">
          <center>
            <span class="star-rating">
              <label class="fa fa-star-o rate" name="rating" value="1" data-rating="1"></label>
              <label class="fa fa-star-o rate" name="rating" value="2" data-rating="2"></label>
              <label class="fa fa-star-o rate" name="rating" value="3" data-rating="3"></label>
              <label class="fa fa-star-o rate" name="rating" value="4" data-rating="4"></label>
              <label class="fa fa-star-o rate" name="rating" value="5" data-rating="5"></label>
              <input type="hidden" name="treatmentRating" class="rating-value" value="">
              <input type="text" name="packageId" class="packageTreatmentId" value="" style="display: none;">
            </span>
          </center>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-outline pull-left">Submit</button>
          <button type="submit" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Package Modals -->
<!-- Modal Edit Package Visit -->
<div class="modal fade" id="modal-edit-package-wise-visit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Package Visit Entry</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('edit-package-wise-entry')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <input type="text" name="flag" value="allVisit" style="display: none;">
              <label for="inputEmail3" class="col-md-2 control-label">Booked Date</label>
              <div class="col-md-4">
                <input type="date" name="booked_date" class="form-control pbooked_date" readonly>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Booked Time</label>
              <div class="col-md-4">
                <input type="text" name="booked_time" class="form-control pbooked_time checkBookedAppointmentTime" readonly>
              </div>
            </div>
            <input type="text" name="packageDayId" value="" class="packageDayId" style="display: none;">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Therapist</label>
              <div class="col-md-4">
                @php
                  $therapistData = therapistList(Auth::user()->id);
                @endphp
                <select class="form-control select2 ptherapist_id" name="therapist_id">
                  <option selected disabled>Please Select</option>
                  @if($therapistData)
                    @foreach($therapistData as $theItem)
                    <option value="{{$theItem->id}}">{{$theItem->name}}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Extra Amount</label>
              <div class="col-md-4">
                <select class="form-control select2 pamount" id="amount" name="extraAmount">
                  <option selected disabled>Please Select</option>
                  @if($allAmt)
                    @foreach($allAmt as $amtItem)
                      <option value="{{$amtItem->amount}}">{{$amtItem->amount}}</option>
                    @endforeach
                  @endif
                </select>
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

<!-- Rating Modal -->
<div class="modal modal-success fade" id="modal-success-package">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Treatment Rating</h4>
      </div>
      <form class="form-horizontal" method="post" action="{{url('package-rating')}}">
        {{ csrf_field() }}
        <div class="modal-body">
          <center>
            <span class="star-rating">
              <label class="fa fa-star-o rate" name="rating" value="1" data-rating="1"></label>
              <label class="fa fa-star-o rate" name="rating" value="2" data-rating="2"></label>
              <label class="fa fa-star-o rate" name="rating" value="3" data-rating="3"></label>
              <label class="fa fa-star-o rate" name="rating" value="4" data-rating="4"></label>
              <label class="fa fa-star-o rate" name="rating" value="5" data-rating="5"></label>
              <input type="hidden" name="treatmentRating" class="rating-value" value="">
              <input type="text" name="dailyEtnryId" class="packageTreatmentId" value="" style="display: none;">
            </span>
          </center>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-outline pull-left">Submit</button>
          <button type="submit" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal Edit Complimentary Visit -->
<div class="modal fade" id="modal-edit-complimentary-visit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Complimentary Entry</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('edit-per-day-entry')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Booked Date</label>
              <div class="col-md-4">
                <input type="date" name="booked_date" class="form-control booked_date" readonly>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Booked Time</label>
              <div class="col-md-4">
                <input type="text" name="booked_time" class="form-control booked_time checkBookedAppointmentTime" readonly>
              </div>
            </div>
            <input type="text" name="perDayId" value="" class="perDayId" style="display: none;">
            <input type="text" name="appointmentId" value="" class="appointmentId" style="display: none;">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Therapist</label>
              <div class="col-md-4">
                @php
                  $therapistData = therapistList(Auth::user()->id);
                @endphp
                <select class="form-control select2 therapist_id" name="therapist_id">
                  <option selected disabled>Please Select</option>
                  @if($therapistData)
                    @foreach($therapistData as $theItem)
                    <option value="{{$theItem->id}}">{{$theItem->name}}</option>
                    @endforeach
                  @endif
                </select>
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

@endsection