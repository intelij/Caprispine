@extends('layouts.apps')
@section('content')

<!-- Main content -->
<section class="content">
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{$totalTherapist}}</h3>

          <p>Total Therapist</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="{{url('all-therapist')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-maroon">
        <div class="inner">
          <h3>{{$totalStaff}}</h3>

          <p>Total Staff Members</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="{{url('all-users')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-navy">
        <div class="inner">
          <h3>{{$totalUser}}</h3>

          <p>Total Patient</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="{{url('all-patient')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{$totalAppointment}}</h3>

          <p>Total Appointment</p>
        </div>
        <div class="icon">
          <i class="ion ion-calendar"></i>
        </div>
        <a href="{{url('all-appointment')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{$totalApprovedAppointment}}</h3>

          <p>Total Approved Appointment</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="{{url('all-appointment')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{$totalPendingAppointment}}</h3>

          <p>Total Pending Appointment</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="{{url('all-appointment')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{$totalCancelAppointment}}</h3>

          <p>Total Cancel Appointment</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="{{url('all-appointment')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-blue">
        <div class="inner">
          <h3>{{$totalCompleteAppointment}}</h3>

          <p>Total Complete Appointment</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6">
      <!-- Bar chart -->
       <div class="box box-primary">
        <div class="box-header with-border">
          <i class="fa fa-bar-chart-o"></i>

          <h3 class="box-title">Bar Chart (Appointment)</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div id="bar-chart" style="height: 300px;"></div>
        </div>
      </div>
      <!-- /.box -->
    </div>
    
    <div class="col-md-6">
      <div class="box box-info">
            <div class="box-header with-border">
              <i class="fa fa-bar-chart-o"></i>
              <h3 class="box-title">Line Chart (Monthly Appointment)</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chartContainer" style="height: 300px; width: 100%;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
    </div>

  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<!-- <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script> -->
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>

<script>
window.onload = function () {
    //  * BAR CHART
    //  * ---------
    //  */

    var bar_data = {
        data : <?php
        $alldata = $allAppointment;
        $data = '[';
        foreach($alldata as $idata){
          $data .= "['".$idata['appointment_date']."', ".$idata['count']."],";
        }
        $data = rtrim($data,',');
        $data .= '],';
        echo $data;
      ?>

      color: '#3c8dbc',
    }
    $.plot('#bar-chart', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
        bars: {
          show    : true,
          barWidth: 0.5,
          align   : 'center'
        }
      },
      // toolTipContent:"title",
      xaxis : {
        mode      : 'categories',
        tickLength: 0
      },
      
      xkey: '',
      ykeys: ['a', 'b'],
      labels: ['CPU', 'DISK'],
      hideHover: 'auto'
    })
    // /* END BAR CHART */
}
$(".chartContainer").CanvasJSChart({
    title: {
      text: ""
    },
    axisY: {
      title: "",
      includeZero: false
    },
    axisX: {
      interval: 1
    },
    data: [
    {
      type: "line", //try changing to column, area
      toolTipContent: "{label}: {y}",
      dataPoints: [
<?php
$allMData = $allMonthlyAppointment;
$data = '';
foreach($allMData as $idata){
  $data .= "{ label: '".$idata['month']."', y: ".$idata['count']."},";
}
$data = rtrim($data,',');
echo $data;
?>
      ]
    }
    ]
  });
</script>
@endsection