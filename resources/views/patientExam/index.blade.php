@extends('layouts.apps')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-6">
          <!-- All Appointment -->
          <div class="box">
              <div class="box-body">
                <div class="col-md-10">
                	<input type="text" name="selectedPatientId" class="selectedPatientId" value="" style="display: none;">
		          <!-- Info Boxes Style 2 -->
		          <div class="info-box bg-yellow">
		            <a href="{{url('exam-details/')}}/chief_complaint/{{$patientId}}" data-id="2"><span class="info-box-icon whiteColor"><i class="ion ion-ios-pricetag-outline"></i></span></a>
		            <div class="info-box-content">
		              <!-- <span class="info-box-text"></span> -->
		              <span class="info-box-number">Chief Complaint</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 50%"></div>
		              </div>
		              <span class="progress-description">Caprispine Patient Exam</span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-green">
		            <a href="{{url('exam-details/')}}/pain_examination/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-heart-outline"></i></span></a>

		            <div class="info-box-content">
		              <!-- <span class="info-box-text"></span> -->
		              <span class="info-box-number">Pain Examination</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 20%"></div>
		              </div>
		              <span class="progress-description">
	                    Caprispine Patient Exam
	                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-red">
		            <a href="{{url('exam-details/')}}/body_chart/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-cloud-download-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Body Chart</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 70%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-aqua">
		            <a href="{{url('exam-details/')}}/motor_examination/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Motor Examination</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 40%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <div class="info-box bg-yellow">
		            <a href="{{url('exam-details/')}}/neurological_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-pricetag-outline"></i></span></a>
		            <div class="info-box-content">
		              <!-- <span class="info-box-text"></span> -->
		              <span class="info-box-number">Neurological Examination</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 50%"></div>
		              </div>
		              <span class="progress-description">Caprispine Patient Exam</span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-green">
		            <a href="{{url('exam-details/')}}/special_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-heart-outline"></i></span></a>
		            <div class="info-box-content">
		              <!-- <span class="info-box-text"></span> -->
		              <span class="info-box-number">Special Examination</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 20%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-red">
		            <a href="{{url('exam-details/')}}/physiotherapeutic_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-cloud-download-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Physiotherapeutic Diagnosis</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 70%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-aqua">
		            <a href="{{url('exam-details/')}}/all_notes/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">All Treatment Notes (Progress/Case)</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 40%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-yellow">
		            <a href="{{url('exam-details/')}}/ortho_case/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Ortho Case</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 40%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		        </div>
              </div>
              <!-- /.box-body -->
          </div>
            <!-- /.box -->
        </div>
        <div class="col-md-6">
          <!-- All Appointment -->
          <div class="box">
              <div class="box-body">
                <div class="col-md-10">
		          <!-- Info Boxes Style 2 -->
		          <div class="info-box bg-yellow">
		            <a href="{{url('exam-details/')}}/history_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-pricetag-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">History</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 50%"></div>
		              </div>
		              <span class="progress-description">Caprispine Patient Exam</span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-green">
		            <a href="{{url('exam-details/')}}/adl_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-heart-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">ADL Examination</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 20%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-red">
		            <a href="{{url('exam-details/')}}/physical_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-cloud-download-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Physical Examination</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 70%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-aqua">
		            <a href="{{url('exam-details/')}}/sensory_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Sensory Examination</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 40%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <div class="info-box bg-yellow">
		            <a href="{{url('exam-details/')}}/ndt_ndp_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-pricetag-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">NDT-NTP Examination</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 50%"></div>
		              </div>
		              <span class="progress-description">Caprispine Patient Exam</span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-green">
		            <a href="{{url('exam-details/')}}/investigation/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-heart-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Investigation</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 20%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-red">
		            <a href="{{url('exam-details/')}}/treatment_goal/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-cloud-download-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Treatment Goal</span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 70%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		          <div class="info-box bg-aqua">
		            <a href="{{url('exam-details/')}}/feedback/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">FeedBack</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 40%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <div class="info-box bg-yellow">
		            <a href="{{url('exam-details/')}}/neuro_case/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>
		            <div class="info-box-content">
		              <span class="info-box-text"></span>
		              <span class="info-box-number">Neuro Case</span>
		              <div class="progress">
		                <div class="progress-bar" style="width: 40%"></div>
		              </div>
		              <span class="progress-description">
		                    Caprispine Patient Exam
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		        </div>
              </div>
              <!-- /.box-body -->
          </div>
            <!-- /.box -->
        </div>
    </div>
</section>

@endsection