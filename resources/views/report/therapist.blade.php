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
	            <form class="form-horizontal formloc" method="post" action="{{url('search-report')}}">
	            	{{ csrf_field() }}
	              <div class="box-body">
	                <div class="form-group row">
	                  <label for="inputEmail3" class="col-md-2 control-label">Therapist Name</label>
	                  <div class="col-md-3">
	                    <input type="text" class="form-control" name="therapistName" value="" placeholder="Enter Therapist Name">
	                  </div>
	                  <label for="inputEmail3" class="col-md-2 control-label">Branch</label>
	                  <div class="col-md-3">
	                    <select class="form-control" name="branch">
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
	                	<label for="inputEmail3" class="col-md-2 control-label">To Date</label>
		                <div class="col-md-3">
		                   <input type="date" class="form-control" name="to_date" value="" placeholder="">
		                </div>
		                <label for="inputEmail3" class="col-md-2 control-label">From Date</label>
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
	              <!--<h3 class="box-title "><a title="Download Report" href="{{url('therapist-report-export')}}" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i> Export</a></h3>-->
	            </div>
	            <div class="box-body">
	              <table id="example1" class="table table-bordered table-striped dataTable">
	                <thead>
		                <tr>
		                  <th>Name</th>
		                  <th>Staff Working Days (TWD)</th>
		                  <th>Staff Collection (TC)</th>
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
		                  <!-- <th>Capri Penalties</th> -->
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
@php
$TWDs = totalWorkingDaysForReport($item->id);
if($TWDs){
	$TWD = $TWDs;
}else{
	$TWD = 0;
}
@endphp
				                  <td>{{$TWD}}</td>
				                  <td>@if(totalStaffCollection($item->id) != 0) {{totalStaffCollection($item->id)}} @else 0 @endif</td>
				                  <td>@if(totalPatientVisit($item->id) != 0) {{totalPatientVisit($item->id)}} @else 0 @endif</td>
				                  <td>@if((totalStaffCollection($item->id) != 0) && ($TWD != 0)) {{round(totalStaffCollection($item->id) / $TWD, 2)}} @else 0 @endif</td>
				                  <td>@if((totalPatientVisit($item->id) != 0) && ($TWD != 0)) {{round(totalPatientVisit($item->id) / $TWD, 2)}} @else 0 @endif</td>
				                  <td>@if(totalPatientVisit($item->id) != 0 && totalStaffCollection($item->id) != 0) {{round(totalStaffCollection($item->id) / totalPatientVisit($item->id), 2)}} @else 0 @endif</td>
@php
	$totalAmount = totalSharingAmount($item->id);
	if($totalAmount){
		$totalSharing = $totalAmount;
	}else{
		$totalSharing = 0;
	}
@endphp

				                  <td>{{$totalSharing}}</td>
@php
	if((totalPatientVisit($item->id) != 0) && !empty($totalSharing)){
		$averageSharing = round($totalSharing / totalPatientVisit($item->id));
	}else{
		$averageSharing = 0;
	}
@endphp
				                  <td>{{$averageSharing}}</td>
				                  <td>@if(totalPlannedAppointment($item->id) != 0) {{totalPlannedAppointment($item->id)}} @else 0 @endif</td>
				                  <td>@if(totalApoointedVisitPatient($item->id)) {{totalApoointedVisitPatient($item->id)}} @else 0 @endif</td>
				                  <td>@if(totalApoointedWithoutVisitedPatient($item->id) != 0) {{totalApoointedWithoutVisitedPatient($item->id)}} @else 0 @endif</td>
				                  <td>@if(totalApoointedTNP($item->id) != 0) {{totalApoointedTNP($item->id)}} @else 0 @endif</td>
				                  <td>@if((totalApoointedVisitPatient($item->id) != 0) && ($TWD != 0)) {{round(totalApoointedVisitPatient($item->id) / $TWD, 2)}} @else 0 @endif</td>
				                  <td>@if((totalApoointedWithoutVisitedPatient($item->id) != 0) && ($TWD != 0)) {{round(totalApoointedWithoutVisitedPatient($item->id) / $TWD, 2)}} @else 0 @endif</td>
				                  <td>@if((totalApoointedTNP($item->id) != 0) && ($TWD != 0)) {{round(totalApoointedTNP($item->id) / $TWD, 2)}} @else 0 @endif</td>
@php
	if(!empty(totalApoointedVisitPatient($item->id)) && ($averageSharing != 0)){
		$SAV = $averageSharing * totalApoointedVisitPatient($item->id);
	}else{
		$SAV = 0;
	}
@endphp
				                  <td>{{$SAV}}</td>
@php
	if(($averageSharing != 0) && !empty(totalApoointedWithoutVisitedPatient($item->id))){
		$SAW = $averageSharing * totalApoointedWithoutVisitedPatient($item->id);
	}else{
		$SAW = 0;
	}
@endphp
				                  <td>{{$SAW}}</td>
@php
	if(($averageSharing != 0) && !empty(totalApoointedTNP($item->id))){
		$SNP = $averageSharing * totalApoointedTNP($item->id);
	}else{
		$SNP = 0;
	}
@endphp
				                  <td>{{$SNP}}</td>
@php
if(totalApoointedVisitPatient($item->id) != 0 && totalPlannedAppointment($item->id) != 0){
	$AAV = totalApoointedVisitPatient($item->id) / totalPlannedAppointment($item->id) * 100;
}else{
	$AAV = 0;
}
@endphp
				                  <td>{{round($AAV, 2)}}</td>
@php
if($AAV == 0){
	$AVMiss = 0;
}else{
	if($AAV > 80){
		$AVMiss = 0;
	}else{
		$AVMiss = 80 - $AAV;
	}	
}
@endphp
				                  <td>{{round($AVMiss,2)}}</td>
@php
if(($SAW != 0) && ($AVMiss != 0)){
	$PAWM = $SAW * $AVMiss / 100;
}else{
	$PAWM = 0;
}
@endphp
				                  <td>{{round($PAWM,2)}}</td>
@php
if($SAW != 0){
	$ESAW = $SAW - $PAWM;
}else{
	$ESAW = 0;
}
@endphp				                  
				                  <td>{{round($ESAW,2)}}</td>
@php
$penaltyCapri = 200;
@endphp
				                  <!-- <td>{{$penaltyCapri}}</td> -->
@php
$totalIPD = totalIPDAmount($item->id);
$IPD = $totalIPD * 800;
@endphp
				                  <td>{{$IPD}}</td>
@php
$ES = $SAV + $SNP + $ESAW + $IPD;
@endphp
				                  <td>{{round($ES,2)}}</td>
@php
if($ES != 0){
	$ESPD = $ES / 30;
}else{
	$ESPD = 0;
}
@endphp
				                  <td>{{round($ESPD,2)}}</td>
@php
$leave = totalLeaves($item->id);
@endphp
				                  <td>{{$leave}}</td>
@php
if($leave > 1){
	$deductLeave = $ESPD * $leave;
}else{
	$deductLeave = 0;
}
@endphp
				                  <td>{{round($deductLeave,2)}}</td>
@php
if($ES != 0){
	$sharingFinal = $ES - $deductLeave;
}else{
	$sharingFinal = 0;
}
@endphp
				                  <td>{{round($sharingFinal,2)}}</td>
@php
if($sharingFinal != 0){
	$TDS = round($sharingFinal * 10 / 100, 0);
}else{
	$TDS = 0;
}
@endphp
				                  <td>{{$TDS}}</td>
@php
if($sharingFinal != 0){
	$amountTransfer = $sharingFinal - $TDS;
}else{
	$amountTransfer = 0;
}
@endphp
				                  <td>{{round($amountTransfer,2)}}</td>
@php
if(totalPlannedAppointment($item->id) != 0){
	$AWM = (totalPlannedAppointment($item->id) * 20 / 100) - totalApoointedWithoutVisitedPatient($item->id);
}else{
	$AWM = 0;
}
@endphp
				                  <td>{{round($AWM,2)}}</td>
@php
if($AWM != 0 && totalPlannedAppointment($item->id) != 0){
	$AWMP = $AWM / totalPlannedAppointment($item->id) * 100;
}else{
	$AWMP = 0;
}
@endphp
				                  <td>{{round($AWMP,2)}}</td>
@php
$patientLoss = $AWMP + $AVMiss;
@endphp
				                  <td>{{round($patientLoss,2)}}</td>
@php
if(totalPlannedAppointment($item->id) != 0 && $patientLoss != 0){
	$noPatientLoss = $patientLoss * totalPlannedAppointment($item->id) / 100;
}else{
	$noPatientLoss = 0;
}
@endphp
				                  <td>{{round($noPatientLoss,2)}}</td>
@php
if(($noPatientLoss != 0) && ($averageSharing != 0)){
	$financialLoss = $noPatientLoss * $averageSharing;
}else{
	$financialLoss = 0;
}
@endphp
				                  <td>{{round($financialLoss,2)}}</td>
@php
$totalPenalty = totalPenalty($item->id);
if($totalPenalty != 0){
	$penaltyKitty = $totalPenalty;
}else{
	$penaltyKitty = 0;
}
@endphp
				                  <td>{{$penaltyKitty}}</td>
@php
$totalLoss = $financialLoss + $penaltyKitty;
@endphp
				                  <td>{{round($totalLoss,2)}}</td>
@php
$amountWithoutLoss = $sharingFinal + $totalLoss;
@endphp
				                  <td>{{round($amountWithoutLoss,2)}}</td>
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

