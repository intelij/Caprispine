@extends('layouts.apps')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
        	<div class="box">
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped myTable">
	                <thead>
		                <tr>
		                  <th>S.No</th>
		                  <th>Name</th>
		                  <th>Patient Name</th>
		                  <th>Capri Point</th>
		                  <th>Capri Amount</th>
		                  <th>Type</th>
		                  <th>Remark</th>
		                  <th>Paytm Name</th>
		                  <th>Paytm No</th>
		                  <th>Bank Name</th>
		                  <th>Transaction ID</th>
		                  <th>Account Name</th>
		                  <th>Account No</th>
		                  <th>IFSC Code</th>
		                  <th>Note</th>
		                  <th>Created At</th>
		                  <th>Action</th>
		                </tr>
	                </thead>
	                <tbody>
		                @if($allData)
		                	@foreach($allData as $item)
		                		<tr>
		                			<td>
			                			{{$no++}}
			                			@if($item->type == 'pendingForDebit')
			                				<img src="{{asset('public/upload/images/2.gif')}}" class="bellImg{{$item->id}}" alt="" width="80" height="50">
			                			@endif
			                		</td>
		                			<td>@if($item->user_id) {{userDetails($item->user_id)->name}} @endif</td>
		                			<td>@if($item->other_user_id) {{userDetails($item->other_user_id)->name}} @endif</td>
		                			<td>{{$item->cp_point}}</td>
		                			<td>{{$item->cp_amount}}</td>
		                			<td class="amtType{{$item->id}}">@if($item->type == 'pendingForDebit') Pending for Approvel @else {{ucfirst($item->type)}} @endif</td>
		                			<td>{{$item->remark}}</td>
		                			<td>{{$item->paytm_name}}</td>
		                			<td>{{$item->paytm_no}}</td>
		                			<td>{{$item->bank_name}}</td>
		                			<td>{{$item->transaction_id}}</td>
		                			<td>{{$item->account_name}}</td>
		                			<td>{{$item->account_no}}</td>
		                			<td>{{$item->ifsc_code}}</td>
		                			<td>{{$item->note}}</td>
		                			<td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
		                			<th>
		                				@if($item->type == 'pendingForDebit')
		                					<a title="Accept Pending Request" href="{{url('accept-pending-wallet-request')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want acept request?')" class="btn bg-info" data-id="{{$item->id}}" style="background: #ffbc00;color: black;">Accept Request</a>
		                				@endif
		                				@if($item->type == 'accepted')
		                					<a title="Approve Pending Request" href="#" id="approvedCPoint" class="btn bg-purple approvedCPoint" data-id="{{$item->id}}" data-toggle="modal" data-target="#modal-approved-cpoint">Approve Request</a>
		                				@endif
		                			</th>
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
<!-- Modal -->
<div class="modal fade" id="modal-approved-cpoint">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Approved Request</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('approve-pending-wallet-request')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <input type="text" name="cpointId" class="cpointId" value="" style="display: none;">
              <label for="inputEmail3" class="col-md-3 control-label">Transaction ID </label>
              <div class="col-md-6">
                <input type="text" name="transactionId" class="form-control" placeholder="Enter Transaction ID" required>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success pull-left">Submit</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection