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
           	<form class="form-horizontal" id="" method="post" action="{{url('search-attendance')}}">
            	{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="nameId">
                      <option disabled selected>Please Select</option>
                      @if($allTherapist)
                        @foreach($allTherapist as $therapist)
                          <option value="{{$therapist->id}}">{{$therapist->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-md-3">
                    <select class="form-control" name="status">
                      <option selected disabled>Please Select</option>
                      <option value="present">Present</option>
                      <option value="apsent">Apsent</option>
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
                    <th>Date</th>
                    <th>Time</th>
                    <th>Late Comming</th>
                    <th>Status</th>
	                  <th>Name</th>
                    <th>Mark Attendance From</th>
	                </tr>
                </thead>
                <tbody>
	                @if($allData)
	                	@foreach($allData as $item)
	                		<tr>
                        <td>{{$no++}}</td>
                        <td>{{date("d-M-Y", strtotime($item->date))}}</td>
                        <td>{{$item->attendance_time}}</td>
                        <td>@if($item->late_coming_min) {{$item->late_coming_min}} min @else 0 min @endif</td>
                        <td>
                          @if($item->status == 'present')
                            <small class="label label-success"><i class="fa fa-clock-o"></i> {{ucfirst($item->status)}}</small>
                          @else
                            <small class="label label-danger"><i class="fa fa-clock-o"></i> {{ucfirst($item->status)}}</small>
                          @endif
                        </td>
	                			<td>{{userDetails($item->therapist_id)->name}}</td>
                        <td>@if(!empty($item->location)) {{$item->location}} @else Admin Panel @endif</td>
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