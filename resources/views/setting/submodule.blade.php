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
              <h3 class="box-title">Add Sub Module</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if(!empty($getData))
            	<form class="form-horizontal" method="post" action="{{url('update-submodule/')}}/{{$getData->id}}">
            @else
            	<form class="form-horizontal formloc" id="" method="post" action="{{url('add-submodule')}}">
            @endif
            	{{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-2 control-label">Module Name</label>
                  <div class="col-xs-4">
                    <select class="form-control select2" name="module_name" required>
                      <option disabled selected>Please Select</option>
                      @if($allModules)
                        @foreach($allModules as $allItem)
                          <option value="{{$allItem->id}}" @if(!empty($getData)) {{$getData->module_id == $allItem->id ? 'selected="selected"' : ''}} @endif>{{$allItem->name}}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Sub Module Name</label>
                  <div class="col-xs-4">
                    <input type="text" class="form-control" name="subModuleName" value="@if(!empty($getData)) {{$getData->name}} @endif" placeholder="Enter Sub Module" required>
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
                    <th>Module Name</th>
	                  <th>Sub Name</th>
	                  <th>Created At</th>
	                  <th>Action</th>
	                </tr>
                </thead>
                <tbody>
	                @if($allData)
	                	@foreach($allData as $item)
	                		<tr>
                        <td>{{$no++}}</td>
                        <td>{{moduleDetails($item->module_id)->name}}</td>
	                			<td>{{$item->name}}</td>
	                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
	                			<td>
	                				<a href="{{url('edit-sub-module/')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
	                				<!-- <a href="{{url('delete-sub-module/')}}/{{$item->id}}" class="btn btn-danger">Delete</a> -->
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