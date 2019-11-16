@extends('layouts.apps')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Total Entry: {{count($allData)}}</b></h3>
          @if(!empty($flagCheck))
            <img title="Package visit is going to be finished" src="{{ALERT_IMAGES}}" width="40" height="40">
          @endif
          @if(($lastDateFlag == 'false') && ($condFlag == 'false'))
            <a title="Daily wise Visit Entry" href="#" data-toggle="modal" data-target="#modal-per-day-visit" data-ids="{{$appointmentId}}" class="btn btn-primary appointmentPerDayVisit" data-url="{{url('check-book-appointment')}}" style="float: right;">Daily Entry (Package)</a>
          @endif
          @if(!empty($invoiceData))
            <a title="Generate Receipt" href="{{url('generate-receipt-package/')}}/{{$appointmentId}}" onclick="return confirm('Are you sure, you want to generate receipt?')" target="_blank" class="btn btn-default" style="background: #999966;color: #ffffff;float: right;">Invoice</a>
          @endif
            <h3 class="box-title "><a title="Download Report" href="{{url('daily-visit-package-list-export')}}/{{$appointmentId}}" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Export</a></h3>
        </div>
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Patient Name</th>
                  <th>Therapist Name</th>
                  <th>Payment Type</th>
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
                      <td>@if($item->appointment_id) {{userDetails(appointmentDetails($item->appointment_id)->user_id)->name}} @endif</td>
                      <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @elseif(!empty(userDetails(appointmentDetails($item->appointment_id)->user_id)->therapist_id)) {{userDetails(userDetails(appointmentDetails($item->appointment_id)->user_id)->therapist_id)->name}} @endif</td>
                      <td>@if($item->type == '1') Per day visit @else Package wise visit ( {{packageDetails($item->package_id)->name}} ) @endif</td>
                      <td>@if($item->app_booked_date) {{$item->app_booked_date}} @endif</td>
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
                      <td>@if($item->due_days) {{$item->due_days}} @endif</td>
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
                        <a title="Edit Entry" href="#" data-toggle="modal" data-target="#modal-edit-per-day-visit" data-id="{{$item->id}}" data-url="{{url('getPackageWiseEntryDetails')}}" class="btn btn-warning packageEditId"><i class="fa fa-edit"></i></a>
                        @if($item->status == 'approval_pending')
                          <a title="Approved Pending Visit" href="{{url('approved-patient-visit')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to approve this pending visit?')" class="btn bg-maroon">Approved</a>
                        @endif
                        @php
                          $createDate = $item->app_booked_date;
                          $cdate = explode(' ',$createDate);
                          $oldCreateDate = $cdate[0];
                          $currentDate = date('Y-m-d');
                        @endphp
                        @if(Auth::User()->user_type == 'superadmin')
                          @if(($item->in_time == '') && ($currentDate == $oldCreateDate))
                            <a title="Come Patient" href="{{url('update-time-daily-entry-for-package/')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to Handshake it?')" class="btn btn-success"><i class="fa fa-handshake-o"></i></a>
                          @endif
                          @if($item->rating == '' && $item->in_time != '')
                            <a title="Rating" href="#" class="btn btn-success packageRating" data-toggle="modal" data-target="#modal-success" data-id="{{$item->id}}" data-url="{{url('rating')}}"><i class="fa fa-star" aria-hidden="true"></i></a>
                          @endif
                            <a title="Cancel Visit" href="{{url('cancel-package-visit')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to delete this?')" class="btn btn-danger"><i class="fa fa-times"></i></a>
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

<!-- Modal Package Visit -->
<div class="modal fade" id="modal-per-day-visit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Package Visit Entry</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('package-visit-appointment')}}" onsubmit="return submitContactForm();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Booked Date</label>
              <div class="col-md-4">
                <input type="date" name="booked_date" class="form-control booked_date datevalidate" data-url="{{url('check-attendance-of-therapist')}}" id="booked_date">
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Booked Time</label>
              <div class="col-md-4">
                <select class="form-control select2 booked_time checkBookedAppointmentTime" data-url="{{url('check-booked-appointment-datetime')}}" name="booked_time" id="booked_time">
                  <option value="" selected disabled>Please Select</option>
                  @foreach($allTime as $timeItem)
                  <option value="{{$timeItem->time}}">{{$timeItem->time}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <input type="hidden" name="Appontment_id_package" value="{{$appointmentId}}" class="Appontment_id_package">
            <input type="hidden" name="therapist_name" class="selectedTherapistName" value="{{userDetails(appointmentDetails($appointmentId)->user_id)->therapist_id}}">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Therapist</label>
              <div class="col-md-4">
                @php
                  $therapistData = therapistList(Auth::user()->id);
                @endphp
                <select class="form-control newTherapistId therapistAttCheck" data-urls="{{url('check-attendance-of-therapist')}}" name="therapist_id">
                  <option value="" selected disabled>Please Select</option>
                  @if(!empty($userTherapist))
                    @if($therapistData)
                      @foreach($therapistData as $theItem)
                        <option value="{{$theItem->id}}" {{ $theItem->id == $userTherapist ? 'selected="selected"' : '' }}>{{$theItem->name}}</option>
                      @endforeach
                    @endif
                  @else
                    @if($therapistData)
                      @foreach($therapistData as $theItem)
                        <option value="{{$theItem->id}}">{{$theItem->name}}</option>
                      @endforeach
                    @endif
                  @endif
                </select>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Extra Amount</label>
              <div class="col-md-4">
                <select class="form-control select2" name="extraAmount">
                  <option disabled selected>Please Select</option>
                  @if($amount)
                    @foreach($amount as $amtItem)
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

<!-- Modal Edit Package Visit -->
<div class="modal fade" id="modal-edit-per-day-visit">
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
                  @if($amount)
                    @foreach($amount as $amtItem)
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
<div class="modal modal-success fade" id="modal-success">
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

<!-- Generate Invoice bill modal -->
<div class="modal fade" id="modal-invoice">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Generate Invoice</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="post" action="{{url('save-invoice-details-for-perday')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Payment Type</label>
              <div class="col-md-3">
                 <select class="form-control selectedPaymentType" name="paymentType">
                  <option disabled selected>Please Select</option>
                  <option value="cash">Cash</option>
                  <option value="paytm">Paytm</option>
                  <option value="sph">SPH</option>
                  <option value="credit">Credit</option>
                  <option value="debit">Debit</option>
                 </select>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Amount</label>
              <div class="col-md-3">
                 <input type="text" class="form-control amount" name="amount" value="" placeholder="Enter Amount" readonly>
              </div>
            </div>
            <div class="reference_check" style="display: none;">
              <div class="form-group row">
                <label for="inputEmail3" class="col-md-2 control-label">Reference No</label>
                <div class="col-md-3">
                  <input type="text" class="form-control packageReferenceNo" data-url="{{url('reference-duplicate')}}" name="reference_no" value="" placeholder="Enter Reference No.">
                </div>
                <label for="inputEmail3" class="col-md-2 control-label">Bank Name</label>
                <div class="col-md-3">
                  <input type="text" class="form-control" name="bank" value="" placeholder="Enter Bank">
                </div>
              </div>
            </div>
            <input type="hidden" name="visitId" value="" class="visitId">
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
<!-- /. end modal -->

<script>
function submitContactForm(){
  var bookedDate = $('#booked_date').val();
  var bookedTime = $(".select2 option:selected").val();
  if(bookedDate.trim() == '' ){
      alert('Please select booked date!');
      $('#booked_date').focus();
      return false;
  }else if(bookedTime.trim() == '' ){
      alert('Please select booked time!');
      $('#booked_time').focus();
      return false;
  }else{
      return true;
  }
}
</script>
@endsection