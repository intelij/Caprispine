@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
        	<!-- Horizontal Form -->
	        <div class="box box-info">
	            <div class="box-header with-border">
	              <h3 class="box-title">Report</h3>
	            </div>
	            <!-- /.box-header -->
	            <!-- form start -->
	            <form class="form-horizontal formloc" method="post" action="{{url('search-all-report')}}">
	            	{{ csrf_field() }}
	              <div class="box-body">
	                <div class="form-group row">
	                  <label for="inputEmail3" class="col-md-2 control-label">Therapist Name</label>
	                  <div class="col-md-3">
	                    <input type="text" class="form-control" name="therapistName" value="" placeholder="Enter Therapist Name">
	                  </div>
	                  <label for="inputEmail3" class="col-md-2 control-label">Branch</label>
	                  <div class="col-md-3">
	                    <select class="form-control select2" name="branch">
	                    	<option disabled selected>Please Select</option>
	                    	@if($allBranch)
	                    		@foreach($allBranch as $branch)
	                    		<option value="{{$branch->id}}">{{$branch->name}}</option>
	                    		@endforeach
	                    	@endif
	                    </select>
	                  </div>
	                </div>
	                <div class="form-group row">
		                <label for="inputEmail3" class="col-md-2 control-label">From Date</label>
		                <div class="col-md-3">
		                   <input type="date" class="form-control" name="to_date" value="" placeholder="">
		                </div>
	                	<label for="inputEmail3" class="col-md-2 control-label">To Date</label>
		                <div class="col-md-3">
		                   <input type="date" class="form-control" name="from_date" value="" placeholder="">
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

        	<div class="box box-info">
        		<div class="box-header with-border right">
	              <!-- <h3 class="box-title "><a title="Download Report" href="{{url('therapist-report-export')}}" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Export</a></h3> -->
	            </div>
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped dataTable">
	                <thead>
		                <tr>
		                  <th>Name</th>
		                  <th>Staff Working Days (TWD)</th>
		                  <th>Staff Collection</th>
		                  <th>Therapist Collection</th>
		                  <th>Capri Collection</th>
		                  <th>Total Visit (TP)</th>
		                  <th>Average Collection/Day (ACD)</th>
		                  <th>Average Patient Treated/Day (APTD)</th>
		                  <th>Average Rx Cost/Patient (CPV)</th>
		                  <th>Total Sharing (TS)</th>
		                  <th>Average Sharing/Visit (ASPV)</th>
		                  <th>Total Planned Patient (TAP)</th>
		                  <th>Total Appointment Visit (TAV)</th>
		                  <th>Total Walkin (TAW)</th>
		                  <th>Total New Pt (TNP)</th>
		                  <th>Average Appointment Visit/DAY (AAV)</th>
		                  <th>Average Walk IN/Day (AAW)</th>
		                  <th>Avergae New Patient/DAY (ANP)</th>
		                  <th>Sharing of Planned Visit (SAV)</th>
		                  <th>Sharing of Walk IN Visit (SAW)</th>
		                  <th>Sharing of NEW VISIT (SNP)</th>
		                  <th>Appointment Visit % (AAV)</th>
		                  <th>Apoointment Visit Miss % (AVM)</th>
		                  <th>Penalty from WALKIN (PAWM)</th>
		                  <th>Effective Sharing of Walk IN (ESAW)</th>
		                  <th>IPD</th>
		                  <th>Effective Sharing (ES)</th>
		                  <th>Effective Sharing/DAY (ESPD)</th>
		                  <th>Leaves</th>
		                  <th>Deducted Leaves</th>
		                  <th>Sharing Final</th>
		                  <th>TDS</th>
		                  <th>Amount to be transferred</th>
		                  <th>Walkin Patient Missed (AWM)</th>
		                  <th>Walkin Patient Missed (AWM%)</th>
		                  <th>Patient Loss %</th>
		                  <th>No. Patient Loss</th>
		                  <th>Financial Loss to Therapist</th>
		                  <th>Capri Penalties</th>
		                  <th>Total Loss for Therapist</th>
		                  <th>Amount without Loss</th>
		                </tr>
	                </thead>
	                <tbody>
	                	@if($allData)
		                	@foreach($allData as $item)
			                	<tr>
			                	  <td>{{userDetails($item->id)->name}}</td>
				                  <td>{{$item->TWD}}</td>
				                  <td>{{$item->totalStaffCollection}} 
				                  	@if(Auth::user()->user_type == 'superadmin')
					                  	@if($item->totalStaffCollection != 0) 
					                  		<a href="{{url('all-staff-collection/')}}/{{$item->id}}/{{$item->to_date}}/{{$item->from_date}}" target="_blank"><i class="fa fa-eye"></i></a> 
					                  	@endif 
				                  	@endif
				                  </td>
				                  <td>{{$item->therapistCollection}}</td>
				                  <td>{{$item->capriCollection}}</td>
				                  <td>{{$item->totalPatientVisit}}</td>
				                  <td>{{$item->ACD}}</td>
				                  <td>{{$item->APTD}}</td>
				                  <td>{{$item->CPV}}</td>
				                  <td>{{$item->totalSharing}}</td>
				                  <td>{{$item->ASPV}}</td>
				                  <td>{{$item->TAP}}</td>
				                  <td>{{$item->TAV}}</td>
				                  <td>{{$item->TAW}}</td>
				                  <td>{{$item->TNP}}</td>
				                  <td>{{$item->AAV}}</td>
				                  <td>{{$item->AAW}}</td>
				                  <td>{{$item->ANP}}</td>
				                  <td>{{$item->SAV}}</td>
				                  <td>{{$item->SAW}}</td>
				                  <td>{{$item->SNP}}</td>
				                  <td>{{$item->AAVP}}</td>
				                  <td>{{$item->AVMiss}}</td>
				                  <td>{{$item->PAWM}}</td>
				                  <td>{{$item->ESAW}}</td>
				                  <td>{{$item->IPD}}</td>
				                  <td>{{$item->ES}}</td>
				                  <td>{{$item->ESPD}}</td>
				                  <td>{{$item->leaves}}</td>
				                  <td>{{$item->deductLeave}}</td>
				                  <td>{{$item->sharingFinal}}</td>
				                  <td>{{$item->TDS}}</td>
				                  <td>{{$item->amountTransfer}}</td>
				                  <td>{{$item->AWM}}</td>
				                  <td>{{$item->AWMP}}</td>
				                  <td>{{$item->patientLoss}}</td>
				                  <td>{{$item->noPatientLoss}}</td>
				                  <td>{{$item->financialLoss}}</td>
				                  <td>{{$item->penaltyKitty}}</td>
				                  <td>{{$item->totalLoss}}</td>
				                  <td>{{$item->amountWithoutLoss}}</td>
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