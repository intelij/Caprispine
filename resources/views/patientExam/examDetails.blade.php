@extends('layouts.apps')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
        	<div class="box">
	            <div class="box-body">
                @if($key == 'chief_complaint')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Chief Complaints</b></h2>
                    <a href="{{url('exam-details/history_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
  	              <table id="example1" class="table table-bordered table-striped">
  	                <thead>
  		                <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Chief Complaint</th>
  		                  <th>Problem Time</th>
                        <th>Problem Before</th>
  		                  <th>Created At</th>
  		                </tr>
  	                </thead>
  	                <tbody>
  		                @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->chief_complaint}}</td>
                            <td>{{$item->problem_time}}</td>
                            <td>{{$item->problem_time}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
  	                </tbody>
  	              </table>
                @elseif($key == 'pain_examination')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Pain Examination</b></h2>
                    <a href="{{url('exam-details/adl_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Intensity of Pain</th>
                        <th>Nature of Pain</th>
                        <th>Onset of Pain</th>
                        <th>Pain</th>
                        <th>Feel More in Pain</th>
                        <th>Aggravating Factor</th>
                        <th>Relieving Factor</th>
                        <th>Aggravating Description</th>
                        <th>Relieving Description</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->intensity_of_pain}}</td>
                            <td>{{$item->nature_of_pain}}</td>
                            <td>{{$item->onset_of_pain}}</td>
                            <td>{{$item->pain}}</td>
                            <td>{{$item->feel_more_pain_in}}</td>
                            <td>{{$item->aggravating_factor}}</td>
                            <td>{{$item->relieving_factor}}</td>
                            <td>{{$item->aggravating_desc}}</td>
                            <td>{{$item->relieving_desc}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'body_chart')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Body Chart</b></h2>
                    <a href="{{url('exam-details/physical_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Front Part</th>
                        <th>Back Part</th>
                        <th>Right Part</th>
                        <th>Left Part</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>@if($item->front_chart) <a href="{{BODY_CHART.$item->front_chart}}" target="_blank">Front Body Chart</a> @endif</td>
                            <td>@if($item->back_chart) <a href="{{BODY_CHART.$item->back_chart}}" target="_blank">Back Body Chart</a> @endif</td>
                            <td>@if($item->right_chart) <a href="{{BODY_CHART.$item->right_chart}}" target="_blank">Right Body Chart</a> @endif</td>
                            <td>@if($item->left_chart) <a href="{{BODY_CHART.$item->left_chart}}" target="_blank">Left Body Chart</a> @endif</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'combined_movement_spine')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Combined Movement Spine</b></h2>
                    <a href="{{url('exam-details/cervical_spine')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Cervical Spine</th>
                        <th>Thoracic Spine</th>
                        <th>Lumbar Spine</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->cervical_spine}}</td>
                            <td>{{$item->thoracic_spine}}</td>
                            <td>{{$item->lumbar_spine}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'thoracic_spine')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Thoracic Spine</b></h2>
                    <a href="{{url('exam-details/lumbar_spine')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion</th>
                        <th>Extension</th>
                        <th>Left Side Flexion</th>
                        <th>Right Side Flexion</th>
                        <th>Left Rotation</th>
                        <th>Right Rotation</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexion}}</td>
                            <td>{{$item->extension}}</td>
                            <td>{{$item->sideFlexionLeft}}</td>
                            <td>{{$item->sideFlexionRight}}</td>
                            <td>{{$item->rotationLeft}}</td>
                            <td>{{$item->rotationRight}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'hip_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Hip Exam</b></h2>
                    <a href="{{url('exam-details/knee_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Abduction Left Tone</th>
                        <th>Abduction Left Power</th>
                        <th>Abduction Left ROM</th>
                        <th>Abduction Right Tone</th>
                        <th>Abduction Right Power</th>
                        <th>Abduction Right ROM</th>
                        <th>Adduction Left Tone</th>
                        <th>Adduction Left Power</th>
                        <th>Adduction Left ROM</th>
                        <th>Adduction Right Tone</th>
                        <th>Adduction Right Power</th>
                        <th>Adduction Right ROM</th>
                        <th>External Rotation Left Tone</th>
                        <th>External Rotation Left Power</th>
                        <th>External Rotation Left ROM</th>
                        <th>External Rotation Right Tone</th>
                        <th>External Rotation Right Power</th>
                        <th>External Rotation Right ROM</th>
                        <th>Internal Rotation Left Tone</th>
                        <th>Internal Rotation Left Power</th>
                        <th>Internal Rotation Left ROM</th>
                        <th>Internal Rotation Right Tone</th>
                        <th>Internal Rotation Right Power</th>
                        <th>Internal Rotation Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{$item->abductionLeftTone}}</td>
                            <td>{{$item->abductionLeftPower}}</td>
                            <td>{{$item->abductionLeftROM}}</td>
                            <td>{{$item->abductionRightTone}}</td>
                            <td>{{$item->abductionRightPower}}</td>
                            <td>{{$item->abductionRightROM}}</td>
                            <td>{{$item->adductionLeftTone}}</td>
                            <td>{{$item->adductionLeftPower}}</td>
                            <td>{{$item->adductionLeftROM}}</td>
                            <td>{{$item->adductionRightTone}}</td>
                            <td>{{$item->adductionRightPower}}</td>
                            <td>{{$item->adductionRightROM}}</td>
                            <td>{{$item->extRotationLeftTone}}</td>
                            <td>{{$item->extRotationLeftPower}}</td>
                            <td>{{$item->extRotationLeftROM}}</td>
                            <td>{{$item->extRotationRightTone}}</td>
                            <td>{{$item->extRotationRightPower}}</td>
                            <td>{{$item->extRotationRightROM}}</td>
                            <td>{{$item->intRotationLeftTone}}</td>
                            <td>{{$item->intRotationLeftPower}}</td>
                            <td>{{$item->intRotationLeftROM}}</td>
                            <td>{{$item->intRotationRightTone}}</td>
                            <td>{{$item->intRotationRightPower}}</td>
                            <td>{{$item->intRotationRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'ankle_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Ankle Exam</b></h2>
                    <a href="{{url('exam-details/toes_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Plantar Flexion Left Tone</th>
                        <th>Plantar Flexion Left Power</th>
                        <th>Plantar Flexion Left ROM</th>
                        <th>Plantar Flexion Right Tone</th>
                        <th>Plantar Flexion Right Power</th>
                        <th>Plantar Flexion Right ROM</th>
                        <th>Dorsiflexion Left Tone</th>
                        <th>Dorsiflexion Left Power</th>
                        <th>Dorsiflexion Left ROM</th>
                        <th>Dorsiflexion Right Tone</th>
                        <th>Dorsiflexion Right Power</th>
                        <th>Dorsiflexion Right ROM</th>
                        <th>Eversion Left Tone</th>
                        <th>Eversion Left Power</th>
                        <th>Eversion Left ROM</th>
                        <th>Eversion Right Tone</th>
                        <th>Eversion Right Power</th>
                        <th>Eversion Right ROM</th>
                        <th>Inversion Left Tone</th>
                        <th>Inversion Left Power</th>
                        <th>Inversion Left ROM</th>
                        <th>Inversion Right Tone</th>
                        <th>Inversion Right Power</th>
                        <th>Inversion Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->plantFlexLeftTone}}</td>
                            <td>{{$item->plantFlexLeftPower}}</td>
                            <td>{{$item->plantFlexLeftROM}}</td>
                            <td>{{$item->plantFlexRightTone}}</td>
                            <td>{{$item->plantFlexRightPower}}</td>
                            <td>{{$item->plantFlexRightROM}}</td>
                            <td>{{$item->dorsiFlexLeftTone}}</td>
                            <td>{{$item->dorsiFlexLeftPower}}</td>
                            <td>{{$item->dorsiFlexLeftROM}}</td>
                            <td>{{$item->dorsiFlexRightTone}}</td>
                            <td>{{$item->dorsiFlexRightPower}}</td>
                            <td>{{$item->dorsiFlexRightROM}}</td>
                            <td>{{$item->eversionLeftTone}}</td>
                            <td>{{$item->eversionLeftPower}}</td>
                            <td>{{$item->eversionLeftROM}}</td>
                            <td>{{$item->eversionRightTone}}</td>
                            <td>{{$item->eversionRightPower}}</td>
                            <td>{{$item->eversionRightROM}}</td>
                            <td>{{$item->inversionLeftTone}}</td>
                            <td>{{$item->inversionLeftPower}}</td>
                            <td>{{$item->inversionLeftROM}}</td>
                            <td>{{$item->inversionRightTone}}</td>
                            <td>{{$item->inversionRightPower}}</td>
                            <td>{{$item->inversionRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'shoulder_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Shoulder Exam</b></h2>
                    <a href="{{url('exam-details/elbow_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Abduction Left Tone</th>
                        <th>Abduction Left Power</th>
                        <th>Abduction Left ROM</th>
                        <th>Abduction Right Tone</th>
                        <th>Abduction Right Power</th>
                        <th>Abduction Right ROM</th>
                        <th>Adduction Left Tone</th>
                        <th>Adduction Left Power</th>
                        <th>Adduction Left ROM</th>
                        <th>Adduction Right Tone</th>
                        <th>Adduction Right Power</th>
                        <th>Adduction Right ROM</th>
                        <th>Internal Rotation Left Tone</th>
                        <th>Internal Rotation Left Power</th>
                        <th>Internal Rotation Left ROM</th>
                        <th>Internal Rotation Right Tone</th>
                        <th>Internal Rotation Right Power</th>
                        <th>Internal Rotation Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{$item->abductionLeftTone}}</td>
                            <td>{{$item->abductionLeftPower}}</td>
                            <td>{{$item->abductionLeftROM}}</td>
                            <td>{{$item->abductionRightTone}}</td>
                            <td>{{$item->abductionRightPower}}</td>
                            <td>{{$item->abductionRightROM}}</td>
                            <td>{{$item->adductionLeftTone}}</td>
                            <td>{{$item->adductionLeftPower}}</td>
                            <td>{{$item->adductionLeftROM}}</td>
                            <td>{{$item->adductionRightTone}}</td>
                            <td>{{$item->adductionRightPower}}</td>
                            <td>{{$item->adductionRightROM}}</td>
                            <td>{{$item->intRotationLeftTone}}</td>
                            <td>{{$item->intRotationLeftPower}}</td>
                            <td>{{$item->intRotationLeftROM}}</td>
                            <td>{{$item->intRotationRightTone}}</td>
                            <td>{{$item->intRotationRightPower}}</td>
                            <td>{{$item->intRotationRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'forearm_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ForeArm Exam</b></h2>
                    <a href="{{url('exam-details/wrist_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Supination Left Tone</th>
                        <th>Supination Left Power</th>
                        <th>Supination Left ROM</th>
                        <th>Supination Right Tone</th>
                        <th>Supination Right Power</th>
                        <th>Supination Right ROM</th>
                        <th>Pronation Left Tone</th>
                        <th>Pronation Left Power</th>
                        <th>Pronation Left ROM</th>
                        <th>Pronation Right Tone</th>
                        <th>Pronation Right Power</th>
                        <th>Pronation Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->supinationLeftTone}}</td>
                            <td>{{$item->supinationLeftPower}}</td>
                            <td>{{$item->supinationLeftROM}}</td>
                            <td>{{$item->supinationRightTone}}</td>
                            <td>{{$item->supinationRightPower}}</td>
                            <td>{{$item->supinationRightROM}}</td>
                            <td>{{$item->pronationLeftTone}}</td>
                            <td>{{$item->pronationLeftPower}}</td>
                            <td>{{$item->pronationLeftROM}}</td>
                            <td>{{$item->pronationRightTone}}</td>
                            <td>{{$item->pronationRightPower}}</td>
                            <td>{{$item->pronationRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'fingers_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Finger Exam</b></h2>
                    <a href="{{url('exam-details/sacroiliac_joint')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Abduction Left Tone</th>
                        <th>Abduction Left Power</th>
                        <th>Abduction Left ROM</th>
                        <th>Abduction Right Tone</th>
                        <th>Abduction Right Power</th>
                        <th>Abduction Right ROM</th>
                        <th>Adduction Left Tone</th>
                        <th>Adduction Left Power</th>
                        <th>Adduction Left ROM</th>
                        <th>Adduction Right Tone</th>
                        <th>Adduction Right Power</th>
                        <th>Adduction Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{$item->abductionLeftTone}}</td>
                            <td>{{$item->abductionLeftPower}}</td>
                            <td>{{$item->abductionLeftROM}}</td>
                            <td>{{$item->abductionRightTone}}</td>
                            <td>{{$item->abductionRightPower}}</td>
                            <td>{{$item->abductionRightROM}}</td>
                            <td>{{$item->adductionLeftTone}}</td>
                            <td>{{$item->adductionLeftPower}}</td>
                            <td>{{$item->adductionLeftROM}}</td>
                            <td>{{$item->adductionRightTone}}</td>
                            <td>{{$item->adductionRightPower}}</td>
                            <td>{{$item->adductionRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'cervical_spine')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Cervical Spine</b></h2>
                    <a href="{{url('exam-details/thoracic_spine')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion</th>
                        <th>Extension</th>
                        <th>Left Side Flexion</th>
                        <th>Right Side Flexion</th>
                        <th>Left Rotation</th>
                        <th>Right Rotation</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexion}}</td>
                            <td>{{$item->extension}}</td>
                            <td>{{$item->sideFlexionLeft}}</td>
                            <td>{{$item->sideFlexionRight}}</td>
                            <td>{{$item->rotationLeft}}</td>
                            <td>{{$item->rotationRight}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'lumbar_spine')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Lumbar Spine</b></h2>
                    <a href="{{url('exam-details/hip_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion</th>
                        <th>Extension</th>
                        <th>Left Side Flexion</th>
                        <th>Right Side Flexion</th>
                        <th>Left Rotation</th>
                        <th>Right Rotation</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexion}}</td>
                            <td>{{$item->extension}}</td>
                            <td>{{$item->sideFlexionLeft}}</td>
                            <td>{{$item->sideFlexionRight}}</td>
                            <td>{{$item->rotationLeft}}</td>
                            <td>{{$item->rotationRight}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'knee_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Knee Exam</b></h2>
                    <a href="{{url('exam-details/ankle_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'toes_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Toes Exam</b></h2>
                    <a href="{{url('exam-details/shoulder_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'elbow_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Elbow Exam</b></h2>
                    <a href="{{url('exam-details/forearm_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'wrist_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Wrist Exam</b></h2>
                    <a href="{{url('exam-details/fingers_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Flexion Left Tone</th>
                        <th>Flexion Left Power</th>
                        <th>Flexion Left ROM</th>
                        <th>Flexion Right Tone</th>
                        <th>Flexion Right Power</th>
                        <th>Flexion Right ROM</th>
                        <th>Extension Left Tone</th>
                        <th>Extension Left Power</th>
                        <th>Extension Left ROM</th>
                        <th>Extension Right Tone</th>
                        <th>Extension Right Power</th>
                        <th>Extension Right ROM</th>
                        <th>Radial Deviation Left Tone</th>
                        <th>Radial Deviation Left Power</th>
                        <th>Radial Deviation Left ROM</th>
                        <th>Radial Deviation Right Tone</th>
                        <th>Radial Deviation Right Power</th>
                        <th>Radial Deviation Right ROM</th>
                        <th>Ulnar Deviation Left Tone</th>
                        <th>Ulnar Deviation Left Power</th>
                        <th>Ulnar Deviation Left ROM</th>
                        <th>Ulnar Deviation Right Tone</th>
                        <th>Ulnar Deviation Right Power</th>
                        <th>Ulnar Deviation Right ROM</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->flexionLeftTone}}</td>
                            <td>{{$item->flexionLeftPower}}</td>
                            <td>{{$item->flexionLeftROM}}</td>
                            <td>{{$item->flexionRightTone}}</td>
                            <td>{{$item->flexionRightPower}}</td>
                            <td>{{$item->flexionRightROM}}</td>
                            <td>{{$item->extensionLeftTone}}</td>
                            <td>{{$item->extensionLeftPower}}</td>
                            <td>{{$item->extensionLeftROM}}</td>
                            <td>{{$item->extensionRightTone}}</td>
                            <td>{{$item->extensionRightPower}}</td>
                            <td>{{$item->extensionRightROM}}</td>
                            <td>{{$item->radialDevLeftTone}}</td>
                            <td>{{$item->radialDevLeftPower}}</td>
                            <td>{{$item->radialDevLeftROM}}</td>
                            <td>{{$item->radialDevRightTone}}</td>
                            <td>{{$item->radialDevRightPower}}</td>
                            <td>{{$item->radialDevRightROM}}</td>
                            <td>{{$item->ulnarDevLeftTone}}</td>
                            <td>{{$item->ulnarDevLeftPower}}</td>
                            <td>{{$item->ulnarDevLeftROM}}</td>
                            <td>{{$item->ulnarDevRightTone}}</td>
                            <td>{{$item->ulnarDevRightPower}}</td>
                            <td>{{$item->ulnarDevRightROM}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'sacroiliac_joint')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Sacroiliac Joint Exam</b></h2>
                    <a href="{{url('exam-details/sensory_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Left Ant Innominate</th>
                        <th>Right Ant Innominate</th>
                        <th>Left Post Innominate </th>
                        <th>Right Post Innominate </th>
                        <th>Left Up Slip</th>
                        <th>Right Up Slip</th>
                        <th>Left Down Slip</th>
                        <th>Right Down Slip</th>
                        <th>Left Ant Tilt</th>
                        <th>Right Ant Tilt</th>
                        <th>Left Post Tilt</th>
                        <th>Right Post Tilt</th>
                        <th>Left Nutation</th>
                        <th>Right Nutation</th>
                        <th>Left Counter Nutation</th>
                        <th>Right Counter Nutation</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->antInnominateLeft}}</td>
                            <td>{{$item->antInnominateRight}}</td>
                            <td>{{$item->postInnominateLeft}}</td>
                            <td>{{$item->postInnominateRight}}</td>
                            <td>{{$item->upSlipLeft}}</td>
                            <td>{{$item->upSlipRight}}</td>
                            <td>{{$item->downSlipLeft}}</td>
                            <td>{{$item->downSlipRight}}</td>
                            <td>{{$item->antTiltLeft}}</td>
                            <td>{{$item->antTiltRight}}</td>
                            <td>{{$item->postTiltLeft}}</td>
                            <td>{{$item->postTiltRight}}</td>
                            <td>{{$item->nutationLeft}}</td>
                            <td>{{$item->nutationRight}}</td>
                            <td>{{$item->counterNutationLeft}}</td>
                            <td>{{$item->counterNutationRight}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'neurological_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Neuro Logical Exam</b></h2>
                    <a href="{{url('exam-details/ndt_ndp_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Eye Open</th>
                        <th>Verbal Response</th>
                        <th>Motor Response</th>
                        <th>Finger Time</th>
                        <th>Finger Speed</th>
                        <th>Finger Error</th>
                        <th>Aternating Time</th>
                        <th>Aternating Speed</th>
                        <th>Aternating Error</th>
                        <th>Heel Time</th>
                        <th>Heel Speed</th>
                        <th>Heel Error</th>
                        <th>Level Surface</th>
                        <th>Gait Speed</th>
                        <th>Horizantal Head Turns</th>
                        <th>Vertical Head Turns</th>
                        <th>Pivot Turn</th>
                        <th>Over Obstacle</th>
                        <th>Around Obstacle</th>
                        <th>Steps</th>
                        <th>Left Analyser</th>
                        <th>Right Analyser</th>
                        <th>Bowels</th>
                        <th>Bladder</th>
                        <th>Grooming</th>
                        <th>Toilet Use</th>
                        <th>Feeding</th>
                        <th>Transfer</th>
                        <th>Mobility</th>
                        <th>Dressing</th>
                        <th>Stairs</th>
                        <th>Bathing</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->eyeOpen}}</td>
                            <td>{{$item->verbalResponse}}</td>
                            <td>{{$item->motorResponse}}</td>
                            <td>{{$item->fingerTime}}</td>
                            <td>{{$item->fingerSpeed}}</td>
                            <td>{{$item->fingerError}}</td>
                            <td>{{$item->aternatingTime}}</td>
                            <td>{{$item->aternatingSpeed}}</td>
                            <td>{{$item->aternatingError}}</td>
                            <td>{{$item->heelTime}}</td>
                            <td>{{$item->heelSpeed}}</td>
                            <td>{{$item->heelError}}</td>
                            <td>{{$item->levelSurface}}</td>
                            <td>{{$item->gaitSpeed}}</td>
                            <td>{{$item->hrHeadTurns}}</td>
                            <td>{{$item->vrHeadTurns}}</td>
                            <td>{{$item->pivotTurn}}</td>
                            <td>{{$item->overObstacle}}</td>
                            <td>{{$item->aroundObstacle}}</td>
                            <td>{{$item->steps}}</td>
                            <td>{{$item->analyserLeft}}</td>
                            <td>{{$item->analyserRight}}</td>
                            <td>{{$item->bowels}}</td>
                            <td>{{$item->bladder}}</td>
                            <td>{{$item->grooming}}</td>
                            <td>{{$item->toiletUse}}</td>
                            <td>{{$item->feeding}}</td>
                            <td>{{$item->transfer}}</td>
                            <td>{{$item->mobility}}</td>
                            <td>{{$item->dressing}}</td>
                            <td>{{$item->stairs}}</td>
                            <td>{{$item->bathing}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'special_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Special Exam</b></h2>
                    <a href="{{url('exam-details/investigation')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Special Test</th>
                        <th>Description</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->special_test}}</td>
                            <td>{{$item->description}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'physiotherapeutic_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Physiotherapeutic Exam</b></h2>
                    <a href="{{url('exam-details/treatment_goal')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Physiotherapeutic Diagnosis</th>
                        <th>Medical Diagnosis</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->physiotherapeutic_diagnosis}}</td>
                            <td>{{$item->medical_diagnosis}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'all_notes')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>All Notes</b></h2>
                    <a href="{{url('exam-details/feedback')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Case Note</th>
                        <th>Progress Note</th>
                        <th>Remark</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->case_note}}</td>
                            <td>{{$item->progress_note}}</td>
                            <td>{{$item->remark}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'history_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>History Exam</b></h2>
                    <a href="{{url('exam-details/pain_examination')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Cause of Problem</th>
                        <th>Medical Problem</th>
                        <th>Any Surgery</th>
                        <th>Any Treatment</th>
                        <th>Smoking</th>
                        <th>Alcoholic</th>
                        <th>Fever & Chill</th>
                        <th>Diabetes</th>
                        <th>Blodd Pressure</th>
                        <th>Heart Diseases</th>
                        <th>Bleeding Disorder</th>
                        <th>Recent Infection</th>
                        <th>Any Reg Flag</th>
                        <th>Any Yellow Flag</th>
                        <th>Limitations</th>
                        <th>Past Surgery</th>
                        <th>Allergies</th>
                        <th>Osteoporotic</th>
                        <th>Any Implants</th>
                        <th>Hereditary Disease</th>
                        <th>Remark</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->cause_of_problem}}</td>
                            <td>{{$item->medical_problem}}</td>
                            <td>{{$item->any_surgery}}</td>
                            <td>{{$item->any_treatment}}</td>
                            <td>{{$item->smoking}}</td>
                            <td>{{$item->alcoholic}}</td>
                            <td>{{$item->fever_and_chill}}</td>
                            <td>{{$item->diabetes}}</td>
                            <td>{{$item->blood_pressure}}</td>
                            <td>{{$item->heart_diseases}}</td>
                            <td>{{$item->bleeding_disorder}}</td>
                            <td>{{$item->recent_infection}}</td>
                            <td>{{$item->any_reg_flags}}</td>
                            <td>{{$item->Any_yellow_flags}}</td>
                            <td>{{$item->limitations}}</td>
                            <td>{{$item->past_surgery}}</td>
                            <td>{{$item->allergies}}</td>
                            <td>{{$item->osteoporotic}}</td>
                            <td>{{$item->any_implants}}</td>
                            <td>{{$item->hereditary_disease}}</td>
                            <td>{{$item->remark}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_neck')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Nack</b></h2>
                    <a href="{{url('exam-details/adl_hip')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Pain in Neck</th>
                        <th>Cervicogenic</th>
                        <th>Personal Care</th>
                        <th>Lifting</th>
                        <th>Reading</th>
                        <th>Concentration</th>
                        <th>Routine Work</th>
                        <th>Driving</th>
                        <th>Sleep Disturbance</th>
                        <th>Recreational</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->painInNeck}}</td>
                            <td>{{$item->cervicogenic}}</td>
                            <td>{{$item->personalCare}}</td>
                            <td>{{$item->lifting}}</td>
                            <td>{{$item->reading}}</td>
                            <td>{{$item->concentration}}</td>
                            <td>{{$item->routineWork}}</td>
                            <td>{{$item->driving}}</td>
                            <td>{{$item->sleepDisturbance}}</td>
                            <td>{{$item->recreational}}</td>
                            <td>{{$item->total_score}}</td>
                            <td>{{$item->get_score}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_hip')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Hip</b></h2>
                    <a href="{{url('exam-details/adl_knee')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Standing</th>
                        <th>In out of car</th>
                        <th>Up slope</th>
                        <th>Down Slope</th>
                        <th>Climbing</th>
                        <th>Down Stairs</th>
                        <th>Stepping Up Down</th>
                        <th>Deep Squatting</th>
                        <th>Bath Tub</th>
                        <th>Initial Walking</th>
                        <th>Walking 10 min</th>
                        <th>Walking 15 min</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->standing}}</td>
                            <td>{{$item->inOutOfCar}}</td>
                            <td>{{$item->upSlope}}</td>
                            <td>{{$item->downSlope}}</td>
                            <td>{{$item->climbing}}</td>
                            <td>{{$item->downStairs}}</td>
                            <td>{{$item->steppingUpDown}}</td>
                            <td>{{$item->deepSquatting}}</td>
                            <td>{{$item->bathTub}}</td>
                            <td>{{$item->initialWalking}}</td>
                            <td>{{$item->walking10Min}}</td>
                            <td>{{$item->walking15Min}}</td>
                            <td>{{$item->total_score}}</td>
                            <td>{{$item->get_score}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_knee')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Knee</b></h2>
                    <a href="{{url('exam-details/adl_elbow')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Walking 5 min</th>
                        <th>Walking 10 min</th>
                        <th>Walking 15 min</th>
                        <th>Climbing</th>
                        <th>Down Stair</th>
                        <th>Sleeping Disturbance</th>
                        <th>Resting</th>
                        <th>Standing</th>
                        <th>Morning Stiffness</th>
                        <th>Stiffness During Day</th>
                        <th>Rising From Chair</th>
                        <th>Rising Floor</th>
                        <th>Bending Floor</th>
                        <th>Walking Surface</th>
                        <th>Getting In Out</th>
                        <th>Shopping</th>
                        <th>Puttings Socks</th>
                        <th>Taking Socks</th>
                        <th>Getting Bed</th>
                        <th>Coming Bed</th>
                        <th>Bath Tub</th>
                        <th>Sitting</th>
                        <th>Sitting On Rising</th>
                        <th>Squatting</th>
                        <th>Light Domestic</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->walking5Min}}</td>
                            <td>{{$item->walking10Min}}</td>
                            <td>{{$item->walking15Min}}</td>
                            <td>{{$item->climbing}}</td>
                            <td>{{$item->downStair}}</td>
                            <td>{{$item->sleepingDisturbance}}</td>
                            <td>{{$item->resting}}</td>
                            <td>{{$item->standing}}</td>
                            <td>{{$item->morningStiffness}}</td>
                            <td>{{$item->stiffnessDuringDay}}</td>
                            <td>{{$item->risingFromChair}}</td>
                            <td>{{$item->risingFloor}}</td>
                            <td>{{$item->bendingFloor}}</td>
                            <td>{{$item->walkingSurface}}</td>
                            <td>{{$item->gettingInOut}}</td>
                            <td>{{$item->shopping}}</td>
                            <td>{{$item->puttingsSocks}}</td>
                            <td>{{$item->takingSocks}}</td>
                            <td>{{$item->gettingBed}}</td>
                            <td>{{$item->comingBed}}</td>
                            <td>{{$item->bathTub}}</td>
                            <td>{{$item->sitting}}</td>
                            <td>{{$item->sittingOnRising}}</td>
                            <td>{{$item->squatting}}</td>
                            <td>{{$item->lightDomestic}}</td>
                            <td>{{$item->total_score}}</td>
                            <td>{{$item->get_score}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_wrist_and_hand')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Wrist & Hand</b></h2>
                    <a href="{{url('exam-details/adl_shoulder')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Hand Work</th>
                        <th>Fingers Move</th>
                        <th>Wrist Move</th>
                        <th>Strength Hand</th>
                        <th>Sensation</th>
                        <th>Door Knob</th>
                        <th>Pick Coin</th>
                        <th>Hold Glass</th>
                        <th>Turn Key</th>
                        <th>Frying Pan</th>
                        <th>Jar</th>
                        <th>Hard Blouse</th>
                        <th>Knife Fork</th>
                        <th>Grocery Bag</th>
                        <th>Dishes</th>
                        <th>Hair</th>
                        <th>Knot</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->handWork}}</td>
                            <td>{{$item->fingersMove}}</td>
                            <td>{{$item->wristMove}}</td>
                            <td>{{$item->strengthHand}}</td>
                            <td>{{$item->sensation}}</td>
                            <td>{{$item->doorKnob}}</td>
                            <td>{{$item->pickCoin}}</td>
                            <td>{{$item->holdGlass}}</td>
                            <td>{{$item->turnKey}}</td>
                            <td>{{$item->fryingPan}}</td>
                            <td>{{$item->jar}}</td>
                            <td>{{$item->hardBlouse}}</td>
                            <td>{{$item->knifeFork}}</td>
                            <td>{{$item->groceryBag}}</td>
                            <td>{{$item->dishes}}</td>
                            <td>{{$item->hair}}</td>
                            <td>{{$item->knot}}</td>
                            <td>{{$item->totalScore}}</td>
                            <td>{{$item->getScore}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_anke_and_foot')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Anke & Foot</b></h2>
                    <a href="{{url('exam-details/adl_back')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Standing</th>
                        <th>Walking</th>
                        <th>Walking Without Shoes</th>
                        <th>Walking Up Slope</th>
                        <th>Walking Down Slope</th>
                        <th>climbing Up</th>
                        <th>Going Down</th>
                        <th>Walking On Uneven</th>
                        <th>Stepping</th>
                        <th>Squatting</th>
                        <th>Toes</th>
                        <th>Walking Initially</th>
                        <th>Walking 5 Min</th>
                        <th>Walking 10 Min</th>
                        <th>Walking 15 Min</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->standing}}</td>
                            <td>{{$item->walking}}</td>
                            <td>{{$item->walkingWithoutShoes}}</td>
                            <td>{{$item->walkingUpSlope}}</td>
                            <td>{{$item->walkingDownSlope}}</td>
                            <td>{{$item->climbingUp}}</td>
                            <td>{{$item->goingDown}}</td>
                            <td>{{$item->walkingOnUneven}}</td>
                            <td>{{$item->stepping}}</td>
                            <td>{{$item->squatting}}</td>
                            <td>{{$item->toes}}</td>
                            <td>{{$item->walkingInitially}}</td>
                            <td>{{$item->walking5Min}}</td>
                            <td>{{$item->walking10Min}}</td>
                            <td>{{$item->walking15Min}}</td>
                            <td>{{$item->totalScore}}</td>
                            <td>{{$item->getScore}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_elbow')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Elbow</b></h2>
                    <a href="{{url('exam-details/adl_wrist_and_hand')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Back Pocket</th>
                        <th>Perineal Care</th>
                        <th>armpit</th>
                        <th>Utensils</th>
                        <th>Rise Chair</th>
                        <th>Hair Combing</th>
                        <th>Arm Side</th>
                        <th>Dress Up</th>
                        <th>Pulling Object</th>
                        <th>Throwing</th>
                        <th>Routine Work</th>
                        <th>Sports</th>
                        <th>Palm Down</th>
                        <th>Palm Up</th>
                        <th>Telephone Hand</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->backPocket}}</td>
                            <td>{{$item->perinealCare}}</td>
                            <td>{{$item->armpit}}</td>
                            <td>{{$item->utensils}}</td>
                            <td>{{$item->riseChair}}</td>
                            <td>{{$item->hairCombing}}</td>
                            <td>{{$item->armSide}}</td>
                            <td>{{$item->dressUp}}</td>
                            <td>{{$item->pullingObject}}</td>
                            <td>{{$item->throwing}}</td>
                            <td>{{$item->routineWork}}</td>
                            <td>{{$item->sports}}</td>
                            <td>{{$item->palmDown}}</td>
                            <td>{{$item->palmUp}}</td>
                            <td>{{$item->telephoneHand}}</td>
                            <td>{{$item->totalScore}}</td>
                            <td>{{$item->getScore}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_shoulder')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Shoulder</b></h2>
                    <a href="{{url('exam-details/adl_anke_and_foot')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Back Pocket</th>
                        <th>Perineal Care</th>
                        <th>Armpit</th>
                        <th>Utensils</th>
                        <th>Combhair</th>
                        <th>Hand Use</th>
                        <th>Arm Side</th>
                        <th>Dress Up</th>
                        <th>Sleep</th>
                        <th>Pulling</th>
                        <th>Hand Overhead</th>
                        <th>Throwing</th>
                        <th>Lifting</th>
                        <th>Usual Work</th>
                        <th>Usual Sports</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->backPocket}}</td>
                            <td>{{$item->perinealCare}}</td>
                            <td>{{$item->armpit}}</td>
                            <td>{{$item->utensils}}</td>
                            <td>{{$item->combhair}}</td>
                            <td>{{$item->handUse}}</td>
                            <td>{{$item->armSide}}</td>
                            <td>{{$item->dressUp}}</td>
                            <td>{{$item->sleep}}</td>
                            <td>{{$item->pulling}}</td>
                            <td>{{$item->handOverhead}}</td>
                            <td>{{$item->throwing}}</td>
                            <td>{{$item->lifting}}</td>
                            <td>{{$item->usualWork}}</td>
                            <td>{{$item->usualSports}}</td>
                            <td>{{$item->totalScore}}</td>
                            <td>{{$item->getScore}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'adl_back')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>ADL Back</b></h2>
                    <a href="{{url('exam-details/body_chart')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Back Comfortable</th>
                        <th>Walk Slowly</th>
                        <th>Job</th>
                        <th>Handrail</th>
                        <th>Lie Down</th>
                        <th>Hold Something</th>
                        <th>Other People</th>
                        <th>Dressing Up</th>
                        <th>Standing Up</th>
                        <th>Bending Kneeling</th>
                        <th>Back Painful</th>
                        <th>Turnover</th>
                        <th>Sock</th>
                        <th>Short Distance</th>
                        <th>Sleep Disturbance</th>
                        <th>Heavy Jobs</th>
                        <th>Irritable Badly Tempered</th>
                        <th>Upstairs</th>
                        <th>Laughing Sneezing</th>
                        <th>Travelling Driving</th>
                        <th>Total Score</th>
                        <th>Get Score</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->backComfortable}}</td>
                            <td>{{$item->walkSlowly}}</td>
                            <td>{{$item->job}}</td>
                            <td>{{$item->handrail}}</td>
                            <td>{{$item->lieDown}}</td>
                            <td>{{$item->holdSomething}}</td>
                            <td>{{$item->otherPeople}}</td>
                            <td>{{$item->dressingUp}}</td>
                            <td>{{$item->standingUp}}</td>
                            <td>{{$item->bendingKneeling}}</td>
                            <td>{{$item->backPainful}}</td>
                            <td>{{$item->turnover}}</td>
                            <td>{{$item->sock}}</td>
                            <td>{{$item->shortDistance}}</td>
                            <td>{{$item->sleepDisturbance}}</td>
                            <td>{{$item->heavyJobs}}</td>
                            <td>{{$item->irritableBadlyTempered}}</td>
                            <td>{{$item->upstairs}}</td>
                            <td>{{$item->laughingSneezing}}</td>
                            <td>{{$item->travellingDriving}}</td>
                            <td>{{$item->totalScore}}</td>
                            <td>{{$item->getScore}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'physical_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Physical Exam</b></h2>
                    <a href="{{url('exam-details/motor_examination')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Blood Pressure</th>
                        <th>Temperature</th>
                        <th>Heart Rate</th>
                        <th>Respiratory Rate</th>
                        <th>Posture</th>
                        <th>Gait</th>
                        <th>Scar Description</th>
                        <th>Swelling</th>
                        <th>Tight Contract Deformity</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->blood_pressure}}</td>
                            <td>{{$item->temperature}}</td>
                            <td>{{$item->heart_rate}}</td>
                            <td>{{$item->respiratory_rate}}</td>
                            <td>{{$item->posture}}</td>
                            <td>{{$item->gait}}</td>
                            <td>{{$item->scar_description}}</td>
                            <td>{{$item->swelling}}</td>
                            <td>{{$item->tight_contract_deformity}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'investigation')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Investigation Exam</b></h2>
                    <a href="{{url('exam-details/physiotherapeutic_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Type Of Investigation</th>
                        <th>Description</th>
                        <th>Document</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->typeOfInvestigation}}</td>
                            <td>{{$item->description}}</td>
                            <td>@if($item->document) <a href="{{INVESTIGATION_DOC.$item->document}}" target="_blank">Document</a> @endif</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'feedback')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Feedback</b></h2>
                    <a href="{{url('exam-details/ortho_case')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Review</th>
                        <th>Comments</th>
                        <th>Signature</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>@if($item->reveiw) {{$item->reveiw}} @endif</td>
                            <td>@if($item->comments) {{$item->comments}} @endif</td>
                            <td>@if($item->signature) <img src="{{SIGNATURE_IMG.$item->signature}}" width="60" height="50">@endif</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'treatment_goal')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Treatment Goal</b></h2>
                    <a href="{{url('exam-details/all_notes')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>shortGoal</th>
                        <th>shortMachine</th>
                        <th>shortDose</th>
                        <th>longGoal</th>
                        <th>longMachine</th>
                        <th>longDose</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->shortGoal}}</td>
                            <td>{{$item->shortMachine}}</td>
                            <td>{{$item->shortDose}}</td>
                            <td>{{$item->longGoal}}</td>
                            <td>{{$item->longMachine}}</td>
                            <td>{{$item->longDose}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'sensory_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Sensory Exam</b></h2>
                    <a href="{{url('exam-details/neurological_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Neck Flexion/Extension</th>
                        <th>Neck Lateral Flexion</th>
                        <th>Shoulder Elevation</th>
                        <th>Shoulder Abduction</th>
                        <th>Elbow flexion/wrist flexion</th>
                        <th>Elbow extension/wrist flexionn</th>
                        <th>Thumb extension/Ulnar Deviation</th>
                        <th>Abduction/Adduction of intrinsic</th>
                        <th>Hip flexion</th>
                        <th>Knee extension</th>
                        <th>Ankle dorsiflexion</th>
                        <th>Toe extension</th>
                        <th>Knee flexion, Ankle plantar flexion/eversion, Hip extension</th>
                        <th>Knee flexion</th>
                        <th>Rectal sphincter tone</th>
                        <th>Back Of Head</th>
                        <th>Neck</th>
                        <th>Ant Shoulder</th>
                        <th>Thumb</th>
                        <th>Back Of Arm</th>
                        <th>Ring</th>
                        <th>Medial Arm</th>
                        <th>Interspace</th>
                        <th>Interspace5</th>
                        <th>Xiphoid</th>
                        <th>Umbilicus</th>
                        <th>Pupis</th>
                        <th>Genitars</th>
                        <th>Medial Thigh</th>
                        <th>Anterior Thigh</th>
                        <th>Great Toe</th>
                        <th>Dersum Of Feet</th>
                        <th>Lateral Foot</th>
                        <th>Posteromedical Thigh</th>
                        <th>Perianal Area</th>
                        <th>Biceps</th>
                        <th>Brachioradialis</th>
                        <th>Triceps</th>
                        <th>Finger Flxion</th>
                        <th>Quadriceps</th>
                        <th>Achilles</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->neckFlxExt}}</td>
                            <td>{{$item->neckLatFlx}}</td>
                            <td>{{$item->shoulderEle}}</td>
                            <td>{{$item->shoulderABD}}</td>
                            <td>{{$item->elbowFlx}}</td>
                            <td>{{$item->elbowExt}}</td>
                            <td>{{$item->thumbExt}}</td>
                            <td>{{$item->abduction}}</td>
                            <td>{{$item->hipFlexion}}</td>
                            <td>{{$item->kneeExt}}</td>
                            <td>{{$item->ankleDorsFlx}}</td>
                            <td>{{$item->toeExt}}</td>
                            <td>{{$item->kneeFlxAnklePlant}}</td>
                            <td>{{$item->kneeFlx}}</td>
                            <td>{{$item->rectalSphTone}}</td>
                            <td>{{$item->backOfHead}}</td>
                            <td>{{$item->neck}}</td>
                            <td>{{$item->antShoulder}}</td>
                            <td>{{$item->thumb}}</td>
                            <td>{{$item->backOfArm}}</td>
                            <td>{{$item->ring}}</td>
                            <td>{{$item->medialArm}}</td>
                            <td>{{$item->interspace}}</td>
                            <td>{{$item->interspace5}}</td>
                            <td>{{$item->xiphoid}}</td>
                            <td>{{$item->umbilicus}}</td>
                            <td>{{$item->pupis}}</td>
                            <td>{{$item->genitars}}</td>
                            <td>{{$item->medialThigh}}</td>
                            <td>{{$item->anteriorThigh}}</td>
                            <td>{{$item->greatToe}}</td>
                            <td>{{$item->dersumOfFeet}}</td>
                            <td>{{$item->lateralFoot}}</td>
                            <td>{{$item->posteromedicalThigh}}</td>
                            <td>{{$item->perianalArea}}</td>
                            <td>{{$item->biceps}}</td>
                            <td>{{$item->brachioradialis}}</td>
                            <td>{{$item->triceps}}</td>
                            <td>{{$item->fingerFlx}}</td>
                            <td>{{$item->quadriceps}}</td>
                            <td>{{$item->achilles}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'ndt_ndp_exam')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>NDT NDP Examination</b></h2>
                    <a href="{{url('exam-details/special_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Neur Ulnar Left</th>
                        <th>Neur Ulnar Right</th>
                        <th>Neur Radial Left</th>
                        <th>Neur Radial Right</th>
                        <th>Neur Median Left</th>
                        <th>Neur Median Right</th>
                        <th>Neur Muscul Left</th>
                        <th>Neur Muscul Right</th>
                        <th>Neur Sciatic Left</th>
                        <th>Neur Sciatic Right</th>
                        <th>Neur Tibial Left</th>
                        <th>Neur Tibial Right</th>
                        <th>Neur Comman Left</th>
                        <th>Neur Comman Right</th>
                        <th>Neur Femoral Left</th>
                        <th>Neur Femoral Right</th>
                        <th>Neur LatCuta Left</th>
                        <th>Neur LatCuta Right</th>
                        <th>Neur Obtur Left</th>
                        <th>Neur Obtur Right</th>
                        <th>Neur Sural Left</th>
                        <th>Neur Sural Right</th>
                        <th>Neur Saph Left</th>
                        <th>Neur Saph Right</th>
                        <th>Tiss Ulnar Left</th>
                        <th>Tiss Ulnar Right</th>
                        <th>Tiss Radial Left</th>
                        <th>Tiss Radial Right</th>
                        <th>Tiss Median Left</th>
                        <th>Tiss Median Right</th>
                        <th>Tiss Sciatic Left</th>
                        <th>Tiss Sciatic Right</th>
                        <th>Tiss Tibial Left</th>
                        <th>Tiss Tibial Right</th>
                        <th>Tiss Peronial Left</th>
                        <th>Tiss Peronial Right</th>
                        <th>Tiss Femoral Left</th>
                        <th>Tiss Femoral Right</th>
                        <th>Tiss Sural Left</th>
                        <th>Tiss Sural Right</th>
                        <th>Tiss Saphenous Left</th>
                        <th>Tiss Saphenous Right</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->neurUlnarLeft}}</td>
                            <td>{{$item->neurUlnarRight}}</td>
                            <td>{{$item->neurRadialLeft}}</td>
                            <td>{{$item->neurRadialRight}}</td>
                            <td>{{$item->neurMedianLeft}}</td>
                            <td>{{$item->neurMedianRight}}</td>
                            <td>{{$item->neurMusculLeft}}</td>
                            <td>{{$item->neurMusculRight}}</td>
                            <td>{{$item->neurSciaticLeft}}</td>
                            <td>{{$item->neurSciaticRight}}</td>
                            <td>{{$item->neurTibialLeft}}</td>
                            <td>{{$item->neurTibialRight}}</td>
                            <td>{{$item->neurCommanLeft}}</td>
                            <td>{{$item->neurCommanRight}}</td>
                            <td>{{$item->neurFemoralLeft}}</td>
                            <td>{{$item->neurFemoralRight}}</td>
                            <td>{{$item->neurLatCutaLeft}}</td>
                            <td>{{$item->neurLatCutaRight}}</td>
                            <td>{{$item->neurObturLeft}}</td>
                            <td>{{$item->neurObturRight}}</td>
                            <td>{{$item->neurSuralLeft}}</td>
                            <td>{{$item->neurSuralRight}}</td>
                            <td>{{$item->neurSaphLeft}}</td>
                            <td>{{$item->neurSaphRight}}</td>
                            <td>{{$item->tissUlnarLeft}}</td>
                            <td>{{$item->tissUlnarRight}}</td>
                            <td>{{$item->tissRadialLeft}}</td>
                            <td>{{$item->tissRadialRight}}</td>
                            <td>{{$item->tissMedianLeft}}</td>
                            <td>{{$item->tissMedianRight}}</td>
                            <td>{{$item->tissSciaticLeft}}</td>
                            <td>{{$item->tissSciaticRight}}</td>
                            <td>{{$item->tissTibialLeft}}</td>
                            <td>{{$item->tissTibialRight}}</td>
                            <td>{{$item->tissPeronialLeft}}</td>
                            <td>{{$item->tissPeronialRight}}</td>
                            <td>{{$item->tissFemoralLeft}}</td>
                            <td>{{$item->tissFemoralRight}}</td>
                            <td>{{$item->tissSuralLeft}}</td>
                            <td>{{$item->tissSuralRight}}</td>
                            <td>{{$item->tissSaphenousLeft}}</td>
                            <td>{{$item->tissSaphenousRight}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'neuro_case')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Neuro Case</b></h2>
                    <!-- <a href="{{ URL::previous() }}" class="btn btn-info" style="float: right;">Go Back</a> -->
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Chest Postural</th>
                        <th>Chest Breathing</th>
                        <th>Positioning</th>
                        <th>Sustained Stretching</th>
                        <th>Weight Bearing Ex.</th>
                        <th>ROM Ex.</th>
                        <th>Strenghening Ex.</th>
                        <th>Balance Ex.</th>
                        <th>Sitting</th>
                        <th>Standing</th>
                        <th>Walking</th>
                        <th>Stairs</th>
                        <th>Washroom Sitting</th>
                        <th>Electrotherapy</th>
                        <th>Hot/Cold Pack</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->chest_pt_postural}}</td>
                            <td>{{$item->chest_pt_breathing}}</td>
                            <td>{{$item->positioning}}</td>
                            <td>{{$item->sustained}}</td>
                            <td>{{$item->weight_bearing}}</td>
                            <td>{{$item->rom_ex}}</td>
                            <td>{{$item->strengthening_ex}}</td>
                            <td>{{$item->balance_ex}}</td>
                            <td>{{$item->sitting}}</td>
                            <td>{{$item->standing}}</td>
                            <td>{{$item->walking}}</td>
                            <td>{{$item->stairs}}</td>
                            <td>{{$item->w_sitting}}</td>
                            <td>{{$item->electrotherapy}}</td>
                            <td>{{$item->hot_cold_pack}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @elseif($key == 'ortho_case')
                  <div class="box-header with-border">
                    <h2 class="box-title"><b>Ortho Case</b></h2>
                    <a href="{{url('exam-details/neuro_case')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
                  </div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Therapist Name</th>
                        <th>Patient Name</th>
                        <th>Chest Postural</th>
                        <th>Chest Breathing</th>
                        <th>CPM</th>
                        <th>ROM Ex.</th>
                        <th>Strengthening Ex.</th>
                        <th>Stretching Ex.</th>
                        <th>Sitting</th>
                        <th>Standing</th>
                        <th>Walking</th>
                        <th>Stairs</th>
                        <th>Washroom Sitting</th>
                        <th>Electrotherapy</th>
                        <th>Hot/Cold Pack</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if($allData)
                        @foreach($allData as $item)
                          <tr>
                            <td>{{$no++}}</td>
                            <td>@if($item->therapist_id) {{userDetails($item->therapist_id)->name}} @endif</td>
                            <td>@if($item->patient_id) {{userDetails($item->patient_id)->name}} @endif</td>
                            <td>{{$item->chest_pt_postural}}</td>
                            <td>{{$item->chest_pt_breathing}}</td>
                            <td>{{$item->cpm}}</td>
                            <td>{{$item->rom_ex}}</td>
                            <td>{{$item->strengthening_ex}}</td>
                            <td>{{$item->stretching_ex}}</td>
                            <td>{{$item->sitting}}</td>
                            <td>{{$item->standing}}</td>
                            <td>{{$item->walking_no_of_step}}</td>
                            <td>{{$item->stairs_no_of_step}}</td>
                            <td>{{$item->w_sitting}}</td>
                            <td>{{$item->electotherapy}}</td>
                            <td>{{$item->hot_cold_pack}}</td>
                            <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          </tr>
                        @endforeach
                      @endif
                    </tbody>
                  </table>
                @endif
	            </div>
	            <!-- /.box-body -->
	        </div>
            <!-- /.box -->
        </div>
    </div>
</section>
@endsection