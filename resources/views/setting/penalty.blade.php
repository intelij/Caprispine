@extends('layouts.apps')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Add Penalty</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if(!empty($getData))
            	<form class="form-horizontal" method="post" action="{{url('update-penalty/')}}/{{$getData->id}}">
            @else
            	<form class="form-horizontal formloc" id="" method="post" action="{{url('add-penalty')}}">
            @endif
            	{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-md-2 control-label">Penalty Name</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="penaltyName" value="@if($getData) {{$getData->name}} @endif" placeholder="Enter Penalty Name" required>
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Penalty Amount</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="penaltyAmount" value="@if($getData) {{$getData->amount}} @endif" placeholder="Enter Penalty Amount" required>
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
	                  <th>Penalty Name</th>
                    <th>Penalty Amount</th>
	                  <th>Created At</th>
	                  <th>Action</th>
	                </tr>
                </thead>
                <tbody>
                  @if($allData)
                    @foreach($allData as $item)
                      <tr>
                        <td>{{$no++}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->amount}} Rs</td>
                        <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                        <td>
                          <a href="{{url('edit-penalty/')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                          <!-- <a href="{{url('delete-penalty/')}}/{{$item->id}}" class="btn btn-danger">Delete</a> -->
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