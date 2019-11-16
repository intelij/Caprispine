@extends('layouts.apps')
@section('content')

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
            <form class="form-horizontal formloc" method="post" action="{{url('search-patient-report')}}">
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Patient Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="patientId" required>
                      <option value="" disabled selected>Please Select</option>
                      @if($allPatient)
                        @foreach($allPatient as $patient)
                          <option value="{{$patient->id}}">{{$patient->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <!--<label for="inputEmail3" class="col-md-2 control-label">Contact No</label>-->
                  <!--<div class="col-md-3">-->
                  <!--  <input type="number" name="contactNo" placeholder="Enter Contact No" class="form-control">-->
                  <!--</div>-->
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">From Date</label>
                  <div class="col-md-3">
                     <input type="date" class="form-control" name="fromDate" value="" placeholder="">
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">To Date</label>
                  <div class="col-md-3">
                     <input type="date" class="form-control" name="toDate" value="" placeholder="">
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
                      <th>Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@if(!empty($pName))
	                  	<tr>
	                  		<td>{{$no++}}</td>
	                  		<td>{{$pName}}</td>
	                  		<td><a href="{{$report}}" target="_blank">Patient Report</a></td>
	                  	</tr>
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