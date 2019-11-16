<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App;
use DB;
use File;
use PDF;
use DateTime;
use App\User;

class ReportController extends Controller
{
    public function patientCaseReport(Request $request){
        // try{
            if(Input::has('patientId') && Input::has('fromDate') && Input::has('toDate')){
                $patientId = $request->patientId;
                $fromDate = $request->fromDate;
                $toDate = $request->toDate;
                if(!empty($patientId)){
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
                        	$pdf = PDF::loadView('report.caseReportCapriApp',compact('userDetails','chiefComplaint','examHistory','physicalExam','painExam','sensoryExam','progressNote','specialExam','investigation','diagnosis','combinedSpineExam','cervicalSpineExam','thoracicSpineExam','lumbarSpineExam','hipExam','ankleExam','kneeExam','wristExam','shoulderExam','elbowExam','forearmExam','toesExam','fingerExam','scarollicReport','ndtndpExam','neurologicalExam','treatmentGoal','orthoCase','neuroCase'));
                        	$ranVal = $this->randomValue(5);
                        	$res = $pdf->save('upload/patient_case_report/report_'.$ranVal.'.pdf');
                        	if($res){
                        		$addData =array();
                        		$addData['patient_id'] = $patientId;
                        		$addData['name'] = 'report_'.$ranVal.'.pdf';
                        		$addData['date'] = date('Y-m-d');
                        		DB::table('case_report')->insert($addData);
                        		$reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                        		$report = API_CASE_REPORT_DOC.$reportData->name;
                        		$response['message'] = 'Successfully save!';
                    			$response['status'] = '1';
                    			$response['report'] = $report;
                        	}else{
                        		$response['message'] = 'Successfully not save!';
                    			$response['status'] = '0';
                        	}
                        }else if(!empty($patientId) && !empty($toDate) && empty($fromDate)){
                            //$response['message'] = 'Working in progress!';
                    		// $response['status'] = '0';
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
                            $res = $pdf->save('upload/patient_case_report/report_'.$ranVal.'.pdf');
                            if($res){
                                $addData =array();
                                $addData['patient_id'] = $patientId;
                                $addData['name'] = 'report_'.$ranVal.'.pdf';
                                $addData['date'] = date('Y-m-d');
                                DB::table('case_report')->insert($addData);
                                $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                                $report = API_CASE_REPORT_DOC.$reportData->name;
                                $response['message'] = 'Successfully save!';
                                $response['status'] = '1';
                                $response['report'] = $report;
                            }else{
                                $response['message'] = 'Successfully not save!';
                                $response['status'] = '0';
                            }
                        }else if(!empty($patientId) && empty($toDate) && !empty($fromDate)){
                      //   	$response['message'] = 'Working in progress!';
                    		// $response['status'] = '0';
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
                            $res = $pdf->save('upload/patient_case_report/report_'.$ranVal.'.pdf');
                            if($res){
                                $addData =array();
                                $addData['patient_id'] = $patientId;
                                $addData['name'] = 'report_'.$ranVal.'.pdf';
                                $addData['date'] = date('Y-m-d');
                                DB::table('case_report')->insert($addData);
                                $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                                $report = API_CASE_REPORT_DOC.$reportData->name;
                                $response['message'] = 'Successfully save!';
                                $response['status'] = '1';
                                $response['report'] = $report;
                            }else{
                                $response['message'] = 'Successfully not save!';
                                $response['status'] = '0';
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
                            $res = $pdf->save('upload/patient_case_report/report_'.$ranVal.'.pdf');
                            if($res){
                                $addData =array();
                                $addData['patient_id'] = $patientId;
                                $addData['name'] = 'report_'.$ranVal.'.pdf';
                                $addData['date'] = date('Y-m-d');
                                DB::table('case_report')->insert($addData);
                                $reportData = DB::table('case_report')->where('patient_id',$patientId)->orderBy('id','DESC')->first();
                                $report = API_CASE_REPORT_DOC.$reportData->name;
                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                                $response['report'] = $report;
                            }else{
                                $response['message'] = 'Successfully not Saved!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Invalid Condition!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Patient not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields cant be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All fields are Mandatory!';
                $response['status'] = '0';
            }
        // }catch(\Exception $e){
        //     $response['message'] = 'Something went wrong!';
        //     $response['status'] = '0';
        // }
        return response()->json($response);
    }


    public function randomValue($length = 10) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}
