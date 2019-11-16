@extends('layouts.apps')
@section('content')
<section class="content">
    <div class="row">
    	<div class="col-md-12">
	      <!-- Horizontal Form -->
	      <div class="box box-info">
	        <div class="box-header with-border">
	          <h3 class="box-title">Select Patient</h3>
	        </div>
	        <!-- /.box-header -->
	        <!-- form start -->
	        	<form class="form-horizontal" method="post" role="" action="{{url('search-patient-exam')}}" name="myFormReport" enctype="multipart/form-data">
	        	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Patient Name</label>
	              		<div class="col-md-6">
	              			<select class="form-control select2" name="patientId" required>
	              				<option selected disabled value="">Please Select</option>
	              				@if($allPatient)
	              					@foreach($allPatient as $item)
	              					<option value="{{$item->id}}">{{$item->name}} ( {{$item->mobile}} )</option>
	              					@endforeach
	              				@endif
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