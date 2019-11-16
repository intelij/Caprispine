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
              <h3 class="box-title">Add Location</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if(!empty($getData))
            	<form class="form-horizontal" method="post" action="{{url('update-location/')}}/{{$getData->id}}">
            @else
            	<form class="form-horizontal formloc" id="" method="post" action="{{url('add-location')}}">
            @endif
            	{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Location Name</label>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="locationName" value="@if(!empty($getData)) {{$getData->name}} @endif" placeholder="Enter Location" required>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Location Key</label>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="b_key" value="@if(!empty($getData)) {{$getData->b_key}} @endif" placeholder="Enter Location Key" required>
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
	                  <th>Location Name</th>
                    <th>Location Key</th>
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
                        <td>{{$item->b_key}}</td>
	                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
	                			<td>
	                				<a href="{{url('edit-location/')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
	                				<!-- <a href="{{url('delete-location/')}}/{{$item->id}}" class="btn btn-danger">Delete</a> -->
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