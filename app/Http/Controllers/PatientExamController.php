<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Redirect;
use App\User;

class PatientExamController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function allPatientExam(){
    	try{
    		$data = array();
	    	$data['title'] = 'All Exams';
	        $data['masterclass'] = 'generalDetails';
	        $data['class'] = 'allTest';
	    	return view('patientExam.index',$data)->with('no',1);
    	}catch(\Exception $e){
            return view('common.503');
        }
    }

    public function examDetailsForPatient($key,$patientId){
    	try{
    		$data = array();
    		$data['title'] = 'Exams Details';
    		$data['masterclass'] = 'generalDetails';
	        $data['class'] = 'allTest';
	        if($key == 'chief_complaint'){
	        	$data['key'] = $key;
	        	$allData = DB::table('chief_complaint')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['patientId'] = $patientId;
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'pain_examination'){
	        	$data['key'] = $key;
	        	$data['patientId'] = $patientId;
	        	$allData = DB::table('pain_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'body_chart'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('body_chart')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'motor_examination'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$data['flag'] = 'motor';
	        	$data['title2'] = 'Sub Examination';
	        	return view('patientExam.subExam',$data)->with('no',1);
	        }else if($key == 'combined_movement_spine'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_combined_spine')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'thoracic_spine'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_cervical_spine')->where('patient_id',$patientId)->where('flag','thoracicSpine')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'hip_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','hip')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'ankle_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_ankle_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'shoulder_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','shoulder')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'forearm_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','forearm')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'fingers_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','fingers')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'cervical_spine'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_cervical_spine')->where('patient_id',$patientId)->where('flag','cervicalSpine')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'lumbar_spine'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_cervical_spine')->where('patient_id',$patientId)->where('flag','lumbarSpine')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'knee_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','knee')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'toes_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','toes')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'elbow_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','elbow')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'wrist_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag','wrist')->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'sacroiliac_joint'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('mt_sacrollic_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'neurological_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('neurological_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'special_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('special_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'physiotherapeutic_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('diagnosis')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'all_notes'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('treatment_note')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'history_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('exam_history')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$data['flag'] = 'adl';
	        	$data['title2'] = 'Sub Examination';
	        	return view('patientExam.subExam',$data)->with('no',1);
	        }else if($key == 'adl_neck'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_neck')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_hip'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_hip')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_knee'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_knee')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_wrist_and_hand'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_wrist_and_hand')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_anke_and_foot'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_anke_and_foot')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_elbow'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_elbow')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_shoulder'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_shoulder')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'adl_back'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('adl_back')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'physical_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('physical_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'investigation'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('investigation_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'feedback'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('treatment_given')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'treatment_goal'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('treatment_goal')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'ndt_ndp_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('ndt_ndp_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'sensory_exam'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('sensory_exam')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'ortho_case'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('ortho_case')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else if($key == 'neuro_case'){
	        	$data['patientId'] = $patientId;
	        	$data['key'] = $key;
	        	$allData = DB::table('neuro_case')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
	        	$data['allData'] = $allData;
	        	return view('patientExam.examDetails',$data)->with('no',1);
	        }else{
	        	return Redirect::to('select-patient');
	        }
    	}catch(\Exception $e){
            return view('common.503');
        }
    }

    public function selectPatientForExam(){
    	try{
    		$data = array();
    		$data['title'] = 'Select Patient';
    		$data['masterclass'] = 'generalDetails';
    		$data['class'] = 'allTest';
    		if(Auth::User()->user_type == 'superadmin'){
    			$allPatient = User::where('user_type',3)->where('status','active')->select('id','name','mobile')->get();
    		}else{
    			$allPatient = User::where('user_type',3)->where('status','active')->where('branch',Auth::User()->branch)->select('id','name','mobile')->get();
    		}
	        $data['allPatient'] = $allPatient;
	        return view('patientExam.selectPatient',$data);
    	}catch(\Exception $e){
            return view('common.503');
        }
    }

    public function searchPatientExam(Request $request){
    	try{
    		$patientId = $request->patientId;
    		$data = array();
    		$data['title'] = 'All Exams';
	        $data['masterclass'] = 'generalDetails';
	        $data['class'] = 'allTest';
    		$data['patientId'] = $patientId;
    		return view('patientExam.index',$data);
    	}catch(\Exception $e){
            return view('common.503');
        }
    }
}
