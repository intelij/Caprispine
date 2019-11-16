@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Permission</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if($editData == '')
            	<form class="form-horizontal" method="post" action="{{url('update-module-assignment')}}" enctype="multipart/form-data">
           	@else
           		<form class="form-horizontal" method="post" action="{{url('update-new-assignment/')}}/{{$editData->id}}" enctype="multipart/form-data">
           	@endif
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">User Type</label>
	              		<div class="col-md-3">
	              			@if($editData == '')
		              			<select class="form-control patientType select2" name="userType" data-placeholder="Select User Type">
		              				@if($userTypeData)
		              					@foreach($userTypeData as $uTypeItem)
		              						<option value="{{$uTypeItem->id}}" >{{$uTypeItem->name}}</option>
		              					@endforeach
		              				@endif
		              			</select>
		              		@else
		              			<input type="text" name="user_type" value="{{userTypeDetails($editData->user_type)->name}}" class="form-control" readonly>
		              		@endif
	              		</div>

	              		<label for="inputEmail3" class="col-md-2 control-label">Module Name</label>
	              		<div class="col-md-3">
	              			@if($editData == '')
		              			<select class="form-control select2 module_name" name="module_name" data-url="{{url('sub-module-data/')}}" id="module_name">
		              				<option disabled selected>Please Select</option>
		              				@if($moduleData)
		              					@foreach($moduleData as $mData)
		              					<option value="{{$mData->id}}" @if($editData) {{ $editData->assign_module == $mData->id ? 'selected="selected"' : ''}} @endif >{{$mData->name}}</option>
		              					@endforeach
		              				@endif
		              			</select>
		              		@else
		              			<input type="text" name="module_name" value="{{moduleDetails($editData->assign_module)->name}}" class="form-control" readonly>
		              		@endif
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-2 control-label">Sub Module Name</label>
	              		<div class="col-md-3">
	              			@if($editData)
	              				@php
								$selectedData = explode(",",$editData->assign_sub_modules);
								@endphp
							@endif
	              			<select class="form-control select2" id="sub_module_name" name="sub_module_name[]" multiple>
	              				@if($allSubModule)
	              					@foreach($allSubModule as $allSubM)
	              						<option value="{{$allSubM->id}}" @foreach($selectedData as $sItem) {{ $sItem == $allSubM->id ? 'selected="selected"' : '' }} @endforeach >{{$allSubM->name}}</option>
	              					@endforeach
	              				@endif
	              			</select>
	              		</div>
	              	</div>
	              </div>
	                <!-- /.box-body -->
	                <div class="box-footer">
		                <button type="submit" class="btn btn-success">Assign</button>
		                <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
	                </div>
	              <!-- /.box-footer -->
	            </form>
          	</div>
      	</div>
 	</div>
 	@if($editData == '')
 	<div class="row">
        <div class="col-xs-12">
        	<div class="box">
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped">
	                <thead>
		                <tr>
		                  <th>User Type</th>
		                  <th>Module</th>
		                  <th>Sub Module</th>
		                  <th>Created At</th>
		                  <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
		                @if($permissionData)
		                	@foreach($permissionData as $item)
		                		<tr>
		                			<td>{{userTypeDetails($item->user_type)->name}}</td>
		                			<td>{{moduleDetails($item->assign_module)->name}}</td>
		                			<td>
		                				@php
		                					$getSubmodules = explode(',',$item->assign_sub_modules);
		                					if(count($getSubmodules) > 0){
		                						$allName = array();
		                						foreach($getSubmodules as $value){
		                							$name = subModuleDetails($value)->name;
		                							array_push($allName,$name);
		                						}
		                						$nname = implode(", ",$allName);
		                					}else{
		                						$nname = '';
		                					}
		                				@endphp
		                				{{$nname}}
		                			</td>
		                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
		                			<td>
		                				<a title="Edit Module" href="{{url('edit-module-assignment/')}}/{{$item->id}}" class="btn btn-warning"><i class="fa fa-edit" aria-hidden="true"></i></a>
		                				<a title="Delete Module Assignment" href="{{url('delete-module-assignment/')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to delete this module?')" class="btn btn-danger"><i class="fa fa-times"></i></a>
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
    </div>
    @endif
</section>

@endsection