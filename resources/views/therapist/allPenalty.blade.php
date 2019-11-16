@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
        	<!-- Horizontal Form -->
	        <div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">All Penalty</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            <form class="form-horizontal" method="post" action="{{url('search-penalty')}}/{{$id}}">
	            	{{ csrf_field() }}
	              <div class="box-body">
	                <div class="form-group">
	                  <label for="inputEmail3" class="col-sm-2 control-label">From Date</label>
	                  <div class="col-md-3">
	                    <input type="date" class="form-control" name="from_date" value="" placeholder="">
	                  </div>
	                  <label for="inputEmail3" class="col-sm-2 control-label">To Date</label>
	                  <div class="col-md-3">
	                    <input type="date" class="form-control" name="to_date" value="" placeholder="">
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
	        	<div class="box-header with-border">
	              <h3 class="box-title"><b>@if($therapistId) {{userDetails($therapistId)->name}} @endif</b></h3> [Total Penalty = @if(!empty($allPenalty)) {{$allPenalty}}/- @else 0/- @endif]
	            </div>
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped">
	                <thead>
		                <tr>
		                  <th>Penalty Name</th>
		                  <th>Late Time</th>
		                  <th>Amount</th>
		                  <th>Created At</th>
		                </tr>
	                </thead>
	                <tbody>
		                @if($allData)
		                	@foreach($allData as $item)
		                		<tr>
		                			<td>
		                				@if($item->penalty_id == 'late_comming')
		                					Late Comming
		                				@else
		                					{{penaltyDetails($item->penalty_id)->name}}
		                				@endif
		                			</td>
		                			<td>@if($item->late_time) {{$item->late_time}} min @else - @endif</td>
		                			<td>@if($item->amount) {{$item->amount}}/- @endif</td>
		                			<td>{{date("d-M-Y", strtotime($item->date))}}</td>
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
    <div class="row">

    </div>
</section>

@endsection