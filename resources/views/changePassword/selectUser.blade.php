@extends('layouts.apps')
@section('content')
<section class="content">
    <div class="row">
    	<div class="col-md-12">
	      <!-- Horizontal Form -->
	      <div class="box box-info">
	        <div class="box-header with-border">
	          <h3 class="box-title">Select User</h3>
	        </div>
	        <!-- /.box-header -->
	        <!-- form start -->
	        	<form class="form-horizontal" method="post" role="" action="{{url('select-user-for-change-password')}}" name="myFormReport" enctype="multipart/form-data">
	        	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">User Type</label>
	              		<div class="col-md-3">
	              			<select class="form-control select2 usertype" data-url="{{url('select-user-basedon-usertype')}}" name="userType" required>
	              				<option selected disabled value="">Please Select</option>
	              				@if($allUserType)
	              					@foreach($allUserType as $item)
	              					<option value="{{$item->id}}">{{$item->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              		<label for="inputEmail3" class="col-md-2 control-label">User Name</label>
	              		<div class="col-md-3">
	              			<select class="form-control select2" name="userId" required>
	              				<option selected disabled value="">Please Select</option>
	              				
	              			</select>
	              		</div>
	              	</div>
	              </div>
	              <!-- /.box-body -->
	              <div class="box-footer">
	              	<button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
	              </div>
	              <!-- /.box-footer -->
	            </form>
	          <!-- </form> -->
		  </div>
	  	</div>
	</div>
</section>



@endsection