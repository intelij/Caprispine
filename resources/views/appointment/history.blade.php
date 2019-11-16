@extends('layouts.apps')
@section('content')
<!-- Main content -->

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Total Amount:</b> <i>{{$totalAmt}} /-</i>, <b>Total Penalty:</b> <i>{{$totalPenalty}} /-</i></h3>
            </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Patient Name</th>
                      <th>Therapist Name</th>
                      <th>Payment Type</th>
                      <th>Package Name</th>
                      <th>In Time</th>
                      <th>Out Time</th>
                      <th>Amount</th>
                      <th>Penalty</th>
                      <th>Created At</th>
                    </tr>
                  </thead>
                  <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                        <tr>
                          <td>{{userDetails(appointmentDetails($item->appointment_id)->user_id)->name}}</td>
                          <td>{{userDetails($item->therapist_id)->name}}</td>
                          <td>@if($item->type == 1) Per day visit @elseif($item->type == 2) Package wise visit @endif</td>
                          <td>@if($item->package_id) {{packageDetails($item->package_id)->name}} @else -- @endif</td>
                          <td>{{$item->in_time}}</td>
                          <td>{{$item->out_time}}</td>
                          <td>@if($item->amount) {{$item->amount}}/- @endif</td>
                          <td>@if($item->penalty) {{$item->penalty}}/- @endif</td>
                          <td>{{date("d-M-Y", strtotime($item->app_booked_date))}}</td>
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