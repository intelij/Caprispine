@extends('layouts.apps')
@section('content')
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}"> -->
<!-- Main content -->

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Therapist - {{$therapistName}}</h3>&nbsp;&nbsp;&nbsp;&nbsp;
              <h3 class="box-title">(Total Penalty Amount - {{$totalAmt}}/-)</h3>
              {{-- <a href="{{ URL::previous() }}" class="btn btn-info" style="float: right;">Go Back</a> --}}
            </div>
          </div>
          <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
	                <tr>
                    <th>S.No</th>
                    <th>Penalty Amount</th>
                    <th>Visit Type</th>
                    <th>Date</th>
	                </tr>
                </thead>
                <tbody>
                  @if($allData)
                    @foreach($allData as $allVal)
    	                <tr>
                       <td>{{$no++}}</td>
                       <td>@if($allVal->penalty) {{$allVal->penalty}}/- @endif</td>
                       <td>{{$allVal->visit_type}}</td>
                       <td>{{date("d-M-Y", strtotime($allVal->app_booked_date))}}</td>
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
    <!-- /.content -->
@endsection