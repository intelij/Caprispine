@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <form class="form-horizontal" method="post" action="{{url('reset-password')}}" enctype="multipart/form-data">
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Name: </label>
	              		<div class="col-md-3">
	              			<label class="control-label">{{Auth::user()->name}}</label>
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">Email: </label>
	              		<div class="col-md-3">
	              			<label class="control-label">{{Auth::user()->email}}</label>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Old Password </label>
	              		<div class="col-md-3">
	              			<input type="password" name="old_password" class="form-control">
	              		</div>
	              		<label for="inputEmail3" class="col-md-3 control-label">New Password </label>
	              		<div class="col-md-3">
	              			<input type="password" name="new_password" class="form-control">
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Confirm Password </label>
	              		<div class="col-md-3">
	              			<input type="password" name="confirm_password" class="form-control">
	              		</div>
	              	</div>
	              	<!-- /.box-body -->
		              <div class="box-footer">
		              	<button type="submit" class="btn btn-primary">Update</button>
	                    <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
		              </div>
		            <!-- /.box-footer -->
	              </div>
	          </form>
          	</div>
      	</div>
 	</div>
</section>

@endsection