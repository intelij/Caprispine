@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-12">
        	<div class="box">
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped">
	                <thead>
		                <tr>
		                  <th>User Type</th>
		                  <th>Created At</th>
		                  <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
		                @if($allData)
		                	@foreach($allData as $item)
		                		<tr>
		                			<td>{{$item->name}}</td>
		                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
		                			<td>
		                				<!-- <a title="View" href="{{url('view-appointment/')}}/{{$item->id}}" class="btn bg-maroon"><i class="fa fa-eye" aria-hidden="true"></i></a> -->
		                				<a title="Permission" href="{{url('add-permission/')}}/{{$item->id}}" class="btn btn-warning"><i class="fa fa-lock"></i></a>
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
</section>

@endsection