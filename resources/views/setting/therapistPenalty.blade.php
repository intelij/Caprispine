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
              <h3 class="box-title">{{$title}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
           	<form class="form-horizontal" id="" method="post" action="{{url('search-therapist-penalty')}}">
            	{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="therapistId">
                      <option disabled selected>Please Select</option>
                      @if($allTherapist)
                        @foreach($allTherapist as $therapist)
                          <option value="{{$therapist->id}}">{{$therapist->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 control-label">From Date</label>
                  <div class="col-md-3">
                    <input type="date" class="form-control" name="from_date" value="" placeholder="Enter From Date">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">To Date</label>
                  <div class="col-md-3">
                    <input type="date" class="form-control" name="to_date" value="" placeholder="Enter To Date">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Submit</button>
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
                    <th>S.No</th>
                    <th>Therapist Name</th>
                    <th>Branch</th>
                    <th>Attendance Penalty</th>
                    <th>Appointment Penalty</th>
                    <th>Total Penalty</th>
	                </tr>
                </thead>
                <tbody>
                  @if($allTherapist)
                    @foreach($allTherapist as $allVal)
    	                <tr>
                       <td>{{$no++}}</td>
                       <td>{{$allVal->name}}</td> 
                       <td>@if($allVal->branch) {{branchDetails($allVal->branch)->name}} @endif</td>
                       <td>@if($allVal->allAttendance != 0) {{$allVal->allAttendance}} <a href="{{url('all-attendance-report')}}/{{$allVal->id}}"><i class="fa fa-eye"></i></a> @endif</td>
                       <td>@if($allVal->allAppPenalty != 0) {{$allVal->allAppPenalty}} <a href="{{url('all-appointment-penalty')}}/{{$allVal->id}}"><i class="fa fa-eye"></i></a>  @endif</td>
                       <td>{{$allVal->allAttendance + $allVal->allAppPenalty}}</td>
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