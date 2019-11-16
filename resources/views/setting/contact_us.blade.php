@extends('layouts.apps')
@section('content')

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
        <form class="form-horizontal" method="post" action="{{url('add-contact-us')}}">
        	{{ csrf_field() }}
          <div class="box-body">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
              <div class="col-xs-4">
                <input type="text" class="form-control" name="uname" value="" placeholder="Enter Name" required>
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
              <div class="col-xs-4">
                <input type="email" class="form-control" name="email" value="" placeholder="Enter Email" required>
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-2 control-label">Mobile</label>
              <div class="col-xs-4">
                <input type="number" class="form-control" name="mobile" value="" placeholder="Enter Mobile No" required>
              </div>
              <label for="inputEmail3" class="col-sm-2 control-label">Description</label>
              <div class="col-xs-4">
                <textarea class="form-control" name="description" placeholder="Enter Description" required></textarea>
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
    </div>
  </div>
</section>
@endsection