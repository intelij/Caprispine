<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Case Report</title>
    <style>
      table {
        width: 100%;
      }
      table, th, td {
        border: 1px solid black;
        border-collapse:collapse;
      }
      .table
      {
      width:100%;
      text-align:center;}
      h1,h2,h3,h4,h5,h6
      {
        margin-bottom: 12px !important;
        margin-top: 0;}
    </style>
</head>
<body>
<div style='position:relative; overflow: hidden;padding: 0px;border: none; width: 743px;'>
<div  style='position:absolute;top:0px;left:0px;z-index:-1; width: 743px; height: 861px;'>
</div>
<div class="dclr"></div>
<div class="header">
   <span style='float:left;'><img src="{{asset('upload/images/reportLogo.png')}}" width="350"></span> 
  <!--<span style='float:left;'><img src="{{asset('public/upload/images/reportLogo.png')}}" width="350"></span>-->
</div>
<p style='font-family: "Open Sans", sans-serif; margin-bottom: 0;font-size: 18px;line-height: 24px;color: #333;  text-align: center;  padding: 0 14px;
'>
<h3 style="font-size: 37px; margin: 0; text-align: center; font-style: italic;">Capri Spine Clinic</h3>

<ul style="margin: 0 -1px 0 56px; float:left; width: 50%;">
<li style="text-align: left; float:left; padding:0 130px; font-weight: 600; ">Karkardooma</li>
<li style="text-align: left; font-weight: 600;">SPH, Civil Lines</li>
<li style="text-align: left;  font-weight: 600;">Greater Kailash</li>
<li style="text-align: left; float:left; padding:0 130px;   font-weight: 600;">Pitampura</li>
<li style=" text-align: left; font-weight: 600;">Gurgaon</li>
<li style="text-align: left; float:left; padding:0 130px; font-weight: 600;">Noida</li>
<li style=" text-align: left;  font-weight: 600;">Pune</li>
</ul> 
</p>
<p style='font-family: "Open Sans", sans-serif;font-size: 18px; margin: 0; text-align:  center; line-height: 10px;color: #333333;padding: 0 14px; margin-top: 0;'>T: 9063696969,9063121212</p>
<p style='font-family: "Open Sans", sans-serif;font-size: 18px; margin: 0; text-align: center; line-height: 24px;color: #333333;padding: 0 14px; margin-top: 0;'>E: info@caprispine.com</p>
<p style='font-family: "Open Sans", sans-serif;font-size: 18px; margin: 0; text-align: center; line-height: 24px;color: #333333;padding: 0 14px; margin-left: 0px;'>www. caprispine.com</p> 
<br>
<p style='font-family: "Open Sans", sans-serif;text-align: center; float; left; font-size: 18px; font-weight: 800; margin: 7px 0 0 0;'>Patient Details</p>
</div>
  <table class="table table-bordered" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333; padding: 1px 54px;">Name:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->name}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Occupation</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->occupation}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Age:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->age}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Weight:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->width}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Gender:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->gender}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Marital Status:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->marital_status}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Branch:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->branch}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Food Habits:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->food_habit}}</td>
      </tr>

        <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Id:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->registration_no}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Height:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->height}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Reference:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->referenceType}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;"></td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;"></td>
      </tr>
    </tbody>
  </table>
  @if(!empty($chiefComplaint))
  <p style="font-family:sans-serif;text-align: center;font-size: 18px;font-weight: 800;margin: 7px 0 0 0;">Chief Complaint </p>
  <table class="table table-bordered" style="border-collapse: collapse;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333; padding: 1px 45px;">Date</td>
        <td  style="border: 2px solid #333; text-align: center;">Chief Complaints</td>
        <td  style="border: 2px solid #333;     padding: 1px 8px; text-align: center;">Duration of Present illness</td>
        <td  style="border: 2px solid #333; padding: 1px 9px; text-align: center;">Past history of Present illness</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 19px; text-align: center;">{{date("d-M-Y", strtotime($chiefComplaint->created_at))}}</td>
        <td  style="border: 2px solid #333; padding: 1px 49px;">{{$chiefComplaint->chief_complaint}}</td>
        <td  style="border: 2px solid #333; padding: 1px 12px; text-align: center;">{{$chiefComplaint->problem_time}}</td>
        <td  style="border: 2px solid #333; padding: 1px 5px; text-align: center;">{{$chiefComplaint->problem_before}}</td>
      </tr>
    </tbody>
  </table>
  @endif
  @if(!empty($examHistory))
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;      font-weight: 800;   margin: 7px 0 0 0;'>History Of</p>
  <table class="table table-bordered" style="border-spacing: 0; border-collapse: collapse; word-wrap:break-word; width:100%;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333; text-align: center;">H/Present illness</td>
        <td  style="border: 2px solid #333; min-width:100px;"><p>{{$examHistory->cause_of_problem}}</p></td>
        <td  style="border: 2px solid #333; text-align: center;">H/Recent Infection</td>
        <td  style="border: 2px solid #333; padding: 1px 75px; text-align: center;">{{$examHistory->recent_infection}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Medical</td>
        <td  style="border: 2px solid #333;">{{$examHistory->medical_problem}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Any Red Flags</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->any_reg_flags}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;text-align: center;     padding: 1px 39px;">H/Surgical</td>
        <td  style="border: 2px solid #333; ">{{$examHistory->any_surgery}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Any Yellow Flags</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->Any_yellow_flags}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Treatment</td>
        <td  style="border: 2px solid #333;">{{$examHistory->any_treatment}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Any Limitations</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->limitations}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Smoking</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$examHistory->smoking}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Any Past Surgery</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->past_surgery}}</td>
      </tr>

        <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Alcholic</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$examHistory->alcoholic}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Any Allergy</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->allergies}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Fever Chill</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$examHistory->fever_and_chill}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Osteoporotic</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->osteoporotic}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Diabetic</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$examHistory->diabetes}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Any Implants</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->any_implants}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">Blood Pressure</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$examHistory->blood_pressure}}</td>
        <td  style="border: 2px solid #333; padding: 1px 11px; text-align: center;">Any Herediatary Disease</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->hereditary_disease}}</td>
      </tr>

       <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">H/Heart Disease</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$examHistory->heart_diseases}}</td>
        <td  style="border: 2px solid #333; padding: 1px 9px; text-align: center;">Any Bleeding Disorder</td>
        <td  style="border: 2px solid #333; padding: 1px 25px; text-align: center;">{{$examHistory->bleeding_disorder}}</td>
      </tr>
    </tbody>
  </table>
  @endif
  @if(!empty($painExam))
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;     font-weight: 800;     margin: 7px 0 0 0;'>Pain History</p>
  <table class="table table-bordered" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333; text-align: center;">Date</td>
        <td  style="border: 2px solid #333; padding: 0 66px; text-align: center;">{{date("d-M-Y", strtotime($painExam->created_at))}}</td>
        <td  style="border: 2px solid #333;  text-align: center;">Intensity of Pain</td>
        <td  style="border: 2px solid #333; padding: 1px 70px; text-align: center;">{{$painExam->intensity_of_pain}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;padding: 0 27px; text-align: center;">Since then Pain is</td>
        <td  style="border: 2px solid #333; text-align: center;">{{$painExam->pain}}</td>
        <td  style="border: 2px solid #333; text-align: center;">Feel more pain in</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$painExam->feel_more_pain_in}}</td>
      </tr>
         <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;  text-align: center;">Nature of pain</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$painExam->nature_of_pain}}</td>
        <td  style="border: 2px solid #333; text-align: center;">Pain Aggravating Factor</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">
          {{$painExam->aggravating_factor}} @if($painExam->aggravating_desc),{{$painExam->aggravating_desc}}@endif
        </td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;  text-align: center;">Onset of Pain</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$painExam->onset_of_pain}}</td>
        <td  style="border: 2px solid #333;  text-align: center;">Relieving Factor</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">
          {{$painExam->relieving_factor}} @if($painExam->relieving_desc),{{$painExam->relieving_desc}}@endif
        </td>
      </tr>
    </tbody>
  </table>
  @endif
  @if(!empty($physicalExam))
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;     font-weight: 800;     margin: 7px 0 0 0;'>Physical Examination</p>
  <table class="table table-bordered" style="border-collapse: collapse;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333;  text-align: center;">Date</td>
        <td  style="border: 2px solid #333; text-align: center;">{{date("d-M-Y", strtotime($physicalExam->created_at))}}</td>
        <td  style="border: 2px solid #333;  text-align: center;">Posture</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$physicalExam->posture}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; text-align: center;">Blood Pressure
(Systolic/Diastolic)</td>
        <td  style="border: 2px solid #333; text-align: center;">{{$physicalExam->blood_pressure}}</td>
        <td  style="border: 2px solid #333; text-align: center;">Gait</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$physicalExam->gait}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;  text-align: center;">Temperature</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$physicalExam->temperature}}</td>
        <td  style="border: 2px solid #333; text-align: center;">Swelling(if any)</td>
        <td  style="border: 2px solid #333; text-align: center;">{{$physicalExam->swelling}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;  text-align: center;">Heart Rate</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$physicalExam->heart_rate}}</td>
        <td  style="border: 2px solid #333;  text-align: center;">Tightness/Contracture/Deformity(if any)</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$physicalExam->tight_contract_deformity}}</td>
      </tr>

      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333;  text-align: center;">Respiratory Rate</td>
        <td  style="border: 2px solid #333;  text-align: center;">{{$physicalExam->respiratory_rate}}</td>
        <td  style="border: 2px solid #333;  text-align: center;">Scar Description</td>
        <td  style="border: 2px solid #333; padding: 1px 50px; text-align: center;">{{$physicalExam->scar_description}}</td>
      </tr>
    </tbody>
  </table>
  @endif
  @if(!empty($combinedSpineExam) || !empty($cervicalSpineExam) || !empty($thoracicSpineExam) || !empty($lumbarSpineExam) || !empty($hipExam) || !empty($ankleExam) || !empty($kneeExam) || !empty($wristExam)|| !empty($shoulderExam) || !empty($elbowExam) || !empty($forearmExam) || !empty($toesExam) || !empty($fingerExam) || !empty($scarollicReport))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0;'>Motor Examination</p>
    @if(!empty($combinedSpineExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;     font-weight: 800;    margin: 7px 0 0 0;'>Combined Movement Assessment of Spine</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr class="ab">
        <td style="padding: 4px 50px;">Cervical Spine</td>
        <td style="padding: 4px 80px;">Thoracic Spine</td>
        <td style="padding: 4px 60px;">Lumbar Spine</td>
      </tr>
    </table>
    <table cellpadding="5" style="margin:0; border-bottom: 2px solid #333; border-left: 2px solid #333; border-right: 2px solid #333; word-wrap:break-word; width:100%; ">
      <tr style="border: 2px solid #ddd; margin:0; padding: 0;">
        <td style="border-right: 1px solid #333; text-align: center;  margin:0; width:30%;">{{$combinedSpineExam->cervical_spine}}</td>
        <td style="border-right: 1px solid #333; text-align: center;  margin:0; width:42%;">{{$combinedSpineExam->thoracic_spine}}</td>
        <td style=" padding: 4px 22px; text-align: center; margin:0; width:33%;">{{$combinedSpineExam->lumbar_spine}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($cervicalSpineExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;     font-weight: 800;    margin: 7px 0 0 0;'>Cervical Spine</p>
    <table border="1" cellpadding="5" style=" border-spacing: 0; border-collapse: collapse;">
      <tr class="ab">
        <td style="padding: 4px 50px;">Flexion</td>
        <td style="padding: 4px 50px;">Extension</td>
        <td style="padding: 4px 60px;">Side Flexion</td>
        <td style="padding: 4px 53px;">Rotation</td>
      </tr>
    </table>
    <table cellpadding="5" style="border-bottom: 2px solid #333; border-left: 2px solid #333; border-right: 2px solid #333;  border-spacing: 0; border-collapse: collapse; word-wrap:break-word; width:100%; ">
      <tr  style="text-align:center;">
        <td style="padding: 0px 0px; width:165px; ">{{$cervicalSpineExam->flexion}}</td>
        <td style="padding: 0px 0px; width:182px; ">{{$cervicalSpineExam->extension}}</td>    
        <td style="padding: 0px 0px; width:95px; "><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;">Left</span>{{$cervicalSpineExam->sideFlexionLeft}}</td>
        <td style="padding: 0px 0px; width:95px; "><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;">Right</span>{{$cervicalSpineExam->sideFlexionRight}}</td>
        <td style="padding: 0px 0px; "><span style="display:block; width:100%; border-bottom: 1px solid #333 !important; position: relative;  top:-9px;">Left</span>{{$cervicalSpineExam->rotationLeft}}</td>    
        <td style="padding: 0px 0px;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;  position: relative;  top:-9px;">Right</span>{{$cervicalSpineExam->rotationRight}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($thoracicSpineExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;     font-weight: 800;    margin: 7px 0 0 0;'>Thoracic Spine</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;  word-wrap:break-word; width:100%;">
      <tr class="ab">
        <td style="padding: 4px 50px;">Flexion</td>
        <td style="padding: 4px 50px;">Extension</td>
        <td style="padding: 4px 60px;">Side Flexion</td>
        <td style="padding: 4px 53px;">Rotation</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr  style="text-align:center;">
       <td style="padding: 0px 0px; width:165px;">{{$thoracicSpineExam->flexion}}</td>
        <td style="padding: 0px 0px; width:170px;">{{$thoracicSpineExam->extension}}</td>    
        <td style="padding: 0px 0px; width:100px;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;">Left</span>{{$thoracicSpineExam->sideFlexionLeft}}</td>
        <td style="padding: 0px 0px; width:105px;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;">Right</span>{{$thoracicSpineExam->sideFlexionRight}}</td>
        <td style="padding: 0px 0px; "><span style="display:block; width:100%; border-bottom: 1px solid #333 !important; position: relative; top:-9px;">Left</span>{{$thoracicSpineExam->rotationLeft}}</td>    
        <td style="padding: 0px 0px; "><span style="display:block; width:100%; border-bottom: 1px solid #333 !important; position: relative; top:-9px;">Right</span>{{$thoracicSpineExam->rotationRight}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($lumbarSpineExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;     font-weight: 800;    margin: 7px 0 0 0;'>Lumbar Spine</p>
    <table  class="table" style="border-spacing: 0; border-collapse: collapse;">
      <tr class="ab">
        <td style="padding: 4px 50px;">Flexion</td>
        <td style="padding: 4px 50px;">Extension</td>
        <td style="padding: 4px 60px;">Side Flexion</td>
        <td style="padding: 4px 53px;">Rotation</td>
      </tr>
    </table>
    <table class="table" style=" border-left: 2px solid #333; border-right: 2px solid #333; border-spacing: 0; border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr  style="text-align:center;">
        <td style="padding: 0px 0px; width:165px; border-right: 1px solid #333;">{{$lumbarSpineExam->flexion}}</td>
        <td style="padding: 0px 0px; width:182px; border-right: 1px solid #333;">{{$lumbarSpineExam->extension}}</td>    
        <td style="padding: 0px 0px; width:95px; border-right: 1px solid #333;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;">Left</span>{{$lumbarSpineExam->sideFlexionLeft}}</td>
        <td style="padding: 0px 0px; width:96px; border-right: 1px solid #333;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important;">Right</span>{{$lumbarSpineExam->sideFlexionRight}}</td>
        <td style="padding: 0px 0px; border-right: 1px solid #333;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important; position: relative; top:-9px;">Left</span>{{$lumbarSpineExam->rotationLeft}}</td>    
        <td style="padding: 0px 0px;"><span style="display:block; width:100%; border-bottom: 1px solid #333 !important; position: relative; top:-9px; ">Right</span>{{$lumbarSpineExam->rotationRight}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($hipExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Hip Joint</p>
    <table class="table" cellpadding="5" style="border-spacing: 0; border-collapse: collapse;" >
      <tr class="ab">
        <td style="width:24.02%; padding: 4px 0px;">Left</td>
        <td style="width:37.34%; padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%; padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table class="table" cellpadding="5" style="border-spacing: 0; border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr  style="text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$hipExam->flexionLeftTone}}</td>
        <td>{{$hipExam->flexionLeftPower}}</td>
        <td>{{$hipExam->flexionLeftROM}}</td>
        <td>FLEXION</td>
        <td>FLEXORS</td>
        <td>L2-L4</td>
        <td>{{$hipExam->flexionRightROM}}</td>
        <td>{{$hipExam->flexionRightPower}}</td>
        <td>{{$hipExam->flexionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$hipExam->extensionLeftTone}}</td>
        <td>{{$hipExam->extensionLeftPower}}</td>
        <td>{{$hipExam->extensionLeftROM}}</td>
        <td>EXTENSION</td>
        <td>EXTENSORS</td>
        <td>L5-S2</td>
        <td>{{$hipExam->extensionRightROM}}</td>
        <td>{{$hipExam->extensionRightPower}}</td>
        <td>{{$hipExam->extensionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$hipExam->abductionLeftTone}}</td>
        <td>{{$hipExam->abductionLeftPower}}</td>
        <td>{{$hipExam->abductionLeftROM}}</td>
        <td>ABDUCTION</td>
        <td>ABDUCTION</td>
        <td>L2-L4</td>
        <td>{{$hipExam->abductionRightROM}}</td>
        <td>{{$hipExam->abductionRightPower}}</td>
        <td>{{$hipExam->abductionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$hipExam->adductionLeftTone}}</td>
        <td>{{$hipExam->adductionLeftPower}}</td>
        <td>{{$hipExam->adductionLeftROM}}</td>
        <td>ADDUCTION</td>
        <td>ADDUCTORS</td>
        <td>L4-S1</td>
        <td>{{$hipExam->adductionRightROM}}</td>
        <td>{{$hipExam->adductionRightPower}}</td>
        <td>{{$hipExam->adductionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$hipExam->extRotationLeftTone}}</td>
        <td>{{$hipExam->extRotationLeftPower}}</td>
        <td>{{$hipExam->extRotationLeftROM}}</td>
        <td>EXTERNAL ROTATION</td>
        <td>EXT. ROTATORS</td>
        <td>L3-S2</td>
        <td>{{$hipExam->extRotationRightROM}}</td>
        <td>{{$hipExam->extRotationRightPower}}</td>
        <td>{{$hipExam->extRotationRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$hipExam->intRotationLeftTone}}</td>
        <td>{{$hipExam->intRotationLeftPower}}</td>
        <td>{{$hipExam->intRotationLeftROM}}</td>
        <td>INTERNAL ROTATION</td>
        <td>INT. ROTATORS</td>
        <td>L4-S1</td>
        <td>{{$hipExam->intRotationRightROM}}</td>
        <td>{{$hipExam->intRotationRightPower}}</td>
        <td>{{$hipExam->intRotationRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($ankleExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>Ankle Joint</p>
    <table class="table" border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr class="ab">
        <td style="width:24.20%; padding: 4px 0px;">Left</td>
        <td style="width:42.10%; padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%; padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr  style="text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$ankleExam->plantFlexLeftTone}}</td>
        <td>{{$ankleExam->plantFlexLeftPower}}</td>
        <td>{{$ankleExam->plantFlexLeftROM}}</td>
        <td>PLANTAR FLEXION</td>
        <td>PLANTAR FLEXORS</td>
        <td>S1-S2</td>
        <td>{{$ankleExam->plantFlexRightROM}}</td>
        <td>{{$ankleExam->plantFlexRightPower}}</td>
        <td>{{$ankleExam->plantFlexRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$ankleExam->dorsiFlexLeftTone}}</td>
        <td>{{$ankleExam->dorsiFlexLeftPower}}</td>
        <td>{{$ankleExam->dorsiFlexLeftROM}}</td>
        <td>DORSIFLEXION</td>
        <td>DORSIFLEXORS</td>
        <td>L4-S1</td>
        <td>{{$ankleExam->dorsiFlexRightROM}}</td>
        <td>{{$ankleExam->dorsiFlexRightPower}}</td>
        <td>{{$ankleExam->dorsiFlexRightTone}}</td>
      
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$ankleExam->eversionLeftTone}}</td>
        <td>{{$ankleExam->eversionLeftPower}}</td>
        <td>{{$ankleExam->eversionLeftROM}}</td>
        <td>EVERSION</td>
        <td>EVERTORS</td>
        <td>L5-S1</td>
        <td>{{$ankleExam->eversionRightROM}}</td>
        <td>{{$ankleExam->eversionRightPower}}</td>
        <td>{{$ankleExam->eversionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px;">
        <td>{{$ankleExam->inversionLeftTone}}</td>
        <td>{{$ankleExam->inversionLeftPower}}</td>
        <td>{{$ankleExam->inversionLeftROM}}</td>
        <td>INVERSION</td>
        <td>INVERTORS</td>
        <td>L4-S1</td>
        <td>{{$ankleExam->inversionRightROM}}</td>
        <td>{{$ankleExam->inversionRightPower}}</td>
        <td>{{$ankleExam->inversionRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($kneeExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>Knee Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="width:24.34%;padding: 4px 0px;">Left</td>
        <td style="width:34.34%;padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%;padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$kneeExam->flexionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$kneeExam->flexionLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$kneeExam->flexionLeftROM}}</td>
        <td style="padding: 0px 0px;">FLEXION</td>
        <td style="padding: 0px 0px;">FLEXORS</td>    
        <td style="padding: 0px 0px;">L5-S2</td>
        <td style="padding: 0px 0px;">{{$kneeExam->flexionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$kneeExam->flexionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$kneeExam->flexionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$kneeExam->extensionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$kneeExam->extensionLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$kneeExam->extensionLeftROM}}</td>
        <td style="padding: 0px 0px;">EXTENSION</td>
        <td style="padding: 0px 0px;">EXTENSORS</td>    
        <td style="padding: 0px 0px;">L2-L4</td>
        <td style="padding: 0px 0px;">{{$kneeExam->extensionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$kneeExam->extensionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$kneeExam->extensionRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($wristExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>Wrist Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="width:24.27%;padding: 4px 0px;">Left</td>
        <td style="width:40.30%;padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%;padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$wristExam->flexionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->flexionLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$wristExam->flexionLeftROM}}</td>
        <td style="padding: 0px 0px;">FLEXION</td>
        <td style="padding: 0px 0px;">FLEXORS</td>    
        <td style="padding: 0px 0px;">C6-T1</td>
        <td style="padding: 0px 0px;">{{$wristExam->flexionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->flexionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->flexionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$wristExam->extensionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->extensionLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$wristExam->extensionLeftROM}}</td>
        <td style="padding: 0px 0px;">EXTENSION</td>
        <td style="padding: 0px 0px;">EXTENSORS</td>    
        <td style="padding: 0px 0px;">C6-C8</td>
        <td style="padding: 0px 0px;">{{$wristExam->extensionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->extensionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->extensionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$wristExam->radialDevLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->radialDevLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$wristExam->radialDevLeftROM}}</td>
        <td style="padding: 0px 0px;">RADIAL DEVIATION</td>
        <td style="padding: 0px 0px;">RADIAL DEVAITORS</td>    
        <td style="padding: 0px 0px;">C6-C8</td>
        <td style="padding: 0px 0px;">{{$wristExam->radialDevRightROM}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->radialDevRightPower}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->radialDevRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$wristExam->ulnarDevLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->ulnarDevLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$wristExam->ulnarDevLeftROM}}</td>
        <td style="padding: 0px 0px;">ULNAR DEVIATION</td>
        <td style="padding: 0px 0px;">ULNAR DEVIATORS</td>    
        <td style="padding: 0px 0px;">C7-T1</td>
        <td style="padding: 0px 0px;">{{$wristExam->ulnarDevRightROM}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->ulnarDevRightPower}}</td>
        <td style="padding: 0px 0px;">{{$wristExam->ulnarDevRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($shoulderExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>Shoulder Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center">
        <td style="width:24.35%;padding: 4px 0px;">Left</td>
        <td style="width:44.35%;padding: 4px 0px;">Parameters</td>
        <td style="width:33.35%;padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 4px 0px;">Tone</td>
        <td style=" padding: 4px 0px;">Power</td>   
        <td style="padding: 4px 0px;">ROM</td>
        <td style="padding: 4px 0px;">Movement</td>
        <td style="padding: 4px 0px;">Muscle</td>    
        <td style="padding: 4px 0px;">Nerve Root</td>
        <td style="padding: 4px 0px;">ROM</td>
        <td style="padding: 4px 0px;">Power</td>
        <td style="padding: 4px 0px;">Tone</td>
      </tr>
      <tr style="text-align:center; font-size: 13px; width:100%;">
        <td>{{$shoulderExam->flexionLeftTone}}</td>
        <td>{{$shoulderExam->flexionLeftPower}}</td>
        <td>{{$shoulderExam->flexionLeftROM}}</td>
        <td>FLEXION</td>
        <td>FLEXORS</td>
        <td>C5-C7</td>
        <td>{{$shoulderExam->flexionRightROM}}</td>
        <td>{{$shoulderExam->flexionRightPower}}</td>
        <td>{{$shoulderExam->flexionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px; width:100%;">
        <td>{{$shoulderExam->extensionLeftTone}}</td>
        <td>{{$shoulderExam->extensionLeftPower}}</td>
        <td>{{$shoulderExam->extensionLeftROM}}</td>
        <td>EXTENSION</td>
        <td>EXTENSORS</td>
        <td>C5-C6</td>
        <td>{{$shoulderExam->extensionRightROM}}</td>
        <td>{{$shoulderExam->extensionRightPower}}</td>
        <td>{{$shoulderExam->extensionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px; width:100%;">
        <td>{{$shoulderExam->abductionLeftTone}}</td>
        <td>{{$shoulderExam->abductionLeftPower}}</td>
        <td>{{$shoulderExam->abductionLeftROM}}</td>
        <td>ABDUCTION</td>
        <td>ABDUCTOR</td>
        <td>C5-C6</td>
        <td>{{$shoulderExam->abductionRightROM}}</td>
        <td>{{$shoulderExam->abductionRightPower}}</td>
        <td>{{$shoulderExam->abductionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px; width:100%;">
        <td>{{$shoulderExam->adductionLeftTone}}</td>
        <td>{{$shoulderExam->adductionLeftPower}}</td>
        <td>{{$shoulderExam->adductionLeftROM}}</td>
        <td>ADDUCTION</td>
        <td>ADDUCTOR</td>
        <td>C6-C8</td>
        <td>{{$shoulderExam->adductionRightROM}}</td>
        <td>{{$shoulderExam->adductionRightPower}}</td>
        <td>{{$shoulderExam->adductionRightTone}}</td>
      </tr>
      <tr style="text-align:center; font-size: 13px; width:100%;">
        <td>{{$shoulderExam->hrAbdLeftTone}}</td>
        <td>{{$shoulderExam->hrAbdLeftPower}}</td>
        <td>{{$shoulderExam->hrAbdLeftROM}}</td>
        <td>EXTERNALROTATION</td>
        <td>EXT. ROTATORS</td>
        <td>C5-C6</td>
        <td>{{$shoulderExam->hrAbdRightROM}}</td>
        <td>{{$shoulderExam->hrAbdRightPower}}</td>
        <td>{{$shoulderExam->hrAbdRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($elbowExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>Elbow Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="width:24.10%;padding: 4px 0px;">Left</td>
        <td style="width:34.34%;padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%;padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse; word-wrap:break-word; width:100%;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;" >Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="width:100%;">
        <td style="padding: 0px 0px;">{{$elbowExam->flexionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$elbowExam->flexionLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$elbowExam->flexionLeftROM}}</td>
        <td style="padding: 0px 0px; text-align:center;" >FLEXION</td>
        <td style="padding: 0px 0px;">FLEXORS</td>    
        <td style="padding: 0px 0px;">C5-C6</td>
        <td style="padding: 0px 0px;">{{$elbowExam->flexionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$elbowExam->flexionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$elbowExam->flexionRightTone}}</td>
      </tr>
      <tr style="width:100%;">
        <td style="padding: 0px 0px;">{{$elbowExam->extensionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$elbowExam->extensionLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$elbowExam->extensionLeftROM}}</td>
        <td style="padding: 0px 0px;">EXTENSION</td>
        <td style="padding: 0px 0px;">EXTENSORS</td>    
        <td style="padding: 0px 0px;">C6-C8</td>
        <td style="padding: 0px 0px;">{{$elbowExam->extensionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$elbowExam->extensionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$elbowExam->extensionRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($forearmExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>ForeArm Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="width:24.05%;padding: 4px 0px;">Left</td>
        <td style="width:36.34%;padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%;padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$forearmExam->supinationLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$forearmExam->supinationLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$forearmExam->supinationLeftROM}}</td>
        <td style="padding: 0px 0px;">SUPINATION</td>
        <td style="padding: 0px 0px;">SUPINATORS</td>    
        <td style="padding: 0px 0px;">C6-C7</td>
        <td style="padding: 0px 0px;">{{$forearmExam->supinationRightROM}}</td>
        <td style="padding: 0px 0px;">{{$forearmExam->supinationRightPower}}</td>
        <td style="padding: 0px 0px;">{{$forearmExam->supinationRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$forearmExam->pronationLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$forearmExam->pronationLeftPower}}</td>   
        <td style="padding: 0px 0px;">{{$forearmExam->pronationLeftROM}}</td>
        <td style="padding: 0px 0px;">PRONATION</td>
        <td style="padding: 0px 0px;">PRONATORS</td>    
        <td style="padding: 0px 0px;">C6-C8</td>
        <td style="padding: 0px 0px;">{{$forearmExam->pronationRightROM}}</td>
        <td style="padding: 0px 0px;">{{$forearmExam->pronationRightPower}}</td>
        <td style="padding: 0px 0px;">{{$forearmExam->pronationRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($toesExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>Toes Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="width:24.15%; padding: 4px 0px;">Left</td>
        <td style="width:39.34%; padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%; padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$toesExam->flexionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->flexionLeftPower}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->flexionLeftROM}}</td>
        <td style="padding: 0px 0px;">FLEXION</td>
        <td style="padding: 0px 0px;">FLEXORS</td>
        <td style="padding: 0px 0px;">L5-S3</td>
        <td style="padding: 0px 0px;">{{$toesExam->flexionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->flexionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->flexionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$toesExam->extensionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->extensionLeftPower}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->extensionLeftROM}}</td>
        <td style="padding: 0px 0px;">EXTENSION EXTENSOR</td>
        <td style="padding: 0px 0px;">EXTENSORS</td>
        <td style="padding: 0px 0px;">L5-S1</td>
        <td style="padding: 0px 0px;">{{$toesExam->extensionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->extensionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$toesExam->extensionRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($fingerExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>finger Joint</p>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="width:24.05%; padding: 4px 0px;">Left</td>
        <td style="width:35.34%; padding: 4px 0px;">Parameters</td>
        <td style="width:33.34%; padding: 4px 0px;">Right</td>
      </tr>
    </table>
    <table border="1" cellpadding="5" style="border-collapse: collapse;">
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">Tone</td>
        <td style="padding: 0px 0px;">Power</td>   
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Movement</td>
        <td style="padding: 0px 0px;">Muscle</td>    
        <td style="padding: 0px 0px;">Nerve Root</td>
        <td style="padding: 0px 0px;">ROM</td>
        <td style="padding: 0px 0px;">Power</td>
        <td style="padding: 0px 0px;">Tone</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$fingerExam->flexionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->flexionLeftPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->flexionLeftROM}}</td>
        <td style="padding: 0px 0px;">FLEXION</td>
        <td style="padding: 0px 0px;">FLEXORS</td>
        <td style="padding: 0px 0px;">C8-T1</td>
        <td style="padding: 0px 0px;">{{$fingerExam->flexionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->flexionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->flexionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$fingerExam->extensionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->extensionLeftPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->extensionLeftROM}}</td>
        <td style="padding: 0px 0px;">EXTENSION</td>
        <td style="padding: 0px 0px;">EXTENSORS</td>
        <td style="padding: 0px 0px;">C7-C8</td>
        <td style="padding: 0px 0px;">{{$fingerExam->extensionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->extensionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->extensionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$fingerExam->abductionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->abductionLeftPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->abductionLeftROM}}</td>
        <td style="padding: 0px 0px;">ABDUCTION</td>
        <td style="padding: 0px 0px;">ABDUCTORS</td>
        <td style="padding: 0px 0px;">C8-T1</td>
        <td style="padding: 0px 0px;">{{$fingerExam->abductionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->abductionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->abductionRightTone}}</td>
      </tr>
      <tr style="width:100%; text-align:center;">
        <td style="padding: 0px 0px;">{{$fingerExam->adductionLeftTone}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->adductionLeftPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->adductionLeftROM}}</td>
        <td style="padding: 0px 0px;">ADDUCTION</td>
        <td style="padding: 0px 0px;">ADDUCTORS</td>
        <td style="padding: 0px 0px;">C8-T1</td>
        <td style="padding: 0px 0px;">{{$fingerExam->adductionRightROM}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->adductionRightPower}}</td>
        <td style="padding: 0px 0px;">{{$fingerExam->adductionRightTone}}</td>
      </tr>
    </table>
    @endif
    @if(!empty($scarollicReport))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Sacroiliac Joint</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Movement</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Left</td>
          <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Right</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Ant Innominate</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->antInnominateLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->antInnominateRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Post Innominate</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->postInnominateLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->postInnominateRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Up Slip</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->upSlipLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->upSlipRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Down Slip</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->downSlipLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->downSlipRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Ant Tilt</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->antTiltLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->antTiltRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Post Tilt</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->postTiltLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->postTiltRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Nutation</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->nutationLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->nutationRight}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Counter Nutation</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->counterNutationLeft}}</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$scarollicReport->counterNutationRight}}</td>
        </tr>
      </tbody>
    </table>
    @endif
  @endif
  @if(!empty($sensoryExam))
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0;'>Sensory Examination </p>
    @if(!empty($sensoryExam->neckFlxExt) || !empty($sensoryExam->neckLatFlx) || !empty($sensoryExam->shoulderEle) || !empty($sensoryExam->shoulderABD) || !empty($sensoryExam->elbowFlx) || !empty($sensoryExam->elbowExt) || !empty($sensoryExam->thumbExt) || !empty($sensoryExam->abduction) || !empty($sensoryExam->hipFlexion) || !empty($sensoryExam->kneeExt) || !empty($sensoryExam->ankleDorsFlx) || !empty($sensoryExam->toeExt) || !empty($sensoryExam->kneeFlxAnklePlant) || !empty($sensoryExam->kneeFlx) || !empty($sensoryExam->rectalSphTone))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Myotome</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Nerve Root</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Myotome</td>
          <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Value</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">C1/C2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Neck Flexion/Extension</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->neckFlxExt}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C3</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Neck Lateral Flexion</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->neckLatFlx}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C4</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Shoulder Elevation</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->shoulderEle}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C5</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Shoulder Abduction</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->shoulderABD}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C6</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Elbow flexion/wrist flexion</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->elbowFlx}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C7</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Elbow extension/wrist flexionn</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->elbowExt}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C8</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Thumb extension/Ulnar Deviation</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->thumbExt}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Abduction/Adduction of intrinsic</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->abduction}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L1/L2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Hip flexion</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->hipFlexion}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L3</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Knee extension</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->kneeExt}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L4</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Ankle dorsiflexion</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->ankleDorsFlx}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L5</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Toe extension</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->toeExt}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Knee flexion, Ankle plantar flexion/eversion, Hip extension</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->kneeFlxAnklePlant}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Knee flexion</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->kneeFlx}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S3</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Rectal sphincter tone</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->rectalSphTone}}</td>
        </tr>
      </tbody>
    </table>
    @endif
    @if(!empty($sensoryExam->backOfHead) || !empty($sensoryExam->neck) || !empty($sensoryExam->antShoulder) || !empty($sensoryExam->thumb) || !empty($sensoryExam->backOfArm) || !empty($sensoryExam->ring) || !empty($sensoryExam->medialArm) || !empty($sensoryExam->interspace) || !empty($sensoryExam->interspace5) || !empty($sensoryExam->xiphoid) || !empty($sensoryExam->umbilicus) || !empty($sensoryExam->pupis) || !empty($sensoryExam->genitars) || !empty($sensoryExam->medialThigh) || !empty($sensoryExam->anteriorThigh) || !empty($sensoryExam->greatToe) || !empty($sensoryExam->dersumOfFeet) || !empty($sensoryExam->lateralFoot) || !empty($sensoryExam->posteromedicalThigh) || !empty($sensoryExam->perianalArea))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Dermatome</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Nerve Root</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Dermatone</td>
          <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Value</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Back of head</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->backOfHead}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C4</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Neck</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->neck}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C5</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Anterolateral Shoulder</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->antShoulder}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C6</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Thumb</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->thumb}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C7</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Back of arms/Index/Middle finger</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->backOfArm}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">C8</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Ring/Little finger</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->ring}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Medial Arm</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->medialArm}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T3</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">3, 4 Interspace</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->interspace}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T4</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Nipple line, 4,5 Interspace</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->interspace5}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T6</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Xiphoid process</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->xiphoid}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T10</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Umbilicus</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->umbilicus}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">T12</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Pupis</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->pupis}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Genitals</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->genitars}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Medial Thigh</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->medialThigh}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L3</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Anterior thigh/Medial knee</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->anteriorThigh}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">L4</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Great Toe, Anterior thigh, Medial ankle</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->greatToe}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Dersum of feet</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->dersumOfFeet}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Lateral foot/sole</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->lateralFoot}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Posteromedical thigh/buttocks</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->posteromedicalThigh}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">S3,S4,S5</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000; ">Perianal area</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000; ">{{$sensoryExam->perianalArea}}</td>
        </tr>
      </tbody>
    </table>
    @endif
    @if(!empty($sensoryExam->biceps) || !empty($sensoryExam->brachioradialis) || !empty($sensoryExam->triceps) || !empty($sensoryExam->fingerFlx) || !empty($sensoryExam->quadriceps) || !empty($sensoryExam->achilles))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Reflexes</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Nerve Root</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Reflexes</td>
          <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Value</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">C5,C6</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Biceps</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$sensoryExam->biceps}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">C5,C6</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Brachioradialis (Radial)</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$sensoryExam->brachioradialis}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">C7,C8</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Triceps</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$sensoryExam->triceps}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">C8,T1</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Finger flexors</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$sensoryExam->fingerFlx}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">L2,L3,L4</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">Quadriceps (Patellar, knee jerk)</td>
          <td style="width:33.33%; padding:10px; text-align:center;">{{$sensoryExam->quadriceps}}</td>
        </tr>
        <tr>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">S1,S2</td>
          <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;border-bottom: 1px solid #000;">Achilles (Ankle jerk)<</td>
          <td style="width:33.33%; padding:10px; text-align:center;border-bottom: 1px solid #000;">{{$sensoryExam->achilles}}</td>
        </tr>
      </tbody>
    </table>
    @endif
  @endif
  @if(!empty($neurologicalExam))
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 700; margin: 7px 0 0 0;'>Co-ordination Test</p>
    @if(!empty($neurologicalExam->fingerTime) && !empty($neurologicalExam->fingerSpeed) && !empty($neurologicalExam->fingerError))
      <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Finger to Nose</p>
      <table style="width:100%; border:1px solid #000;">
        <tbody>
          <tr>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Time Taken</td>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Speed</td>
            <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Error Made</td>
          </tr>
          <tr>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">{{$neurologicalExam->fingerTime}}</td>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">{{$neurologicalExam->fingerSpeed}}<</td>
            <td style="width:33.33%; padding:10px; text-align:center;">{{$neurologicalExam->fingerError}}</td>
          </tr>
        </tbody>
      </table>
    @endif
    @if(!empty($neurologicalExam->aternatingTime) && !empty($neurologicalExam->aternatingSpeed) && !empty($neurologicalExam->aternatingError))
      <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Aternating Supination</p>
      <table style="width:100%; border:1px solid #000;">
        <tbody>
          <tr>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Time Taken</td>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Speed</td>
            <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Error Made</td>
          </tr>
          <tr>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">{{$neurologicalExam->aternatingTime}}</td>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">{{$neurologicalExam->aternatingSpeed}}<</td>
            <td style="width:33.33%; padding:10px; text-align:center;">{{$neurologicalExam->aternatingError}}</td>
          </tr>
        </tbody>
      </table>
    @endif
    @if(!empty($neurologicalExam->heelTime) && !empty($neurologicalExam->heelSpeed) && !empty($neurologicalExam->heelError))
      <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Heel to Shin</p>
      <table style="width:100%; border:1px solid #000;">
        <tbody>
          <tr>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Time Taken</td>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Speed</td>
            <td style="width:33.33%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Error Made</td>
          </tr>
          <tr>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">{{$neurologicalExam->heelTime}}</td>
            <td style="width:33.33%; padding:10px; border-right:1px solid #000; text-align:center;">{{$neurologicalExam->heelSpeed}}</td>
            <td style="width:33.33%; padding:10px; text-align:center;">{{$neurologicalExam->heelError}}</td>
          </tr>
        </tbody>
      </table>
    @endif
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;    margin: 7px 0 0 0;'>Gait and Balance Testings</p>
    <table class="table table-bordered sbkch" style="border-collapse: collapse;">
      <tbody>
        <tr>
          <th>Gait level Surface</th>
          <th >{{$neurologicalExam->levelSurface}}</th>
          <th>Gait and Pivot Turn</th>
          <th>{{$neurologicalExam->pivotTurn}}</th>
        </tr>
        <tr>
          <td>Change in Gait Speed</td>
          <td >{{$neurologicalExam->gaitSpeed}}</td>
          <td >Step Over Obstacle</td>
          <td>{{$neurologicalExam->overObstacle}}</td>
        </tr>
        <tr>
          <td rowspan="2">Gait with Horizontal HeadTurns</td>
          <td rowspan="2">{{$neurologicalExam->hrHeadTurns}}</td>
        <td>Step Around Obstacles</td>
        <td >{{$neurologicalExam->aroundObstacle}}</td>
        </tr>
        <tr>
          <td>Steps</td>
          <td >{{$neurologicalExam->steps}}</td>
        </tr>
        <tr>
          <td>Gait with Vertical Head Turns</td>
          <td >{{$neurologicalExam->vrHeadTurns}}</td>
          <td >Balance and Movement Analyzer</td>
        <td >Left : {{$neurologicalExam->analyserLeft}}, Right : {{$neurologicalExam->analyserRight}}</td>
        </tr>
      </tbody>
    </table>
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;    margin: 7px 0 0 0;'>Modified Barthel Index-Info</p>
    <table class="table table-bordered sbkch" style="border-collapse: collapse;">
      <tbody>
        <tr>
          <th>Bowels(Preceding Week)</th>
          <th >{{$neurologicalExam->bowels}}</th>
          <th>Transfer (from bed to chair and back)</th>
          <th>{{$neurologicalExam->transfer}}</th>
        </tr>
        <tr>
          <td>Bladder(Preceding Week)</td>
          <td >{{$neurologicalExam->bladder}}</td>
          <td >Mobility</td>
          <td>{{$neurologicalExam->mobility}}</td>
        </tr>
        <tr>
          <td>Toilet Use</td>
          <td >{{$neurologicalExam->toiletUse}}</td>
          <td >Stairs</td>
          <td>{{$neurologicalExam->stairs}}</td>
        </tr>
        <tr>
          <td>Feeding</td>
          <td >{{$neurologicalExam->feeding}}</td>
          <td >Bathing</td>
          <td>{{$neurologicalExam->bathing}}</td>
        </tr>
      </tbody>
    </table>
  @endif
  @if(!empty($ndtndpExam))
    @if(!empty($ndtndpExam->neurUlnarLeft) || !empty($ndtndpExam->neurUlnarRight) || !empty($ndtndpExam->neurRadialLeft) || !empty($ndtndpExam->neurRadialRight) || !empty($ndtndpExam->neurMedianLeft) || !empty($ndtndpExam->neurMedianRight) || !empty($ndtndpExam->neurMusculLeft) || !empty($ndtndpExam->neurMusculRight) || !empty($ndtndpExam->neurSciaticLeft) || !empty($ndtndpExam->neurSciaticRight) || !empty($ndtndpExam->neurTibialLeft) || !empty($ndtndpExam->neurTibialRight) || !empty($ndtndpExam->neurCommanLeft) || !empty($ndtndpExam->neurCommanRight) || !empty($ndtndpExam->neurFemoralLeft) || !empty($ndtndpExam->neurFemoralRight) || !empty($ndtndpExam->neurLatCutaLeft) || !empty($ndtndpExam->neurLatCutaRight) || !empty($ndtndpExam->neurObturLeft) || !empty($ndtndpExam->neurObturRight) || !empty($ndtndpExam->neurSuralLeft) || !empty($ndtndpExam->neurSuralRight) || !empty($ndtndpExam->neurSaphLeft) || !empty($ndtndpExam->neurSaphRight))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 700; margin: 7px 0 0 0;'>Neurodynamic Test</p>
    <table class="table table-bordered sbkch" style="border-collapse: collapse;">
      <tbody>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Ulnar N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurUlnarLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurUlnarRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Radial N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurRadialLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurRadialRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Media N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurMedianLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurMedianRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Musculocutaneous</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurMusculLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurMusculRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Sciatic N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurSciaticLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurSciaticRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Tibial N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurTibialLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurTibialRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Common Peroneal N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurCommanLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurCommanRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Femoral N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurFemoralLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurFemoralRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Lat Cutaneous N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurLatCutaLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurLatCutaRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Obturator N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurObturLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurObturRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Sural N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurSuralLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurSuralRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Saphenous N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->neurSaphLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->neurSaphRight}}</td>
        </tr>
      </tbody>
    </table>
    @endif
    @if(!empty($ndtndpExam->tissUlnarLeft) || !empty($ndtndpExam->tissUlnarRight) || !empty($ndtndpExam->tissRadialLeft) || !empty($ndtndpExam->tissRadialRight) || !empty($ndtndpExam->tissMedianLeft) || !empty($ndtndpExam->tissSciaticRight) || !empty($ndtndpExam->tissTibialLeft) || !empty($ndtndpExam->tissTibialRight) || !empty($ndtndpExam->tissPeronialLeft) || !empty($ndtndpExam->tissPeronialRight) || !empty($ndtndpExam->tissFemoralLeft) || !empty($ndtndpExam->tissFemoralRight) || !empty($ndtndpExam->tissSuralLeft) || !empty($ndtndpExam->tissSuralRight) || !empty($ndtndpExam->tissSaphenousLeft) || !empty($ndtndpExam->tissSaphenousRight))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 700; margin: 7px 0 0 0;'>Neural Tissue Palpation</p>
    <table class="table table-bordered sbkch" style="border-collapse: collapse;">
      <tbody>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Ulnar N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissUlnarLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissUlnarRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Radial N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissRadialLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissRadialRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Media N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissMedianLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissMedianRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Sciatic N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissSciaticLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissSciaticRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Tibial N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissTibialLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissTibialRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Peronial N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissPeronialLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissPeronialRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Femoral N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissFemoralLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissFemoralRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Sural N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissSuralLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissSuralRight}}</td>
        </tr>
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333;text-align: center; padding: 1px 39px;">Saphenous N</td>
          <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">{{$ndtndpExam->tissSaphenousLeft}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$ndtndpExam->tissSaphenousRight}}</td>
        </tr>
      </tbody>
    </table>
    @endif
  @endif
  @if(!empty($specialExam))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Special Exam:</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Special Test</td>
          <td style="width:50%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Description</td>
        </tr>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; text-align:center;">{{$specialExam->special_test}}</td>
          <td style="width:50%; padding:10px; text-align:center;">{{$specialExam->description}}</td>
        </tr>
      </tbody>
    </table>
  @endif
  @if(!empty($investigation))
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Investigation:</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Type of Investigation</td>
          <td style="width:50%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Description</td>
        </tr>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; text-align:center;">{{$investigation->typeOfInvestigation}}</td>
          <td style="width:50%; padding:10px; text-align:center;">{{$investigation->description}}</td>
        </tr>
      </tbody>
    </table>
  @endif
  @if(!empty($diagnosis))
    @if(!empty($diagnosis->physiotherapeutic_diagnosis))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Physio/Bio-Mechanical Diagnosis:</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Date</td>
          <td style="width:50%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Physiotherapy Diagnosis</td>
        </tr>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; text-align:center;">{{date("d-M-Y", strtotime($diagnosis->created_at))}}</td>
          <td style="width:50%; padding:10px; text-align:center;">{{$diagnosis->physiotherapeutic_diagnosis}}</td>
        </tr>
      </tbody>
    </table>
    @endif
    @if(!empty($diagnosis->medical_diagnosis))
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Medical Diagnosis:</p>
    <table style="width:100%; border:1px solid #000;">
      <tbody>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Date</td>
          <td style="width:50%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Medical Diagnosis</td>
        </tr>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; text-align:center;">{{date("d-M-Y", strtotime($diagnosis->created_at))}}</td>
          <td style="width:50%; padding:10px; text-align:center;">{{$diagnosis->medical_diagnosis}}</td>
        </tr>
      </tbody>
    </table>
    @endif
  @endif
  @if(count($treatmentGoal) > 0)
    <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0;margin-bottom: 5px; font-weight: bold;'>Treatment Goal</p>
    @foreach($treatmentGoal as $goal)
    <table style="width:100%; border:1px solid #000;">
      <tbody>
          <tr>
          <td style="width:20%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">Date: </td>
          <td style="width:80%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">{{date("d-M-Y", strtotime($goal->created_at))}}</td>
        </tr>
        <tr>
          <td style="width:20%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">Short Term Goal: </td>
          <td style="width:80%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">{{$goal->shortGoal}}</td>
        </tr>
        <tr>
          <td style="width:20%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">Treatment: </td>
          <td style="width:80%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">{{$goal->shortMachine}}</td>
        </tr>
        <tr>
          <td style="width:20%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">Long Term Goal: </td>
          <td style="width:80%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">{{$goal->longGoal}}</td>
        </tr>
        <tr>
          <td style="width:20%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">Treatment: </td>
          <td style="width:80%; padding:10px; border-right:0px solid #000; border-bottom: 1px solid #000; text-align:left;">{{$goal->longMachine}}</td>
        </tr>
      </tbody>
    </table>
    @endforeach
  @endif
  
  @if(!empty($orthoCase) && (count($orthoCase) > 0))
    <p style="font-family:sans-serif;text-align: center;font-size: 18px;font-weight: 800;margin: 7px 0 5px 0;">Ortho Case</p>
    <table class="table table-bordered" style="border-collapse: collapse; width:100%; word-wrap:break-word;">
      <tbody>
        <tr style="border: 2px solid #ddd;">
          <td style="border: 2px solid #333; text-align: left;">Date</td>
          <td  style="border: 2px solid #333;  text-align: center;">Chest PT</td>
          <td  style="border: 2px solid #333;  text-align: center;">Chest BR</td>
          <td  style="border: 2px solid #333;  text-align: center;">CPM</td>
          <td  style="border: 2px solid #333;  text-align: center;">ROM Ex</td>
          <td  style="border: 2px solid #333;  text-align: center;">Strengthening Ex</td>
          <td  style="border: 2px solid #333;  text-align: center;">Stretching Ex</td>
          <td  style="border: 2px solid #333;  text-align: center;">Sitting</td>
          <td  style="border: 2px solid #333;  text-align: center;">Standing</td>
          <td  style="border: 2px solid #333;  text-align: center;">Walking</td>
          <td  style="border: 2px solid #333;  text-align: center;">Stairs</td>
          <td  style="border: 2px solid #333; text-align: center;">Washroom Sitting</td>
          <td  style="border: 2px solid #333; text-align: center;">Electrotherapy</td>
          <td  style="border: 2px solid #333; text-align: center;">Hot/Cold Pack</td>
        </tr>
        @foreach($orthoCase as $ocase)
          <tr style="border: 2px solid #333;">
            <td  style="border: 2px solid #333; text-align: left;">{{date("d-M-Y", strtotime($ocase->created_at))}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->chest_pt_postural}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->chest_pt_breathing}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->cpm}}</td>
            <td  style="border: 2px solid #333; ">{{$ocase->rom_ex}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->strengthening_ex}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->stretching_ex}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->sitting}}</td>
            <td  style="border: 2px solid #333; ">{{$ocase->standing}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->walking_no_of_step}}</td>
            <td  style="border: 2px solid #333; ">{{$ocase->stairs_no_of_step}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->w_sitting}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->electotherapy}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ocase->hot_cold_pack}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  @if(!empty($neuroCase) && (count($neuroCase) > 0))
    <p style="font-family:sans-serif;text-align: center;font-size: 18px;font-weight: 800;margin: 7px 0 5px 0; ">Neuro Case </p>
    <table class="table table-bordered" style="border-collapse: collapse; width:100%; word-wrap:break-word;">
      <tbody>
        <tr style="border: 2px solid #ddd;">
          <td style="border: 2px solid #333; text-align: left;">Date</td>
          <td  style="border: 2px solid #333;  text-align: center;">Chest PT</td>
          <td  style="border: 2px solid #333;  text-align: center;">Chest BR</td>
          <td  style="border: 2px solid #333;  text-align: center;">Positioning</td>
          <td  style="border: 2px solid #333;  text-align: center;">Sustained</td>
          <td  style="border: 2px solid #333;  text-align: center;">Weight Bearing</td>
          <td  style="border: 2px solid #333;  text-align: center;">ROM Ex</td>
          <td  style="border: 2px solid #333;  text-align: center;">Strengthening Ex</td>
          <td  style="border: 2px solid #333;  text-align: center;">Balance Ex</td>
          <td  style="border: 2px solid #333;  text-align: center;">Sitting</td>
          <td  style="border: 2px solid #333;  text-align: center;">Standing</td>
          <td  style="border: 2px solid #333; text-align: center;">Walking</td>
          <td  style="border: 2px solid #333;  text-align: center;">Stairs</td>
          <td  style="border: 2px solid #333;  text-align: center;">Washroom Sitting</td>
          <td  style="border: 2px solid #333;  text-align: center;">Electrotherapy</td>
          <td  style="border: 2px solid #333;  text-align: center;">Hot/Cold Pack</td>
        </tr>
        @foreach($neuroCase as $ncase)
          <tr style="border: 2px solid #333;">
            <td  style="border: 2px solid #333; text-align: left;">{{date("d-M-Y", strtotime($ncase->created_at))}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->chest_pt_postural}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->chest_pt_breathing}}</td>
            <td  style="border: 2px solid #333; ">{{$ncase->positioning}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->sustained}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->weight_bearing}}</td>
            <td  style="border: 2px solid #333; ">{{$ncase->rom_ex}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->strengthening_ex}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->balance_ex}}</td>
            <td  style="border: 2px solid #333; ">{{$ncase->sitting}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->standing}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->walking}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->stairs}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->w_sitting}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->electrotherapy}}</td>
            <td  style="border: 2px solid #333; text-align: center;">{{$ncase->hot_cold_pack}}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  @if((count($progressNote) > 0))
    @foreach($progressNote as $progressItem)
      @if(!empty($progressItem->progress_note))
        <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px; font-weight: 800; margin: 7px 0 0 0; margin-bottom: 5px;font-weight: bold;'>Progress Note:</p>
        <table style="width:100%; border:1px solid #000;">
          <tbody>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">Date</td>
          <td style="width:50%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">Progress Notes</td>
        </tr>
        <tr>
          <td style="width:50%; padding:10px; border-right:1px solid #000; border-bottom: 1px solid #000; text-align:center;">{{date("d-M-Y", strtotime($progressItem->created_at))}}</td>
          <td style="width:50%; padding:10px; text-align:center; border-bottom: 1px solid #000; ">{{$progressItem->progress_note}}</td>
        </tr>
          </tbody>
        </table>
      @endif
    @endforeach
  @endif

</div>
</body>
</html>
