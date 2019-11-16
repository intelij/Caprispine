@extends('layouts.apps')
@section('content')
<section class="content">
  <div class="row">
    @if($flag == 'motor')
      <div class="box-header with-border">
        <h3 class="box-title">{{ucfirst($flag)}} ( {{$title2}} )</h3>
        <a href="{{url('exam-details/sensory_exam')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
      </div>
      <div class="col-md-6">
        <!-- All Appointment -->
        <div class="box">
            <div class="box-body">
              <div class="col-md-10">
            <!-- Info Boxes Style 2 -->
            <div class="info-box bg-yellow">
              <a href="{{url('exam-details/')}}/combined_movement_spine/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-pricetag-outline"></i></span></a>
              <div class="info-box-content">
                <!-- <span class="info-box-text"></span> -->
                <span class="info-box-number">Combined Movement Spine</span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <span class="progress-description">Caprispine Patient Exam</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box bg-green">
              <a href="{{url('exam-details/')}}/thoracic_spine/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-heart-outline"></i></span></a>

              <div class="info-box-content">
                <!-- <span class="info-box-text"></span> -->
                <span class="info-box-number">Thoracic Spine</span>
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
              <a href="{{url('exam-details/')}}/hip_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/4.png')}}"></span></a>
              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">Hip</span>
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
              <a href="{{url('exam-details/')}}/ankle_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/6.png')}}"></span></a>
              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">Ankle</span>
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
              <a href="{{url('exam-details/')}}/shoulder_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/2.png')}}"></span></a>
              <div class="info-box-content">
                <!-- <span class="info-box-text"></span> -->
                <span class="info-box-number">Shoulder</span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <span class="progress-description">Caprispine Patient Exam</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box bg-green">
              <a href="{{url('exam-details/')}}/forearm_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/1.png')}}"></span></a>
              <div class="info-box-content">
                <!-- <span class="info-box-text"></span> -->
                <span class="info-box-number">Forearm</span>

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
              <a href="{{url('exam-details/')}}/fingers_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/6.png')}}"></span></a>
              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">Finger</span>

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
                  <a href="{{url('exam-details/')}}/cervical_spine/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-pricetag-outline"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Cervical Spine</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">Caprispine Patient Exam</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box bg-green">
                  <a href="{{url('exam-details/')}}/lumbar_spine/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion-ios-chatbubble-outline"></i></span></a>

                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Lumbar Spine</span>

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
                  <a href="{{url('exam-details/')}}/knee_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/4.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Knee</span>

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
                  <a href="{{url('exam-details/')}}/toes_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/7.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Toes</span>
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
                  <a href="{{url('exam-details/')}}/elbow_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/4.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Elbow</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">Caprispine Patient Exam</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box bg-green">
                  <a href="{{url('exam-details/')}}/wrist_exam/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/1.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Wrist</span>

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
                  <a href="{{url('exam-details/')}}/sacroiliac_joint/{{$patientId}}"><span class="info-box-icon whiteColor"><i class="ion ion-ios-cloud-download-outline"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Sacroiliac Joint</span>

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
              </div>
                </div>
                <!-- /.box-body -->
            </div>
              <!-- /.box -->
      </div>
    @elseif($flag == 'adl')
      <div class="box-header with-border">
        <h3 class="box-title">{{ucfirst($flag)}} ( {{$title2}} )</h3>
        <a href="{{url('exam-details/body_chart')}}/{{$patientId}}" class="btn btn-info" style="float: right;">Next Exam</a>
      </div>
      <div class="col-md-6">
        <!-- All Appointment -->
        <div class="box">
            <div class="box-body">
              <div class="col-md-10">
            <!-- Info Boxes Style 2 -->
            <div class="info-box bg-yellow">
              <a href="{{url('exam-details/')}}/adl_neck/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/4.png')}}"></span></a>
              <div class="info-box-content">
                <!-- <span class="info-box-text"></span> -->
                <span class="info-box-number">Neck</span>
                <div class="progress">
                  <div class="progress-bar" style="width: 50%"></div>
                </div>
                <span class="progress-description">Caprispine Patient Exam</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box bg-green">
              <a href="{{url('exam-details/')}}/adl_knee/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/4.png')}}"></span></a>

              <div class="info-box-content">
                <!-- <span class="info-box-text"></span> -->
                <span class="info-box-number">Knee</span>
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
              <a href="{{url('exam-details/')}}/adl_wrist_and_hand/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/1.png')}}"></span></a>
              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">Wrist & Hand</span>
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
              <a href="{{url('exam-details/')}}/adl_anke_and_foot/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/3.png')}}"></span></a>
              <div class="info-box-content">
                <span class="info-box-text"></span>
                <span class="info-box-number">Anke & Foot</span>
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
                  <a href="{{url('exam-details/')}}/adl_hip/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/4.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Hip</span>
                    <div class="progress">
                      <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">Caprispine Patient Exam</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
                <div class="info-box bg-green">
                  <a href="{{url('exam-details/')}}/adl_elbow/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/7.png')}}"></span></a>

                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Elbow</span>

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
                  <a href="{{url('exam-details/')}}/adl_shoulder/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/2.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Shoulder</span>

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
                  <a href="{{url('exam-details/')}}/adl_back/{{$patientId}}"><span class="info-box-icon whiteColor"><img src="{{asset('public/icon/2.png')}}"></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text"></span>
                    <span class="info-box-number">Back</span>
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
    @endif
  </div>
</section>
@endsection