@extends('layouts.apps')
@section('content')
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Search Appointment</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal formloc" method="post" action="{{url('search-all-appointment')}}">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Therapist Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="therapistName">
                      <option value="" disabled selected>Please Select</option>
                      @if($allTherapist)
                        @foreach($allTherapist as $therapist)
                          <option value="{{$therapist->id}}">{{$therapist->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Appointment Type</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="appointmentType">
                      <option value="" disabled selected>Please Select</option>
                      <option value="package_wise">Package Wise</option>
                      <option value="per_day_visit">Per day Wise</option>
                      <option value="complimentary">Complimentary</option>
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
                  <label for="inputEmail3" class="col-md-2 control-label">Status</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="status">
                      <option value="" disabled selected>Please Select</option>
                      <option value="pending">Pending</option>
                      <option value="approved">Approved</option>
                      <option value="complete">Complete</option>
                      <option value="cancel">Cancel</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Service Type</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="serviceName">
                      <option value="" disabled selected>Please Select</option>
                      @if($allService)
                        @foreach($allService as $servItem)
                          <option value="{{$servItem->id}}">{{$servItem->name}}</option>
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
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
            <!-- /.box -->

          <!-- All Appointment -->
          <div class="box">
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Patient</th>
                      @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
                      <th>Phone No</th>
                      @endif
                      <th>Therapist</th>
                      <th>Reference Type</th>
                      <th>Branch</th>
                      <th>Payment Type</th>
                      <th>Payment Status</th>
                      <th>App Status</th>
                      <th>App Type</th>
                      <th>Created At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($allData)
                      @foreach($allData as $item)
                        <tr>
                          <td>{{$no++}}</td>
                          <td>@if($item->user_id)
                              <a href="{{url('view-patient/')}}/{{$item->user_id}}">{{$item->patientName}}</a>
                              @if($item->app_service_type == 7)
                                <img title="IPD" src="{{asset('public/upload/images/ipd2gif.gif')}}" alt="" width="60" height="30">
                              @elseif(($item->app_service_type == 9) || ($item->app_service_type == 8) || ($item->app_service_type == 1))
                                <img title="Private Home Visit" src="{{asset('public/upload/images/homeCare1.gif')}}" alt="" width="100" height="50">
                              @elseif($item->payment_method == 'complimentary')
                                <img title="Complimentary" src="{{asset('public/upload/images/comp.gif')}}" alt="" width="80" height="60">
                              @endif
                          @endif</td>
                          @if((Auth::user()->user_type == 'superadmin') || (Auth::user()->user_type == '1'))
                          <td>@if($item->phoneNo) {{$item->phoneNo}} @endif</td>
                          @endif
                          <td>@if($item->therapistName) {{$item->therapistName}} @endif</td>
                          <td>@if($item->reference_type) {{$item->reference_type}} @endif</td>
                          <td>@if($item->branchName) {{$item->branchName}} @endif</td>
                          <td>@if($item->payment_method) {{$item->payment_method}} @endif</td>
                          <td>@if($item->payment_status) {{$item->payment_status}} @endif</td>
                          @php
                          $packageNames = dailyPackageReport($item->id);
                          @endphp
                          @php
                          $dueDaysData = packageDueDaysRemine($item->id);
                          if($dueDaysData){
                            $dueData = $dueDaysData->due_days;
                          }else{
                            $dueData = 0;
                          }
                          @endphp
                          <td>
                          @if($item->status == 'approved')
                            <p style="color: green;font-weight: bold;">{{ucfirst($item->status)}}</p>
                          @elseif($item->status == 'cancel')
                            <p style="color: red;font-weight: bold;">{{ucfirst($item->status)}}</p>
                          @elseif($item->status == 'completed')
                            <p style="color: #46b8da;font-weight: bold;">{{ucfirst($item->status)}}</p>
                          @elseif($item->status == 'inactive')
                            <p style="color: #bb0202;font-weight: bold;">{{ucfirst($item->status)}}</p>
                          @else
                            <p id="appStatus{{$item->id}}" style="font-weight: bold;">{{ucfirst($item->status)}}</p>
                          @endif                          
                          </td>
                          <td>@if($item->appointment_type) {{ucfirst($item->appointment_type)}} @endif</td>
                          <td>@if($item->appointment_date) {{date("d-M-Y", strtotime($item->appointment_date))}} @endif</td>
                          <td>
                            <a title="View Appointment" target="_blank" href="{{url('view-appointment/')}}/{{$item->id}}" class="btn bg-maroon"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            @if($item->status == 'pending')
                              <a title="Approve Appointment" href="{{url('approve-appointment/')}}/{{$item->id}}" class="btn btn-success ajaxApp{{$item->id}}"><i class="fa fa-check" aria-hidden="true"></i></a>
                              <!-- <a title="Cancel Appointment" href="{{url('cancel-appointment')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to delete this?')" class="btn btn-danger "><i class="fa fa-times" aria-hidden="true"></i></a> -->
                              <a title="Cancel Appointment" data-url="{{url('cancel-appointment')}}" data-id="{{$item->id}}" class="btn btn-danger  ajaxApp{{$item->id}} cancelAppointment"><i class="fa fa-times" aria-hidden="true"></i></a>
                            @elseif($item->status == 'approved')
                              <a title="Edit Appointment" target="_blank"  href="{{url('edit-appointment/')}}/{{$item->id}}" class="btn btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
                              @if($item->payment_method == 'per_day_visit')
                              <a title="Daily Visit Entry" href="{{url('per-day-daily-entry/')}}/{{$item->id}}" target="_blank" class="btn bg-olive">Per Day wise</a>
                              @elseif($item->payment_method == 'package_wise')
                                @if(!empty($item->payment_method) && !empty($item->package_type))
                                  <a title="Package wise Visit Entry" href="{{url('package-wise-entry/')}}/{{$item->id}}" target="_blank" class="btn bg-purple appointmentPackageWise">Package  wise</a>
                                @endif
                              @elseif($item->payment_method == 'complimentary')
                                <a title="Complimentary Visit Entry" target="_blank" href="{{url('complimentary-entry')}}/{{$item->id}}" class="btn bg-olive">Complimentary</a>
                              @endif
                              <a title="Finish Appointment" href="#" data-toggle="modal" data-target="#modal-complete-appointment" data-id="{{$item->id}}" class="btn btn-info appointmentComplete"><i class="fa fa-flag-checkered"></i></a>
                              @php
                                $consentRecord = getConsentRecord($item->id);
                              @endphp
                              @if($consentRecord == 'true')
                                <a href="{{url('get-consent-record')}}/{{$item->id}}" target="_blank" class="btn btn-info" style="background: #0072ff;">Consent Report</a>
                              @endif
                            @endif

                            <a title="Reminder" href="{{url('reminder-appointment/')}}/{{$item->id}}" class="btn bg-navy" onclick="return confirm('Are you sure, you want to send reminder?')"><i class="fa fa-bell"></i></a>
                            <a title="Google Ranking Notification" href="{{url('google-ranking-notification/')}}/{{$item->id}}" class="btn bg-default" onclick="return confirm('Are you sure, you want to send google ranking notification?')"><img src="{{asset('public/upload/images/google1.png')}}" width="25" height="25" alt=""></a>
                            <a title="Visit History" href="{{url('visit-history/')}}/{{$item->id}}" class="btn bg-purple"><i class="fa fa-history"></i></a>
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


<!-- Modal Package wise Visit -->
<div class="modal fade" id="modal-package-wise">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Package Wise Entry</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('package-wise-visit-appointment')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <input type="hidden" name="Appontment_id_package" value="" class="Appontment_id_package">
              <label for="inputEmail3" class="col-md-2 control-label">In Time</label>
              <div class="col-md-4">
                <input type="text" name="inTime" class="form-control" placeholder="HH:MM:SS" required>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Out Time</label>
              <div class="col-md-4">
                <input type="text" name="outTime" class="form-control" placeholder="HH:MM:SS" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Therapist</label>
              <div class="col-md-4">
                @php
                  $therapistData = therapistList(Auth::user()->id);
                @endphp
                <select class="form-control select2" name="therapist_id">
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
                <input type="number" name="extamount" class="form-control" placeholder="Extra Amount">
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

<!-- Modal -->
<div class="modal fade" id="modal-complete-appointment">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Finish Appointment</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('complete-appointment')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <input type="text" name="AppontmentId_complete" class="AppontmentId_complete" value="" style="display: none;">
              <label for="inputEmail3" class="col-md-2 control-label">Remark </label>
              <div class="col-md-8">
                <!-- <textarea name="remark" class="form-control" placeholder="Enter Remark" required></textarea> -->
                <input type="text" name="remark" class="form-control" placeholder="Enter Remark" required>
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