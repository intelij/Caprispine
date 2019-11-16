<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Auth;
use File;
use PDF;
use App\User;
use App\Helper\SendSMS;
use App\DailyEntry;

class PatientController extends Controller
{
    use SendSMS;
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function allPatient(Request $request){
        try{
        	$data = array();
        	$data['title'] = 'All Patient';
            if(Auth::user()->user_type == 'superadmin'){
                if(!empty($request->patientName) || !empty($request->therapistName) || !empty($request->to_date) || !empty($request->from_date)){
                    $query = DB::table('users');
                    if(!empty($request->therapistName)){
                        $query = $query->where('therapist_id',$request->therapistName);
                    }
                    if(!empty($request->patientName)){
                        $query = $query->where('id',$request->patientName);
                    }
                    if(!empty($request->from_date)){
                        $query = $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '>=', $request->from_date);
                    }
                    if(!empty($request->to_date)){
                        $query = $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date);
                    }
                    if(!empty($request->to_date) && !empty($request->from_date)){
                        $query = $query->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date]);
                    }
                    $query = $query->get();
                    $allData = $query;
                }else{
                    $allData = User::where('user_type',3)->orderBy('id','DESC')->take(10)->get();
                }
                $therapistData = User::where('user_type',5)->where('status','active')->get();
                $allPatient = User::where('user_type',3)->where('status','active')->get();
            }else{
                if(!empty($request->patientName) || !empty($request->therapistName) || !empty($request->to_date) || !empty($request->from_date)){
                    $query = DB::table('users')->where('branch',Auth::user()->branch);
                    if(!empty($request->therapistName)){
                        $query = $query->where('therapist_id',$request->therapistName);
                    }
                    if(!empty($request->patientName)){
                        $query = $query->where('id',$request->patientName);
                    }
                    if(!empty($request->from_date)){
                        $query = $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '>=', $request->from_date);
                    }
                    if(!empty($request->to_date)){
                        $query = $query->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $request->to_date);
                    }
                    if(!empty($request->to_date) && !empty($request->from_date)){
                        $query = $query->whereBetween(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), [$request->to_date, $request->from_date]);
                    }
                    $query = $query->get();
                    $allData = $query;
                }else{
                    $allData = User::where('user_type',3)->where('branch',Auth::user()->branch)->orderBy('id','DESC')->take(10)->get();
                }
                $therapistData = User::where('user_type',5)->where('status','active')->where('branch',Auth::user()->branch)->get();
                $allPatient = User::where('user_type',3)->where('status','active')->where('branch',Auth::User()->branch)->get();
            }
            if(count($allData) > 0){
                foreach ($allData as $fVal) {
                    $checkFeedback = DB::table('treatment_given')->where('patient_id',$fVal->id)->get();
                    if(count($checkFeedback) > 0){
                        $fVal->flag = 'true';
                    }else{
                        $fVal->flag = 'false';
                    }
                }
            }
            $data['allData'] = $allData;
            $data['therapistData'] = $therapistData;
            $data['allPatient'] = $allPatient;
            $data['masterclass'] = 'patient';
            $data['class'] = 'patients';
        	return view('patient.list',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function addPatient(){
    	try{
            $data = array();
        	$data['title'] = 'Add Patient';
        	$allUserType = DB::table('user_type')->where('name','!=','Admin')->where('id',3)->get();
            $data['allUserType'] = $allUserType;
            $state = DB::table('states')->get();
            $data['states'] = $state;
            if(Auth::user()->user_type == 'superadmin'){
                $branch = DB::table('location')->get();
            }else{
                $branch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
            }
            $data['branch'] = $branch;
            $patientService = DB::table('service')->get();
            $data['patientService'] = $patientService;
            $data['masterclass'] = 'patient';
            $data['class'] = 'addpatient';
        	return view('patient.addNew',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function savePatient(Request $request){
        try{
            $duplicateData = User::where('mobile',$request->mobile)->first();
            if($duplicateData){
                echo "<script>alert('Mobile no and Email already exist');</script>";
                return redirect()->back();
            }else{
                $branch = $request->branch;
            	$userdata = new User();
            	$userdata['name'] = $request->uname;
                $userdata['email'] = $request->uemail;
                $userdata['user_type'] = $request->userType;
                $userdata['mobile'] = $request->mobile;
                $userdata['gender'] = $request->gender;
                $userdata['status'] = $request->status;
                $userdata['type'] = $request->patient_type;
                $userdata['state'] = $request->state;
                $userdata['city'] = $request->city;
                $userdata['address'] = $request->address;
                $userdata['service_type'] = $request->service_type;
                $userdata['branch'] = $branch;
                $username = quickRandom(4);
                $userdata['username'] = 'user_'.$username;
                $password = quickRandom(6);
                $userdata['password'] = bcrypt($password);
                $userdata['confirmpassword'] = $password;
                $userdata->save();

                $userId = $userdata->id;
                $regData = array();
                $regNo = 100000 + $userId;
                $branch_key = branchDetails($branch)->b_key;
                $regData = array();
                // $regData['registration_no'] = $regNo.'_'.$branch_key.'_PT';

                $userType = $request->userType;
                $userTypeDetails = DB::table('user_type')->where('id',$userType)->first();
                $userTypeName = $userTypeDetails->name;
                $userTypeChar = mb_substr($userTypeName, 0, 2);
                $userTypeChar = strtoupper($userTypeChar);
                $regData['registration_no'] = $regNo.'_'.$branch_key.'_'.$userTypeChar;
                $saveData = User::where('id',$userId)->update($regData);

                if($saveData){
                    $mobileNo = $request->mobile;
                    $message = 'Your details are successfully submited, your latest password is '.$password;
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function therapistAssignment($id){
        try{
            $data = array();
            $therapistData = User::where('user_type',5)->where('branch',Auth::user()->branch)->get();
            $data['therapistData'] = $therapistData;
            $data['title'] = 'Therapist Assignment';
            $data['masterclass'] = 'patient';
            $data['class'] = 'patients';
            return view('patient.assignment',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function assignTherapist(Request $request){
        try{
            $userId = $request->userId;
            $therapistId = $request->therapistId;
            $data = array();
            $data['therapist_id'] = $therapistId;
            User::where('id',$userId)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function patientDetails($id){
        try{
            $data = array();
            $data['title'] = 'Patient Details';
            $getData = User::where('id',$id)->first();
            $data['allData'] = $getData;
            $data['masterclass'] = 'patient';
            $data['class'] = 'patients';
            return view('patient.view',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editPatient($id){
        try{
            $data = array();
            $data['title'] = 'Edit Patient';
            $getData = User::where('id',$id)->first();
            $data['allData'] = $getData;
            $allUserType = DB::table('user_type')->where('name','!=','Admin')->get();
            $data['allUserType'] = $allUserType;
            $state = DB::table('states')->get();
            $data['states'] = $state;
            $cities = DB::table('cities')->get();
            $data['cities'] = $cities;
            if(Auth::user()->user_type == 'superadmin'){
                $branch = DB::table('location')->get();
            }else{
                $branch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
            }
            $data['branch'] = $branch;
            $service = DB::table('service')->get();
            $data['service'] = $service;
            $data['masterclass'] = 'patient';
            $data['class'] = 'patients';
            return view('patient.edit',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updatePatient(Request $request, $id){
        try{
            $userData = array();
            $userData['name'] = $request->uname;
            $userData['email'] = $request->email;
            $userData['user_type'] = $request->userType;
            $userData['mobile'] = $request->mobile;
            $userData['gender'] = $request->gender;
            $userData['status'] = $request->status;
            $userData['state'] = $request->state;
            $userData['city'] = $request->city;
            $userData['branch'] = $request->branch;
            $userData['service_type'] = $request->service;
            User::where('id',$id)->update($userData);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function checkDuplicateContactNo($contactNo){
        $checkData = User::where('mobile',$contactNo)->first();
        if(!empty($checkData)){
            $result = 'false';
        }else{
            $result = 'true';
        }
        return json_encode($result);
    }

    public function visitDetailsReport($id){
        try{
            $userDetails = User::where('id',$id)->first();
            if($userDetails){
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
                  if(!empty($userDetails->branch)){
                      $branchsName = DB::table('location')->where('id',$userDetails->branch)->first();
                      $branchName = $branchsName->name;
                  }else{
                      $branchName = '';
                  }
                  $userDetails->branch = $branchName;
                  $data['userDetails'] = $userDetails;
                $feedback = DB::table('treatment_given')->join('daily_entry','daily_entry.id','=','treatment_given.visit_id')->where('treatment_given.patient_id',$id)->select('treatment_given.visit_id','treatment_given.comments','treatment_given.signature','daily_entry.rating','treatment_given.created_at')->get();
                $data['feedback'] = $feedback;
                return view('patient.visitDetailsReport',$data);
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function randomValue($length = 10) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}
