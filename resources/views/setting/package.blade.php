@extends('layouts.apps')
@section('content')
<!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <!-- Horizontal Form -->
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Add Package</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          @if(!empty($getData))
          	<form class="form-horizontal" method="post" action="{{url('update-package/')}}/{{$getData->id}}">
          @else
          	<form class="form-horizontal formloc" id="" method="post" action="{{url('add-package')}}">
          @endif
          	{{ csrf_field() }}
            <div class="box-body">
              <div class="form-group row">
                <label for="inputEmail3" class="col-md-2 control-label">Package Name</label>
                <div class="col-md-3">
                  <select class="form-control select2" name="package">
                    <option value="" selected disabled>Please Select</option>
                    <option value="1st Package" @if($getData) {{ $getData->name == '1st Package' ? 'selected="selected"' : '' }} @endif>1st Package</option>
                    <option value="2nd Package" @if($getData) {{ $getData->name == '2nd Package' ? 'selected="selected"' : '' }} @endif>2nd Package</option>
                    <option value="3rd Package" @if($getData) {{ $getData->name == '3rd Package' ? 'selected="selected"' : '' }} @endif>3rd Package</option>
                    <option value="4th Package" @if($getData) {{ $getData->name == '4th Package' ? 'selected="selected"' : '' }} @endif>4th Package</option>
                    <option value="5th Package" @if($getData) {{ $getData->name == '5th Package' ? 'selected="selected"' : '' }} @endif>5th Package</option>
                    <option value="6th Package" @if($getData) {{ $getData->name == '6th Package' ? 'selected="selected"' : '' }} @endif>6th Package</option>
                    <option value="7th Package" @if($getData) {{ $getData->name == '7th Package' ? 'selected="selected"' : '' }} @endif>7th Package</option>
                    <option value="8th Package" @if($getData) {{ $getData->name == '8th Package' ? 'selected="selected"' : '' }} @endif>8th Package</option>
                    <option value="9th Package" @if($getData) {{ $getData->name == '9th Package' ? 'selected="selected"' : '' }} @endif>9th Package</option>
                    <option value="10th Package" @if($getData) {{ $getData->name == '10th Package' ? 'selected="selected"' : '' }} @endif>10th Package</option>
                    <option value="11th Package" @if($getData) {{ $getData->name == '11th Package' ? 'selected="selected"' : '' }} @endif>11th Package</option>
                    <option value="12th Package" @if($getData) {{ $getData->name == '12th Package' ? 'selected="selected"' : '' }} @endif>12th Package</option>
                    <option value="13th Package" @if($getData) {{ $getData->name == '13th Package' ? 'selected="selected"' : '' }} @endif>13th Package</option>
                    <option value="14th Package" @if($getData) {{ $getData->name == '14th Package' ? 'selected="selected"' : '' }} @endif>14th Package</option>
                    <option value="15th Package" @if($getData) {{ $getData->name == '15th Package' ? 'selected="selected"' : '' }} @endif>15th Package</option>
                  </select>
                </div>
                <label for="inputEmail3" class="col-md-2 control-label">Validity</label>
                <div class="col-md-3">
                  <input type="text" class="form-control" name="validity" value="@if($getData) {{$getData->validity}} @endif" placeholder="Enter Validity" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-md-2 control-label">Days</label>
                <div class="col-md-3">
                  <input type="text" name="days" class="form-control" value="@if($getData) {{$getData->days}} @endif" placeholder="Enter Package Days" required>
                </div>
                <label for="inputEmail3" class="col-md-2 control-label">Type</label>
                <div class="col-md-3">
                  <select class="form-control select2" name="type" required>
                    <option value="" selected disabled>Please Select</option>
                    <option value="opd">OPD</option>
                    <option value="homeCare">Home Care</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-md-2 control-label">Package Amount</label>
                <div class="col-md-3">
                  <input type="text" name="amount" class="form-control" value="@if($getData) {{$getData->package_amount}} @endif" placeholder="Enter Package Amount" required>
                </div>
                <label for="inputEmail3" class="col-md-2 control-label">Per day Amount</label>
                <div class="col-md-3">
                  <input type="text" class="form-control" name="per_amount" value="@if($getData) {{$getData->per_amount}} @endif" placeholder="Enter per day amount" required>
                </div>
              </div>
              <div class="form-group row">
                <label for="inputEmail3" class="col-md-2 control-label">Joints</label>
                <div class="col-md-3">
                  <select class="form-control select2" name="joints" required>
                    <option value="" disabled selected>Please Select</option>
                    <option value="one_joint" @if($getData) {{ $getData->joints == 'one_joint' ? 'selected="selected"' : '' }} @endif>One Joint</option>
                    <option value="two_joint" @if($getData) {{ $getData->joints == 'two_joint' ? 'selected="selected"' : '' }} @endif>Two Joint</option>
                    <option value="three_joint" @if($getData) {{ $getData->joints == 'three_joint' ? 'selected="selected"' : '' }} @endif>Three Joint</option>
                    <option value="neuro" @if($getData) {{ $getData->joints == 'neuro' ? 'selected="selected"' : '' }} @endif>Neuro</option>
                  </select>
                </div>
                <label for="inputEmail3" class="col-md-2 control-label">Commission (%)</label>
                <div class="col-md-3">
                  <input type="text" class="form-control" name="commission" value="@if($getData) {{$getData->commission}} @endif" placeholder="Enter Commission %" required>
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
                  <th>Type</th>
                  <th>Package Name</th>
                  <th>Days</th>
                  <th>Package Amount</th>
                  <th>Per day Amount</th>
                  <th>Joints</th>
                  <th>Commission</th>
                  <th>Validity</th>
                  <th>Created At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if($allData)
                  @foreach($allData as $item)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>
                        @if($item->type == 'opd')
                          OPD
                        @elseif($item->type == 'homeCare')
                          Home Care
                        @endif
                      </td>
                      <td>{{$item->name}}</td>
                      <td>{{$item->days}}</td>
                      <td>{{$item->package_amount}} /-</td>
                      <td>{{$item->per_amount}} /-</td>
                      <td>
                        @if($item->joints == 'one_joint')
                          One Joint
                        @elseif($item->joints == 'two_joint')
                          Two Joint
                        @elseif($item->joints == 'three_joint')
                          Three Joint
                        @elseif($item->joints == 'neuro')
                          Neuro
                        @endif
                      </td>
                      <td>@if($item->commission) {{$item->commission}}% @endif</td>
                      <td>@if($item->validity) {{$item->validity}} Days @endif</td>
                      <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                      <td>
                        <a href="{{url('edit-package/')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                        <!-- <a href="{{url('delete-package/')}}/{{$item->id}}" class="btn btn-danger">Delete</a> -->
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
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
<!-- /.content -->
@endsection