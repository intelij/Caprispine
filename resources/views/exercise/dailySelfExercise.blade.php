@extends('layouts.apps')
@section('content')
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal formloc" id="" method="post" action="{{url('search-self-exercise')}}"  enctype="multipart/form-data">
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Patient Name</label>
                  <div class="col-md-3">
                    <select class="form-control select2" name="patientName">
                      <option value="" selected disabled>Please Select</option>
                      @if($allPatient)
                        @foreach($allPatient as $allpat)
                        <option value="{{$allpat->id}}">{{$allpat->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Therapist Name</label>
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

          <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Patient Name</th>
                      <th>Therapist Name</th>
                      <th>Exercise Name</th>
                      <th>Activity Status</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @if($allData)
                      @foreach($allData as $item)
                        <tr>
                          <td>{{$no++}}</td>
                          <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                          <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                          <td>@if($item->exerciseId) {{exerciseDetials($item->exerciseId)->name}} @endif</td>
                          <td><b><i>{{$item->status}}</i></b></td>
                          <td>{{$item->date}}</td>
                          <td>{{$item->time}}</td>
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