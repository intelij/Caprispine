<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use Excel;
use Auth;
use File;
use PDF;
use DateTime;
use DB;

class ReportController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
  }
    
  public function therapistReport(Request $request){
    try{
      $data = array();
      $data['title'] = 'Therapist Report';
      $data['masterclass'] = 'report';
      $data['class'] = 'thReport';
      if(Auth::User()->user_type == 'superadmin'){
        if(!empty($request->therapistName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date)){
          $query = User::where('user_type','5')->where('status','active');
          if (!empty($request->therapistName)) {
              $query = $query->where('name', 'LIKE', '%'.$request->therapistName.'%');
          }
          if (!empty($request->branch)){
              $query = $query->where('branch', $request->branch);
          }
          $results = $query->get();
          $allData = $results;

          $data['to_date'] = $request->to_date;
        }else{
          $allData = User::where('user_type','5')->where('status','active')->get();
        }
      }else{
        if(!empty($request->therapistName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date)){
          $query = User::where('user_type','5')->where('status','active')->where('branch',Auth::user()->branch);
          if (!empty($request->therapistName)) {
              $query = $query->where('name', 'LIKE', '%'.$request->therapistName.'%');
          }
          if (!empty($request->branch)) {
              $query = $query->where('branch', $request->branch);
          }
          $results = $query->get();
          $allData = $results;
        }else{
          $allData = User::where('user_type','5')->where('status','active')->where('branch',Auth::user()->branch)->get();
        }
      }

      $data['allData'] = $allData;
      if(Auth::user()->user_type == 'superadmin'){
            $allBranch = DB::table('location')->get();
      }else{
          $allBranch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
      }
      $data['allBranch'] = $allBranch;
      return view('report.therapist',$data);
    }catch(\Exception $e){
      return view('common.503');
    }
  }

  public function patientReport(){
    try{
      $data = array();
      $data['title'] = 'Patient Report';
      $data['masterclass'] = 'report';
      $data['class'] = 'pReport';
      if(Auth::User()->user_type == 'superadmin'){
        $allPatient = User::where('user_type',3)->where('status','active')->get();
      }else{
        $allPatient = User::where('user_type',3)->where('branch',Auth::User()->branch)->where('status','active')->get();
      }
      $data['allPatient'] = $allPatient;
      $data['allPatientReport'] = $allPatient;
      return view('report.patientReport',$data)->with('no',1);
    }catch(\Exception $e){
      return view('common.503');
    }
  }

  public function searchPatientReport(Request $request){
      // try{
          if(!empty($request->patientId)){
              $patientId = $request->patientId;
              $fromDate = $request->fromDate;
              $toDate = $request->toDate;
              // if(!empty($patientId)){
                  $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                  if($checkPatient){
                      if(!empty($patientId) && empty($toDate) && empty($fromDate)){
                        // Fetch all data
                        $userDetails = DB::table('users')->where('id',$patientId)->first();
                        // age
                        if(!empty($userDetails->dob) && ($userDetails->dob != "0000-00-00")){
                          $dd1 = date("d-m-Y", strtotime($userDetails->dob));
                            $today = date("Y-m-d");
                            $diff = date_diff(date_create($dd1), date_create($today));
                            $age = $diff->format('%y');
                            $age = $age.' Years';
                        }else{
                          $age = '';
                        }
                        $userDetails->age = $age;
                        $referenceType = DB::table('appointment')->where('user_id',$patientId)->where('status','approved')->orderBy('id','DESC')->first();
                        if(!empty($referenceType)){
                            $referenceData = DB::table('reference')->where('id',$referenceType->reference_type)->first();
                            $userDetails->referenceType = $referenceData->name;
                        }else{
                            $userDetails->referenceType = '';
                        }
                          if(!empty($userDetails->branch)){
                              $branchsName = DB::table('location')->where('id',$userDetails->branch)->first();
                              $branchName = $branchsName->name;
                          }else{
                              $branchName = '';
                          }
                          $userDetails->branch = $branchName;
                        $chiefComplaint = DB::table('chief_complaint')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $examHistory = DB::table('exam_history')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $physicalExam = DB::table('physical_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $painExam = DB::table('pain_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $sensoryExam = DB::table('sensory_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $progressNote = DB::table('treatment_note')->where('patient_id',$patientId)->orderBy('id','ASC')->get();
                        $specialExam = DB::table('special_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $investigation = DB::table('investigation_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $diagnosis = DB::table('diagnosis')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $combinedSpineExam = DB::table('mt_combined_spine')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $cervicalSpineExam = DB::table('mt_cervical_spine')->where('flag','cervicalSpine')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $thoracicSpineExam = DB::table('mt_cervical_spine')->where('flag','thoracicSpine')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $lumbarSpineExam = DB::table('mt_cervical_spine')->where('flag','lumbarSpine')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $hipExam = DB::table('mt_hip_exam')->where('flag','hip')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $ankleExam = DB::table('mt_ankle_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $kneeExam = DB::table('mt_hip_exam')->where('flag','knee')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $wristExam = DB::table('mt_hip_exam')->where('flag','wrist')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $shoulderExam = DB::table('mt_hip_exam')->where('flag','shoulder')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $elbowExam = DB::table('mt_hip_exam')->where('flag','elbow')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $forearmExam = DB::table('mt_hip_exam')->where('flag','forearm')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $toesExam = DB::table('mt_hip_exam')->where('flag','toes')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $fingerExam = DB::table('mt_hip_exam')->where('flag','fingers')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $scarollicReport = DB::table('mt_sacrollic_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $ndtndpExam = DB::table('ndt_ndp_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $neurologicalExam = DB::table('neurological_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        $treatmentGoal = DB::table('treatment_goal')->where('patient_id',$patientId)->orderBy('id','ASC')->get();
                        $orthoCase = DB::table('ortho_case')->where('patient_id',$patientId)->orderBy('id','ASC')->get();
                        $neuroCase = DB::table('neuro_case')->where('patient_id',$patientId)->orderBy('id','ASC')->get();
                        // dd($treatmentGoal);
                        // generate report in pdf format file
                        $pdf = PDF::loadView('report.caseReportCapriWeb',compact('userDetails','chiefComplaint','examHistory','physicalExam','painExam','sensoryExam','progressNote','specialExam','investigation','diagnosis','combinedSpineExam','cervicalSpineExam','thoracicSpineExam','lumbarSpineExam','hipExam','ankleExam','kneeExam','wristExam','shoulderExam','elbowExam','forearmExam','toesExam','fingerExam','scarollicReport','ndtndpExam','neurologicalExam','treatmentGoal','orthoCase','neuroCase'));
                        $ranVal = $this->randomValue(5);
                        $res = $pdf->save('public/upload/patient_case_report/report_'.$ranVal.'.pdf');
                        if($res){
                          $addData =array();
                          $addData['patient_id'] = $patientId;
                          $addData['name'] = 'report_'.$ranVal.'.pdf';
                          $addData['date'] = date('Y-m-d');
                          DB::table('case_report')->insert($addData);
                          $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                          $report = CASE_REPORT_DOC.$reportData->name;
                          $data['report'] = $report;
                          $data['pName'] = $checkPatient->name;
                        }
                      }else if(!empty($patientId) && !empty($toDate) && empty($fromDate)){
                          // Fetch all data
                          $userDetails = DB::table('users')->where('id',$patientId)->first();
                          // age
                          if(!empty($userDetails->dob) && ($userDetails->dob != "0000-00-00")){
                              $dd1 = date("d-m-Y", strtotime($userDetails->dob));
                              $today = date("Y-m-d");
                              $diff = date_diff(date_create($dd1), date_create($today));
                              $age = $diff->format('%y');
                              $age = $age.' Years';
                          }else{
                              $age = '';
                          }
                          $userDetails->age = $age;
                          $referenceType = DB::table('appointment')->where('user_id',$patientId)->where('status','approved')->orderBy('id','DESC')->first();
                          if(!empty($referenceType)){
                              $referenceData = DB::table('reference')->where('id',$referenceType->reference_type)->first();
                              $userDetails->referenceType = $referenceData->name;
                          }else{
                              $userDetails->referenceType = '';
                          }
                          $chiefComplaint = DB::table('chief_complaint')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $examHistory = DB::table('exam_history')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $physicalExam = DB::table('physical_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $painExam = DB::table('pain_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $sensoryExam = DB::table('sensory_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $progressNote = DB::table('treatment_note')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','ASC')->get();
                          $specialExam = DB::table('special_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $investigation = DB::table('investigation_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $diagnosis = DB::table('diagnosis')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();

                          $combinedSpineExam = DB::table('mt_combined_spine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $cervicalSpineExam = DB::table('mt_cervical_spine')->where('flag','cervicalSpine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $thoracicSpineExam = DB::table('mt_cervical_spine')->where('flag','thoracicSpine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $lumbarSpineExam = DB::table('mt_cervical_spine')->where('flag','lumbarSpine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $hipExam = DB::table('mt_hip_exam')->where('flag','hip')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $ankleExam = DB::table('mt_ankle_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $kneeExam = DB::table('mt_hip_exam')->where('flag','knee')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $wristExam = DB::table('mt_hip_exam')->where('flag','wrist')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $shoulderExam = DB::table('mt_hip_exam')->where('flag','shoulder')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $elbowExam = DB::table('mt_hip_exam')->where('flag','elbow')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $forearmExam = DB::table('mt_hip_exam')->where('flag','forearm')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $toesExam = DB::table('mt_hip_exam')->where('flag','toes')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $fingerExam = DB::table('mt_hip_exam')->where('flag','fingers')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $scarollicReport = DB::table('mt_sacrollic_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $ndtndpExam = DB::table('ndt_ndp_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $neurologicalExam = DB::table('neurological_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','DESC')->first();
                          $treatmentGoal = DB::table('treatment_goal')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','ASC')->get();
                          $orthoCase = DB::table('ortho_case')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','ASC')->get();
                          $neuroCase = DB::table('neuro_case')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$toDate)->orderBy('id','ASC')->get();
                          // generate report in pdf format file
                          $pdf = PDF::loadView('report.caseReportCapriApp',compact('userDetails','chiefComplaint','examHistory','physicalExam','painExam','sensoryExam','progressNote','specialExam','investigation','diagnosis','combinedSpineExam','cervicalSpineExam','thoracicSpineExam','lumbarSpineExam','hipExam','ankleExam','kneeExam','wristExam','shoulderExam','elbowExam','forearmExam','toesExam','fingerExam','scarollicReport','ndtndpExam','neurologicalExam','treatmentGoal','orthoCase','neuroCase'));
                          $ranVal = $this->randomValue(5);
                          $res = $pdf->save('public/upload/patient_case_report/report_'.$ranVal.'.pdf');
                          if($res){
                              $addData =array();
                              $addData['patient_id'] = $patientId;
                              $addData['name'] = 'report_'.$ranVal.'.pdf';
                              $addData['date'] = date('Y-m-d');
                              DB::table('case_report')->insert($addData);
                              $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                              $report = CASE_REPORT_DOC.$reportData->name;
                              $data['report'] = $report;
                              $data['pName'] = $checkPatient->name;
                          }
                      }else if(!empty($patientId) && empty($toDate) && !empty($fromDate)){
                          // Fetch all data
                          $userDetails = DB::table('users')->where('id',$patientId)->first();
                          // age
                          if(!empty($userDetails->dob) && ($userDetails->dob != "0000-00-00")){
                              $dd1 = date("d-m-Y", strtotime($userDetails->dob));
                              $today = date("Y-m-d");
                              $diff = date_diff(date_create($dd1), date_create($today));
                              $age = $diff->format('%y');
                              $age = $age.' Years';
                          }else{
                              $age = '';
                          }
                          $userDetails->age = $age;
                          $referenceType = DB::table('appointment')->where('user_id',$patientId)->where('status','approved')->orderBy('id','DESC')->first();
                          if(!empty($referenceType)){
                              $referenceData = DB::table('reference')->where('id',$referenceType->reference_type)->first();
                              $userDetails->referenceType = $referenceData->name;
                          }else{
                              $userDetails->referenceType = '';
                          }
                          $chiefComplaint = DB::table('chief_complaint')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $examHistory = DB::table('exam_history')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $physicalExam = DB::table('physical_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $painExam = DB::table('pain_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $sensoryExam = DB::table('sensory_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $progressNote = DB::table('treatment_note')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','ASC')->get();
                          $specialExam = DB::table('special_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $investigation = DB::table('investigation_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $diagnosis = DB::table('diagnosis')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();

                          $combinedSpineExam = DB::table('mt_combined_spine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $cervicalSpineExam = DB::table('mt_cervical_spine')->where('flag','cervicalSpine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $thoracicSpineExam = DB::table('mt_cervical_spine')->where('flag','thoracicSpine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $lumbarSpineExam = DB::table('mt_cervical_spine')->where('flag','lumbarSpine')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $hipExam = DB::table('mt_hip_exam')->where('flag','hip')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $ankleExam = DB::table('mt_ankle_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $kneeExam = DB::table('mt_hip_exam')->where('flag','knee')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $wristExam = DB::table('mt_hip_exam')->where('flag','wrist')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $shoulderExam = DB::table('mt_hip_exam')->where('flag','shoulder')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $elbowExam = DB::table('mt_hip_exam')->where('flag','elbow')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $forearmExam = DB::table('mt_hip_exam')->where('flag','forearm')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $toesExam = DB::table('mt_hip_exam')->where('flag','toes')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $fingerExam = DB::table('mt_hip_exam')->where('flag','fingers')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $scarollicReport = DB::table('mt_sacrollic_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $ndtndpExam = DB::table('ndt_ndp_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $neurologicalExam = DB::table('neurological_exam')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','DESC')->first();
                          $treatmentGoal = DB::table('treatment_goal')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','ASC')->get();
                          $orthoCase = DB::table('ortho_case')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','ASC')->get();
                          $neuroCase = DB::table('neuro_case')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $fromDate)->orderBy('id','ASC')->get();
                          // generate report in pdf format file
                          $pdf = PDF::loadView('report.caseReportCapriApp',compact('userDetails','chiefComplaint','examHistory','physicalExam','painExam','sensoryExam','progressNote','specialExam','investigation','diagnosis','combinedSpineExam','cervicalSpineExam','thoracicSpineExam','lumbarSpineExam','hipExam','ankleExam','kneeExam','wristExam','shoulderExam','elbowExam','forearmExam','toesExam','fingerExam','scarollicReport','ndtndpExam','neurologicalExam','treatmentGoal','orthoCase','neuroCase'));
                          $ranVal = $this->randomValue(5);
                          $res = $pdf->save('public/upload/patient_case_report/report_'.$ranVal.'.pdf');
                          if($res){
                              $addData =array();
                              $addData['patient_id'] = $patientId;
                              $addData['name'] = 'report_'.$ranVal.'.pdf';
                              $addData['date'] = date('Y-m-d');
                              DB::table('case_report')->insert($addData);
                              $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                              $report = CASE_REPORT_DOC.$reportData->name;
                              $data['report'] = $report;
                              $data['pName'] = $checkPatient->name;
                          }
                      }else if(!empty($patientId) && !empty($toDate) && !empty($fromDate)){
                          // Fetch all data
                          $userDetails = DB::table('users')->where('id',$patientId)->first();
                          // age
                          if(!empty($userDetails->dob) && ($userDetails->dob != "0000-00-00")){
                              $dd1 = date("d-m-Y", strtotime($userDetails->dob));
                              $today = date("Y-m-d");
                              $diff = date_diff(date_create($dd1), date_create($today));
                              $age = $diff->format('%y');
                              $age = $age.' Years';
                          }else{
                              $age = '';
                          }
                          $userDetails->age = $age;
                          $referenceType = DB::table('appointment')->where('user_id',$patientId)->where('status','approved')->orderBy('id','DESC')->first();
                          if(!empty($referenceType)){
                              $referenceData = DB::table('reference')->where('id',$referenceType->reference_type)->first();
                              $userDetails->referenceType = $referenceData->name;
                          }else{
                              $userDetails->referenceType = '';
                          }
                          $chiefComplaint = DB::table('chief_complaint')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $examHistory = DB::table('exam_history')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $physicalExam = DB::table('physical_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $painExam = DB::table('pain_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $sensoryExam = DB::table('sensory_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $progressNote = DB::table('treatment_note')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','ASC')->get();
                          $specialExam = DB::table('special_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $investigation = DB::table('investigation_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $diagnosis = DB::table('diagnosis')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();

                          $combinedSpineExam = DB::table('mt_combined_spine')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $cervicalSpineExam = DB::table('mt_cervical_spine')->where('flag','cervicalSpine')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $thoracicSpineExam = DB::table('mt_cervical_spine')->where('flag','thoracicSpine')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $lumbarSpineExam = DB::table('mt_cervical_spine')->where('flag','lumbarSpine')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $hipExam = DB::table('mt_hip_exam')->where('flag','hip')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $ankleExam = DB::table('mt_ankle_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $kneeExam = DB::table('mt_hip_exam')->where('flag','knee')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $wristExam = DB::table('mt_hip_exam')->where('flag','wrist')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $shoulderExam = DB::table('mt_hip_exam')->where('flag','shoulder')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $elbowExam = DB::table('mt_hip_exam')->where('flag','elbow')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $forearmExam = DB::table('mt_hip_exam')->where('flag','forearm')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $toesExam = DB::table('mt_hip_exam')->where('flag','toes')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $fingerExam = DB::table('mt_hip_exam')->where('flag','fingers')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $scarollicReport = DB::table('mt_sacrollic_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $ndtndpExam = DB::table('ndt_ndp_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $neurologicalExam = DB::table('neurological_exam')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','DESC')->first();
                          $treatmentGoal = DB::table('treatment_goal')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','ASC')->get();
                          $orthoCase = DB::table('ortho_case')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','ASC')->get();
                          $neuroCase = DB::table('neuro_case')->where('patient_id',$patientId)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$toDate, $fromDate])->orderBy('id','ASC')->get();
                          // generate report in pdf format file
                          $pdf = PDF::loadView('report.caseReportCapriApp',compact('userDetails','chiefComplaint','examHistory','physicalExam','painExam','sensoryExam','progressNote','specialExam','investigation','diagnosis','combinedSpineExam','cervicalSpineExam','thoracicSpineExam','lumbarSpineExam','hipExam','ankleExam','kneeExam','wristExam','shoulderExam','elbowExam','forearmExam','toesExam','fingerExam','scarollicReport','ndtndpExam','neurologicalExam','treatmentGoal','orthoCase','neuroCase'));
                          $ranVal = $this->randomValue(5);
                          $res = $pdf->save('public/upload/patient_case_report/report_'.$ranVal.'.pdf');
                          if($res){
                              $addData =array();
                              $addData['patient_id'] = $patientId;
                              $addData['name'] = 'report_'.$ranVal.'.pdf';
                              $addData['date'] = date('Y-m-d');
                              DB::table('case_report')->insert($addData);
                              $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                              $report = CASE_REPORT_DOC.$reportData->name;
                              $data['report'] = $report;
                              $data['pName'] = $checkPatient->name;
                          }
                      }
                  }
              // }else{
              //     $response['message'] = 'Fields cant be empty!';
              //     $response['status'] = '0';
              // }
          }
      // }catch(\Exception $e){
      //     return view('common.503');
      // }
      // return response()->json($response);
      $data['title'] = 'Patient Report';
      $data['masterclass'] = 'report';
      $data['class'] = 'pReport';
      if(Auth::User()->user_type == 'superadmin'){
        $allPatient = User::where('user_type',3)->where('status','active')->get();
      }else{
        $allPatient = User::where('user_type',3)->where('branch',Auth::User()->branch)->where('status','active')->get();
      }
      $data['allPatient'] = $allPatient;
      $data['allPatientReport'] = '';
      return view('report.patientReport',$data)->with('no',1);
  }

  public function exportTherapistReport(){
      Excel::create('Therapist_report', function($excel){
        $excel->sheet('All', function($sheet){
        $sheet->row(1, array('Therapist Name',''));
        $data = User::where('user_type','5')->get();
        $count = 2;
        foreach ($data as $data_item) {
          $sheet->appendRow($count++, array($data_item->name,''));
        }
      });
      })->download('xlsx');
      return TRUE;
  }

  // All Report with date filter
  public function allReport(Request $request){
    try{
      $data = array();
      $data['title'] = 'Therapist Report';
      $data['masterclass'] = 'report';
      $data['class'] = 'tReport';
      if(Auth::User()->user_type == 'superadmin'){
        if(!empty($request->therapistName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date)){
          $query = User::where('user_type','5')->where('status','active');
          if (!empty($request->therapistName)) {
              $query = $query->where('name', 'LIKE', '%'.$request->therapistName.'%');
          }
          if (!empty($request->branch)){
              $query = $query->where('branch', $request->branch);
          }
          $results = $query->get();
          $allData = $results;
          //  In between to date --- from date
          if(!empty($request->to_date) && !empty($request->from_date)){
            foreach($allData as $value){
              $value->to_date = $request->to_date;
              $value->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;
              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              // dd($staffCollection,$extraAmt);
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('capri_account');
              $value->capriCollection = $capriCollection;
              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;
              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              // $totalAppointedTNP = DB::table('appointment')->
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              if($AAVP == 0){
                $AVMiss = 0;
              }else{
                if($AAV > 80){
                  $AVMiss = 0;
                }else{
                  $AVMiss = round(80 - $AAVP,2);
                }
              }
              $value->AVMiss = $AVMiss;

              if(($SAW != 0) && ($AVMiss != 0)){
                $PAWM = round($SAW * $AVMiss / 100,2);
              }else{
                $PAWM = 0;
              }
              $value->PAWM = $PAWM;

              if($SAW != 0){
                $ESAW = round($SAW - $PAWM,2);
              }else{
                $ESAW = 0;
              }
              $value->ESAW = $ESAW;

              $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->whereBetween('attendance.date', [$request->to_date, $request->from_date])->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              if($totalIPD){
                $IPD = round($totalIPD * 800,2);
              }else{
                $IPD = 0;
              }
              $value->IPD = $IPD;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty, 2);
              $value->ES = $ES;

              if($ES != 0){
                $ESPD = round($ES / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              $value->leaves = $leaves;

              if($leaves > 1){
                $deductLeave = round($ESPD * $leaves,2);
              }else{
                $deductLeave = 0;
              }
              $value->deductLeave = $deductLeave;

              if($ES != 0){
                $sharingFinal = round($ES - $deductLeave,2);
              }else{
                $sharingFinal = 0;
              }
              $value->sharingFinal = $sharingFinal;

              if($sharingFinal != 0){
                $TDS = round($sharingFinal * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              if(($TDS != 0) && ($totalSharing != 0)){
                $PHC = round($totalSharing - $TDS, 0);
              }else{
                $PHC = 0;
              }
              $value->PHC = $PHC;


              if($sharingFinal != 0){
                $amountTransfer = round($sharingFinal - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              if($TAP != 0){
                $AWM = round(($TAP * 20 / 100) - $TAW,2);
              }else{
                $AWM = 0;
              }
              $value->AWM = $AWM;

              if(($AWM != 0) && ($TAP != 0)){
                $AWMP = round($AWM / $TAP * 100,2);
              }else{
                $AWMP = 0;
              }
              $value->AWMP = $AWMP;

              $patientLoss = round($AWMP + $AVMiss,2);
              $value->patientLoss = $patientLoss;

              if(($patientLoss != 0) && ($TAP != 0)){
                $noPatientLoss = round($patientLoss * $TAP / 100,2);
              }else{
                $noPatientLoss = 0;
              }
              $value->noPatientLoss = $noPatientLoss;

              if(($noPatientLoss != 0) && ($ASPV != 0)){
                $financialLoss = round($noPatientLoss * $ASPV,2);
              }else{
                $financialLoss = 0;
              }
              $value->financialLoss = $financialLoss;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              if($totalPenalty){
                $penaltyKitty = round($totalPenalty,2);
              }else{
                $penaltyKitty = 0;
              }
              $value->penaltyKitty = $penaltyKitty;

              $totalLoss = round($financialLoss + $penaltyKitty,2);
              $value->totalLoss = $totalLoss;

              $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // to date filter only
          if(!empty($request->to_date) && empty($request->from_date)){
            foreach($allData as $value) {
              $value->to_date = $request->to_date;
              $value->from_date = 0;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->where('date', '<=', $request->to_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('capri_account');
              $value->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$request->to_date)->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              if($AAVP == 0){
                $AVMiss = 0;
              }else{
                if($AAV > 80){
                  $AVMiss = 0;
                }else{
                  $AVMiss = round(80 - $AAVP,2);
                } 
              }
              $value->AVMiss = $AVMiss;

              if(($SAW != 0) && ($AVMiss != 0)){
                $PAWM = round($SAW * $AVMiss / 100,2);
              }else{
                $PAWM = 0;
              }
              $value->PAWM = $PAWM;

              if($SAW != 0){
                $ESAW = round($SAW - $PAWM,2);
              }else{
                $ESAW = 0;
              }
              $value->ESAW = $ESAW;

              $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.date', '<=', $request->to_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              if($totalIPD){
                $IPD = $totalIPD * 800;
              }else{
                $IPD = 0;
              }
              $value->IPD = $IPD;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty, 2);
              $value->ES = $ES;

              if($ES != 0){
                $ESPD = round($ES / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->where('date', '<=', $request->to_date)->count('id');
              $value->leaves = $leaves;

              if($leaves > 1){
                $deductLeave = round($ESPD * $leaves,2);
              }else{
                $deductLeave = 0;
              }
              $value->deductLeave = $deductLeave;

              if($ES != 0){
                $sharingFinal = round($ES - $deductLeave,2);
              }else{
                $sharingFinal = 0;
              }
              $value->sharingFinal = $sharingFinal;

              if($sharingFinal != 0){
                $TDS = round($sharingFinal * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              if(($TDS != 0) && ($totalSharing != 0)){
                $PHC = round($totalSharing - $TDS, 0);
              }else{
                $PHC = 0;
              }
              $value->PHC = $PHC;

              if($sharingFinal != 0){
                $amountTransfer = round($sharingFinal - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              if($TAP != 0){
                $AWM = round(($TAP * 20 / 100) - $TAW,2);
              }else{
                $AWM = 0;
              }
              $value->AWM = $AWM;

              if(($AWM != 0) && ($TAP != 0)){
                $AWMP = round($AWM / $TAP * 100,2);
              }else{
                $AWMP = 0;
              }
              $value->AWMP = $AWMP;

              $patientLoss = round($AWMP + $AVMiss,2);
              $value->patientLoss = $patientLoss;

              if(($patientLoss != 0) && ($TAP != 0)){
                $noPatientLoss = round($patientLoss * $TAP / 100,2);
              }else{
                $noPatientLoss = 0;
              }
              $value->noPatientLoss = $noPatientLoss;

              if(($noPatientLoss != 0) && ($ASPV != 0)){
                $financialLoss = round($noPatientLoss * $ASPV,2);
              }else{
                $financialLoss = 0;
              }
              $value->financialLoss = $financialLoss;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              if($totalPenalty){
                $penaltyKitty = round($totalPenalty,2);
              }else{
                $penaltyKitty = 0;
              }
              $value->penaltyKitty = $penaltyKitty;

              $totalLoss = round($financialLoss + $penaltyKitty,2);
              $value->totalLoss = $totalLoss;

              $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // from date filter only
          if(!empty($request->from_date) && empty($request->to_date)){
            foreach($allData as $values) {
              $values->to_date = 0;
              $values->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$values->id)->where('status','present')->where('flag','not_ipd')->where('date', '>=', $request->from_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $values->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $values->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('therapist_account');
              $values->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('capri_account');
              $values->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','!=','')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $values->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $values->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $values->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $values->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('therapist_account');
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$values->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $values->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $values->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $values->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AV')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $values->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AW')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $values->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $values->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $values->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $values->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $values->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $values->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $values->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $values->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $values->AAVP = $AAVP;

              if($AAVP == 0){
                $AVMiss = 0;
              }else{
                if($AAV > 80){
                  $AVMiss = 0;
                }else{
                  $AVMiss = round(80 - $AAVP,2);
                } 
              }
              $values->AVMiss = $AVMiss;

              if(($SAW != 0) && ($AVMiss != 0)){
                $PAWM = round($SAW * $AVMiss / 100,2);
              }else{
                $PAWM = 0;
              }
              $values->PAWM = $PAWM;

              if($SAW != 0){
                $ESAW = round($SAW - $PAWM,2);
              }else{
                $ESAW = 0;
              }
              $values->ESAW = $ESAW;

              $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$values->id)->where('attendance.date', '>=', $request->from_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              if($totalIPD){
                $IPD = round($totalIPD * 800,2);
              }else{
                $IPD = 0;
              }
              $values->IPD = $IPD;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              $values->ES = $ES;

              if($ES != 0){
                $ESPD = round($ES / 30,2);
              }else{
                $ESPD = 0;
              }
              $values->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$values->id)->where('date', '>=', $request->from_date)->count('id');
              $values->leaves = $leaves;

              if($leaves > 1){
                $deductLeave = round($ESPD * $leaves,2);
              }else{
                $deductLeave = 0;
              }
              $values->deductLeave = $deductLeave;

              if($ES != 0){
                $sharingFinal = round($ES - $deductLeave,2);
              }else{
                $sharingFinal = 0;
              }
              $values->sharingFinal = $sharingFinal;

              if($sharingFinal != 0){
                $TDS = round($sharingFinal * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $values->TDS = $TDS;

              if(($TDS != 0) && ($totalSharing != 0)){
                $PHC = round($totalSharing - $TDS, 0);
              }else{
                $PHC = 0;
              }
              $value->PHC = $PHC;

              if($sharingFinal != 0){
                $amountTransfer = round($sharingFinal - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $values->amountTransfer = $amountTransfer;

              if($TAP != 0){
                $AWM = round(($TAP * 20 / 100) - $TAW,2);
              }else{
                $AWM = 0;
              }
              $values->AWM = $AWM;

              if(($AWM != 0) && ($TAP != 0)){
                $AWMP = round($AWM / $TAP * 100,2);
              }else{
                $AWMP = 0;
              }
              $values->AWMP = $AWMP;

              $patientLoss = round($AWMP + $AVMiss,2);
              $values->patientLoss = $patientLoss;

              if(($patientLoss != 0) && ($TAP != 0)){
              $noPatientLoss = round($patientLoss * $TAP / 100,2);
              }else{
                $noPatientLoss = 0;
              }
              $values->noPatientLoss = $noPatientLoss;

              if(($noPatientLoss != 0) && ($ASPV != 0)){
                $financialLoss = round($noPatientLoss * $ASPV,2);
              }else{
                $financialLoss = 0;
              }
              $values->financialLoss = $financialLoss;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              if($totalPenalty){
                $penaltyKitty = round($totalPenalty,2);
              }else{
                $penaltyKitty = 0;
              }
              $values->penaltyKitty = $penaltyKitty;

              $totalLoss = round($financialLoss + $penaltyKitty,2);
              $values->totalLoss = $totalLoss;

              $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              $values->amountWithoutLoss = $amountWithoutLoss;
            }
          }
        }else{
          $allData = User::where('user_type','5')->where('status','active')->get();
          foreach($allData as $value) {
            $value->to_date = 0;
            $value->from_date = 0;
            $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->count('id');
            if($totalWorkingDays){
              $TWD = $totalWorkingDays;
            }else{
              $TWD = 0;
            }
            $value->TWD = $TWD;

            $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('amount');
            $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('extra_amount');
            $staffCollection = $staffCollection + $extraAmt;
            if($staffCollection != 0){
              $totalStaffCollection = round($staffCollection,2);
            }else{
              $totalStaffCollection = 0;
            }
            $value->totalStaffCollection = $totalStaffCollection;
            $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            $value->therapistCollection = $therapistCollection;
            $capriCollection = DB::table('account')->where('therapist_id',$value->id)->sum('capri_account');
            $value->capriCollection = $capriCollection;

            $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->count('id');
            if($patientVisit != 0){
              $totalPatientVisit = $patientVisit;
            }else{
              $totalPatientVisit = 0;
            }
            $value->totalPatientVisit = $totalPatientVisit;

            if(($staffCollection != 0) && ($TWD != 0)){
              $ACD = round($staffCollection / $TWD,2);
            }else{
              $ACD = 0;
            }
            $value->ACD = $ACD;

            if(($totalPatientVisit != 0) && ($TWD != 0)){
              $APTD = round($totalPatientVisit / $TWD,2);
            }else{
              $APTD = 0;
            }
            $value->APTD = $APTD;

            if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
              $CPV = round($totalStaffCollection / $totalPatientVisit,2);
            }else{
              $CPV = 0;
            }
            $value->CPV = $CPV;

            // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            // if($totalSharingAmount){
            //   $totalSharing = round($totalSharingAmount,2);
            // }else{
            //   $totalSharing = 0;
            // }
            $thDetails = DB::table('users')->where('id',$value->id)->first();
            $baseAmt = $thDetails->base_commision;
            if(($totalStaffCollection != 0) && !empty($baseAmt)){
              $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
            }else{
              $totalSharing = 0;
            }
            $value->totalSharing = $totalSharing;

            if(($totalSharing != 0) && ($totalPatientVisit != 0)){
              $ASPV = round($totalSharing / $totalPatientVisit,2);
            }else{
              $ASPV = 0;
            }
            $value->ASPV = $ASPV;
              
            // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->count('appointment.id');
            $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->count('id');
            if($totalPlannedPatient != 0){
              $TAP = $totalPlannedPatient;
            }else{
              $TAP = 0;
            }
            $value->TAP = $TAP;

            $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->count('id');
            if($totalAppointedVisitPatient != 0){
              $TAV = $totalAppointedVisitPatient;
            }else{
              $TAV = 0;
            }
            $value->TAV = $TAV;

            $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->count('id');
            if($totalApoointedWithoutVisitedPatient != 0){
              $TAW = $totalApoointedWithoutVisitedPatient;
            }else{
              $TAW = 0;
            }
            $value->TAW = $TAW;

            // $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->count('appointment.id');
            $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->groupBy('appointment.user_id')->where('users.therapist_id',$value->id)->distinct('appointment.user_id')->count('appointment.id');
            if($totalAppointedTNP != 0){
              $TNP = $totalAppointedTNP;
            }else{
              $TNP = 0;
            }
            $value->TNP = $TNP;

            if(($TAV != 0) && ($TWD != 0)){
              $AAV = round($TAV / $TWD,2);
            }else{
              $AAV = 0;
            }
            $value->AAV = $AAV;

            if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
            }else{
              $AAW = 0;
            }
            $value->AAW = $AAW;

            if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
            }else{
              $ANP = 0;
            }
            $value->ANP = $ANP;

            if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
            }else{
              $SAV = 0;
            }
            $value->SAV = $SAV;

            if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
            }else{
              $SAW = 0;
            }
            $value->SAW = $SAW;

            if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
            }else{
              $SNP = 0;
            }
            $value->SNP = $SNP;

            if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
            }else{
              $AAVP = 0;
            }
            $value->AAVP = $AAVP;

            if($AAVP == 0){
              $AVMiss = 0;
            }else{
              if($AAVP > 80){
                $AVMiss = 0;
              }else{
                $AVMiss = round(80 - $AAVP,2);
              } 
            }
            $value->AVMiss = $AVMiss;

            if(($SAW != 0) && ($AVMiss != 0)){
              $PAWM = round($SAW * $AVMiss / 100,2);
            }else{
              $PAWM = 0;
            }
            $value->PAWM = $PAWM;

            if($SAW != 0){
              $ESAW = round($SAW - $PAWM,2);
            }else{
              $ESAW = 0;
            }
            $value->ESAW = $ESAW;

            $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
            if($totalIPD){
              $IPD = $totalIPD * 800;
            }else{
              $IPD = 0;
            }
            $value->IPD = $IPD;

            $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
            $value->ES = $ES;

            if($ES != 0){
              $ESPD = round($ES / 30,2);
            }else{
              $ESPD = 0;
            }
            $value->ESPD = $ESPD;

            $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->count('id');
            $value->leaves = $leaves;

            if($leaves > 1){
              $deductLeave = round($ESPD * $leaves,2);
            }else{
              $deductLeave = 0;
            }
            $value->deductLeave = $deductLeave;

            if($ES != 0){
              $sharingFinal = round($ES - $deductLeave,2);
            }else{
              $sharingFinal = 0;
            }
            $value->sharingFinal = $sharingFinal;

            if($sharingFinal != 0){
              $TDS = round($sharingFinal * 10 / 100, 0);
            }else{
              $TDS = 0;
            }
            $value->TDS = $TDS;

            if(($TDS != 0) && ($totalSharing != 0)){
              $PHC = round($totalSharing - $TDS, 0);
            }else{
              $PHC = 0;
            }
            $value->PHC = $PHC;

            if($sharingFinal != 0){
              $amountTransfer = round($sharingFinal - $TDS,2);
            }else{
              $amountTransfer = 0;
            }
            $value->amountTransfer = $amountTransfer;

            if($TAP != 0){
              $AWM = round(($TAP * 20 / 100) - $TAW,2);
            }else{
              $AWM = 0;
            }
            $value->AWM = $AWM;

            if(($AWM != 0) && ($TAP != 0)){
              $AWMP = round($AWM / $TAP * 100,2);
            }else{
              $AWMP = 0;
            }
            $value->AWMP = $AWMP;

            $patientLoss = round($AWMP + $AVMiss,2);
            $value->patientLoss = $patientLoss;

            if(($patientLoss != 0) && ($TAP != 0)){
              $noPatientLoss = round($patientLoss * $TAP / 100,2);
            }else{
              $noPatientLoss = 0;
            }
            $value->noPatientLoss = $noPatientLoss;

            if(($noPatientLoss != 0) && ($ASPV != 0)){
              $financialLoss = round($noPatientLoss * $ASPV,2);
            }else{
              $financialLoss = 0;
            }
            $value->financialLoss = $financialLoss;

            $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            if($totalPenalty){
              $penaltyKitty = round($totalPenalty,2);
            }else{
              $penaltyKitty = 0;
            }
            $value->penaltyKitty = $penaltyKitty;

            $totalLoss = round($financialLoss + $penaltyKitty,2);
            $value->totalLoss = $totalLoss;

            $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
            $value->amountWithoutLoss = $amountWithoutLoss;

          }
        }
      }else{
        if(!empty($request->therapistName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date)){
          $query = User::where('user_type','5')->where('status','active')->where('branch',Auth::User()->branch);
          if (!empty($request->therapistName)) {
              $query = $query->where('name', 'LIKE', '%'.$request->therapistName.'%');
          }
          if (!empty($request->branch)){
              $query = $query->where('branch', $request->branch);
          }
          $results = $query->get();
          $allData = $results;
          //  In between to date --- from date
          if(!empty($request->to_date) && !empty($request->from_date)){
            foreach($allData as $value){
              $value->to_date = $request->to_date;
              $value->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('capri_account');
              $value->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;
              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              if($AAVP == 0){
                $AVMiss = 0;
              }else{
                if($AAV > 80){
                  $AVMiss = 0;
                }else{
                  $AVMiss = round(80 - $AAVP,2);
                }
              }
              $value->AVMiss = $AVMiss;

              if(($SAW != 0) && ($AVMiss != 0)){
                $PAWM = round($SAW * $AVMiss / 100,2);
              }else{
                $PAWM = 0;
              }
              $value->PAWM = $PAWM;

              if($SAW != 0){
                $ESAW = round($SAW - $PAWM,2);
              }else{
                $ESAW = 0;
              }
              $value->ESAW = $ESAW;

              $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->whereBetween('attendance.date', [$request->to_date, $request->from_date])->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              if($totalIPD){
                $IPD = round($totalIPD * 800,2);
              }else{
                $IPD = 0;
              }
              $value->IPD = $IPD;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              $value->ES = $ES;

              if($ES != 0){
                $ESPD = round($ES / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              $value->leaves = $leaves;

              if($leaves > 1){
                $deductLeave = round($ESPD * $leaves,2);
              }else{
                $deductLeave = 0;
              }
              $value->deductLeave = $deductLeave;

              if($ES != 0){
                $sharingFinal = round($ES - $deductLeave,2);
              }else{
                $sharingFinal = 0;
              }
              $value->sharingFinal = $sharingFinal;

              if($sharingFinal != 0){
                $TDS = round($sharingFinal * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              if(($TDS != 0) && ($totalSharing != 0)){
                $PHC = round($totalSharing - $TDS, 0);
              }else{
                $PHC = 0;
              }
              $value->PHC = $PHC;

              if($sharingFinal != 0){
                $amountTransfer = round($sharingFinal - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              if($TAP != 0){
                $AWM = round(($TAP * 20 / 100) - $TAW,2);
              }else{
                $AWM = 0;
              }
              $value->AWM = $AWM;

              if(($AWM != 0) && ($TAP != 0)){
                $AWMP = round($AWM / $TAP * 100,2);
              }else{
                $AWMP = 0;
              }
              $value->AWMP = $AWMP;

              $patientLoss = round($AWMP + $AVMiss,2);
              $value->patientLoss = $patientLoss;

              if(($patientLoss != 0) && ($TAP != 0)){
                $noPatientLoss = round($patientLoss * $TAP / 100,2);
              }else{
                $noPatientLoss = 0;
              }
              $value->noPatientLoss = $noPatientLoss;

              if(($noPatientLoss != 0) && ($ASPV != 0)){
                $financialLoss = round($noPatientLoss * $ASPV,2);
              }else{
                $financialLoss = 0;
              }
              $value->financialLoss = $financialLoss;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              if($totalPenalty){
                $penaltyKitty = round($totalPenalty,2);
              }else{
                $penaltyKitty = 0;
              }
              $value->penaltyKitty = $penaltyKitty;

              $totalLoss = round($financialLoss + $penaltyKitty,2);
              $value->totalLoss = $totalLoss;

              $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // to date filter only
          if(!empty($request->to_date) && empty($request->from_date)){
            foreach($allData as $value) {
              $value->to_date = $request->to_date;
              $value->from_date = 0;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->where('date', '<=', $request->to_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('capri_account');
              $value->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$request->to_date)->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              if($AAVP == 0){
                $AVMiss = 0;
              }else{
                if($AAV > 80){
                  $AVMiss = 0;
                }else{
                  $AVMiss = round(80 - $AAVP,2);
                } 
              }
              $value->AVMiss = $AVMiss;

              if(($SAW != 0) && ($AVMiss != 0)){
                $PAWM = round($SAW * $AVMiss / 100,2);
              }else{
                $PAWM = 0;
              }
              $value->PAWM = $PAWM;

              if($SAW != 0){
                $ESAW = round($SAW - $PAWM,2);
              }else{
                $ESAW = 0;
              }
              $value->ESAW = $ESAW;

              $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.date', '<=', $request->to_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              if($totalIPD){
                $IPD = $totalIPD * 800;
              }else{
                $IPD = 0;
              }
              $value->IPD = $IPD;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              $value->ES = $ES;

              if($ES != 0){
                $ESPD = round($ES / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->where('date', '<=', $request->to_date)->count('id');
              $value->leaves = $leaves;

              if($leaves > 1){
                $deductLeave = round($ESPD * $leaves,2);
              }else{
                $deductLeave = 0;
              }
              $value->deductLeave = $deductLeave;

              if($ES != 0){
                $sharingFinal = round($ES - $deductLeave,2);
              }else{
                $sharingFinal = 0;
              }
              $value->sharingFinal = $sharingFinal;

              if($sharingFinal != 0){
                $TDS = round($sharingFinal * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              if(($TDS != 0) && ($totalSharing != 0)){
                $PHC = round($totalSharing - $TDS, 0);
              }else{
                $PHC = 0;
              }
              $value->PHC = $PHC;

              if($sharingFinal != 0){
                $amountTransfer = round($sharingFinal - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              if($TAP != 0){
                $AWM = round(($TAP * 20 / 100) - $TAW,2);
              }else{
                $AWM = 0;
              }
              $value->AWM = $AWM;

              if(($AWM != 0) && ($TAP != 0)){
                $AWMP = round($AWM / $TAP * 100,2);
              }else{
                $AWMP = 0;
              }
              $value->AWMP = $AWMP;

              $patientLoss = round($AWMP + $AVMiss,2);
              $value->patientLoss = $patientLoss;

              if(($patientLoss != 0) && ($TAP != 0)){
                $noPatientLoss = round($patientLoss * $TAP / 100,2);
              }else{
                $noPatientLoss = 0;
              }
              $value->noPatientLoss = $noPatientLoss;

              if(($noPatientLoss != 0) && ($ASPV != 0)){
                $financialLoss = round($noPatientLoss * $ASPV,2);
              }else{
                $financialLoss = 0;
              }
              $value->financialLoss = $financialLoss;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              if($totalPenalty){
                $penaltyKitty = round($totalPenalty,2);
              }else{
                $penaltyKitty = 0;
              }
              $value->penaltyKitty = $penaltyKitty;

              $totalLoss = round($financialLoss + $penaltyKitty,2);
              $value->totalLoss = $totalLoss;

              $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // from date filter only
          if(!empty($request->from_date) && empty($request->to_date)){
            foreach($allData as $values) {
              $values->to_date = 0;
              $values->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$values->id)->where('status','present')->where('flag','not_ipd')->where('date', '>=', $request->from_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $values->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $values->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('therapist_account');
              $values->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('capri_account');
              $values->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','!=','')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $values->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $values->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $values->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $values->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('therapist_account');
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$values->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $values->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $values->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $values->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AV')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $values->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AW')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $values->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $values->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $values->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $values->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $values->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $values->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $values->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $values->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $values->AAVP = $AAVP;

              if($AAVP == 0){
                $AVMiss = 0;
              }else{
                if($AAV > 80){
                  $AVMiss = 0;
                }else{
                  $AVMiss = round(80 - $AAVP,2);
                } 
              }
              $values->AVMiss = $AVMiss;

              if(($SAW != 0) && ($AVMiss != 0)){
                $PAWM = round($SAW * $AVMiss / 100,2);
              }else{
                $PAWM = 0;
              }
              $values->PAWM = $PAWM;

              if($SAW != 0){
                $ESAW = round($SAW - $PAWM,2);
              }else{
                $ESAW = 0;
              }
              $values->ESAW = $ESAW;

              $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$values->id)->where('attendance.date', '>=', $request->from_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              if($totalIPD){
                $IPD = round($totalIPD * 800,2);
              }else{
                $IPD = 0;
              }
              $values->IPD = $IPD;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              $values->ES = $ES;

              if($ES != 0){
                $ESPD = round($ES / 30,2);
              }else{
                $ESPD = 0;
              }
              $values->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$values->id)->where('date', '>=', $request->from_date)->count('id');
              $values->leaves = $leaves;

              if($leaves > 1){
                $deductLeave = round($ESPD * $leaves,2);
              }else{
                $deductLeave = 0;
              }
              $values->deductLeave = $deductLeave;

              if($ES != 0){
                $sharingFinal = round($ES - $deductLeave,2);
              }else{
                $sharingFinal = 0;
              }
              $values->sharingFinal = $sharingFinal;

              if($sharingFinal != 0){
                $TDS = round($sharingFinal * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $values->TDS = $TDS;

              if(($TDS != 0) && ($totalSharing != 0)){
                $PHC = round($totalSharing - $TDS, 0);
              }else{
                $PHC = 0;
              }
              $value->PHC = $PHC;

              if($sharingFinal != 0){
                $amountTransfer = round($sharingFinal - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $values->amountTransfer = $amountTransfer;

              if($TAP != 0){
                $AWM = round(($TAP * 20 / 100) - $TAW,2);
              }else{
                $AWM = 0;
              }
              $values->AWM = $AWM;

              if(($AWM != 0) && ($TAP != 0)){
                $AWMP = round($AWM / $TAP * 100,2);
              }else{
                $AWMP = 0;
              }
              $values->AWMP = $AWMP;

              $patientLoss = round($AWMP + $AVMiss,2);
              $values->patientLoss = $patientLoss;

              if(($patientLoss != 0) && ($TAP != 0)){
              $noPatientLoss = round($patientLoss * $TAP / 100,2);
              }else{
                $noPatientLoss = 0;
              }
              $values->noPatientLoss = $noPatientLoss;

              if(($noPatientLoss != 0) && ($ASPV != 0)){
                $financialLoss = round($noPatientLoss * $ASPV,2);
              }else{
                $financialLoss = 0;
              }
              $values->financialLoss = $financialLoss;

              $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              if($totalPenalty){
                $penaltyKitty = round($totalPenalty,2);
              }else{
                $penaltyKitty = 0;
              }
              $values->penaltyKitty = $penaltyKitty;

              $totalLoss = round($financialLoss + $penaltyKitty,2);
              $values->totalLoss = $totalLoss;

              $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              $values->amountWithoutLoss = $amountWithoutLoss;
            }
          }
        }else{
          $allData = User::where('user_type','5')->where('status','active')->where('branch',Auth::User()->branch)->get();
          foreach($allData as $value) {
            $value->to_date = 0;
            $value->from_date = 0;
            $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->count('id');
            if($totalWorkingDays){
              $TWD = $totalWorkingDays;
            }else{
              $TWD = 0;
            }
            $value->TWD = $TWD;

            $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('amount');
            $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('extra_amount');
            $staffCollection = $staffCollection + $extraAmt;
            if($staffCollection != 0){
              $totalStaffCollection = round($staffCollection,2);
            }else{
              $totalStaffCollection = 0;
            }
            $value->totalStaffCollection = $totalStaffCollection;
            $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            $value->therapistCollection = $therapistCollection;
            $capriCollection = DB::table('account')->where('therapist_id',$value->id)->sum('capri_account');
            $value->capriCollection = $capriCollection;

            $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->count('id');
            if($patientVisit != 0){
              $totalPatientVisit = $patientVisit;
            }else{
              $totalPatientVisit = 0;
            }
            $value->totalPatientVisit = $totalPatientVisit;

            if(($staffCollection != 0) && ($TWD != 0)){
              $ACD = round($staffCollection / $TWD,2);
            }else{
              $ACD = 0;
            }
            $value->ACD = $ACD;

            if(($totalPatientVisit != 0) && ($TWD != 0)){
              $APTD = round($totalPatientVisit / $TWD,2);
            }else{
              $APTD = 0;
            }
            $value->APTD = $APTD;

            if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
              $CPV = round($totalStaffCollection / $totalPatientVisit,2);
            }else{
              $CPV = 0;
            }
            $value->CPV = $CPV;

            // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            // if($totalSharingAmount){
            //   $totalSharing = round($totalSharingAmount,2);
            // }else{
            //   $totalSharing = 0;
            // }
            $thDetails = DB::table('users')->where('id',$value->id)->first();
            $baseAmt = $thDetails->base_commision;
            if(($totalStaffCollection != 0) && !empty($baseAmt)){
              $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
            }else{
              $totalSharing = 0;
            }
            $value->totalSharing = $totalSharing;

            if(($totalSharing != 0) && ($totalPatientVisit != 0)){
              $ASPV = round($totalSharing / $totalPatientVisit,2);
            }else{
              $ASPV = 0;
            }
            $value->ASPV = $ASPV;
              
            // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->count('appointment.id');
            $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->count('id');
            if($totalPlannedPatient != 0){
              $TAP = $totalPlannedPatient;
            }else{
              $TAP = 0;
            }
            $value->TAP = $TAP;

            $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->count('id');
            if($totalAppointedVisitPatient != 0){
              $TAV = $totalAppointedVisitPatient;
            }else{
              $TAV = 0;
            }
            $value->TAV = $TAV;

            $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->count('id');
            if($totalApoointedWithoutVisitedPatient != 0){
              $TAW = $totalApoointedWithoutVisitedPatient;
            }else{
              $TAW = 0;
            }
            $value->TAW = $TAW;

            $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->count('appointment.id');
            if($totalAppointedTNP != 0){
              $TNP = $totalAppointedTNP;
            }else{
              $TNP = 0;
            }
            $value->TNP = $TNP;

            if(($TAV != 0) && ($TWD != 0)){
              $AAV = round($TAV / $TWD,2);
            }else{
              $AAV = 0;
            }
            $value->AAV = $AAV;

            if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
            }else{
              $AAW = 0;
            }
            $value->AAW = $AAW;

            if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
            }else{
              $ANP = 0;
            }
            $value->ANP = $ANP;

            if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
            }else{
              $SAV = 0;
            }
            $value->SAV = $SAV;

            if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
            }else{
              $SAW = 0;
            }
            $value->SAW = $SAW;

            if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
            }else{
              $SNP = 0;
            }
            $value->SNP = $SNP;

            if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
            }else{
              $AAVP = 0;
            }
            $value->AAVP = $AAVP;

            if($AAVP == 0){
              $AVMiss = 0;
            }else{
              if($AAVP > 80){
                $AVMiss = 0;
              }else{
                $AVMiss = round(80 - $AAVP,2);
              } 
            }
            $value->AVMiss = $AVMiss;

            if(($SAW != 0) && ($AVMiss != 0)){
              $PAWM = round($SAW * $AVMiss / 100,2);
            }else{
              $PAWM = 0;
            }
            $value->PAWM = $PAWM;

            if($SAW != 0){
              $ESAW = round($SAW - $PAWM,2);
            }else{
              $ESAW = 0;
            }
            $value->ESAW = $ESAW;

            $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
            if($totalIPD){
              $IPD = $totalIPD * 800;
            }else{
              $IPD = 0;
            }
            $value->IPD = $IPD;

            $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
            $value->ES = $ES;

            if($ES != 0){
              $ESPD = round($ES / 30,2);
            }else{
              $ESPD = 0;
            }
            $value->ESPD = $ESPD;

            $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->count('id');
            $value->leaves = $leaves;

            if($leaves > 1){
              $deductLeave = round($ESPD * $leaves,2);
            }else{
              $deductLeave = 0;
            }
            $value->deductLeave = $deductLeave;

            if($ES != 0){
              $sharingFinal = round($ES - $deductLeave,2);
            }else{
              $sharingFinal = 0;
            }
            $value->sharingFinal = $sharingFinal;

            if($sharingFinal != 0){
              $TDS = round($sharingFinal * 10 / 100, 0);
            }else{
              $TDS = 0;
            }
            $value->TDS = $TDS;

            if(($TDS != 0) && ($totalSharing != 0)){
              $PHC = round($totalSharing - $TDS, 0);
            }else{
              $PHC = 0;
            }
            $value->PHC = $PHC;

            if($sharingFinal != 0){
              $amountTransfer = round($sharingFinal - $TDS,2);
            }else{
              $amountTransfer = 0;
            }
            $value->amountTransfer = $amountTransfer;

            if($TAP != 0){
              $AWM = round(($TAP * 20 / 100) - $TAW,2);
            }else{
              $AWM = 0;
            }
            $value->AWM = $AWM;

            if(($AWM != 0) && ($TAP != 0)){
              $AWMP = round($AWM / $TAP * 100,2);
            }else{
              $AWMP = 0;
            }
            $value->AWMP = $AWMP;

            $patientLoss = round($AWMP + $AVMiss,2);
            $value->patientLoss = $patientLoss;

            if(($patientLoss != 0) && ($TAP != 0)){
              $noPatientLoss = round($patientLoss * $TAP / 100,2);
            }else{
              $noPatientLoss = 0;
            }
            $value->noPatientLoss = $noPatientLoss;

            if(($noPatientLoss != 0) && ($ASPV != 0)){
              $financialLoss = round($noPatientLoss * $ASPV,2);
            }else{
              $financialLoss = 0;
            }
            $value->financialLoss = $financialLoss;

            $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            if($totalPenalty){
              $penaltyKitty = round($totalPenalty,2);
            }else{
              $penaltyKitty = 0;
            }
            $value->penaltyKitty = $penaltyKitty;

            $totalLoss = round($financialLoss + $penaltyKitty,2);
            $value->totalLoss = $totalLoss;

            $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
            $value->amountWithoutLoss = $amountWithoutLoss;
          }
        }
      }
      $data['allData'] = $allData;
      
      if(Auth::user()->user_type == 'superadmin'){
        $allBranch = DB::table('location')->get();
      }else{
        $allBranch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
      }
      $data['allBranch'] = $allBranch;
      return view('report.allReport',$data);
    }catch(\Exception $e){
      return view('common.503');
    }
  }

  public function randomValue($length = 10) {
      $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
  }

  public function allStaffCollection($id,$to_date,$from_date){
    try{
      $data = array();
      $data['title'] = 'All Data';
      $data['masterclass'] = 'report';
      $data['class'] = 'tReport';
      if((!empty($to_date) || ($to_date != 0)) && (!empty($from_date) || ($from_date != 0))){
        $allData = DB::table('daily_entry')->where('therapist_id',$id)->whereBetween('app_booked_date', [$to_date, $from_date])->where('status','complete')->get();
        $allAmount = DB::table('daily_entry')->where('therapist_id',$id)->whereBetween('app_booked_date', [$to_date, $from_date])->where('status','complete')->sum('amount');
        $extraAmt = DB::table('daily_entry')->where('therapist_id',$id)->whereBetween('app_booked_date', [$to_date, $from_date])->sum('extra_amount');
        $totalAV = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AV')->whereBetween('app_booked_date', [$to_date, $from_date])->count('id');
        $totalAW = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AW')->whereBetween('app_booked_date', [$to_date, $from_date])->count('id');
      }else if((empty($to_date) || ($to_date == 0)) && (!empty($from_date) || ($from_date != 0))){
        $allData = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date', '>=', $from_date)->where('status','complete')->get();
        $allAmount = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date', '>=', $from_date)->where('status','complete')->sum('amount');
        $extraAmt = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date', '>=', $from_date)->sum('extra_amount');
        $totalAV = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AV')->where('app_booked_date', '>=', $from_date)->count('id');
        $totalAW = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AW')->where('app_booked_date', '>=', $from_date)->count('id');
      }else if((!empty($to_date) || ($to_date != 0)) && (empty($from_date) || ($from_date == 0))){
        $allData = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date', '<=', $to_date)->where('status','complete')->get();
        $allAmount = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date', '<=', $to_date)->where('status','complete')->sum('amount');
        $extraAmt = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date', '<=', $to_date)->sum('extra_amount');
        $totalAV = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AV')->where('app_booked_date', '<=', $to_date)->count('id');
        $totalAW = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AW')->where('app_booked_date', '<=', $to_date)->count('id');
      }else{
        $allData = DB::table('daily_entry')->where('therapist_id',$id)->where('status','complete')->get();
        $allAmount = DB::table('daily_entry')->where('therapist_id',$id)->where('status','!=','')->where('status','complete')->sum('amount');
        $extraAmt = DB::table('daily_entry')->where('therapist_id',$id)->where('status','!=','')->sum('extra_amount');
        // $allAmount = $allAmount + $extraAmt;
        $totalAV = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AV')->count('id');
        $totalAW = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AW')->count('id');
      }
      $data['allAmount'] = $allAmount;
      $data['extraAmt'] = $extraAmt;
      $data['totalAV'] = $totalAV;
      $data['allData'] = $allData;
      $data['totalAW'] = $totalAW;
      return view('report.allVisitEntry',$data)->with('no',1);
    }catch(\Exception $e){
      return view('common.503');
    }
  }

  public function privateHomeCareTherapistReport(Request $request){
    try{
      $data = array();
      $data['title'] = 'Therapist  Report (Private Home Care)';
      $data['masterclass'] = 'report';
      $data['class'] = 'phReport';
      if(Auth::User()->user_type == 'superadmin'){
        if(!empty($request->therapistName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date)){
          $query = User::where('user_type','5')->where('status','active');
          if (!empty($request->therapistName)) {
              $query = $query->where('name', 'LIKE', '%'.$request->therapistName.'%');
          }
          if (!empty($request->branch)){
              $query = $query->where('branch', $request->branch);
          }
          $results = $query->get();
          $allData = $results;
          //  In between to date --- from date
          if(!empty($request->to_date) && !empty($request->from_date)){
            foreach($allData as $value){
              $value->to_date = $request->to_date;
              $value->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;
              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              // dd($staffCollection,$extraAmt);
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('capri_account');
              $value->capriCollection = $capriCollection;
              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;
              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              // $totalAppointedTNP = DB::table('appointment')->
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              // if($AAVP == 0){
              //   $AVMiss = 0;
              // }else{
              //   if($AAV > 80){
              //     $AVMiss = 0;
              //   }else{
              //     $AVMiss = round(80 - $AAVP,2);
              //   }
              // }
              // $value->AVMiss = $AVMiss;

              // if(($SAW != 0) && ($AVMiss != 0)){
              //   $PAWM = round($SAW * $AVMiss / 100,2);
              // }else{
              //   $PAWM = 0;
              // }
              // $value->PAWM = $PAWM;

              // if($SAW != 0){
              //   $ESAW = round($SAW - $PAWM,2);
              // }else{
              //   $ESAW = 0;
              // }
              // $value->ESAW = $ESAW;

              // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->whereBetween('attendance.date', [$request->to_date, $request->from_date])->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              // if($totalIPD){
              //   $IPD = round($totalIPD * 800,2);
              // }else{
              //   $IPD = 0;
              // }
              // $value->IPD = $IPD;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty, 2);
              // $value->ES = $ES;

              if($totalSharing != 0){
                $ESPD = round($totalSharing / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              $value->leaves = $leaves;

              // if($leaves > 1){
              //   $deductLeave = round($ESPD * $leaves,2);
              // }else{
              //   $deductLeave = 0;
              // }
              // $value->deductLeave = $deductLeave;

              // if($ES != 0){
              //   $sharingFinal = round($ES - $deductLeave,2);
              // }else{
              //   $sharingFinal = 0;
              // }
              // $value->sharingFinal = $sharingFinal;

              if($totalSharing != 0){
                $TDS = round($totalSharing * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              // if(($TDS != 0) && ($totalSharing != 0)){
              //   $PHC = round($totalSharing - $TDS, 0);
              // }else{
              //   $PHC = 0;
              // }
              // $value->PHC = $PHC;


              if($totalSharing != 0){
                $amountTransfer = round($totalSharing - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              // if($TAP != 0){
              //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
              // }else{
              //   $AWM = 0;
              // }
              // $value->AWM = $AWM;

              // if(($AWM != 0) && ($TAP != 0)){
              //   $AWMP = round($AWM / $TAP * 100,2);
              // }else{
              //   $AWMP = 0;
              // }
              // $value->AWMP = $AWMP;

              // $patientLoss = round($AWMP + $AVMiss,2);
              // $value->patientLoss = $patientLoss;

              // if(($patientLoss != 0) && ($TAP != 0)){
              //   $noPatientLoss = round($patientLoss * $TAP / 100,2);
              // }else{
              //   $noPatientLoss = 0;
              // }
              // $value->noPatientLoss = $noPatientLoss;

              // if(($noPatientLoss != 0) && ($ASPV != 0)){
              //   $financialLoss = round($noPatientLoss * $ASPV,2);
              // }else{
              //   $financialLoss = 0;
              // }
              // $value->financialLoss = $financialLoss;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              // if($totalPenalty){
              //   $penaltyKitty = round($totalPenalty,2);
              // }else{
              //   $penaltyKitty = 0;
              // }
              // $value->penaltyKitty = $penaltyKitty;

              // $totalLoss = round($financialLoss + $penaltyKitty,2);
              // $value->totalLoss = $totalLoss;

              // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              // $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // to date filter only
          if(!empty($request->to_date) && empty($request->from_date)){
            foreach($allData as $value) {
              $value->to_date = $request->to_date;
              $value->from_date = 0;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->where('date', '<=', $request->to_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('capri_account');
              $value->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$request->to_date)->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              // if($AAVP == 0){
              //   $AVMiss = 0;
              // }else{
              //   if($AAV > 80){
              //     $AVMiss = 0;
              //   }else{
              //     $AVMiss = round(80 - $AAVP,2);
              //   } 
              // }
              // $value->AVMiss = $AVMiss;

              // if(($SAW != 0) && ($AVMiss != 0)){
              //   $PAWM = round($SAW * $AVMiss / 100,2);
              // }else{
              //   $PAWM = 0;
              // }
              // $value->PAWM = $PAWM;

              // if($SAW != 0){
              //   $ESAW = round($SAW - $PAWM,2);
              // }else{
              //   $ESAW = 0;
              // }
              // $value->ESAW = $ESAW;

              // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.date', '<=', $request->to_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              // if($totalIPD){
              //   $IPD = $totalIPD * 800;
              // }else{
              //   $IPD = 0;
              // }
              // $value->IPD = $IPD;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty, 2);
              // $value->ES = $ES;

              if($totalSharing != 0){
                $ESPD = round($totalSharing / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->where('date', '<=', $request->to_date)->count('id');
              $value->leaves = $leaves;

              // if($leaves > 1){
              //   $deductLeave = round($ESPD * $leaves,2);
              // }else{
              //   $deductLeave = 0;
              // }
              // $value->deductLeave = $deductLeave;

              // if($ES != 0){
              //   $sharingFinal = round($ES - $deductLeave,2);
              // }else{
              //   $sharingFinal = 0;
              // }
              // $value->sharingFinal = $sharingFinal;

              if($totalSharing != 0){
                $TDS = round($totalSharing * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              // if(($TDS != 0) && ($totalSharing != 0)){
              //   $PHC = round($totalSharing - $TDS, 0);
              // }else{
              //   $PHC = 0;
              // }
              // $value->PHC = $PHC;

              if($totalSharing != 0){
                $amountTransfer = round($totalSharing - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              // if($TAP != 0){
              //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
              // }else{
              //   $AWM = 0;
              // }
              // $value->AWM = $AWM;

              // if(($AWM != 0) && ($TAP != 0)){
              //   $AWMP = round($AWM / $TAP * 100,2);
              // }else{
              //   $AWMP = 0;
              // }
              // $value->AWMP = $AWMP;

              // $patientLoss = round($AWMP + $AVMiss,2);
              // $value->patientLoss = $patientLoss;

              // if(($patientLoss != 0) && ($TAP != 0)){
              //   $noPatientLoss = round($patientLoss * $TAP / 100,2);
              // }else{
              //   $noPatientLoss = 0;
              // }
              // $value->noPatientLoss = $noPatientLoss;

              // if(($noPatientLoss != 0) && ($ASPV != 0)){
              //   $financialLoss = round($noPatientLoss * $ASPV,2);
              // }else{
              //   $financialLoss = 0;
              // }
              // $value->financialLoss = $financialLoss;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              // if($totalPenalty){
              //   $penaltyKitty = round($totalPenalty,2);
              // }else{
              //   $penaltyKitty = 0;
              // }
              // $value->penaltyKitty = $penaltyKitty;

              // $totalLoss = round($financialLoss + $penaltyKitty,2);
              // $value->totalLoss = $totalLoss;

              // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              // $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // from date filter only
          if(!empty($request->from_date) && empty($request->to_date)){
            foreach($allData as $values) {
              $values->to_date = 0;
              $values->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$values->id)->where('status','present')->where('flag','not_ipd')->where('date', '>=', $request->from_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $values->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $values->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('therapist_account');
              $values->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('capri_account');
              $values->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','!=','')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $values->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $values->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $values->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $values->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('therapist_account');
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$values->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $values->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $values->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $values->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AV')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $values->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AW')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $values->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $values->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $values->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $values->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $values->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $values->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $values->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $values->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $values->AAVP = $AAVP;

              // if($AAVP == 0){
              //   $AVMiss = 0;
              // }else{
              //   if($AAV > 80){
              //     $AVMiss = 0;
              //   }else{
              //     $AVMiss = round(80 - $AAVP,2);
              //   } 
              // }
              // $values->AVMiss = $AVMiss;

              // if(($SAW != 0) && ($AVMiss != 0)){
              //   $PAWM = round($SAW * $AVMiss / 100,2);
              // }else{
              //   $PAWM = 0;
              // }
              // $values->PAWM = $PAWM;

              // if($SAW != 0){
              //   $ESAW = round($SAW - $PAWM,2);
              // }else{
              //   $ESAW = 0;
              // }
              // $values->ESAW = $ESAW;

              // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$values->id)->where('attendance.date', '>=', $request->from_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              // if($totalIPD){
              //   $IPD = round($totalIPD * 800,2);
              // }else{
              //   $IPD = 0;
              // }
              // $values->IPD = $IPD;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              // $values->ES = $ES;

              if($totalSharing != 0){
                $ESPD = round($totalSharing / 30,2);
              }else{
                $ESPD = 0;
              }
              $values->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$values->id)->where('date', '>=', $request->from_date)->count('id');
              $values->leaves = $leaves;

              // if($leaves > 1){
              //   $deductLeave = round($ESPD * $leaves,2);
              // }else{
              //   $deductLeave = 0;
              // }
              // $values->deductLeave = $deductLeave;

              // if($ES != 0){
              //   $sharingFinal = round($ES - $deductLeave,2);
              // }else{
              //   $sharingFinal = 0;
              // }
              // $values->sharingFinal = $sharingFinal;

              if($totalSharing != 0){
                $TDS = round($totalSharing * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $values->TDS = $TDS;

              // if(($TDS != 0) && ($totalSharing != 0)){
              //   $PHC = round($totalSharing - $TDS, 0);
              // }else{
              //   $PHC = 0;
              // }
              // $value->PHC = $PHC;

              if($totalSharing != 0){
                $amountTransfer = round($totalSharing - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $values->amountTransfer = $amountTransfer;

              // if($TAP != 0){
              //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
              // }else{
              //   $AWM = 0;
              // }
              // $values->AWM = $AWM;

              // if(($AWM != 0) && ($TAP != 0)){
              //   $AWMP = round($AWM / $TAP * 100,2);
              // }else{
              //   $AWMP = 0;
              // }
              // $values->AWMP = $AWMP;

              // $patientLoss = round($AWMP + $AVMiss,2);
              // $values->patientLoss = $patientLoss;

              // if(($patientLoss != 0) && ($TAP != 0)){
              // $noPatientLoss = round($patientLoss * $TAP / 100,2);
              // }else{
              //   $noPatientLoss = 0;
              // }
              // $values->noPatientLoss = $noPatientLoss;

              // if(($noPatientLoss != 0) && ($ASPV != 0)){
              //   $financialLoss = round($noPatientLoss * $ASPV,2);
              // }else{
              //   $financialLoss = 0;
              // }
              // $values->financialLoss = $financialLoss;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              // if($totalPenalty){
              //   $penaltyKitty = round($totalPenalty,2);
              // }else{
              //   $penaltyKitty = 0;
              // }
              // $values->penaltyKitty = $penaltyKitty;

              // $totalLoss = round($financialLoss + $penaltyKitty,2);
              // $values->totalLoss = $totalLoss;

              // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              // $values->amountWithoutLoss = $amountWithoutLoss;
            }
          }
        }else{
          $allData = User::where('user_type','5')->where('status','active')->get();
          // dd($allData);
          foreach($allData as $value) {
            $value->to_date = 0;
            $value->from_date = 0;
            $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->count('id');
            if($totalWorkingDays){
              $TWD = $totalWorkingDays;
            }else{
              $TWD = 0;
            }
            $value->TWD = $TWD;

            $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('amount');
            $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('extra_amount');
            $staffCollection = $staffCollection + $extraAmt;
            if($staffCollection != 0){
              $totalStaffCollection = round($staffCollection,2);
            }else{
              $totalStaffCollection = 0;
            }
            $value->totalStaffCollection = $totalStaffCollection;
            $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            $value->therapistCollection = $therapistCollection;
            $capriCollection = DB::table('account')->where('therapist_id',$value->id)->sum('capri_account');
            $value->capriCollection = $capriCollection;

            $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->count('id');
            if($patientVisit != 0){
              $totalPatientVisit = $patientVisit;
            }else{
              $totalPatientVisit = 0;
            }
            $value->totalPatientVisit = $totalPatientVisit;

            if(($staffCollection != 0) && ($TWD != 0)){
              $ACD = round($staffCollection / $TWD,2);
            }else{
              $ACD = 0;
            }
            $value->ACD = $ACD;

            if(($totalPatientVisit != 0) && ($TWD != 0)){
              $APTD = round($totalPatientVisit / $TWD,2);
            }else{
              $APTD = 0;
            }
            $value->APTD = $APTD;

            if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
              $CPV = round($totalStaffCollection / $totalPatientVisit,2);
            }else{
              $CPV = 0;
            }
            $value->CPV = $CPV;

            // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            // if($totalSharingAmount){
            //   $totalSharing = round($totalSharingAmount,2);
            // }else{
            //   $totalSharing = 0;
            // }
            $thDetails = DB::table('users')->where('id',$value->id)->first();
            $baseAmt = $thDetails->base_commision;
            if(($totalStaffCollection != 0) && !empty($baseAmt)){
              $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
            }else{
              $totalSharing = 0;
            }
            $value->totalSharing = $totalSharing;

            if(($totalSharing != 0) && ($totalPatientVisit != 0)){
              $ASPV = round($totalSharing / $totalPatientVisit,2);
            }else{
              $ASPV = 0;
            }
            $value->ASPV = $ASPV;
              
            // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->count('appointment.id');
            $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->count('id');
            if($totalPlannedPatient != 0){
              $TAP = $totalPlannedPatient;
            }else{
              $TAP = 0;
            }
            $value->TAP = $TAP;

            $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->count('id');
            if($totalAppointedVisitPatient != 0){
              $TAV = $totalAppointedVisitPatient;
            }else{
              $TAV = 0;
            }
            $value->TAV = $TAV;

            $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->count('id');
            if($totalApoointedWithoutVisitedPatient != 0){
              $TAW = $totalApoointedWithoutVisitedPatient;
            }else{
              $TAW = 0;
            }
            $value->TAW = $TAW;

            // $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->count('appointment.id');
            $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->groupBy('appointment.user_id')->where('users.therapist_id',$value->id)->distinct('appointment.user_id')->count('appointment.id');
            if($totalAppointedTNP != 0){
              $TNP = $totalAppointedTNP;
            }else{
              $TNP = 0;
            }
            $value->TNP = $TNP;

            if(($TAV != 0) && ($TWD != 0)){
              $AAV = round($TAV / $TWD,2);
            }else{
              $AAV = 0;
            }
            $value->AAV = $AAV;

            if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
            }else{
              $AAW = 0;
            }
            $value->AAW = $AAW;

            if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
            }else{
              $ANP = 0;
            }
            $value->ANP = $ANP;

            if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
            }else{
              $SAV = 0;
            }
            $value->SAV = $SAV;

            if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
            }else{
              $SAW = 0;
            }
            $value->SAW = $SAW;

            if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
            }else{
              $SNP = 0;
            }
            $value->SNP = $SNP;

            if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
            }else{
              $AAVP = 0;
            }
            $value->AAVP = $AAVP;

            // if($AAVP == 0){
            //   $AVMiss = 0;
            // }else{
            //   if($AAVP > 80){
            //     $AVMiss = 0;
            //   }else{
            //     $AVMiss = round(80 - $AAVP,2);
            //   } 
            // }
            // $value->AVMiss = $AVMiss;

            // if(($SAW != 0) && ($AVMiss != 0)){
            //   $PAWM = round($SAW * $AVMiss / 100,2);
            // }else{
            //   $PAWM = 0;
            // }
            // $value->PAWM = $PAWM;

            // if($SAW != 0){
            //   $ESAW = round($SAW - $PAWM,2);
            // }else{
            //   $ESAW = 0;
            // }
            // $value->ESAW = $ESAW;

            // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
            // if($totalIPD){
            //   $IPD = $totalIPD * 800;
            // }else{
            //   $IPD = 0;
            // }
            // $value->IPD = $IPD;

            // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
            // $value->ES = $ES;

            if($totalSharing != 0){
              $ESPD = round($totalSharing / 30,2);
            }else{
              $ESPD = 0;
            }
            $value->ESPD = $ESPD;

            $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->count('id');
            $value->leaves = $leaves;

            // if($leaves > 1){
            //   $deductLeave = round($ESPD * $leaves,2);
            // }else{
            //   $deductLeave = 0;
            // }
            // $value->deductLeave = $deductLeave;

            // if($ES != 0){
            //   $sharingFinal = round($ES - $deductLeave,2);
            // }else{
            //   $sharingFinal = 0;
            // }
            // $value->sharingFinal = $sharingFinal;

            if($totalSharing != 0){
              $TDS = round($totalSharing * 10 / 100, 0);
            }else{
              $TDS = 0;
            }
            $value->TDS = $TDS;

            // if(($TDS != 0) && ($totalSharing != 0)){
            //   $PHC = round($totalSharing - $TDS, 0);
            // }else{
            //   $PHC = 0;
            // }
            // $value->PHC = $PHC;

            if($totalSharing != 0){
              $amountTransfer = round($totalSharing - $TDS,2);
            }else{
              $amountTransfer = 0;
            }
            $value->amountTransfer = $amountTransfer;

            // if($TAP != 0){
            //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
            // }else{
            //   $AWM = 0;
            // }
            // $value->AWM = $AWM;

            // if(($AWM != 0) && ($TAP != 0)){
            //   $AWMP = round($AWM / $TAP * 100,2);
            // }else{
            //   $AWMP = 0;
            // }
            // $value->AWMP = $AWMP;

            // $patientLoss = round($AWMP + $AVMiss,2);
            // $value->patientLoss = $patientLoss;

            // if(($patientLoss != 0) && ($TAP != 0)){
            //   $noPatientLoss = round($patientLoss * $TAP / 100,2);
            // }else{
            //   $noPatientLoss = 0;
            // }
            // $value->noPatientLoss = $noPatientLoss;

            // if(($noPatientLoss != 0) && ($ASPV != 0)){
            //   $financialLoss = round($noPatientLoss * $ASPV,2);
            // }else{
            //   $financialLoss = 0;
            // }
            // $value->financialLoss = $financialLoss;

            // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            // if($totalPenalty){
            //   $penaltyKitty = round($totalPenalty,2);
            // }else{
            //   $penaltyKitty = 0;
            // }
            // $value->penaltyKitty = $penaltyKitty;

            // $totalLoss = round($financialLoss + $penaltyKitty,2);
            // $value->totalLoss = $totalLoss;

            // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
            // $value->amountWithoutLoss = $amountWithoutLoss;

          }
        }
      }else{
        if(!empty($request->therapistName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date)){
          $query = User::where('user_type','5')->where('status','active')->where('branch',Auth::User()->branch);
          if (!empty($request->therapistName)) {
              $query = $query->where('name', 'LIKE', '%'.$request->therapistName.'%');
          }
          if (!empty($request->branch)){
              $query = $query->where('branch', $request->branch);
          }
          $results = $query->get();
          $allData = $results;
          //  In between to date --- from date
          if(!empty($request->to_date) && !empty($request->from_date)){
            foreach($allData as $value){
              $value->to_date = $request->to_date;
              $value->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->whereBetween('payment_date', [$request->to_date, $request->from_date])->sum('capri_account');
              $value->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;
              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->whereBetween('appointment.appointment_date', [$request->to_date, $request->from_date])->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              // if($AAVP == 0){
              //   $AVMiss = 0;
              // }else{
              //   if($AAV > 80){
              //     $AVMiss = 0;
              //   }else{
              //     $AVMiss = round(80 - $AAVP,2);
              //   }
              // }
              // $value->AVMiss = $AVMiss;

              // if(($SAW != 0) && ($AVMiss != 0)){
              //   $PAWM = round($SAW * $AVMiss / 100,2);
              // }else{
              //   $PAWM = 0;
              // }
              // $value->PAWM = $PAWM;

              // if($SAW != 0){
              //   $ESAW = round($SAW - $PAWM,2);
              // }else{
              //   $ESAW = 0;
              // }
              // $value->ESAW = $ESAW;

              // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->whereBetween('attendance.date', [$request->to_date, $request->from_date])->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              // if($totalIPD){
              //   $IPD = round($totalIPD * 800,2);
              // }else{
              //   $IPD = 0;
              // }
              // $value->IPD = $IPD;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              // $value->ES = $ES;

              if($totalSharing != 0){
                $ESPD = round($totalSharing / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->whereBetween('date', [$request->to_date, $request->from_date])->count('id');
              $value->leaves = $leaves;

              // if($leaves > 1){
              //   $deductLeave = round($ESPD * $leaves,2);
              // }else{
              //   $deductLeave = 0;
              // }
              // $value->deductLeave = $deductLeave;

              // if($ES != 0){
              //   $sharingFinal = round($ES - $deductLeave,2);
              // }else{
              //   $sharingFinal = 0;
              // }
              // $value->sharingFinal = $sharingFinal;

              if($totalSharing != 0){
                $TDS = round($totalSharing * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              // if(($TDS != 0) && ($totalSharing != 0)){
              //   $PHC = round($totalSharing - $TDS, 0);
              // }else{
              //   $PHC = 0;
              // }
              // $value->PHC = $PHC;

              if($totalSharing != 0){
                $amountTransfer = round($totalSharing - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              // if($TAP != 0){
              //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
              // }else{
              //   $AWM = 0;
              // }
              // $value->AWM = $AWM;

              // if(($AWM != 0) && ($TAP != 0)){
              //   $AWMP = round($AWM / $TAP * 100,2);
              // }else{
              //   $AWMP = 0;
              // }
              // $value->AWMP = $AWMP;

              // $patientLoss = round($AWMP + $AVMiss,2);
              // $value->patientLoss = $patientLoss;

              // if(($patientLoss != 0) && ($TAP != 0)){
              //   $noPatientLoss = round($patientLoss * $TAP / 100,2);
              // }else{
              //   $noPatientLoss = 0;
              // }
              // $value->noPatientLoss = $noPatientLoss;

              // if(($noPatientLoss != 0) && ($ASPV != 0)){
              //   $financialLoss = round($noPatientLoss * $ASPV,2);
              // }else{
              //   $financialLoss = 0;
              // }
              // $value->financialLoss = $financialLoss;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date])->sum('amount');
              // if($totalPenalty){
              //   $penaltyKitty = round($totalPenalty,2);
              // }else{
              //   $penaltyKitty = 0;
              // }
              // $value->penaltyKitty = $penaltyKitty;

              // $totalLoss = round($financialLoss + $penaltyKitty,2);
              // $value->totalLoss = $totalLoss;

              // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              // $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // to date filter only
          if(!empty($request->to_date) && empty($request->from_date)){
            foreach($allData as $value) {
              $value->to_date = $request->to_date;
              $value->from_date = 0;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->where('date', '<=', $request->to_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $value->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $value->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('therapist_account');
              $value->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$value->id)->where('payment_date', '<=', $request->to_date)->sum('capri_account');
              $value->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $value->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $value->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $value->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $value->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=',$request->to_date)->sum('therapist_account');
              // // dd($totalSharingAmount);
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$value->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $value->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $value->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $value->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $value->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $value->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '<=', $request->to_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $value->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $value->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $value->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $value->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $value->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $value->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $value->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $value->AAVP = $AAVP;

              // if($AAVP == 0){
              //   $AVMiss = 0;
              // }else{
              //   if($AAV > 80){
              //     $AVMiss = 0;
              //   }else{
              //     $AVMiss = round(80 - $AAVP,2);
              //   } 
              // }
              // $value->AVMiss = $AVMiss;

              // if(($SAW != 0) && ($AVMiss != 0)){
              //   $PAWM = round($SAW * $AVMiss / 100,2);
              // }else{
              //   $PAWM = 0;
              // }
              // $value->PAWM = $PAWM;

              // if($SAW != 0){
              //   $ESAW = round($SAW - $PAWM,2);
              // }else{
              //   $ESAW = 0;
              // }
              // $value->ESAW = $ESAW;

              // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.date', '<=', $request->to_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              // if($totalIPD){
              //   $IPD = $totalIPD * 800;
              // }else{
              //   $IPD = 0;
              // }
              // $value->IPD = $IPD;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              // $value->ES = $ES;

              if($totalSharing != 0){
                $ESPD = round($totalSharing / 30,2);
              }else{
                $ESPD = 0;
              }
              $value->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->where('date', '<=', $request->to_date)->count('id');
              $value->leaves = $leaves;

              // if($leaves > 1){
              //   $deductLeave = round($ESPD * $leaves,2);
              // }else{
              //   $deductLeave = 0;
              // }
              // $value->deductLeave = $deductLeave;

              // if($ES != 0){
              //   $sharingFinal = round($ES - $deductLeave,2);
              // }else{
              //   $sharingFinal = 0;
              // }
              // $value->sharingFinal = $sharingFinal;

              if($totalSharing != 0){
                $TDS = round($totalSharing * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $value->TDS = $TDS;

              // if(($TDS != 0) && ($totalSharing != 0)){
              //   $PHC = round($totalSharing - $TDS, 0);
              // }else{
              //   $PHC = 0;
              // }
              // $value->PHC = $PHC;

              if($totalSharing != 0){
                $amountTransfer = round($totalSharing - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $value->amountTransfer = $amountTransfer;

              // if($TAP != 0){
              //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
              // }else{
              //   $AWM = 0;
              // }
              // $value->AWM = $AWM;

              // if(($AWM != 0) && ($TAP != 0)){
              //   $AWMP = round($AWM / $TAP * 100,2);
              // }else{
              //   $AWMP = 0;
              // }
              // $value->AWMP = $AWMP;

              // $patientLoss = round($AWMP + $AVMiss,2);
              // $value->patientLoss = $patientLoss;

              // if(($patientLoss != 0) && ($TAP != 0)){
              //   $noPatientLoss = round($patientLoss * $TAP / 100,2);
              // }else{
              //   $noPatientLoss = 0;
              // }
              // $value->noPatientLoss = $noPatientLoss;

              // if(($noPatientLoss != 0) && ($ASPV != 0)){
              //   $financialLoss = round($noPatientLoss * $ASPV,2);
              // }else{
              //   $financialLoss = 0;
              // }
              // $value->financialLoss = $financialLoss;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date)->sum('amount');
              // if($totalPenalty){
              //   $penaltyKitty = round($totalPenalty,2);
              // }else{
              //   $penaltyKitty = 0;
              // }
              // $value->penaltyKitty = $penaltyKitty;

              // $totalLoss = round($financialLoss + $penaltyKitty,2);
              // $value->totalLoss = $totalLoss;

              // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              // $value->amountWithoutLoss = $amountWithoutLoss;
            }
          }
          // from date filter only
          if(!empty($request->from_date) && empty($request->to_date)){
            foreach($allData as $values) {
              $values->to_date = 0;
              $values->from_date = $request->from_date;
              $totalWorkingDays = DB::table('attendance')->where('therapist_id',$values->id)->where('status','present')->where('flag','not_ipd')->where('date', '>=', $request->from_date)->count('id');
              if($totalWorkingDays){
                $TWD = $totalWorkingDays;
              }else{
                $TWD = 0;
              }
              $values->TWD = $TWD;

              $staffCollection = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('amount');
              $extraAmt = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '>=', $request->from_date)->where('status','complete')->sum('extra_amount');
              $staffCollection = $staffCollection + $extraAmt;
              if($staffCollection != 0){
                $totalStaffCollection = round($staffCollection,2);
              }else{
                $totalStaffCollection = 0;
              }
              $values->totalStaffCollection = $totalStaffCollection;
              $therapistCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('therapist_account');
              $values->therapistCollection = $therapistCollection;
              $capriCollection = DB::table('account')->where('therapist_id',$values->id)->where('payment_date', '>=', $request->from_date)->sum('capri_account');
              $values->capriCollection = $capriCollection;

              $patientVisit = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','!=','')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($patientVisit != 0){
                $totalPatientVisit = $patientVisit;
              }else{
                $totalPatientVisit = 0;
              }
              $values->totalPatientVisit = $totalPatientVisit;

              if(($staffCollection != 0) && ($TWD != 0)){
                $ACD = round($staffCollection / $TWD,2);
              }else{
                $ACD = 0;
              }
              $values->ACD = $ACD;

              if(($totalPatientVisit != 0) && ($TWD != 0)){
                $APTD = round($totalPatientVisit / $TWD,2);
              }else{
                $APTD = 0;
              }
              $values->APTD = $APTD;

              if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
                $CPV = round($totalStaffCollection / $totalPatientVisit,2);
              }else{
                $CPV = 0;
              }
              $values->CPV = $CPV;

              // $totalSharingAmount = DB::table('account')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('therapist_account');
              // if($totalSharingAmount){
              //   $totalSharing = round($totalSharingAmount,2);
              // }else{
              //   $totalSharing = 0;
              // }
              $thDetails = DB::table('users')->where('id',$values->id)->first();
              $baseAmt = $thDetails->base_commision;
              if(($totalStaffCollection != 0) && !empty($baseAmt)){
                $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
              }else{
                $totalSharing = 0;
              }
              $values->totalSharing = $totalSharing;

              if(($totalSharing != 0) && ($totalPatientVisit != 0)){
                $ASPV = round($totalSharing / $totalPatientVisit,2);
              }else{
                $ASPV = 0;
              }
              $values->ASPV = $ASPV;

              // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('app_booked_date', '<=', $request->to_date)->count('id');
              if($totalPlannedPatient != 0){
                $TAP = $totalPlannedPatient;
              }else{
                $TAP = 0;
              }
              $values->TAP = $TAP;

              $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AV')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalAppointedVisitPatient != 0){
                $TAV = $totalAppointedVisitPatient;
              }else{
                $TAV = 0;
              }
              $values->TAV = $TAV;

              $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$values->id)->where('visit_type','AW')->where('app_booked_date', '>=', $request->from_date)->count('id');
              if($totalApoointedWithoutVisitedPatient != 0){
                $TAW = $totalApoointedWithoutVisitedPatient;
              }else{
                $TAW = 0;
              }
              $values->TAW = $TAW;

              $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$values->id)->where('appointment.patient_type','new')->where('appointment.appointment_date', '>=', $request->from_date)->count('appointment.id');
              if($totalAppointedTNP != 0){
                $TNP = $totalAppointedTNP;
              }else{
                $TNP = 0;
              }
              $values->TNP = $TNP;

              if(($TAV != 0) && ($TWD != 0)){
                $AAV = round($TAV / $TWD,2);
              }else{
                $AAV = 0;
              }
              $values->AAV = $AAV;

              if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
              }else{
                $AAW = 0;
              }
              $values->AAW = $AAW;

              if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
              }else{
                $ANP = 0;
              }
              $values->ANP = $ANP;

              if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
              }else{
                $SAV = 0;
              }
              $values->SAV = $SAV;

              if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
              }else{
                $SAW = 0;
              }
              $values->SAW = $SAW;

              if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
              }else{
                $SNP = 0;
              }
              $values->SNP = $SNP;

              if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
              }else{
                $AAVP = 0;
              }
              $values->AAVP = $AAVP;

              // if($AAVP == 0){
              //   $AVMiss = 0;
              // }else{
              //   if($AAV > 80){
              //     $AVMiss = 0;
              //   }else{
              //     $AVMiss = round(80 - $AAVP,2);
              //   } 
              // }
              // $values->AVMiss = $AVMiss;

              // if(($SAW != 0) && ($AVMiss != 0)){
              //   $PAWM = round($SAW * $AVMiss / 100,2);
              // }else{
              //   $PAWM = 0;
              // }
              // $values->PAWM = $PAWM;

              // if($SAW != 0){
              //   $ESAW = round($SAW - $PAWM,2);
              // }else{
              //   $ESAW = 0;
              // }
              // $values->ESAW = $ESAW;

              // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$values->id)->where('attendance.date', '>=', $request->from_date)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
              // if($totalIPD){
              //   $IPD = round($totalIPD * 800,2);
              // }else{
              //   $IPD = 0;
              // }
              // $values->IPD = $IPD;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
              // $values->ES = $ES;

              if($totalSharing != 0){
                $ESPD = round($totalSharing / 30,2);
              }else{
                $ESPD = 0;
              }
              $values->ESPD = $ESPD;

              $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$values->id)->where('date', '>=', $request->from_date)->count('id');
              $values->leaves = $leaves;

              // if($leaves > 1){
              //   $deductLeave = round($ESPD * $leaves,2);
              // }else{
              //   $deductLeave = 0;
              // }
              // $values->deductLeave = $deductLeave;

              // if($ES != 0){
              //   $sharingFinal = round($ES - $deductLeave,2);
              // }else{
              //   $sharingFinal = 0;
              // }
              // $values->sharingFinal = $sharingFinal;

              if($totalSharing != 0){
                $TDS = round($totalSharing * 10 / 100, 0);
              }else{
                $TDS = 0;
              }
              $values->TDS = $TDS;

              // if(($TDS != 0) && ($totalSharing != 0)){
              //   $PHC = round($totalSharing - $TDS, 0);
              // }else{
              //   $PHC = 0;
              // }
              // $value->PHC = $PHC;

              if($totalSharing != 0){
                $amountTransfer = round($totalSharing - $TDS,2);
              }else{
                $amountTransfer = 0;
              }
              $values->amountTransfer = $amountTransfer;

              // if($TAP != 0){
              //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
              // }else{
              //   $AWM = 0;
              // }
              // $values->AWM = $AWM;

              // if(($AWM != 0) && ($TAP != 0)){
              //   $AWMP = round($AWM / $TAP * 100,2);
              // }else{
              //   $AWMP = 0;
              // }
              // $values->AWMP = $AWMP;

              // $patientLoss = round($AWMP + $AVMiss,2);
              // $values->patientLoss = $patientLoss;

              // if(($patientLoss != 0) && ($TAP != 0)){
              // $noPatientLoss = round($patientLoss * $TAP / 100,2);
              // }else{
              //   $noPatientLoss = 0;
              // }
              // $values->noPatientLoss = $noPatientLoss;

              // if(($noPatientLoss != 0) && ($ASPV != 0)){
              //   $financialLoss = round($noPatientLoss * $ASPV,2);
              // }else{
              //   $financialLoss = 0;
              // }
              // $values->financialLoss = $financialLoss;

              // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$values->id)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->from_date)->sum('amount');
              // if($totalPenalty){
              //   $penaltyKitty = round($totalPenalty,2);
              // }else{
              //   $penaltyKitty = 0;
              // }
              // $values->penaltyKitty = $penaltyKitty;

              // $totalLoss = round($financialLoss + $penaltyKitty,2);
              // $values->totalLoss = $totalLoss;

              // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
              // $values->amountWithoutLoss = $amountWithoutLoss;
            }
          }
        }else{
          $allData = User::where('user_type','5')->where('status','active')->where('branch',Auth::User()->branch)->get();
          foreach($allData as $value) {
            $value->to_date = 0;
            $value->from_date = 0;
            $totalWorkingDays = DB::table('attendance')->where('therapist_id',$value->id)->where('status','present')->where('flag','not_ipd')->count('id');
            if($totalWorkingDays){
              $TWD = $totalWorkingDays;
            }else{
              $TWD = 0;
            }
            $value->TWD = $TWD;

            $staffCollection = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('amount');
            $extraAmt = DB::table('daily_entry')->where('therapist_id',$value->id)->where('status','complete')->sum('extra_amount');
            $staffCollection = $staffCollection + $extraAmt;
            if($staffCollection != 0){
              $totalStaffCollection = round($staffCollection,2);
            }else{
              $totalStaffCollection = 0;
            }
            $value->totalStaffCollection = $totalStaffCollection;
            $therapistCollection = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            $value->therapistCollection = $therapistCollection;
            $capriCollection = DB::table('account')->where('therapist_id',$value->id)->sum('capri_account');
            $value->capriCollection = $capriCollection;

            $patientVisit = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','!=','')->count('id');
            if($patientVisit != 0){
              $totalPatientVisit = $patientVisit;
            }else{
              $totalPatientVisit = 0;
            }
            $value->totalPatientVisit = $totalPatientVisit;

            if(($staffCollection != 0) && ($TWD != 0)){
              $ACD = round($staffCollection / $TWD,2);
            }else{
              $ACD = 0;
            }
            $value->ACD = $ACD;

            if(($totalPatientVisit != 0) && ($TWD != 0)){
              $APTD = round($totalPatientVisit / $TWD,2);
            }else{
              $APTD = 0;
            }
            $value->APTD = $APTD;

            if(($totalStaffCollection != 0) && ($totalPatientVisit != 0)){
              $CPV = round($totalStaffCollection / $totalPatientVisit,2);
            }else{
              $CPV = 0;
            }
            $value->CPV = $CPV;

            // $totalSharingAmount = DB::table('account')->where('therapist_id',$value->id)->sum('therapist_account');
            // if($totalSharingAmount){
            //   $totalSharing = round($totalSharingAmount,2);
            // }else{
            //   $totalSharing = 0;
            // }
            $thDetails = DB::table('users')->where('id',$value->id)->first();
            $baseAmt = $thDetails->base_commision;
            if(($totalStaffCollection != 0) && !empty($baseAmt)){
              $totalSharing = ($totalStaffCollection * $baseAmt) / 100;
            }else{
              $totalSharing = 0;
            }
            $value->totalSharing = $totalSharing;

            if(($totalSharing != 0) && ($totalPatientVisit != 0)){
              $ASPV = round($totalSharing / $totalPatientVisit,2);
            }else{
              $ASPV = 0;
            }
            $value->ASPV = $ASPV;
              
            // $totalPlannedPatient = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->count('appointment.id');
            $totalPlannedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->count('id');
            if($totalPlannedPatient != 0){
              $TAP = $totalPlannedPatient;
            }else{
              $TAP = 0;
            }
            $value->TAP = $TAP;

            $totalAppointedVisitPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AV')->count('id');
            if($totalAppointedVisitPatient != 0){
              $TAV = $totalAppointedVisitPatient;
            }else{
              $TAV = 0;
            }
            $value->TAV = $TAV;

            $totalApoointedWithoutVisitedPatient = DB::table('daily_entry')->where('therapist_id',$value->id)->where('visit_type','AW')->count('id');
            if($totalApoointedWithoutVisitedPatient != 0){
              $TAW = $totalApoointedWithoutVisitedPatient;
            }else{
              $TAW = 0;
            }
            $value->TAW = $TAW;

            $totalAppointedTNP = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$value->id)->where('appointment.patient_type','new')->count('appointment.id');
            if($totalAppointedTNP != 0){
              $TNP = $totalAppointedTNP;
            }else{
              $TNP = 0;
            }
            $value->TNP = $TNP;

            if(($TAV != 0) && ($TWD != 0)){
              $AAV = round($TAV / $TWD,2);
            }else{
              $AAV = 0;
            }
            $value->AAV = $AAV;

            if(($TAW != 0) && ($TWD != 0)){
              $AAW = round($TAW / $TWD,2);
            }else{
              $AAW = 0;
            }
            $value->AAW = $AAW;

            if(($TNP != 0) && ($TWD != 0)){
              $ANP = round($TNP / $TWD,2);
            }else{
              $ANP = 0;
            }
            $value->ANP = $ANP;

            if(($TAV != 0) && ($ASPV != 0)){
                $SAV = round($TAV * $ASPV,2);
            }else{
              $SAV = 0;
            }
            $value->SAV = $SAV;

            if(($TAW != 0) && ($ASPV != 0)){
              $SAW = round($TAW * $ASPV,2);
            }else{
              $SAW = 0;
            }
            $value->SAW = $SAW;

            if(($ASPV != 0) && ($TNP != 0)){
                $SNP = round($ASPV * $TNP,2);
            }else{
              $SNP = 0;
            }
            $value->SNP = $SNP;

            if(($TAV != 0) && ($TAP != 0)){
              $AAVP = round($TAV / $TAP * 100,2);
            }else{
              $AAVP = 0;
            }
            $value->AAVP = $AAVP;

            // if($AAVP == 0){
            //   $AVMiss = 0;
            // }else{
            //   if($AAVP > 80){
            //     $AVMiss = 0;
            //   }else{
            //     $AVMiss = round(80 - $AAVP,2);
            //   } 
            // }
            // $value->AVMiss = $AVMiss;

            // if(($SAW != 0) && ($AVMiss != 0)){
            //   $PAWM = round($SAW * $AVMiss / 100,2);
            // }else{
            //   $PAWM = 0;
            // }
            // $value->PAWM = $PAWM;

            // if($SAW != 0){
            //   $ESAW = round($SAW - $PAWM,2);
            // }else{
            //   $ESAW = 0;
            // }
            // $value->ESAW = $ESAW;

            // $totalIPD = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$value->id)->where('attendance.flag','ipd')->where('attendance.status','present')->count('attendance.id');
            // if($totalIPD){
            //   $IPD = $totalIPD * 800;
            // }else{
            //   $IPD = 0;
            // }
            // $value->IPD = $IPD;

            // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            // $ES = round($SAV + $SNP + $ESAW + $IPD - $totalPenalty,2);
            // $value->ES = $ES;

            if($totalSharing != 0){
              $ESPD = round($totalSharing / 30,2);
            }else{
              $ESPD = 0;
            }
            $value->ESPD = $ESPD;

            $leaves = DB::table('attendance')->where('status','apsent')->where('therapist_id',$value->id)->count('id');
            $value->leaves = $leaves;

            // if($leaves > 1){
            //   $deductLeave = round($ESPD * $leaves,2);
            // }else{
            //   $deductLeave = 0;
            // }
            // $value->deductLeave = $deductLeave;

            // if($ES != 0){
            //   $sharingFinal = round($ES - $deductLeave,2);
            // }else{
            //   $sharingFinal = 0;
            // }
            // $value->sharingFinal = $sharingFinal;

            if($totalSharing != 0){
              $TDS = round($totalSharing * 10 / 100, 0);
            }else{
              $TDS = 0;
            }
            $value->TDS = $TDS;

            // if(($TDS != 0) && ($totalSharing != 0)){
            //   $PHC = round($totalSharing - $TDS, 0);
            // }else{
            //   $PHC = 0;
            // }
            // $value->PHC = $PHC;

            if($totalSharing != 0){
              $amountTransfer = round($totalSharing - $TDS,2);
            }else{
              $amountTransfer = 0;
            }
            $value->amountTransfer = $amountTransfer;

            // if($TAP != 0){
            //   $AWM = round(($TAP * 20 / 100) - $TAW,2);
            // }else{
            //   $AWM = 0;
            // }
            // $value->AWM = $AWM;

            // if(($AWM != 0) && ($TAP != 0)){
            //   $AWMP = round($AWM / $TAP * 100,2);
            // }else{
            //   $AWMP = 0;
            // }
            // $value->AWMP = $AWMP;

            // $patientLoss = round($AWMP + $AVMiss,2);
            // $value->patientLoss = $patientLoss;

            // if(($patientLoss != 0) && ($TAP != 0)){
            //   $noPatientLoss = round($patientLoss * $TAP / 100,2);
            // }else{
            //   $noPatientLoss = 0;
            // }
            // $value->noPatientLoss = $noPatientLoss;

            // if(($noPatientLoss != 0) && ($ASPV != 0)){
            //   $financialLoss = round($noPatientLoss * $ASPV,2);
            // }else{
            //   $financialLoss = 0;
            // }
            // $value->financialLoss = $financialLoss;

            // $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$value->id)->sum('amount');
            // if($totalPenalty){
            //   $penaltyKitty = round($totalPenalty,2);
            // }else{
            //   $penaltyKitty = 0;
            // }
            // $value->penaltyKitty = $penaltyKitty;

            // $totalLoss = round($financialLoss + $penaltyKitty,2);
            // $value->totalLoss = $totalLoss;

            // $amountWithoutLoss = round($sharingFinal + $totalLoss,2);
            // $value->amountWithoutLoss = $amountWithoutLoss;
          }
        }
      }
      $data['allData'] = $allData;
      
      if(Auth::user()->user_type == 'superadmin'){
        $allBranch = DB::table('location')->get();
      }else{
        $allBranch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
      }
      $data['allBranch'] = $allBranch;
      return view('report.therapistPrivatHomeReport',$data);
    }catch(\Exception $e){
      return view('common.503');
    }
  }
}
