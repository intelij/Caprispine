<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App;
use DB;
use File;
use PDF;
use App\User;
use App\DailyEntry;
use DateTime;
use App\Appointment;
use App\AppointmentHistory;
use App\Helper\FileUpload;
use App\Helper\SendNotification;
use App\Helper\SendSMS;

class TherapistController extends Controller
{
    use SendNotification;
    use SendSMS;
    public function login(Request $request){
        try{
            if(Input::has('phone') && Input::has('password')){
                $phone = $request->phone;
                $password = $request->password;
                $tokenId = $request->tokenId;
                if(!empty($phone) && !empty($password)){
                    $noCheck = ctype_digit($phone);
                    if($noCheck == 'true'){
                        $userDetails = User::where('mobile',$phone)->where('status','active')->where('user_type',5)->first();
                        if($userDetails){
                            $userPhone = $userDetails->mobile;
                            $userPassword = $userDetails->confirmpassword;
                            if($userPassword == $password){
                                if(!empty($tokenId)){
                                    $updateToken = array();
                                    $updateToken['token_id'] = $tokenId;
                                    User::where('mobile',$phone)->update($updateToken);
                                }
                                // Refer code update in user details
                                $dbReferCode = $userDetails->refer_code;
                                if(empty($dbReferCode)){
                                    $patientName = $userDetails->email;
                                    $patientNam = substr($patientName, 0, 3);
                                    $userDetailsData = array();
                                    $randomValue = 100000 + $userDetails->id;
                                    $referCode = $patientNam."_".$randomValue;
                                    $userDetailsData['refer_code'] = strtoupper($referCode);
                                    $res = User::where('id',$userDetails->id)->update($userDetailsData);
                                    $userDetails1 = userDetails($userDetails->id);
                                    if($res){
                                        $response['message'] = 'Successfully Login!';
                                        $response['status'] = '1';
                                        $response['userId'] = $userDetails->id;
                                        $response['userType'] = $userDetails->user_type;
                                        $response['referCode'] = $userDetails1->refer_code;
                                    }else{
                                        $response['message'] = 'Something went wrong!';
                                        $response['status'] = '0';
                                    }
                                }else{
                                    $response['message'] = 'Successfully Login!';
                                    $response['status'] = '1';
                                    $response['userId'] = $userDetails->id;
                                    $response['userType'] = $userDetails->user_type;
                                    $response['referCode'] = $userDetails->refer_code;
                                }
                            }else{
                                $response['message'] = 'Incorrect phone no and password';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Therapist not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Please enter valid phone no!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function forgetPasswordForTherapist(Request $request){
        try{
            if(Input::has('phone')){
                $phone = $request->phone;
                if(!empty($phone)){
                    $noCheck = ctype_digit($phone);
                    if($noCheck == 'true'){
                        $userDetails = User::where('mobile',$phone)->where('status','active')->where('user_type','=',5)->first();
                        if($userDetails){
                            $password = $userDetails->confirmpassword;
                            if($password){
                                $message = 'Your password is '.$password.'!';
                                $sendsms = $this->sendSMSMessage($message,$phone);    
                            }
                            $response['message'] = 'Successfully send password in your mentioned mobile no!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Please enter valid phone no!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function therapistProfile(Request $request){
        try{
            if(Input::has('userId')){
                $userId = $request->userId;
                if(!empty($userId)){
                    $getData = User::where('id',$userId)->where('status','active')->select('id','registration_no','name','email','gender','mobile','state','city','dob','branch','user_type','profile_pic','adhar_card','degree','base_commision','occupation')->first();
                    if($getData){
                        //Convert null value to empty string 
                        array_walk_recursive($getData,function(&$item){$item=strval($item);});
                        // state and city name
                        if(!empty($getData->state)){
                            $stateName = DB::table('states')->where('id',$getData->state)->first();
                            $state = $stateName->name;
                        }else{
                            $state = '';
                        }
                        if(!empty($getData->city)){
                           $cityName = DB::table('cities')->where('id',$getData->city)->first();
                           $city = $cityName->name;
                        }else{
                            $city = '';
                        }
                        //branch name
                        if(!empty($getData->branch)){
                            $bName = DB::table('location')->where('id',$getData->branch)->first();
                            $branch = $bName->name;
                        }else{
                            $branch = '';
                        }
                        // profile picture
                        if(!empty($getData->profile_pic)){
                            $profile_pics = API_PROFILE_PIC.$getData->profile_pic;
                        }else{
                            $profile_pics = API_FOR_DEFAULT_IMG;
                        }                                                  
                        // degree
                        if(!empty($getData->degree)){
                            $degrees = API_THERAPIS_DOC.$getData->degree;
                        }else{
                            $degrees = '';
                        }
                        // age
                        if(!empty($getData->dob) && ($getData->dob != "0000-00-00")){
                            $dd1 = date("d-m-Y", strtotime($getData->dob));
                            $today = date("Y-m-d");
                            $diff = date_diff(date_create($dd1), date_create($today));
                            $age = $diff->format('%y');
                            $age = $age.' Years';
                        }else{
                            $age = '';
                        }

                        $getData['age'] = $age;
                        $getData['stateName'] = $state;
                        $getData['cityName'] = $city;
                        $getData['branchName'] = $branch;
                        $getData['profile_pics'] = $profile_pics;
                        $getData['degrees'] = $degrees;
                        $response['message'] = 'Profile successfully view!';
                        $response['status'] = '1';
                        $response['allData'] = $getData;
                    }else{
                        $response['message'] = 'Therapist not available!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allPendingVisits(Request $request){
        try{
            if(Input::has('userId')){
                $userId = $request->userId;
                if(!empty($userId)){
                    $checkPatient = User::where('id',$userId)->where('user_type',5)->where('status','active')->first();
                    if($checkPatient){
                        $date = date('Y-m-d');
                        $therapistDetails = User::where('id',$userId)->first();
                        $therapistBranch = $therapistDetails->branch;
                        if(Input::has('flag') && !empty($request->flag)){
                            // For IPD & HomeCare Appointments
                            $flag = $request->flag;
                            if($flag == 'ipd'){
                                $allVisit = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.therapist_id',$userId)->where('daily_entry.app_booked_date',$date)->where('daily_entry.status','!=','complete')->where('daily_entry.status','!=','approval_pending')->where('appointment.app_service_type',7)->select('daily_entry.id','users.name as patientName','daily_entry.package_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.amount','daily_entry.in_time','daily_entry.out_time','daily_entry.rating','appointment.app_service_type as service_type','daily_entry.type','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','users.profile_pic')->orderBy('daily_entry.app_booked_time','ASC')->get();
                                if(count($allVisit) > 0){
                                    foreach($allVisit as $visitValue) {
                                        if($visitValue->type == 2){
                                            $visitValue->appointmentType = 'Package';
                                        }else if($visitValue->type == 3){
                                            $visitValue->appointmentType = 'Complimentary';
                                        }else{
                                            $visitValue->appointmentType = 'Perday';
                                        }
                                        if(($visitValue->package_id) && $visitValue->type == 2){
                                            $packageData = DB::table('package')->where('id',$visitValue->package_id)->first();
                                            $visitValue->packageName = $packageData->name.'('.$packageData->package_amount.'/-)';
                                            $visitValue->packageAmount = $visitValue->amount;
                                            $visitValue->packageDays = $packageData->days;
                                        }else{
                                            $visitValue->packageName = '';
                                            $visitValue->packageAmount = $visitValue->amount;
                                            $visitValue->packageDays = '';
                                        }
                                        
                                        if($visitValue->profile_pic){
                                            $visitValue->patientProfilePicture = API_PROFILE_PIC.$visitValue->profile_pic;
                                        }else{
                                            $visitValue->patientProfilePicture = API_FOR_DEFAULT_IMG;
                                        }

                                        if($visitValue->service_type){
                                            $serviceType = DB::table('service')->where('id',$visitValue->service_type)->first();
                                            $visitValue->patientService = $serviceType->name;
                                        }else{
                                            $visitValue->patientService = '';
                                        }
                                        array_walk_recursive($visitValue, function (&$item, $key) {
                                            $item = null === $item ? '' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data fetch successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $allVisit;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'home'){
                                $allVisit = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.therapist_id',$userId)->where('daily_entry.app_booked_date',$date)->where('daily_entry.status','!=','complete')->where('daily_entry.status','!=','approval_pending')->where('appointment.app_service_type','!=',3)->where('appointment.app_service_type','!=',4)->where('appointment.app_service_type','!=',6)->where('appointment.app_service_type','!=',7)->select('daily_entry.id','users.name as patientName','daily_entry.package_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.amount','daily_entry.in_time','daily_entry.out_time','daily_entry.rating','appointment.app_service_type as service_type','daily_entry.type','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','users.profile_pic')->orderBy('daily_entry.app_booked_time','DESC')->get();
                                if(count($allVisit) > 0){
                                    foreach($allVisit as $visitValue){
                                        if($visitValue->type == 2){
                                            $visitValue->appointmentType = 'Package';
                                        }else if($visitValue->type == 3){
                                            $visitValue->appointmentType = 'Complimentary';
                                        }else{
                                            $visitValue->appointmentType = 'Perday';
                                        }
                                        if(($visitValue->package_id) && $visitValue->type == 2){
                                            $packageData = DB::table('package')->where('id',$visitValue->package_id)->first();
                                            $visitValue->packageName = $packageData->name.'('.$packageData->package_amount.'/-)';
                                            $visitValue->packageAmount = $visitValue->amount;
                                            $visitValue->packageDays = $packageData->days;
                                        }else{
                                            $visitValue->packageName = '';
                                            $visitValue->packageAmount = $visitValue->amount;
                                            $visitValue->packageDays = '';
                                        }
                                        
                                        if($visitValue->profile_pic){
                                            $visitValue->patientProfilePicture = API_PROFILE_PIC.$visitValue->profile_pic;
                                        }else{
                                            $visitValue->patientProfilePicture = API_FOR_DEFAULT_IMG;
                                        }

                                        if($visitValue->service_type){
                                            $serviceType = DB::table('service')->where('id',$visitValue->service_type)->first();
                                            $visitValue->patientService = $serviceType->name;
                                        }else{
                                            $visitValue->patientService = '';
                                        }
                                        array_walk_recursive($visitValue, function (&$item, $key) {
                                            $item = null === $item ? '' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data fetch successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $allVisit;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Flag!';
                                $response['status'] = '0';
                            }
                        }else{
                            // For non IPD & HomeCare Appointments
                            $allVisit = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.therapist_id',$userId)->where('daily_entry.app_booked_date',$date)->where('users.branch',$therapistBranch)->where('daily_entry.status','!=','complete')->where('daily_entry.status','!=','approval_pending')->select('daily_entry.id','users.name as patientName','daily_entry.package_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.amount','daily_entry.in_time','daily_entry.out_time','daily_entry.rating','appointment.app_service_type as service_type','daily_entry.type','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','users.profile_pic')->orderBy('daily_entry.app_booked_time','DESC')->get();
                            if(count($allVisit) > 0){
                                foreach($allVisit as $visitValue) {
                                    if($visitValue->type == 2){
                                        $visitValue->appointmentType = 'Package';
                                    }else if($visitValue->type == 3){
                                        $visitValue->appointmentType = 'Complimentary';
                                    }else{
                                        $visitValue->appointmentType = 'Perday';
                                    }
                                    if(($visitValue->package_id) && $visitValue->type == 2){
                                        $packageData = DB::table('package')->where('id',$visitValue->package_id)->first();
                                        $visitValue->packageName = $packageData->name.'('.$packageData->package_amount.'/-)';
                                        $visitValue->packageAmount = $visitValue->amount;
                                        $visitValue->packageDays = $packageData->days;
                                    }else{
                                        $visitValue->packageName = '';
                                        $visitValue->packageAmount = $visitValue->amount;
                                        $visitValue->packageDays = '';
                                    }
                                    
                                    if($visitValue->profile_pic){
                                        $visitValue->patientProfilePicture = API_PROFILE_PIC.$visitValue->profile_pic;
                                    }else{
                                        $visitValue->patientProfilePicture = API_FOR_DEFAULT_IMG;
                                    }

                                    if($visitValue->service_type){
                                        $serviceType = DB::table('service')->where('id',$visitValue->service_type)->first();
                                        $visitValue->patientService = $serviceType->name;
                                    }else{
                                        $visitValue->patientService = '';
                                    }
                                    array_walk_recursive($visitValue, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allVisit;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allNextPendingVisits(Request $request){
        try{
            if(Input::has('userId')){
                $userId = $request->userId;
                if(!empty($userId)){
                    $date = date('Y-m-d');
                    $next1Day = date('Y-m-d', strtotime($date. ' + 1 days'));
                    $therapistDetails = User::where('id',$userId)->first();
                    $therapistBranch = $therapistDetails->branch;
                    $val = '';
                    $allVisit = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.therapist_id',$userId)->where('daily_entry.app_booked_date',$next1Day)->where('users.branch',$therapistBranch)->where('daily_entry.status','!=','complete')->select('daily_entry.id','users.name as patientName','daily_entry.package_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.amount','daily_entry.in_time','daily_entry.out_time','daily_entry.rating','users.service_type','daily_entry.type','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','users.profile_pic')->orderBy('daily_entry.app_booked_time','ASC')->get();
                    if(count($allVisit) > 0){
                        foreach($allVisit as $visitValue){
                            if($visitValue->type == 2){
                                $visitValue->appointmentType = 'Package';
                            }else if($visitValue->type == 1){
                                $visitValue->appointmentType = 'Perday';
                            }else if($visitValue->type == 3){
                                $visitValue->appointmentType = 'Complimentary';
                            }else{
                                $visitValue->appointmentType = '';
                            }

                            if(($visitValue->package_id) && ($visitValue->type == 2)){
                                $packageData = DB::table('package')->where('id',$visitValue->package_id)->first();
                                $visitValue->packageName = $packageData->name.'('.$packageData->package_amount.'/-)';
                                $visitValue->packageAmount = $visitValue->amount;
                                $visitValue->packageDays = $packageData->days;
                            }else{
                                $visitValue->packageName = '';
                                $visitValue->packageAmount = $visitValue->amount;
                                $visitValue->packageDays = '';
                            }
                            
                            if($visitValue->profile_pic){
                                $visitValue->patientProfilePicture = API_PROFILE_PIC.$visitValue->profile_pic;
                            }else{
                                $visitValue->patientProfilePicture = API_FOR_DEFAULT_IMG;
                            }

                            if($visitValue->service_type){
                                $serviceType = DB::table('service')->where('id',$visitValue->service_type)->first();
                                $visitValue->patientService = $serviceType->name;
                            }else{
                                $visitValue->patientService = '';
                            }
                            array_walk_recursive($visitValue, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }
                        $response['message'] = 'Data fetch successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allVisit;
                    }else{
                        $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allCompleteVisits(Request $request){
        try{
            if(Input::has('userId')){
                $userId = $request->userId;
                if(!empty($userId)){
                    $date = date('Y-m-d');
                    $daysBeforeDate = date("Y-m-d", strtotime('-7 days'));
                    $therapistDetails = User::where('id',$userId)->first();
                    $therapistBranch = $therapistDetails->branch;                
                    $allVisit = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.therapist_id',$userId)->where('daily_entry.status','complete')->where('daily_entry.app_booked_date','>=',$daysBeforeDate)->select('daily_entry.id','users.id as patient_id','daily_entry.appointment_id as appointment_id','users.name as patientName','daily_entry.package_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.amount','daily_entry.in_time','daily_entry.out_time','daily_entry.rating','appointment.app_service_type as service_type','daily_entry.type','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','users.profile_pic')->orderBy('daily_entry.app_booked_date','DESC')->orderBy('daily_entry.app_booked_time','DESC')->orderBy('daily_entry.app_booked_date','DESC')->get();
                    if(count($allVisit) > 0){
                        foreach($allVisit as $visitValue){
                            if($visitValue->type == 2){
                                $visitValue->appointmentType = 'Package';
                            }else if($visitValue->type == 1){
                                $visitValue->appointmentType = 'Perday';
                            }else if($visitValue->type == 3){
                                $visitValue->appointmentType = 'Complimentary';
                            }else{
                                $visitValue->appointmentType = '';
                            }

                            if(($visitValue->package_id) && ($visitValue->type == 2)){
                                $packageData = DB::table('package')->where('id',$visitValue->package_id)->first();
                                $visitValue->packageName = $packageData->name.'('.$packageData->package_amount.'/-)';
                                $visitValue->packageAmount = $visitValue->amount;
                                $visitValue->packageDays = $packageData->days;
                            }else{
                                $visitValue->packageName = '';
                                $visitValue->packageAmount = $visitValue->amount;
                                $visitValue->packageDays = '';
                            }
                            
                            if($visitValue->profile_pic){
                                $visitValue->patientProfilePicture = API_PROFILE_PIC.$visitValue->profile_pic;
                            }else{
                                $visitValue->patientProfilePicture = API_FOR_DEFAULT_IMG;
                            }

                            if($visitValue->service_type){
                                $serviceType = DB::table('service')->where('id',$visitValue->service_type)->first();
                                $visitValue->patientService = $serviceType->name;
                            }else{
                                $visitValue->patientService = '';
                            }
                            array_walk_recursive($visitValue, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }
                        $response['message'] = 'Data fetch successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allVisit;
                    }else{
                        $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function markAttendance(Request $request){
      try{
        if(Input::has('userId') && Input::has('location')){
            $userId = $request->userId;
            $location = $request->location;
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            $currentMonth = date('m');
            if(!empty($userId) && !empty($location)){
                $checkData = User::where('id',$userId)->where('status','active')->first();
                $appServiceId = $checkData->service_type;
                if($checkData){
                    $checkAttendance = DB::table('attendance')->where('therapist_id',$userId)->where('date',$currentDate)->first();
                    if($checkAttendance){
                        $response['message'] = 'Attendance already marked!';
                        $response['status'] = '0';
                    }else{
                        $lastPresentDate = DB::table('attendance')->where('therapist_id',$userId)->where('status','present')->orderBy('date','DESC')->first();
                        if($lastPresentDate){
                            $today = $lastPresentDate->date;
                            $currPrevDate = date('Y-m-d', strtotime($currentDate. ' - 1 days'));
                            $diff = abs(strtotime($currPrevDate) - strtotime($today));
                            $years = floor($diff / (365*60*60*24));
                            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                            $day = floor(($diff - $years*365*60*60*24 - $months*30*60*60*24) / (60*60*24));

                            if($day > 0){
                                for($i=1; $i<=$day; $i++)
                                {
                                    $repeat = strtotime("+1 day",strtotime($today));
                                    $today = date('Y-m-d',$repeat);

                                    $attandanceData = array();
                                    $attandanceData['flag'] = 'not_ipd';
                                    $attandanceData['therapist_id'] = $userId;
                                    $attandanceData['date'] = $today;
                                    $attandanceData['attendance_time'] = $currentTime;
                                    $attandanceData['late_coming_min'] = 0;
                                    $dayName = DB::select(DB::raw("SELECT DAYNAME('".$today."')  as adate"));
                                    if($dayName[0]->adate === 'Sunday'){
                                        $attandanceData['status'] = 'sunday';
                                    }else{
                                        $attandanceData['status'] = 'apsent';
                                    }
                                    $attandanceData['created_by'] = 'app';
                                    DB::table('attendance')->insert($attandanceData);
                                }
                            }
                        
                            // // Late comming penalty
                            // $workTime = $checkData->timing;
                            // $wwTime = explode(' to ', $workTime);
                            // $fromWork = $wwTime[0];
                            // $toWork = $wwTime[1];
                            // if(!empty($fromWork) && !empty($toWork)){
                            //     $currentMonth = date('m');
                            //     $workingInTime = date("H:i", strtotime($fromWork));
                            //     $workingOutTime = date("H:i", strtotime($toWork));
                            //     $time1 = new DateTime($workingInTime);
                            //     $time2 = new DateTime($currentTime);
                            //     $interval = $time2->diff($time1);
                            //     $totalLateComming = $interval->format('%h:%i:%s');
                            //     $totalLateCommingMinutes = ($interval->h * 60) + $interval->i;
                            //     $getAllAttData = DB::table('attendance')->where('therapist_id',$userId)->whereMonth('date',$currentMonth)->where('status','present')->get();
                            // }else{
                            //     $totalLateCommingMinutes = 0;
                            // }
                            
                            // Late comming penalty
                            $workTime = $checkData->timing;
                            $wwTime = explode(' to ', $workTime);
                            $fromWork = $wwTime[0];
                            $toWork = $wwTime[1];
                            $workingInTime = date("H:i", strtotime($fromWork));
                            $workingOutTime = date("H:i", strtotime($toWork));
                            if(!empty($fromWork) && !empty($toWork) && (strtotime($workingInTime) < time())){
                                $currentMonth = date('m');
                                $time1 = new DateTime($workingInTime);
                                $time2 = new DateTime($currentTime);
                                $interval = $time2->diff($time1);
                                $totalLateComming = $interval->format('%h:%i:%s');
                                $totalLateCommingMinutes = ($interval->h * 60) + $interval->i;
                                $getAllAttData = DB::table('attendance')->where('therapist_id',$userId)->whereMonth('date',$currentMonth)->where('status','present')->get();
                            }else if(strtotime($workingInTime) > time()){
                                $totalLateCommingMinutes = 0;
                            }else{
                                $totalLateCommingMinutes = 0;
                            }

                            $newattandanceData = array();
                            $checkIPD = DB::table('ipd_calendar')->where('date',$currentDate)->first();
                            if($checkIPD){
                                if($appServiceId == 9){
                                    $newattandanceData['flag'] = 'not_ipd';
                                }else{
                                    $newattandanceData['flag'] = 'ipd';
                                }
                            }else{
                                $newattandanceData['flag'] = 'not_ipd';
                            }
                            $newattandanceData['therapist_id'] = $userId;
                            $newattandanceData['date'] = $currentDate;
                            $newattandanceData['attendance_time'] = $currentTime;
                            $newattandanceData['late_coming_min'] = $totalLateCommingMinutes;
                            $newattandanceData['status'] = 'present';
                            $newattandanceData['location'] = $location;
                            $newattandanceData['created_by'] = 'app';
                            DB::table('attendance')->insert($newattandanceData);

                            // late comming add penalty (after 60 min start add penalty on therapist)
                            $checkPenaltyTime = DB::table('attendance')->where('therapist_id',$userId)->where('status','present')->whereMonth('date',$currentMonth)->sum('late_coming_min');

                            if(($checkPenaltyTime > 30) && ($totalLateCommingMinutes > 0)){
                                $getPenaltyData = DB::table('daily_penalty')->where('therapist_id',$userId)->whereMonth('date',$currentMonth)->where('penalty_id','late_comming')->orderBy('id','DESC')->first();
                                if($getPenaltyData){
                                    $totalLateTime = $totalLateCommingMinutes;
                                }else{
                                    $totalLateTime = $checkPenaltyTime - 30;
                                }
                                $totalPenaltyAmt = $totalLateTime * 10;       //per minute add 10 Rs as penalty
                                $dailyPenaltyData = array();
                                $dailyPenaltyData['therapist_id'] = $userId;
                                $dailyPenaltyData['penalty_id'] = 'late_comming';
                                $dailyPenaltyData['amount'] = $totalPenaltyAmt;
                                $dailyPenaltyData['late_time'] = $totalLateTime;
                                $dailyPenaltyData['total_take_time'] = $checkPenaltyTime;
                                $dailyPenaltyData['date'] = $currentDate;
                                $dailyPenaltyData['time'] = $currentTime;
                                DB::table('daily_penalty')->insert($dailyPenaltyData);
                            }
                        }else{
                            // add today attendance only if therapist is new created
                            // Late comming penalty
                            $workTime = $checkData->timing;
                            $wwTime = explode(' to ', $workTime);
                            $fromWork = $wwTime[0];
                            $toWork = $wwTime[1];
                            if(!empty($fromWork) && !empty($toWork)){
                                $workingInTime = date("H:i", strtotime($fromWork));
                                $workingOutTime = date("H:i", strtotime($toWork));
                                $time1 = new DateTime($workingInTime);
                                $time2 = new DateTime($currentTime);
                                $interval = $time2->diff($time1);
                                $totalLateComming = $interval->format('%h:%i:%s');
                                $totalLateCommingMinutes = ($interval->h * 60) + $interval->i;
                                $getAllAttData = DB::table('attendance')->where('therapist_id',$userId)->whereMonth('date',$currentMonth)->where('status','present')->get();
                            }else{
                                $totalLateCommingMinutes = 0;
                            }

                            $newattandanceData = array();
                            $checkIPD = DB::table('ipd_calendar')->where('date',$currentDate)->first();
                            if($checkIPD){
                                if($appServiceId == 9){
                                    $newattandanceData['flag'] = 'not_ipd';
                                }else{
                                    $newattandanceData['flag'] = 'ipd';
                                }
                            }else{
                                $newattandanceData['flag'] = 'not_ipd';
                            }
                            $newattandanceData['therapist_id'] = $userId;
                            $newattandanceData['date'] = $currentDate;
                            $newattandanceData['attendance_time'] = $currentTime;
                            $newattandanceData['late_coming_min'] = $totalLateCommingMinutes;
                            $newattandanceData['status'] = 'present';
                            $newattandanceData['location'] = $location;
                            $newattandanceData['created_by'] = 'app';
                            DB::table('attendance')->insert($newattandanceData);

                            // late comming add penalty (after 60 min start add penalty on therapist)
                            $checkPenaltyTime = DB::table('attendance')->where('therapist_id',$userId)->where('status','present')->whereMonth('date',$currentMonth)->sum('late_coming_min');

                            if($checkPenaltyTime > 30){
                                $getPenaltyData = DB::table('daily_penalty')->where('therapist_id',$userId)->whereMonth('date',$currentMonth)->where('penalty_id','late_comming')->orderBy('id','DESC')->first();
                                if($getPenaltyData){
                                    $totalLateTime = $totalLateCommingMinutes;
                                }else{
                                    $totalLateTime = $checkPenaltyTime - 30;
                                }
                                $totalPenaltyAmt = $totalLateTime * 10;       //per minute add 10 Rs as penalty
                                $dailyPenaltyData = array();
                                $dailyPenaltyData['therapist_id'] = $userId;
                                $dailyPenaltyData['penalty_id'] = 'late_comming';
                                $dailyPenaltyData['amount'] = $totalPenaltyAmt;
                                $dailyPenaltyData['late_time'] = $totalLateTime;
                                $dailyPenaltyData['total_take_time'] = $checkPenaltyTime;
                                $dailyPenaltyData['date'] = $currentDate;
                                $dailyPenaltyData['time'] = $currentTime;
                                DB::table('daily_penalty')->insert($dailyPenaltyData);
                            }
                        }
                        $response['message'] = 'Succesfully Marked Attendance!!';
                        $response['status'] = '1';
                    }
                }else{
                    $response['message'] = 'User not exist!';
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
      }catch(\Exception $e){
        $response['message'] = 'Something went wrong!';
        $response['status'] = '0';
      }
      return response()->json($response);
    }

    public function allTherapistReport(Request $request){
        try{
            if(Input::has('userId') && Input::has('flag')){
                $therapistId = $request->userId;
                $flag = $request->flag;
                $currentYear = date("Y");
                $currentMonth = date("m");
                if(!empty($therapistId) && !empty($flag)){
                    $checkData = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkData){
                        $baseComm = $checkData->base_commision;
                        if($baseComm){
                            $baseAmount = $baseComm.' %';
                        }else{
                            $baseAmount = '0 %';
                        }
                        if($flag == 'financial'){
                            $totalLeaves = DB::table('attendance')->where('attendance.therapist_id',$therapistId)->where('attendance.status','apsent')->whereYear('attendance.date',$currentYear)->select(DB::raw('count(attendance.id) as leaves'), DB::raw("DATE_FORMAT(attendance.date, '%M-%Y') month"))->groupBy('month')->orderBy('date','DESC')->get();
                            if($totalLeaves){
                                foreach ($totalLeaves as $leVal){
                                    $penaltyData = DB::table('daily_penalty')->where('therapist_id',$therapistId)->whereYear('date',$currentYear)->where(DB::raw("DATE_FORMAT(date, '%M-%Y')"),'=',$leVal->month)->sum('amount');
                                    if($penaltyData){
                                        $leVal->penalty = $penaltyData;
                                    }else{
                                        $leVal->penalty = 0;
                                    }
                                }
                                $allData = array();
                                $allData['BaseAmount'] = $baseAmount;
                                $allData['leaves'] = $totalLeaves;

                                $response['message'] = 'Record fetch succesfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData; 
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'package'){
                            $totalData = DB::table('attendance')->where('therapist_id',$therapistId)->select(DB::raw("DATE_FORMAT(date, '%M') month"))->groupBy(DB::raw("DATE_FORMAT(date, '%M')"))->orderBy('date','DESC')->get();
                            if($totalData){
                                foreach ($totalData as $dataVal) {
                                    $completeData = DB::table('daily_entry')->where('therapist_id',$therapistId)->where('status','complete')->where('type',2)->where(DB::raw("DATE_FORMAT(app_booked_date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->Complete = $completeData;
                                    $pendingData = DB::table('daily_entry')->where('therapist_id',$therapistId)->where('status','pending')->where('type',2)->where(DB::raw("DATE_FORMAT(app_booked_date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->Pending = $pendingData;
                                }
                                $response['message'] = 'Record fetch succesfully!';
                                $response['status'] = '1';
                                $response['allData'] = $totalData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'perday'){
                            $totalData = DB::table('attendance')->where('therapist_id',$therapistId)->select(DB::raw("DATE_FORMAT(date, '%M') month"))->groupBy(DB::raw("DATE_FORMAT(date, '%M')"))->orderBy('date','DESC')->get();
                            if($totalData){
                                foreach ($totalData as $dataVal) {
                                    $completeData = DB::table('daily_entry')->where('therapist_id',$therapistId)->where('status','complete')->where('type',1)->where(DB::raw("DATE_FORMAT(app_booked_date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->Complete = $completeData;
                                    $pendingData = DB::table('daily_entry')->where('therapist_id',$therapistId)->where('status','pending')->where('type',1)->where(DB::raw("DATE_FORMAT(app_booked_date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->Pending = $pendingData;
                                }
                                $response['message'] = 'Record fetch succesfully!';
                                $response['status'] = '1';
                                $response['allData'] = $totalData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'myvisit'){
                            $totalData = DB::table('attendance')->where('therapist_id',$therapistId)->select(DB::raw("DATE_FORMAT(date, '%M') month"))->groupBy(DB::raw("DATE_FORMAT(date, '%M')"))->orderBy('date','DESC')->get();
                            if($totalData){
                                foreach ($totalData as $dataVal) {
                                    $awData = DB::table('daily_entry')->where('therapist_id',$therapistId)->where('visit_type','AW')->where(DB::raw("DATE_FORMAT(app_booked_date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->AW = $awData;
                                    $avData = DB::table('daily_entry')->where('therapist_id',$therapistId)->where('visit_type','AV')->where(DB::raw("DATE_FORMAT(app_booked_date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->AV = $avData;
                                    $ipdData = DB::table('attendance')->where('flag','ipd')->where('status','present')->where(DB::raw("DATE_FORMAT(date, '%M')"),'=',$dataVal->month)->count('id');
                                    $dataVal->IPD = $ipdData;
                                }                                
                                $response['message'] = 'Record fetch succesfully!';
                                $response['status'] = '1';
                                $response['allData'] = $totalData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Invalid Flag!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'User not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function monthlyTherapistReport(Request $request){
        try{
            if(Input::has('userId')){
                $userId = $request->userId;
                $currentYear = date("Y");
                $currentMonth = date("m");
                if(!empty($userId)){
                    $checkData = User::where('id',$userId)->where('user_type',5)->where('status','active')->first();
                    if($checkData){
                        $AWs = DB::table('daily_entry')->where('therapist_id',$userId)->where('visit_type','AW')->whereMonth('app_booked_date',$currentMonth)->count('id');
                        if($AWs){
                            $AW = $AWs;
                        }else{
                            $AW = '';
                        }
                        $AVs = DB::table('daily_entry')->where('therapist_id',$userId)->where('visit_type','AV')->whereMonth('app_booked_date',$currentMonth)->count('id');
                        if($AVs){
                            $AV = $AVs;
                        }else{
                            $AV = '';
                        }
                        $totalIpd = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$userId)->where('attendance.status','present')->whereMonth('attendance.date',$currentMonth)->count('attendance.id');
                        if($totalIpd){
                            $IPD = $totalIpd * 800;
                        }else{
                            $IPD = 0;
                        }
                        $baseComm = $checkData->base_commision;
                        if($baseComm){
                            $baseAmount = $baseComm.' %';
                        }else{
                            $baseAmount = '0 %';
                        }
                        $completePackage = DailyEntry::where('therapist_id',$userId)->where('status','complete')->where('type',2)->whereYear('app_booked_date',$currentYear)->groupBy(DB::raw("MONTH(app_booked_date)"))->count('id');
                        $pendingPackage = DailyEntry::where('therapist_id',$userId)->where('type',2)->where('status','!=','complete')->whereYear('app_booked_date',$currentYear)->groupBy(DB::raw("MONTH(app_booked_date)"))->count('id');
                        $completePerday = DailyEntry::where('therapist_id',$userId)->where('type',1)->where('status','complete')->whereYear('app_booked_date',$currentYear)->groupBy(DB::raw("MONTH(app_booked_date)"))->count('id');
                        $pendingPerday = DailyEntry::where('therapist_id',$userId)->where('type',1)->where('status','!=','complete')->whereYear('app_booked_date',$currentYear)->groupBy(DB::raw("MONTH(app_booked_date)"))->count('id');
                        $totalPenalty = DB::table('daily_penalty')->where('therapist_id',$userId)->whereMonth('date',$currentMonth)->whereYear('date',$currentYear)->sum('amount');
                        if($totalPenalty){
                            $penalty = $totalPenalty;
                        }else{
                            $penalty = '0';
                        }
                        $totalLeaves = DB::table('attendance')->where('therapist_id',$userId)->where('status','apsent')->whereMonth('date',$currentMonth)->whereYear('date',$currentYear)->count('id');
                        $allData = array();
                        $allData['BaseAmount'] = $baseAmount;
                        $allData['completePackage'] = $completePackage;
                        $allData['pendingPackage'] = $pendingPackage;
                        $allData['completePerday'] = $completePerday;
                        $allData['pendingPerday'] = $pendingPerday;
                        $allData['AW'] = $AW;
                        $allData['AV'] = $AV;
                        $allData['IPD'] = $IPD;
                        $allData['leaves'] = $totalLeaves;
                        $allData['totalPenalty'] = $penalty;

                        $response['message'] = 'Record fetch succesfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allData;
                    }else{
                        $response['message'] = 'User not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function dailyEntryDetails(Request $request){
        try{
            if(Input::has('id')){
                $id = $request->id;
                if(!empty($id)){
                    $checkId = DB::table('daily_entry')->where('id',$id)->first();
                    if($checkId){
                        $getData = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.id',$id)->select('users.id as patientId','users.registration_no','users.name as patientName','users.mobile','users.email','users.gender','appointment.reference_type as reference','users.address','users.dob','users.marital_status','users.food_habit','users.height','users.width','users.branch','appointment.app_service_type as service_type','users.description','users.profile_pic','users.occupation')->first();
                        if($getData->service_type){
                            $serviceType = DB::table('service')->where('id',$getData->service_type)->first();
                            $getData->patientService = $serviceType->name;
                        }else{
                            $getData->patientService = '';
                        }
                        // age
                        if(!empty($getData->dob) && ($getData->dob != "0000-00-00")){
                            $dd1 = date("d-m-Y", strtotime($getData->dob));
                            $today = date("Y-m-d");
                            $diff = date_diff(date_create($dd1), date_create($today));
                            $age = $diff->format('%y');
                            $getData->dob = $age.' Years';
                        }else{
                            $getData->dob = '';
                        }

                        if(!empty($getData->branch)){
                            $bName = DB::table('location')->where('id',$getData->branch)->first();
                            $getData->branchName = $bName->name;
                        }else{
                            $getData->branchName = '';
                        }
                        if(!empty($getData->profile_pic)){
                            $getData->profile_pics = API_PROFILE_PIC.$getData->profile_pic;
                        }else{
                            $getData->profile_pics = '';
                        }
                        if(!empty($getData->reference)){
                            $refName = DB::table('reference')->where('id',$getData->reference)->first();
                            $getData->referenceName = $refName->name;
                        }else{
                            $getData->referenceName = '';
                        }
                        //Convert null value to empty string 
                        array_walk_recursive($getData,function(&$item){$item=strval($item);});
                        $response['message'] = 'Data Fetch Successfully!';
                        $response['status'] = '1';
                        $response['getData'] = $getData;

                    }else{
                        $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function editDailyEntryDetails(Request $request){
        try{
            if(Input::has('id') && Input::has('name') && Input::has('email') && Input::has('gender') && Input::has('reference') && Input::has('address') && Input::has('dob') && Input::has('marital_status') && Input::has('food_habit') && Input::has('height') && Input::has('weight') && Input::has('service_type') && Input::has('description') && Input::has('description')){
                $id = $request->id;
                if(!empty($id)){
                    $getData = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->join('users','users.id','=','appointment.user_id')->where('daily_entry.id',$id)->select('users.id','daily_entry.appointment_id')->first();
                    if($getData){
                        $appId = $getData->appointment_id;
                        $userId = $getData->id;
                        $name = $request->name;
                        $email = $request->email;
                        $gender = $request->gender;
                        $reference = $request->reference;
                        $address = $request->address;
                        $dob = $request->dob;
                        $marital_status = $request->marital_status;
                        $food_habit = $request->food_habit;
                        $height = $request->height;
                        $weight = $request->weight;
                        $service_type = $request->service_type;
                        $description = $request->description;
                        $occupation = $request->occupation;

                        $updateData = array();
                        if(!empty($name)){
                            $updateData['name'] = $name;
                        }
                        if(!empty($email)){
                            $updateData['email'] = $email;
                        }
                        if(!empty($gender)){
                            $updateData['gender'] = $gender;
                        }
                        if(!empty($reference)){
                            $updateData['reference'] = $reference;
                        }
                        if(!empty($address)){
                            $updateData['address'] = $address;
                        }
                        if(!empty($dob) && (DateTime::createFromFormat('Y-m-d', $dob) !== FALSE) ){
                            $updateData['dob'] = $dob;
                        }
                        if(!empty($marital_status)){
                            $updateData['marital_status'] = $marital_status;
                        }
                        if(!empty($food_habit)){
                            $updateData['food_habit'] = $food_habit;
                        }
                        if(!empty($height)){
                            $updateData['height'] = $height;
                        }
                        if(!empty($weight)){
                            $updateData['width'] = $weight;
                        }
                        if(!empty($service_type)){
                            $updateData['service_type'] = $service_type;
                        }
                        if(!empty($description)){
                            $updateData['description'] = $description;
                        }
                        if(!empty($occupation)){
                            $updateData['occupation'] = $occupation;
                        }
                        User::where('id',$userId)->update($updateData);

                        // update service and reference in appointment
                        $appUpdate = array();
                        $appUpdate['reference_type'] = $reference;
                        $appUpdate['app_service_type'] = $service_type;
                        DB::table('appointment')->where('id',$appId)->update($appUpdate);

                        $response['message'] = 'Successfully Updated!';
                        $response['status'] = '1';
                    }else{
                        $response['message'] = 'Visit id not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function randomValue($length = 10) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function allNotification(Request $request){
        try{
            if(Input::has('userId')){
                $therapistId = $request->userId;
                if(!empty($therapistId)){
                    $checkTherapist = DB::table('users')->where('id',$therapistId)->where('status','active')->first();
                    if($checkTherapist){
                        $appServiceType = $checkTherapist->service_type;
                        if(($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1)){
                            $allData = DB::table('notification')->orderBy('id','DESC')->where('flag','therapist')->where('type',9)->get();
                        }else if($appServiceType == 6){
                            $allData = DB::table('notification')->orderBy('id','DESC')->where('flag','therapist')->where('type',6)->get();
                        }else if($appServiceType == 7){
                            $allData = DB::table('notification')->orderBy('id','DESC')->where('flag','therapist')->where('type',7)->get();
                        }else{
                            $allData = DB::table('notification')->orderBy('id','DESC')->where('flag','therapist')->where('type',6)->get();
                        }
                        
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                $value->description = $value->message;
                                if(!empty($value->image)){
                                    $value->image = API_NOTIFICATION_IMG.$value->image;
                                }else{
                                    $value->image = '';
                                }
                            }
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Fields can not be Empty!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields can not be Empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All Fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function monthlyCalendar(Request $request){
        try{
            if(Input::has('userId')){
                $therapistId = $request->userId;
                if(!empty($therapistId)){
                    $checkId = User::where('id',$therapistId)->where('status','active')->first();
                    if($checkId){
                        $currentMonth = date('m');
                        $currentYear = date('Y');
                        $currmonth = date('F');
                        $getData = DB::table('attendance')->where('therapist_id',$therapistId)->whereRaw('MONTH(date) = ?',[$currentMonth])->select('id','date','status','flag')->orderBy('date','DESC')->get();
                        if(count($getData) > 0){
                            foreach($getData as $value){
                                $value->date = date("d", strtotime($value->date));
                                if($value->flag == 'ipd'){
                                    $value->status = 'IPD';
                                }else if($value->status == 'present'){
                                    $value->status = 'Present';
                                }else if($value->status == 'apsent'){
                                    $value->status = 'Absent';
                                }else if($value->status == 'sunday'){
                                    $value->status = 'Sunday';
                                }
                            }
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['currentMonthYear'] = $currmonth.', '.$currentYear;
                            $response['allData']  = $getData;
                        }else{
                            $response['message'] = 'Data does not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'User not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function branchFaculty(Request $request){
        try{
            if(Input::has('userId')){
                $therapistId = $request->userId;
                if(!empty($therapistId)){
                    $checkId = User::where('id',$therapistId)->where('status','active')->first();
                    if($checkId){
                        $branchId = $checkId->branch;
                        $allData = User::Where('branch',$branchId)->where('user_type','!=','3')->select('id','name','mobile','email','profile_pic','branch','user_type')->where('id','!=',$therapistId)->get();
                        if(count($allData)){
                            foreach ($allData as $value) {
                                if(!empty($value->profile_pic)){
                                    $value->profile_pic = API_PROFILE_PIC.$value->profile_pic;
                                }else{
                                    $value->profile_pic = API_FOR_DEFAULT_IMG;
                                }

                                if(!empty($value->branch)){
                                    $bName = DB::table('location')->where('id',$value->branch)->first();
                                    $value->branch = $bName->name;
                                }else{
                                    $value->branch = '';
                                }

                                if(!empty($value->branch)){
                                    $userType = DB::table('user_type')->where('id',$value->user_type)->first();
                                    $value->userType = $userType->name;
                                }else{
                                    $value->userType = '';
                                }
                            }

                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'User not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);   
    }

    public function saveChiefComplaint(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
            // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('chiefComplaint') && Input::has('problemTime') && Input::has('problemBefore')){
                    $visitId = $request->visitId;
                    $patientId = $request->patientId;
                    $therapistId = $request->userId;
                    $chiefComplaint = $request->chiefComplaint;
                    $problemTime = $request->problemTime;
                    $problemBefore = $request->problemBefore;
                    $problemDesc = $request->problemDesc;
                    if(!empty($visitId) && !empty($patientId) && !empty($therapistId) && !empty($chiefComplaint) && !empty($problemTime) && !empty($problemBefore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $insertData = array();
                                $insertData['visit_id'] = $visitId;
                                $insertData['appointment_id'] = '';
                                $insertData['therapist_id'] = $therapistId;
                                $insertData['patient_id'] = $patientId;
                                $insertData['chief_complaint'] = $chiefComplaint;
                                $insertData['problem_time'] = $problemTime;
                                $insertData['problem_before'] = $problemBefore;
                                $insertData['problemDesc'] = $problemDesc;
                                $insertData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('chief_complaint')->insert($insertData);

                                $response['message'] = 'Chief complaint add successfully!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Data does not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
            // for patient app
                if(Input::has('patientId') && Input::has('chiefComplaint') && Input::has('problemTime') && Input::has('problemBefore')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $chiefComplaint = $request->chiefComplaint;
                    $problemTime = $request->problemTime;
                    $problemBefore = $request->problemBefore;
                    $problemDesc = $request->problemDesc;
                    if(!empty($appId) && !empty($patientId) && !empty($chiefComplaint) && !empty($problemTime) && !empty($problemBefore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $insertData = array();
                            $insertData['visit_id'] = '';
                            $insertData['appointment_id'] = $appId;
                            $insertData['therapist_id'] = '';
                            $insertData['patient_id'] = $patientId;
                            $insertData['chief_complaint'] = $chiefComplaint;
                            $insertData['problem_time'] = $problemTime;
                            $insertData['problem_before'] = $problemBefore;
                            $insertData['problemDesc'] = $problemDesc;
                            $insertData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('chief_complaint')->insert($insertData);

                            $response['message'] = 'Chief complaint add successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Data does not exist!';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allChiefComplaint(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $getData = DB::table('chief_complaint')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','chief_complaint','problem_time','problem_before','problemDesc','created_at')->orderBy('id','DESC')->get();
                        if(count($getData) > 0){
                            foreach($getData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Chief complaint get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $getData;
                        }else{
                            $response['message'] = 'Data does not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'User not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addHistoryExam(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('causeOfProblem') && Input::has('medicalProblem') && Input::has('anySurgery') && Input::has('anyTreatment') && Input::has('smoking') && Input::has('alcoholic') && Input::has('feverAndChill') && Input::has('diabetes') && Input::has('bloodPressure') && Input::has('heartDiseases') && Input::has('bleedingDisorder') && Input::has('recentInfection') && Input::has('anyRegFlags') && Input::has('AnyYellowFlags') && Input::has('limitations') && Input::has('pastSurgery') && Input::has('allergies') && Input::has('osteoporotic') && Input::has('anyImplants') && Input::has('hereditaryDisease') && Input::has('remark')){
                    $visitId = $request->visitId;
                    $patientId = $request->patientId;
                    $therapistId = $request->userId;
                    $causeOfProblem = $request->causeOfProblem;
                    $medicalProblem = $request->medicalProblem;
                    $anySurgery = $request->anySurgery;
                    $anyTreatment = $request->anyTreatment;
                    $smoking = $request->smoking;
                    $alcoholic = $request->alcoholic;
                    $feverAndChill = $request->feverAndChill;
                    $diabetes = $request->diabetes;
                    $bloodPressure = $request->bloodPressure;
                    $heartDiseases = $request->heartDiseases;
                    $bleedingDisorder = $request->bleedingDisorder;
                    $recentInfection = $request->recentInfection;
                    $anyRegFlags = $request->anyRegFlags;
                    $AnyYellowFlags = $request->AnyYellowFlags;
                    $limitations = $request->limitations;
                    $pastSurgery = $request->pastSurgery;
                    $allergies = $request->allergies;
                    $osteoporotic = $request->osteoporotic;
                    $anyImplants = $request->anyImplants;
                    $hereditaryDisease = $request->hereditaryDisease;
                    $remark = $request->remark;
                    if(!empty($visitId) && !empty($patientId) && !empty($therapistId) && !empty($causeOfProblem) && !empty($medicalProblem) && !empty($anySurgery) && !empty($anyTreatment)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $examdata = array();
                                $examdata['visit_id'] = $visitId;
                                $examdata['appointment_id'] = '';
                                $examdata['patient_id'] = $patientId;
                                $examdata['therapist_id'] = $therapistId;
                                $examdata['cause_of_problem'] = $causeOfProblem;
                                $examdata['medical_problem'] = $medicalProblem;
                                $examdata['any_surgery'] = $anySurgery;
                                $examdata['any_treatment'] = $anyTreatment;
                                $examdata['smoking'] = $smoking;
                                $examdata['alcoholic'] = $alcoholic;
                                $examdata['fever_and_chill'] = $feverAndChill;
                                $examdata['diabetes'] = $diabetes;
                                $examdata['blood_pressure'] = $bloodPressure;
                                $examdata['heart_diseases'] = $heartDiseases;
                                $examdata['bleeding_disorder'] = $bleedingDisorder;
                                $examdata['recent_infection'] = $recentInfection;
                                $examdata['any_reg_flags'] = $anyRegFlags;
                                $examdata['Any_yellow_flags'] = $AnyYellowFlags;
                                $examdata['limitations'] = $limitations;
                                $examdata['past_surgery'] = $pastSurgery;
                                $examdata['allergies'] = $allergies;
                                $examdata['osteoporotic'] = $osteoporotic;
                                $examdata['any_implants'] = $anyImplants;
                                $examdata['hereditary_disease'] = $hereditaryDisease;
                                $examdata['remark'] = $remark;
                                $examdata['created_at'] = date('Y-m-d H:i:s');
                                DB::table('exam_history')->insert($examdata);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('causeOfProblem') && Input::has('medicalProblem') && Input::has('anySurgery') && Input::has('anyTreatment') && Input::has('smoking') && Input::has('alcoholic') && Input::has('feverAndChill') && Input::has('diabetes') && Input::has('bloodPressure') && Input::has('heartDiseases') && Input::has('bleedingDisorder') && Input::has('recentInfection') && Input::has('anyRegFlags') && Input::has('AnyYellowFlags') && Input::has('limitations') && Input::has('pastSurgery') && Input::has('allergies') && Input::has('osteoporotic') && Input::has('anyImplants') && Input::has('hereditaryDisease') && Input::has('remark')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $causeOfProblem = $request->causeOfProblem;
                    $medicalProblem = $request->medicalProblem;
                    $anySurgery = $request->anySurgery;
                    $anyTreatment = $request->anyTreatment;
                    $smoking = $request->smoking;
                    $alcoholic = $request->alcoholic;
                    $feverAndChill = $request->feverAndChill;
                    $diabetes = $request->diabetes;
                    $bloodPressure = $request->bloodPressure;
                    $heartDiseases = $request->heartDiseases;
                    $bleedingDisorder = $request->bleedingDisorder;
                    $recentInfection = $request->recentInfection;
                    $anyRegFlags = $request->anyRegFlags;
                    $AnyYellowFlags = $request->AnyYellowFlags;
                    $limitations = $request->limitations;
                    $pastSurgery = $request->pastSurgery;
                    $allergies = $request->allergies;
                    $osteoporotic = $request->osteoporotic;
                    $anyImplants = $request->anyImplants;
                    $hereditaryDisease = $request->hereditaryDisease;
                    $remark = $request->remark;
                    if(!empty($appId) && !empty($patientId) && !empty($causeOfProblem) && !empty($medicalProblem) && !empty($anySurgery) && !empty($anyTreatment)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $examdata = array();
                            $examdata['appointment_id'] = $appId;
                            $examdata['visit_id'] = '';
                            $examdata['patient_id'] = $patientId;
                            $examdata['therapist_id'] = '';
                            $examdata['cause_of_problem'] = $causeOfProblem;
                            $examdata['medical_problem'] = $medicalProblem;
                            $examdata['any_surgery'] = $anySurgery;
                            $examdata['any_treatment'] = $anyTreatment;
                            $examdata['smoking'] = $smoking;
                            $examdata['alcoholic'] = $alcoholic;
                            $examdata['fever_and_chill'] = $feverAndChill;
                            $examdata['diabetes'] = $diabetes;
                            $examdata['blood_pressure'] = $bloodPressure;
                            $examdata['heart_diseases'] = $heartDiseases;
                            $examdata['bleeding_disorder'] = $bleedingDisorder;
                            $examdata['recent_infection'] = $recentInfection;
                            $examdata['any_reg_flags'] = $anyRegFlags;
                            $examdata['Any_yellow_flags'] = $AnyYellowFlags;
                            $examdata['limitations'] = $limitations;
                            $examdata['past_surgery'] = $pastSurgery;
                            $examdata['allergies'] = $allergies;
                            $examdata['osteoporotic'] = $osteoporotic;
                            $examdata['any_implants'] = $anyImplants;
                            $examdata['hereditary_disease'] = $hereditaryDisease;
                            $examdata['remark'] = $remark;
                            $examdata['created_at'] = date('Y-m-d H:i:s');
                            DB::table('exam_history')->insert($examdata);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function HistoryExams(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('exam_history')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','patient_id','cause_of_problem','medical_problem','any_surgery','any_treatment','smoking','alcoholic','fever_and_chill','diabetes','blood_pressure','heart_diseases','bleeding_disorder','recent_infection','any_reg_flags','Any_yellow_flags','limitations','past_surgery','allergies','osteoporotic','any_implants','hereditary_disease','remark','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Exam History get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addPainExams(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                if(Input::has('visitId') && Input::has('patientId') && Input::has('userId') && Input::has('intensityOfPain') && Input::has('natureOfPain') && Input::has('onsetOfPain') && Input::has('pain') && Input::has('feelMorePainIn') && Input::has('aggravatingFactor') && Input::has('relievingFactor') ){
                    $visitId = $request->visitId;
                    $patientId = $request->patientId;
                    $therapistId = $request->userId;
                    $intensityOfPain = $request->intensityOfPain;
                    $natureOfPain = $request->natureOfPain;
                    $onsetOfPain = $request->onsetOfPain;
                    $pain = $request->pain;
                    $feelMorePainIn = $request->feelMorePainIn;
                    $aggravatingFactor = $request->aggravatingFactor;
                    $relievingFactor = $request->relievingFactor;
                    $aggravatingDesc = $request->aggravating_desc;
                    $relievingDesc = $request->relieving_desc;
                    if(!empty($visitId) && !empty($patientId) && !empty($therapistId) && (!empty($intensityOfPain) || !empty($natureOfPain) || !empty($onsetOfPain) || !empty($pain) || !empty($feelMorePainIn) || !empty($aggravatingFactor) || !empty($relievingFactor) || !empty($aggravatingDesc) || !empty($relievingDesc))){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $painExamData = array();
                                $painExamData['visit_id'] = $visitId;
                                $painExamData['patient_id'] = $patientId;
                                $painExamData['appointment_id'] = '';
                                $painExamData['therapist_id'] = $therapistId;
                                $painExamData['intensity_of_pain'] = $intensityOfPain;
                                $painExamData['nature_of_pain'] = $natureOfPain;
                                $painExamData['onset_of_pain'] = $onsetOfPain;
                                $painExamData['pain'] = $pain;
                                $painExamData['feel_more_pain_in'] = $feelMorePainIn;
                                $painExamData['aggravating_factor'] = $aggravatingFactor;
                                $painExamData['relieving_factor'] = $relievingFactor;
                                $painExamData['aggravating_desc'] = $aggravatingDesc;
                                $painExamData['relieving_desc'] = $relievingDesc;
                                $painExamData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('pain_exam')->insert($painExamData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                if(Input::has('patientId') && Input::has('intensityOfPain') && Input::has('natureOfPain') && Input::has('onsetOfPain') && Input::has('pain') && Input::has('feelMorePainIn') && Input::has('aggravatingFactor') && Input::has('relievingFactor') ){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $intensityOfPain = $request->intensityOfPain;
                    $natureOfPain = $request->natureOfPain;
                    $onsetOfPain = $request->onsetOfPain;
                    $pain = $request->pain;
                    $feelMorePainIn = $request->feelMorePainIn;
                    $aggravatingFactor = $request->aggravatingFactor;
                    $relievingFactor = $request->relievingFactor;
                    $aggravatingDesc = $request->aggravating_desc;
                    $relievingDesc = $request->relieving_desc;
                    if(!empty($appId) && !empty($patientId) && !empty($intensityOfPain) && (!empty($natureOfPain) || !empty($onsetOfPain) || !empty($pain) || !empty($feelMorePainIn) || !empty($aggravatingFactor) || !empty($relievingFactor) || !empty($aggravatingDesc) || !empty($relievingDesc))){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $painExamData = array();
                            $painExamData['visit_id'] = '';
                            $painExamData['patient_id'] = $patientId;
                            $painExamData['appointment_id'] = $appId;
                            $painExamData['therapist_id'] = '';
                            $painExamData['intensity_of_pain'] = $intensityOfPain;
                            $painExamData['nature_of_pain'] = $natureOfPain;
                            $painExamData['onset_of_pain'] = $onsetOfPain;
                            $painExamData['pain'] = $pain;
                            $painExamData['feel_more_pain_in'] = $feelMorePainIn;
                            $painExamData['aggravating_factor'] = $aggravatingFactor;
                            $painExamData['relieving_factor'] = $relievingFactor;
                            $painExamData['aggravating_desc'] = $aggravatingDesc;
                            $painExamData['relieving_desc'] = $relievingDesc;
                            $painExamData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('pain_exam')->insert($painExamData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function painExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('pain_exam')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','patient_id','intensity_of_pain','nature_of_pain','onset_of_pain','pain','feel_more_pain_in','aggravating_factor','relieving_factor','aggravating_desc','relieving_desc','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Pain Exam get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addPhysicalExam(Request $request){
        try{
            if(Input::has('visitId') && Input::has('patientId') && Input::has('userId') && Input::has('bloodPressure') && Input::has('temperature') && Input::has('heartRate') && Input::has('respiratoryRate') && Input::has('posture') && Input::has('gait') && Input::has('scarDescription') && Input::has('swelling') && Input::has('tightContractDeformity')){
                $visitId = $request->visitId;
                $patientId = $request->patientId;
                $therapistId = $request->userId;
                $bloodPressure = $request->bloodPressure;
                $temperature = $request->temperature;
                $heartRate = $request->heartRate;
                $respiratoryRate = $request->respiratoryRate;
                $posture = $request->posture;
                $gait = $request->gait;
                $scarDescription = $request->scarDescription;
                $swelling = $request->swelling;
                $tightContractDeformity = $request->tightContractDeformity;
                if(!empty($visitId) && !empty($patientId) && !empty($therapistId) && !empty($bloodPressure) && !empty($temperature) && !empty($heartRate) && !empty($respiratoryRate) && !empty($posture) && !empty($gait) && !empty($scarDescription) && !empty($swelling) && !empty($tightContractDeformity)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $physicalExamData = array();
                            $physicalExamData['visit_id'] = $visitId;
                            $physicalExamData['patient_id'] = $patientId;
                            $physicalExamData['therapist_id'] = $therapistId;
                            $physicalExamData['blood_pressure'] = $bloodPressure;
                            $physicalExamData['temperature'] = $temperature;
                            $physicalExamData['heart_rate'] = $heartRate;
                            $physicalExamData['respiratory_rate'] = $respiratoryRate;
                            $physicalExamData['posture'] = $posture;
                            $physicalExamData['gait'] = $gait;
                            $physicalExamData['scar_description'] = $scarDescription;
                            $physicalExamData['swelling'] = $swelling;
                            $physicalExamData['tight_contract_deformity'] = $tightContractDeformity;
                            $physicalExamData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('physical_exam')->insert($physicalExamData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function physicalExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('physical_exam')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','patient_id','blood_pressure','temperature','heart_rate','respiratory_rate','posture','gait','scar_description','swelling','tight_contract_deformity','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Physical Exam get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addBodyChart(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('front') && Input::has('back') && Input::has('right') && Input::has('left')){
                    $visitId = $request->visitId;
                    $patientId = $request->patientId;
                    $therapistId = $request->userId;
                    $front = $request->front;
                    $back = $request->back;
                    $right = $request->right;
                    $left = $request->left;
                    if(!empty($visitId) && !empty($patientId) && !empty($therapistId)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $currentDate = date('Y-m-d');
                                $entryCheck = DB::table('body_chart')->where('patient_id',$patientId)->where('visit_id',$visitId)->where('therapist_id',$therapistId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $currentDate)->orderBy('id','DESC')->first();
                                if($entryCheck){
                                    $bodychartdata = array();
                                    $basePath = 'upload/body_chart/';
                                    if($front){
                                        $frontImgName = $this->uploadImage($front, $basePath);
                                        if($frontImgName){
                                            $bodychartdata['front_chart'] = $frontImgName;
                                        }else{
                                            $bodychartdata['front_chart'] = '';
                                        }
                                    }

                                    if($back){
                                        $backImgName = $this->uploadImage($back, $basePath);
                                        if($backImgName){
                                            $bodychartdata['back_chart'] = $backImgName;
                                        }else{
                                            $bodychartdata['back_chart'] = '';
                                        }
                                    }

                                    if($right){
                                        $rightImgName = $this->uploadImage($right, $basePath);
                                        if($rightImgName){
                                            $bodychartdata['right_chart'] = $rightImgName;
                                        }else{
                                            $bodychartdata['right_chart'] = '';
                                        }
                                    }

                                    if($left){
                                        $leftImgName = $this->uploadImage($left, $basePath);
                                        if($leftImgName){
                                            $bodychartdata['left_chart'] = $leftImgName;
                                        }else{
                                            $bodychartdata['left_chart'] = '';
                                        }
                                    }
                                    DB::table('body_chart')->where('id',$entryCheck->id)->update($bodychartdata);
                                }else{
                                    $bodychartdata = array();
                                    $bodychartdata['visit_id'] = $visitId;
                                    $bodychartdata['appointment_id'] = '';
                                    $bodychartdata['therapist_id'] = $therapistId;
                                    $bodychartdata['patient_id'] = $patientId;
                                    $basePath = 'upload/body_chart/';
                                    if($front){
                                        $frontImgName = $this->uploadImage($front, $basePath);
                                        if($frontImgName){
                                            $bodychartdata['front_chart'] = $frontImgName;
                                        }else{
                                            $bodychartdata['front_chart'] = '';
                                        }
                                    }

                                    if($back){
                                        $backImgName = $this->uploadImage($back, $basePath);
                                        if($backImgName){
                                            $bodychartdata['back_chart'] = $backImgName;
                                        }else{
                                            $bodychartdata['back_chart'] = '';
                                        }
                                    }

                                    if($right){
                                        $rightImgName = $this->uploadImage($right, $basePath);
                                        if($rightImgName){
                                            $bodychartdata['right_chart'] = $rightImgName;
                                        }else{
                                            $bodychartdata['right_chart'] = '';
                                        }
                                    }

                                    if($left){
                                        $leftImgName = $this->uploadImage($left, $basePath);
                                        if($leftImgName){
                                            $bodychartdata['left_chart'] = $leftImgName;
                                        }else{
                                            $bodychartdata['left_chart'] = '';
                                        }
                                    }
                                    $bodychartdata['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('body_chart')->insert($bodychartdata);
                                }
                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('front') && Input::has('back') && Input::has('right') && Input::has('left')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $front = $request->front;
                    $back = $request->back;
                    $right = $request->right;
                    $left = $request->left;
                    if(!empty($patientId)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $currentDate = date('Y-m-d');
                            $entryCheck = DB::table('body_chart')->where('patient_id',$patientId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), '<=', $currentDate)->orderBy('id','DESC')->first();
                            if($entryCheck){
                                $bodychartdata = array();
                                $basePath = 'upload/body_chart/';
                                if($front){
                                    $frontImgName = $this->uploadImage($front, $basePath);
                                    if($frontImgName){
                                        $bodychartdata['front_chart'] = $frontImgName;
                                    }else{
                                        $bodychartdata['front_chart'] = '';
                                    }
                                }

                                if($back){
                                    $backImgName = $this->uploadImage($back, $basePath);
                                    if($backImgName){
                                        $bodychartdata['back_chart'] = $backImgName;
                                    }else{
                                        $bodychartdata['back_chart'] = '';
                                    }
                                }

                                if($right){
                                    $rightImgName = $this->uploadImage($right, $basePath);
                                    if($rightImgName){
                                        $bodychartdata['right_chart'] = $rightImgName;
                                    }else{
                                        $bodychartdata['right_chart'] = '';
                                    }
                                }

                                if($left){
                                    $leftImgName = $this->uploadImage($left, $basePath);
                                    if($leftImgName){
                                        $bodychartdata['left_chart'] = $leftImgName;
                                    }else{
                                        $bodychartdata['left_chart'] = '';
                                    }
                                }
                                DB::table('body_chart')->where('id',$entryCheck->id)->update($bodychartdata);
                            }else{
                                $bodychartdata = array();
                                $bodychartdata['visit_id'] = '';
                                $bodychartdata['appointment_id'] = $appId;
                                $bodychartdata['therapist_id'] = '';
                                $bodychartdata['patient_id'] = $patientId;
                                $basePath = 'upload/body_chart/';
                                if($front){
                                    $frontImgName = $this->uploadImage($front, $basePath);
                                    if($frontImgName){
                                        $bodychartdata['front_chart'] = $frontImgName;
                                    }else{
                                        $bodychartdata['front_chart'] = '';
                                    }
                                }

                                if($back){
                                    $backImgName = $this->uploadImage($back, $basePath);
                                    if($backImgName){
                                        $bodychartdata['back_chart'] = $backImgName;
                                    }else{
                                        $bodychartdata['back_chart'] = '';
                                    }
                                }

                                if($right){
                                    $rightImgName = $this->uploadImage($right, $basePath);
                                    if($rightImgName){
                                        $bodychartdata['right_chart'] = $rightImgName;
                                    }else{
                                        $bodychartdata['right_chart'] = '';
                                    }
                                }

                                if($left){
                                    $leftImgName = $this->uploadImage($left, $basePath);
                                    if($leftImgName){
                                        $bodychartdata['left_chart'] = $leftImgName;
                                    }else{
                                        $bodychartdata['left_chart'] = '';
                                    }
                                }
                                $bodychartdata['created_at'] = date('Y-m-d H:i:s');
                                DB::table('body_chart')->insert($bodychartdata);
                            }
                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allBodyChart(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('body_chart')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','patient_id','front_chart','back_chart','right_chart','left_chart','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                if($value->front_chart){
                                    $value->front_chart = API_BODY_CHART_IMG.$value->front_chart;
                                }
                                if($value->back_chart){
                                    $value->back_chart = API_BODY_CHART_IMG.$value->back_chart;
                                }
                                if($value->right_chart){
                                    $value->right_chart = API_BODY_CHART_IMG.$value->right_chart;
                                }
                                if($value->left_chart){
                                    $value->left_chart = API_BODY_CHART_IMG.$value->left_chart;
                                }
                                
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Body Chart get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function uploadImage($image, $basePath){
        if($image){
            $img_pic = $image;
            $img_pic = str_replace('data:image/png;base64,', '', $img_pic); 
            $img_pic = str_replace(' ', '+', $img_pic);
            $imageName = $this->randomValue(10).'.'.'png';
            $sendFile = File::put($basePath.$imageName, base64_decode($img_pic));
            return $imageName;
        }
    }

    public function addDiagnosis(Request $request){
        try{
            if(Input::has('visitId') && Input::has('patientId') && Input::has('userId') && Input::has('physiotherapeuticDiagnosis') && Input::has('medicalDiagnosis')){
                $visitId = $request->visitId;
                $patientId = $request->patientId;
                $therapistId = $request->userId;
                $physiotherapeuticDiagnosis = $request->physiotherapeuticDiagnosis;
                $medicalDiagnosis = $request->medicalDiagnosis;
                if(!empty($visitId) && !empty($patientId) && !empty($therapistId) && !empty($physiotherapeuticDiagnosis) && !empty($medicalDiagnosis)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $diagnosisdata = array();
                            $diagnosisdata['visit_id'] = $visitId;
                            $diagnosisdata['therapist_id'] = $therapistId;
                            $diagnosisdata['patient_id'] = $patientId;
                            $diagnosisdata['physiotherapeutic_diagnosis'] = $physiotherapeuticDiagnosis;
                            $diagnosisdata['medical_diagnosis'] = $medicalDiagnosis;
                            $diagnosisdata['created_at'] = date('Y-m-d H:i:s');
                            DB::table('diagnosis')->insert($diagnosisdata);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allDiagnosis(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('diagnosis')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','physiotherapeutic_diagnosis','medical_diagnosis','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Diagnosis data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addNotes(Request $request){
        try{
            if(Input::has('visitId') && Input::has('patientId') &&Input::has('userId') && Input::has('caseNote') && Input::has('progressNote') && Input::has('remarkNote')){
                $visitId = $request->visitId;
                $patientId = $request->patientId;
                $therapistId = $request->userId;
                $caseNote = $request->caseNote;
                $progressNote = $request->progressNote;
                $remarkNote = $request->remarkNote;
                if(!empty($visitId) && !empty($patientId) && !empty($therapistId) && (!empty($caseNote) || !empty($progressNote) || !empty($remarkNote))){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $notedata = array();
                            $notedata['visit_id'] = $visitId;
                            $notedata['patient_id'] = $patientId;
                            $notedata['therapist_id'] = $therapistId;
                            $notedata['case_note'] = $caseNote;
                            $notedata['progress_note'] = $progressNote;
                            $notedata['remark'] = $remarkNote;
                            $notedata['created_date'] = date('Y-m-d');
                            $notedata['created_at'] = date('Y-m-d H:i:s');
                            DB::table('treatment_note')->insert($notedata);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allNotes(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('treatment_note')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','case_note','progress_note','remark','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Notes get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addSensoryExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('userId') && Input::has('visitId') && Input::has('neckFlxExt') && Input::has('neckLatFlx') && Input::has('shoulderEle') && Input::has('shoulderABD') && Input::has('elbowFlx') && Input::has('elbowExt') && Input::has('thumbExt') && Input::has('abduction') && Input::has('hipFlexion') && Input::has('kneeExt') && Input::has('ankleDorsFlx') && Input::has('toeExt') && Input::has('kneeFlxAnklePlant') && Input::has('kneeFlx') && Input::has('rectalSphTone') && Input::has('backOfHead') && Input::has('neck') && Input::has('antShoulder') && Input::has('thumb') && Input::has('backOfArm') && Input::has('ring') && Input::has('medialArm') && Input::has('interspace') && Input::has('interspace5') && Input::has('xiphoid') && Input::has('umbilicus') && Input::has('pupis') && Input::has('genitars') && Input::has('medialThigh') && Input::has('anteriorThigh') && Input::has('greatToe') && Input::has('dersumOfFeet') && Input::has('lateralFoot') && Input::has('posteromedicalThigh') && Input::has('perianalArea') && Input::has('biceps') && Input::has('brachioradialis') && Input::has('triceps') && Input::has('fingerFlx') && Input::has('quadriceps') && Input::has('achilles')){
                $patientId = $request->patientId;
                $therapistId = $request->userId;
                $visitId = $request->visitId;
                $neckFlxExt = $request->neckFlxExt;
                $neckLatFlx = $request->neckLatFlx;
                $shoulderEle = $request->shoulderEle;
                $shoulderABD = $request->shoulderABD;
                $elbowFlx = $request->elbowFlx;
                $elbowExt = $request->elbowExt;
                $thumbExt = $request->thumbExt;
                $abduction = $request->abduction;
                $hipFlexion = $request->hipFlexion;
                $kneeExt = $request->kneeExt;
                $ankleDorsFlx = $request->ankleDorsFlx;
                $toeExt = $request->toeExt;
                $kneeFlxAnklePlant = $request->kneeFlxAnklePlant;
                $kneeFlx = $request->kneeFlx;
                $rectalSphTone = $request->rectalSphTone;
                $backOfHead = $request->backOfHead;
                $neck = $request->neck;
                $antShoulder = $request->antShoulder;
                $thumb = $request->thumb;
                $backOfArm = $request->backOfArm;
                $ring = $request->ring;
                $medialArm = $request->medialArm;
                $interspace = $request->interspace;
                $interspace5 = $request->interspace5;
                $xiphoid = $request->xiphoid;
                $umbilicus = $request->umbilicus;
                $pupis = $request->pupis;
                $genitars = $request->genitars;
                $medialThigh = $request->medialThigh;
                $anteriorThigh = $request->anteriorThigh;
                $greatToe = $request->greatToe;
                $dersumOfFeet = $request->dersumOfFeet;
                $lateralFoot = $request->lateralFoot;
                $posteromedicalThigh = $request->posteromedicalThigh;
                $perianalArea = $request->perianalArea;
                $biceps = $request->biceps;
                $brachioradialis = $request->brachioradialis;
                $triceps = $request->triceps;
                $fingerFlx = $request->fingerFlx;
                $quadriceps = $request->quadriceps;
                $achilles = $request->achilles;
                if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($neckFlxExt)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $sensoryData = array();
                            $sensoryData['visit_id'] = $visitId;
                            $sensoryData['patient_id'] = $patientId;
                            $sensoryData['therapist_id'] = $therapistId;
                            $sensoryData['neckFlxExt'] = $neckFlxExt;
                            $sensoryData['neckLatFlx'] = $neckLatFlx;
                            $sensoryData['shoulderEle'] = $shoulderEle;
                            $sensoryData['shoulderABD'] = $shoulderABD;
                            $sensoryData['elbowFlx'] = $elbowFlx;
                            $sensoryData['elbowExt'] = $elbowExt;
                            $sensoryData['thumbExt'] = $thumbExt;
                            $sensoryData['abduction'] = $abduction;
                            $sensoryData['hipFlexion'] = $hipFlexion;
                            $sensoryData['kneeExt'] = $kneeExt;
                            $sensoryData['ankleDorsFlx'] = $ankleDorsFlx;
                            $sensoryData['toeExt'] = $toeExt;
                            $sensoryData['kneeFlxAnklePlant'] = $kneeFlxAnklePlant;
                            $sensoryData['kneeFlx'] = $kneeFlx;
                            $sensoryData['rectalSphTone'] = $rectalSphTone;
                            $sensoryData['backOfHead'] = $backOfHead;
                            $sensoryData['neck'] = $neck;
                            $sensoryData['antShoulder'] = $antShoulder;
                            $sensoryData['thumb'] = $thumb;
                            $sensoryData['backOfArm'] = $backOfArm;
                            $sensoryData['ring'] = $ring;
                            $sensoryData['medialArm'] = $medialArm;
                            $sensoryData['interspace'] = $interspace;
                            $sensoryData['interspace5'] = $interspace5;
                            $sensoryData['xiphoid'] = $xiphoid;
                            $sensoryData['umbilicus'] = $umbilicus;
                            $sensoryData['pupis'] = $pupis;
                            $sensoryData['genitars'] = $genitars;
                            $sensoryData['medialThigh'] = $medialThigh;
                            $sensoryData['anteriorThigh'] = $anteriorThigh;
                            $sensoryData['greatToe'] = $greatToe;
                            $sensoryData['dersumOfFeet'] = $dersumOfFeet;
                            $sensoryData['lateralFoot'] = $lateralFoot;
                            $sensoryData['posteromedicalThigh'] = $posteromedicalThigh;
                            $sensoryData['perianalArea'] = $perianalArea;
                            $sensoryData['biceps'] = $biceps;
                            $sensoryData['brachioradialis'] = $brachioradialis;
                            $sensoryData['triceps'] = $triceps;
                            $sensoryData['fingerFlx'] = $fingerFlx;
                            $sensoryData['quadriceps'] = $quadriceps;
                            $sensoryData['achilles'] = $achilles;
                            $sensoryData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('sensory_exam')->insert($sensoryData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';

                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allSensoryExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('sensory_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','neckFlxExt','neckLatFlx','shoulderEle','shoulderABD','elbowFlx','elbowExt','thumbExt','abduction','hipFlexion','kneeExt','ankleDorsFlx','toeExt','kneeFlxAnklePlant','kneeFlx','rectalSphTone','backOfHead','neck','antShoulder','thumb','backOfArm','ring','medialArm','interspace','interspace5','xiphoid','umbilicus','pupis','genitars','medialThigh','anteriorThigh','greatToe','dersumOfFeet','lateralFoot','posteromedicalThigh','perianalArea','biceps','brachioradialis','triceps','fingerFlx','quadriceps','achilles','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Sensory Exam get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addSpecialExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('visitId') && Input::has('userId') && Input::has('specialTest') && Input::has('description')){
                $patientId = $request->patientId;
                $visitId = $request->visitId;
                $therapistId = $request->userId;
                $specialTest = $request->specialTest;
                $description = $request->description;
                if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($specialTest) && !empty($description)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $specialData = array();
                            $specialData['visit_id'] = $visitId;
                            $specialData['patient_id'] = $patientId;
                            $specialData['therapist_id'] = $therapistId;
                            $specialData['special_test'] = $specialTest;
                            $specialData['description'] = $description;
                            $specialData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('special_exam')->insert($specialData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allSpecialExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('special_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','special_test','description','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Special Exams get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addNDTNDPExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('visitId') && Input::has('userId') && Input::has('neurUlnarLeft') && Input::has('neurUlnarRight') && Input::has('neurRadialLeft') && Input::has('neurRadialRight') && Input::has('neurMedianLeft') && Input::has('neurMedianRight') && Input::has('neurMusculLeft') && Input::has('neurMusculRight') && Input::has('neurSciaticLeft') && Input::has('neurSciaticRight') && Input::has('neurTibialLeft') && Input::has('neurTibialRight') && Input::has('neurCommanLeft') && Input::has('neurCommanRight') && Input::has('neurFemoralLeft') && Input::has('neurFemoralRight') && Input::has('neurLatCutaLeft') && Input::has('neurLatCutaRight') && Input::has('neurObturLeft') && Input::has('neurObturRight') && Input::has('neurSuralLeft') && Input::has('neurSuralRight') && Input::has('neurSaphLeft') && Input::has('neurSaphRight') && Input::has('tissUlnarLeft') && Input::has('tissUlnarRight') && Input::has('tissRadialLeft') && Input::has('tissRadialRight') && Input::has('tissMedianLeft') && Input::has('tissMedianRight') && Input::has('tissSciaticLeft') && Input::has('tissSciaticRight') && Input::has('tissTibialLeft') && Input::has('tissTibialRight') && Input::has('tissPeronialLeft') && Input::has('tissPeronialRight') && Input::has('tissFemoralLeft') && Input::has('tissFemoralRight') && Input::has('tissSuralLeft') && Input::has('tissSuralRight') && Input::has('tissSaphenousLeft') && Input::has('tissSaphenousRight')){

                $patientId = $request->patientId;
                $visitId = $request->visitId;
                $therapistId = $request->userId;
                $neurUlnarLeft = $request->neurUlnarLeft;
                $neurUlnarRight = $request->neurUlnarRight;
                $neurRadialLeft = $request->neurRadialLeft;
                $neurRadialRight = $request->neurRadialRight;
                $neurMedianLeft = $request->neurMedianLeft;
                $neurMedianRight = $request->neurMedianRight;
                $neurMusculLeft = $request->neurMusculLeft;
                $neurMusculRight = $request->neurMusculRight;
                $neurSciaticLeft = $request->neurSciaticLeft;
                $neurSciaticRight = $request->neurSciaticRight;
                $neurTibialLeft = $request->neurTibialLeft;
                $neurTibialRight = $request->neurTibialRight;
                $neurCommanLeft = $request->neurCommanLeft;
                $neurCommanRight = $request->neurCommanRight;
                $neurFemoralLeft = $request->neurFemoralLeft;
                $neurFemoralRight = $request->neurFemoralRight;
                $neurLatCutaLeft = $request->neurLatCutaLeft;
                $neurLatCutaRight = $request->neurLatCutaRight;
                $neurObturLeft = $request->neurObturLeft;
                $neurObturRight = $request->neurObturRight;
                $neurSuralLeft = $request->neurSuralLeft;
                $neurSuralRight = $request->neurSuralRight;
                $neurSaphLeft = $request->neurSaphLeft;
                $neurSaphRight = $request->neurSaphRight;
                $tissUlnarLeft = $request->tissUlnarLeft;
                $tissUlnarRight = $request->tissUlnarRight;
                $tissRadialLeft = $request->tissRadialLeft;
                $tissRadialRight = $request->tissRadialRight;
                $tissMedianLeft = $request->tissMedianLeft;
                $tissMedianRight = $request->tissMedianRight;
                $tissSciaticLeft = $request->tissSciaticLeft;
                $tissSciaticRight = $request->tissSciaticRight;
                $tissTibialLeft = $request->tissTibialLeft;
                $tissTibialRight = $request->tissTibialRight;
                $tissPeronialLeft = $request->tissPeronialLeft;
                $tissPeronialRight = $request->tissPeronialRight;
                $tissFemoralLeft = $request->tissFemoralLeft;
                $tissFemoralRight = $request->tissFemoralRight;
                $tissSuralLeft = $request->tissSuralLeft;
                $tissSuralRight = $request->tissSuralRight;
                $tissSaphenousLeft = $request->tissSaphenousLeft;
                $tissSaphenousRight = $request->tissSaphenousRight;
                if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && (!empty($neurUlnarLeft) || !empty($neurUlnarRight) || !empty($neurRadialLeft) || !empty($neurRadialRight) || !empty($neurMedianLeft) || !empty($neurMedianRight) || !empty($neurMusculLeft) || !empty($neurMusculRight) || !empty($neurSciaticLeft) || !empty($neurSciaticRight) || !empty($neurTibialLeft) || !empty($neurTibialRight) || !empty($neurCommanLeft) || !empty($neurCommanRight) || !empty($neurFemoralLeft) || !empty($neurFemoralRight) || !empty($neurLatCutaLeft) || !empty($neurLatCutaRight) || !empty($neurObturLeft) || !empty($neurObturRight) || !empty($neurSuralLeft) || !empty($neurSuralRight) || !empty($neurSaphLeft) || !empty($neurSaphRight) || !empty($tissUlnarLeft) || !empty($tissUlnarRight) || !empty($tissRadialLeft) || !empty($tissRadialRight) || !empty($tissMedianLeft) || !empty($tissMedianRight) || !empty($tissSciaticLeft) || !empty($tissSciaticRight) || !empty($tissTibialLeft) || !empty($tissTibialRight) || !empty($tissPeronialLeft) || !empty($tissPeronialRight) || !empty($tissFemoralLeft) || !empty($tissFemoralRight) || !empty($tissSuralLeft) || !empty($tissSuralRight) || !empty($tissSaphenousLeft) || !empty($tissSaphenousRight))){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $examData = array();
                        $examData['patient_id'] = $patientId;
                        $examData['visit_id'] = $visitId;
                        $examData['therapist_id'] = $therapistId;
                        $examData['neurUlnarLeft'] = $neurUlnarLeft;
                        $examData['neurUlnarRight'] = $neurUlnarRight;
                        $examData['neurRadialLeft'] = $neurRadialLeft;
                        $examData['neurRadialRight'] = $neurRadialRight;
                        $examData['neurMedianLeft'] = $neurMedianLeft;
                        $examData['neurMedianRight'] = $neurMedianRight;
                        $examData['neurMusculLeft'] = $neurMusculLeft;
                        $examData['neurMusculRight'] = $neurMusculRight;
                        $examData['neurSciaticLeft'] = $neurSciaticLeft;
                        $examData['neurSciaticRight'] = $neurSciaticRight;
                        $examData['neurTibialLeft'] = $neurTibialLeft;
                        $examData['neurTibialRight'] = $neurTibialRight;
                        $examData['neurCommanLeft'] = $neurCommanLeft;
                        $examData['neurCommanRight'] = $neurCommanRight;
                        $examData['neurFemoralLeft'] = $neurFemoralLeft;
                        $examData['neurFemoralRight'] = $neurFemoralRight;
                        $examData['neurLatCutaLeft'] = $neurLatCutaLeft;
                        $examData['neurLatCutaRight'] = $neurLatCutaRight;
                        $examData['neurObturLeft'] = $neurObturLeft;
                        $examData['neurObturRight'] = $neurObturRight;
                        $examData['neurSuralLeft'] = $neurSuralLeft;
                        $examData['neurSuralRight'] = $neurSuralRight;
                        $examData['neurSaphLeft'] = $neurSaphLeft;
                        $examData['neurSaphRight'] = $neurSaphRight;
                        $examData['tissUlnarLeft'] = $tissUlnarLeft;
                        $examData['tissUlnarRight'] = $tissUlnarRight;
                        $examData['tissRadialLeft'] = $tissRadialLeft;
                        $examData['tissRadialRight'] = $tissRadialRight;
                        $examData['tissMedianLeft'] = $tissMedianLeft;
                        $examData['tissMedianRight'] = $tissMedianRight;
                        $examData['tissSciaticLeft'] = $tissSciaticLeft;
                        $examData['tissSciaticRight'] = $tissSciaticRight;
                        $examData['tissTibialLeft'] = $tissTibialLeft;
                        $examData['tissTibialRight'] = $tissTibialRight;
                        $examData['tissPeronialLeft'] = $tissPeronialLeft;
                        $examData['tissPeronialRight'] = $tissPeronialRight;
                        $examData['tissFemoralLeft'] = $tissFemoralLeft;
                        $examData['tissFemoralRight'] = $tissFemoralRight;
                        $examData['tissSuralLeft'] = $tissSuralLeft;
                        $examData['tissSuralRight'] = $tissSuralRight;
                        $examData['tissSaphenousLeft'] = $tissSaphenousLeft;
                        $examData['tissSaphenousRight'] = $tissSaphenousRight;
                        $examData['created_at'] = date('Y-m-d H:i:s');
                        DB::table('ndt_ndp_exam')->insert($examData);
                        
                        $response['message'] = 'Successfully Saved!';
                        $response['status'] = '1';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allNDTNDPExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('ndt_ndp_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','neurUlnarLeft','neurUlnarRight','neurRadialLeft','neurRadialRight','neurMedianLeft','neurMedianRight','neurMusculLeft','neurMusculRight','neurSciaticLeft','neurSciaticRight','neurTibialLeft','neurTibialRight','neurCommanLeft','neurCommanRight','neurFemoralLeft','neurFemoralRight','neurLatCutaLeft','neurLatCutaRight','neurObturLeft','neurObturRight','neurSuralLeft','neurSuralRight','neurSaphLeft','neurSaphRight','tissUlnarLeft','tissUlnarRight','tissRadialLeft','tissRadialRight','tissMedianLeft','tissMedianRight','tissSciaticLeft','tissSciaticRight','tissTibialLeft','tissTibialRight','tissPeronialLeft','tissPeronialRight','tissFemoralLeft','tissFemoralRight','tissSuralLeft','tissSuralRight','tissSaphenousLeft','tissSaphenousRight','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All NDT NDP Exam get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allTreatmentGiven(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('treatment_given')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','comments','signature','reveiw','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                if($value->signature){
                                    $value->signature = API_SIGNATURE_IMG.$value->signature;
                                }
                                $getRatingValue = DB::table('daily_entry')->where('id',$value->visit_id)->first();
                                if($getRatingValue){
                                    $value->rating = $getRatingValue->rating;
                                }else{
                                    $value->rating = $getRatingValue->rating;
                                }
                                array_walk_recursive($value, function (&$item, $key){
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Given Treatment data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addNeurologicalExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('visitId') && Input::has('userId') && Input::has('eyeOpen') && Input::has('verbalResponse') && Input::has('motorResponse') && Input::has('fingerTime') && Input::has('fingerSpeed') && Input::has('fingerError') && Input::has('aternatingTime') && Input::has('aternatingSpeed') && Input::has('aternatingError') && Input::has('heelTime') && Input::has('heelSpeed') && Input::has('heelError') && Input::has('levelSurface') && Input::has('gaitSpeed') && Input::has('hrHeadTurns') && Input::has('vrHeadTurns') && Input::has('pivotTurn') && Input::has('overObstacle') && Input::has('aroundObstacle') && Input::has('steps') && Input::has('analyserLeft') && Input::has('analyserRight') && Input::has('bowels') && Input::has('bladder') && Input::has('grooming') && Input::has('toiletUse') && Input::has('feeding') && Input::has('transfer') && Input::has('mobility') && Input::has('dressing') && Input::has('stairs') && Input::has('bathing')){
                $patientId = $request->patientId;
                $visitId = $request->visitId;
                $therapistId = $request->userId;
                $eyeOpen = $request->eyeOpen;
                $verbalResponse = $request->verbalResponse;
                $motorResponse = $request->motorResponse;
                $fingerTime = $request->fingerTime;
                $fingerSpeed = $request->fingerSpeed;
                $fingerError = $request->fingerError;
                $aternatingTime = $request->aternatingTime;
                $aternatingSpeed = $request->aternatingSpeed;
                $aternatingError = $request->aternatingError;
                $heelTime = $request->heelTime;
                $heelSpeed = $request->heelSpeed;
                $heelError = $request->heelError;
                $levelSurface = $request->levelSurface;
                $gaitSpeed = $request->gaitSpeed;
                $hrHeadTurns = $request->hrHeadTurns;
                $vrHeadTurns = $request->vrHeadTurns;
                $pivotTurn = $request->pivotTurn;
                $overObstacle = $request->overObstacle;
                $aroundObstacle = $request->aroundObstacle;
                $steps = $request->steps;
                $analyserLeft = $request->analyserLeft;
                $analyserRight = $request->analyserRight;
                $bowels = $request->bowels;
                $bladder = $request->bladder;
                $grooming = $request->grooming;
                $toiletUse = $request->toiletUse;
                $feeding = $request->feeding;
                $transfer = $request->transfer;
                $mobility = $request->mobility;
                $dressing = $request->dressing;
                $stairs = $request->stairs;
                $bathing = $request->bathing;
                if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && ($eyeOpen != '')){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $neurodata = array();
                            $neurodata['patient_id'] = $patientId;
                            $neurodata['visit_id'] = $visitId;
                            $neurodata['therapist_id'] = $therapistId;
                            $neurodata['eyeOpen'] = $eyeOpen;
                            $neurodata['verbalResponse'] = $verbalResponse;
                            $neurodata['motorResponse'] = $motorResponse;
                            $neurodata['fingerTime'] = $fingerTime;
                            $neurodata['fingerSpeed'] = $fingerSpeed;
                            $neurodata['fingerError'] = $fingerError;
                            $neurodata['aternatingTime'] = $aternatingTime;
                            $neurodata['aternatingSpeed'] = $aternatingSpeed;
                            $neurodata['aternatingError'] = $aternatingError;
                            $neurodata['heelTime'] = $heelTime;
                            $neurodata['heelSpeed'] = $heelSpeed;
                            $neurodata['heelError'] = $heelError;
                            $neurodata['levelSurface'] = $levelSurface;
                            $neurodata['gaitSpeed'] = $gaitSpeed;
                            $neurodata['hrHeadTurns'] = $hrHeadTurns;
                            $neurodata['vrHeadTurns'] = $vrHeadTurns;
                            $neurodata['pivotTurn'] = $pivotTurn;
                            $neurodata['overObstacle'] = $overObstacle;
                            $neurodata['aroundObstacle'] = $aroundObstacle;
                            $neurodata['steps'] = $steps;
                            $neurodata['analyserLeft'] = $analyserLeft;
                            $neurodata['analyserRight'] = $analyserRight;
                            $neurodata['bowels'] = $bowels;
                            $neurodata['bladder'] = $bladder;
                            $neurodata['grooming'] = $grooming;
                            $neurodata['toiletUse'] = $toiletUse;
                            $neurodata['feeding'] = $feeding;
                            $neurodata['transfer'] = $transfer;
                            $neurodata['mobility'] = $mobility;
                            $neurodata['dressing'] = $dressing;
                            $neurodata['stairs'] = $stairs;
                            $neurodata['bathing'] = $bathing;
                            $neurodata['created_at'] = date('Y-m-d H:i:s');
                            DB::table('neurological_exam')->insert($neurodata);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allNeurologicalExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('neurological_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','eyeOpen','verbalResponse','motorResponse','fingerTime','fingerSpeed','fingerError','aternatingTime','aternatingSpeed','aternatingError','heelTime','heelSpeed','heelError','levelSurface','gaitSpeed','hrHeadTurns','vrHeadTurns','pivotTurn','overObstacle','aroundObstacle','steps','analyserLeft','analyserRight','bowels','bladder','grooming','toiletUse','feeding','transfer','mobility','dressing','stairs','bathing','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Neurological Exam get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addInvestigationExam(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('userId') && Input::has('typeOfInvestigation') && Input::has('description') && Input::has('document')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $typeOfInvestigation = $request->typeOfInvestigation;
                    $description = $request->description;
                    $document = $request->document;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($typeOfInvestigation) && !empty($description)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $invstdata = array();
                                $invstdata['patient_id'] = $patientId;
                                $invstdata['appointment_id'] = '';
                                $invstdata['visit_id'] = $visitId;
                                $invstdata['therapist_id'] = $therapistId;
                                $invstdata['typeOfInvestigation'] = $typeOfInvestigation;
                                $invstdata['description'] = $description;
                                $basePath = 'upload/investigation_doc/';
                                if($document){
                                    $docName = $this->uploadImage($document, $basePath);
                                    if($docName){
                                        $invstdata['document'] = $docName;
                                    }else{
                                        $invstdata['document'] = '';
                                    }
                                }
                                $invstdata['created_at'] = date('Y-m-d H:i:s');
                                DB::table('investigation_exam')->insert($invstdata);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('typeOfInvestigation') && Input::has('description') && Input::has('document')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $typeOfInvestigation = $request->typeOfInvestigation;
                    $description = $request->description;
                    $document = $request->document;
                    if(!empty($patientId) && !empty($typeOfInvestigation) && !empty($description)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $invstdata = array();
                            $invstdata['patient_id'] = $patientId;
                            $invstdata['appointment_id'] = $appId;
                            $invstdata['visit_id'] = '';
                            $invstdata['therapist_id'] = '';
                            $invstdata['typeOfInvestigation'] = $typeOfInvestigation;
                            $invstdata['description'] = $description;
                            $basePath = 'upload/investigation_doc/';
                            if($document){
                                $docName = $this->uploadImage($document, $basePath);
                                if($docName){
                                    $invstdata['document'] = $docName;
                                }else{
                                    $invstdata['document'] = '';
                                }
                            }
                            $invstdata['created_at'] = date('Y-m-d H:i:s');
                            DB::table('investigation_exam')->insert($invstdata);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allInvestigationExam(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('investigation_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','typeOfInvestigation','description','document','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                if(!empty($value->document)){
                                    $value->document = API_INVESTIGATION_DOC.$value->document;
                                }else{
                                    $value->document = API_DEFAULT_DOC;
                                }
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Investigation Exam get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlNeck(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('painInNeck') && Input::has('cervicogenic') && Input::has('personalCare') && Input::has('lifting') && Input::has('reading') && Input::has('concentration') && Input::has('routineWork') && Input::has('driving') && Input::has('sleepDisturbance') && Input::has('recreational') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $therapistId = $request->userId;
                    $visitId = $request->visitId;
                    $painInNeck = $request->painInNeck;
                    $cervicogenic = $request->cervicogenic;
                    $personalCare = $request->personalCare;
                    $lifting = $request->lifting;
                    $reading = $request->reading;
                    $concentration = $request->concentration;
                    $routineWork = $request->routineWork;
                    $driving = $request->driving;
                    $sleepDisturbance = $request->sleepDisturbance;
                    $recreational = $request->recreational;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($therapistId) && !empty($visitId) && !empty($painInNeck) && !empty($cervicogenic) && !empty($personalCare) && !empty($lifting) && !empty($reading) && !empty($concentration) && !empty($routineWork) && !empty($driving) && !empty($sleepDisturbance) && !empty($recreational) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['painInNeck'] = $painInNeck;
                                $addData['cervicogenic'] = $cervicogenic;
                                $addData['personalCare'] = $personalCare;
                                $addData['lifting'] = $lifting;
                                $addData['reading'] = $reading;
                                $addData['concentration'] = $concentration;
                                $addData['routineWork'] = $routineWork;
                                $addData['driving'] = $driving;
                                $addData['sleepDisturbance'] = $sleepDisturbance;
                                $addData['recreational'] = $recreational;
                                $addData['total_score'] = $totalScore;
                                $addData['get_score'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_neck')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('painInNeck') && Input::has('cervicogenic') && Input::has('personalCare') && Input::has('lifting') && Input::has('reading') && Input::has('concentration') && Input::has('routineWork') && Input::has('driving') && Input::has('sleepDisturbance') && Input::has('recreational') && Input::has('totalScore') && Input::has('getScore')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $painInNeck = $request->painInNeck;
                    $cervicogenic = $request->cervicogenic;
                    $personalCare = $request->personalCare;
                    $lifting = $request->lifting;
                    $reading = $request->reading;
                    $concentration = $request->concentration;
                    $routineWork = $request->routineWork;
                    $driving = $request->driving;
                    $sleepDisturbance = $request->sleepDisturbance;
                    $recreational = $request->recreational;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($painInNeck) && !empty($cervicogenic) && !empty($personalCare) && !empty($lifting) && !empty($reading) && !empty($concentration) && !empty($routineWork) && !empty($driving) && !empty($sleepDisturbance) && !empty($recreational) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['painInNeck'] = $painInNeck;
                            $addData['cervicogenic'] = $cervicogenic;
                            $addData['personalCare'] = $personalCare;
                            $addData['lifting'] = $lifting;
                            $addData['reading'] = $reading;
                            $addData['concentration'] = $concentration;
                            $addData['routineWork'] = $routineWork;
                            $addData['driving'] = $driving;
                            $addData['sleepDisturbance'] = $sleepDisturbance;
                            $addData['recreational'] = $recreational;
                            $addData['total_score'] = $totalScore;
                            $addData['get_score'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_neck')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlNeck(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_neck')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','painInNeck','cervicogenic','personalCare','lifting','reading','concentration','routineWork','driving','sleepDisturbance','recreational','total_score','get_score','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->get_score.'/'.$value->total_score;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Neck data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlHip(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('standing') && Input::has('inOutOfCar') && Input::has('upSlope') && Input::has('downSlope') && Input::has('climbing') && Input::has('downStairs') && Input::has('steppingUpDown') && Input::has('deepSquatting') && Input::has('bathTub') && Input::has('initialWalking') && Input::has('walking10Min') && Input::has('walking15Min') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $standing = $request->standing;
                    $inOutOfCar = $request->inOutOfCar;
                    $upSlope = $request->upSlope;
                    $downSlope = $request->downSlope;
                    $climbing = $request->climbing;
                    $downStairs = $request->downStairs;
                    $steppingUpDown = $request->steppingUpDown;
                    $deepSquatting = $request->deepSquatting;
                    $bathTub = $request->bathTub;
                    $initialWalking = $request->initialWalking;
                    $walking10Min = $request->walking10Min;
                    $walking15Min = $request->walking15Min;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($standing) && !empty($inOutOfCar) && !empty($upSlope) && !empty($downSlope) && !empty($climbing) && !empty($downStairs) && !empty($steppingUpDown) && !empty($deepSquatting) && !empty($bathTub) && !empty($initialWalking) && !empty($walking10Min) && !empty($walking15Min) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['appointment_id'] = '';
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['standing'] = $standing;
                                $addData['inOutOfCar'] = $inOutOfCar;
                                $addData['upSlope'] = $upSlope;
                                $addData['downSlope'] = $downSlope;
                                $addData['climbing'] = $climbing;
                                $addData['downStairs'] = $downStairs;
                                $addData['steppingUpDown'] = $steppingUpDown;
                                $addData['deepSquatting'] = $deepSquatting;
                                $addData['bathTub'] = $bathTub;
                                $addData['initialWalking'] = $initialWalking;
                                $addData['walking10Min'] = $walking10Min;
                                $addData['walking15Min'] = $walking15Min;
                                $addData['total_score'] = $totalScore;
                                $addData['get_score'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_hip')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('standing') && Input::has('inOutOfCar') && Input::has('upSlope') && Input::has('downSlope') && Input::has('climbing') && Input::has('downStairs') && Input::has('steppingUpDown') && Input::has('deepSquatting') && Input::has('bathTub') && Input::has('initialWalking') && Input::has('walking10Min') && Input::has('walking15Min') && Input::has('totalScore') && Input::has('getScore')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $standing = $request->standing;
                    $inOutOfCar = $request->inOutOfCar;
                    $upSlope = $request->upSlope;
                    $downSlope = $request->downSlope;
                    $climbing = $request->climbing;
                    $downStairs = $request->downStairs;
                    $steppingUpDown = $request->steppingUpDown;
                    $deepSquatting = $request->deepSquatting;
                    $bathTub = $request->bathTub;
                    $initialWalking = $request->initialWalking;
                    $walking10Min = $request->walking10Min;
                    $walking15Min = $request->walking15Min;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($standing) && !empty($inOutOfCar) && !empty($upSlope) && !empty($downSlope) && !empty($climbing) && !empty($downStairs) && !empty($steppingUpDown) && !empty($deepSquatting) && !empty($bathTub) && !empty($initialWalking) && !empty($walking10Min) && !empty($walking15Min) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['appointment_id'] = $appId;
                            $addData['visit_id'] = '';
                            $addData['therapist_id'] = '';
                            $addData['standing'] = $standing;
                            $addData['inOutOfCar'] = $inOutOfCar;
                            $addData['upSlope'] = $upSlope;
                            $addData['downSlope'] = $downSlope;
                            $addData['climbing'] = $climbing;
                            $addData['downStairs'] = $downStairs;
                            $addData['steppingUpDown'] = $steppingUpDown;
                            $addData['deepSquatting'] = $deepSquatting;
                            $addData['bathTub'] = $bathTub;
                            $addData['initialWalking'] = $initialWalking;
                            $addData['walking10Min'] = $walking10Min;
                            $addData['walking15Min'] = $walking15Min;
                            $addData['total_score'] = $totalScore;
                            $addData['get_score'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_hip')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlHip(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_hip')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','standing','inOutOfCar','upSlope','downSlope','climbing','downStairs','steppingUpDown','deepSquatting','bathTub','initialWalking','walking10Min','walking15Min','total_score','get_score','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->get_score.'/'.$value->total_score;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Hip data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlKnee(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                if(Input::has('patientId') && Input::has('userId') && Input::has('walking5Min') && Input::has('walking10Min') && Input::has('walking15Min') && Input::has('climbing') && Input::has('downStair') && Input::has('sleepingDisturbance') && Input::has('resting') && Input::has('standing') && Input::has('morningStiffness') && Input::has('stiffnessDuringDay') && Input::has('risingFromChair') && Input::has('risingFloor') && Input::has('bendingFloor') && Input::has('walkingSurface') && Input::has('gettingInOut') && Input::has('shopping') && Input::has('puttingsSocks') && Input::has('takingSocks') && Input::has('gettingBed') && Input::has('comingBed') && Input::has('bathTub') && Input::has('sitting') && Input::has('sittingOnRising') && Input::has('squatting') && Input::has('lightDomestic') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $walking5Min = $request->walking5Min;
                    $walking10Min = $request->walking10Min;
                    $walking15Min = $request->walking15Min;
                    $climbing = $request->climbing;
                    $downStair = $request->downStair;
                    $sleepingDisturbance = $request->sleepingDisturbance;
                    $resting = $request->resting;
                    $standing = $request->standing;
                    $morningStiffness = $request->morningStiffness;
                    $stiffnessDuringDay = $request->stiffnessDuringDay;
                    $risingFromChair = $request->risingFromChair;
                    $risingFloor = $request->risingFloor;
                    $bendingFloor = $request->bendingFloor;
                    $walkingSurface = $request->walkingSurface;
                    $gettingInOut = $request->gettingInOut;
                    $shopping = $request->shopping;
                    $puttingsSocks = $request->puttingsSocks;
                    $takingSocks = $request->takingSocks;
                    $gettingBed = $request->gettingBed;
                    $comingBed = $request->comingBed;
                    $bathTub = $request->bathTub;
                    $sitting = $request->sitting;
                    $sittingOnRising = $request->sittingOnRising;
                    $squatting = $request->squatting;
                    $lightDomestic = $request->lightDomestic;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($walking5Min) && !empty($walking10Min) && !empty($walking15Min) && !empty($climbing) && !empty($downStair) && !empty($sleepingDisturbance) && !empty($resting) && !empty($standing) && !empty($morningStiffness) && !empty($stiffnessDuringDay) && !empty($risingFromChair) && !empty($risingFloor) && !empty($bendingFloor) && !empty($walkingSurface) && !empty($gettingInOut) && !empty($shopping) && !empty($puttingsSocks) && !empty($takingSocks) && !empty($gettingBed) && !empty($comingBed) && !empty($bathTub) && !empty($sitting) && !empty($sittingOnRising) && !empty($squatting) && !empty($lightDomestic) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['walking5Min'] = $walking5Min;
                                $addData['walking10Min'] = $walking10Min;
                                $addData['walking15Min'] = $walking15Min;
                                $addData['climbing'] = $climbing;
                                $addData['downStair'] = $downStair;
                                $addData['sleepingDisturbance'] = $sleepingDisturbance;
                                $addData['resting'] = $resting;
                                $addData['standing'] = $standing;
                                $addData['morningStiffness'] = $morningStiffness;
                                $addData['stiffnessDuringDay'] = $stiffnessDuringDay;
                                $addData['risingFromChair'] = $risingFromChair;
                                $addData['risingFloor'] = $risingFloor;
                                $addData['bendingFloor'] = $bendingFloor;
                                $addData['walkingSurface'] = $walkingSurface;
                                $addData['gettingInOut'] = $gettingInOut;
                                $addData['shopping'] = $shopping;
                                $addData['puttingsSocks'] = $puttingsSocks;
                                $addData['takingSocks'] = $takingSocks;
                                $addData['gettingBed'] = $gettingBed;
                                $addData['comingBed'] = $comingBed;
                                $addData['bathTub'] = $bathTub;
                                $addData['sitting'] = $sitting;
                                $addData['sittingOnRising'] = $sittingOnRising;
                                $addData['squatting'] = $squatting;
                                $addData['lightDomestic'] = $lightDomestic;
                                $addData['total_score'] = $totalScore;
                                $addData['get_score'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_knee')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                if(Input::has('patientId') && Input::has('walking5Min') && Input::has('walking10Min') && Input::has('walking15Min') && Input::has('climbing') && Input::has('downStair') && Input::has('sleepingDisturbance') && Input::has('resting') && Input::has('standing') && Input::has('morningStiffness') && Input::has('stiffnessDuringDay') && Input::has('risingFromChair') && Input::has('risingFloor') && Input::has('bendingFloor') && Input::has('walkingSurface') && Input::has('gettingInOut') && Input::has('shopping') && Input::has('puttingsSocks') && Input::has('takingSocks') && Input::has('gettingBed') && Input::has('comingBed') && Input::has('bathTub') && Input::has('sitting') && Input::has('sittingOnRising') && Input::has('squatting') && Input::has('lightDomestic') && Input::has('totalScore') && Input::has('getScore')){
                    $appId = $request->appId;
                    $patientId = $request->patientId;
                    $walking5Min = $request->walking5Min;
                    $walking10Min = $request->walking10Min;
                    $walking15Min = $request->walking15Min;
                    $climbing = $request->climbing;
                    $downStair = $request->downStair;
                    $sleepingDisturbance = $request->sleepingDisturbance;
                    $resting = $request->resting;
                    $standing = $request->standing;
                    $morningStiffness = $request->morningStiffness;
                    $stiffnessDuringDay = $request->stiffnessDuringDay;
                    $risingFromChair = $request->risingFromChair;
                    $risingFloor = $request->risingFloor;
                    $bendingFloor = $request->bendingFloor;
                    $walkingSurface = $request->walkingSurface;
                    $gettingInOut = $request->gettingInOut;
                    $shopping = $request->shopping;
                    $puttingsSocks = $request->puttingsSocks;
                    $takingSocks = $request->takingSocks;
                    $gettingBed = $request->gettingBed;
                    $comingBed = $request->comingBed;
                    $bathTub = $request->bathTub;
                    $sitting = $request->sitting;
                    $sittingOnRising = $request->sittingOnRising;
                    $squatting = $request->squatting;
                    $lightDomestic = $request->lightDomestic;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($walking5Min) && !empty($walking10Min) && !empty($walking15Min) && !empty($climbing) && !empty($downStair) && !empty($sleepingDisturbance) && !empty($resting) && !empty($standing) && !empty($morningStiffness) && !empty($stiffnessDuringDay) && !empty($risingFromChair) && !empty($risingFloor) && !empty($bendingFloor) && !empty($walkingSurface) && !empty($gettingInOut) && !empty($shopping) && !empty($puttingsSocks) && !empty($takingSocks) && !empty($gettingBed) && !empty($comingBed) && !empty($bathTub) && !empty($sitting) && !empty($sittingOnRising) && !empty($squatting) && !empty($lightDomestic) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['walking5Min'] = $walking5Min;
                            $addData['walking10Min'] = $walking10Min;
                            $addData['walking15Min'] = $walking15Min;
                            $addData['climbing'] = $climbing;
                            $addData['downStair'] = $downStair;
                            $addData['sleepingDisturbance'] = $sleepingDisturbance;
                            $addData['resting'] = $resting;
                            $addData['standing'] = $standing;
                            $addData['morningStiffness'] = $morningStiffness;
                            $addData['stiffnessDuringDay'] = $stiffnessDuringDay;
                            $addData['risingFromChair'] = $risingFromChair;
                            $addData['risingFloor'] = $risingFloor;
                            $addData['bendingFloor'] = $bendingFloor;
                            $addData['walkingSurface'] = $walkingSurface;
                            $addData['gettingInOut'] = $gettingInOut;
                            $addData['shopping'] = $shopping;
                            $addData['puttingsSocks'] = $puttingsSocks;
                            $addData['takingSocks'] = $takingSocks;
                            $addData['gettingBed'] = $gettingBed;
                            $addData['comingBed'] = $comingBed;
                            $addData['bathTub'] = $bathTub;
                            $addData['sitting'] = $sitting;
                            $addData['sittingOnRising'] = $sittingOnRising;
                            $addData['squatting'] = $squatting;
                            $addData['lightDomestic'] = $lightDomestic;
                            $addData['total_score'] = $totalScore;
                            $addData['get_score'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_knee')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlKnee(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_knee')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','walking5Min','walking10Min','walking15Min','climbing','downStair','sleepingDisturbance','resting','standing','morningStiffness','stiffnessDuringDay','risingFromChair','risingFloor','bendingFloor','walkingSurface','gettingInOut','shopping','puttingsSocks','takingSocks','gettingBed','comingBed','bathTub','sitting','sittingOnRising','squatting','lightDomestic','total_score','get_score','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value) {
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->get_score.'/'.$value->total_score;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Knee data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlElbow(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('backPocket') && Input::has('perinealCare') && Input::has('armpit') && Input::has('utensils') && Input::has('riseChair') && Input::has('hairCombing') && Input::has('armSide') && Input::has('dressUp') && Input::has('pullingObject') && Input::has('throwing') && Input::has('routineWork') && Input::has('sports') && Input::has('palmDown') && Input::has('palmUp') && Input::has('telephoneHand') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $backPocket = $request->backPocket;
                    $perinealCare = $request->perinealCare;
                    $armpit = $request->armpit;
                    $utensils = $request->utensils;
                    $riseChair = $request->riseChair;
                    $hairCombing = $request->hairCombing;
                    $armSide = $request->armSide;
                    $dressUp = $request->dressUp;
                    $pullingObject = $request->pullingObject;
                    $throwing = $request->throwing;
                    $routineWork = $request->routineWork;
                    $sports = $request->sports;
                    $palmDown = $request->palmDown;
                    $palmUp = $request->palmUp;
                    $telephoneHand = $request->telephoneHand;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($backPocket) && !empty($perinealCare) && !empty($armpit) && !empty($utensils) && !empty($riseChair) && !empty($hairCombing) && !empty($armSide) && !empty($dressUp) && !empty($pullingObject) && !empty($throwing) && !empty($routineWork) && !empty($sports) && !empty($palmDown) && !empty($palmUp) && !empty($telephoneHand) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['backPocket'] = $backPocket;
                                $addData['perinealCare'] = $perinealCare;
                                $addData['armpit'] = $armpit;
                                $addData['utensils'] = $utensils;
                                $addData['riseChair'] = $riseChair;
                                $addData['hairCombing'] = $hairCombing;
                                $addData['armSide'] = $armSide;
                                $addData['dressUp'] = $dressUp;
                                $addData['pullingObject'] = $pullingObject;
                                $addData['throwing'] = $throwing;
                                $addData['routineWork'] = $routineWork;
                                $addData['sports'] = $sports;
                                $addData['palmDown'] = $palmDown;
                                $addData['palmUp'] = $palmUp;
                                $addData['telephoneHand'] = $telephoneHand;
                                $addData['totalScore'] = $totalScore;
                                $addData['getScore'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_elbow')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('backPocket') && Input::has('perinealCare') && Input::has('armpit') && Input::has('utensils') && Input::has('riseChair') && Input::has('hairCombing') && Input::has('armSide') && Input::has('dressUp') && Input::has('pullingObject') && Input::has('throwing') && Input::has('routineWork') && Input::has('sports') && Input::has('palmDown') && Input::has('palmUp') && Input::has('telephoneHand') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $appId = $request->appId;
                    $backPocket = $request->backPocket;
                    $perinealCare = $request->perinealCare;
                    $armpit = $request->armpit;
                    $utensils = $request->utensils;
                    $riseChair = $request->riseChair;
                    $hairCombing = $request->hairCombing;
                    $armSide = $request->armSide;
                    $dressUp = $request->dressUp;
                    $pullingObject = $request->pullingObject;
                    $throwing = $request->throwing;
                    $routineWork = $request->routineWork;
                    $sports = $request->sports;
                    $palmDown = $request->palmDown;
                    $palmUp = $request->palmUp;
                    $telephoneHand = $request->telephoneHand;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($backPocket) && !empty($perinealCare) && !empty($armpit) && !empty($utensils) && !empty($riseChair) && !empty($hairCombing) && !empty($armSide) && !empty($dressUp) && !empty($pullingObject) && !empty($throwing) && !empty($routineWork) && !empty($sports) && !empty($palmDown) && !empty($palmUp) && !empty($telephoneHand) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['backPocket'] = $backPocket;
                            $addData['perinealCare'] = $perinealCare;
                            $addData['armpit'] = $armpit;
                            $addData['utensils'] = $utensils;
                            $addData['riseChair'] = $riseChair;
                            $addData['hairCombing'] = $hairCombing;
                            $addData['armSide'] = $armSide;
                            $addData['dressUp'] = $dressUp;
                            $addData['pullingObject'] = $pullingObject;
                            $addData['throwing'] = $throwing;
                            $addData['routineWork'] = $routineWork;
                            $addData['sports'] = $sports;
                            $addData['palmDown'] = $palmDown;
                            $addData['palmUp'] = $palmUp;
                            $addData['telephoneHand'] = $telephoneHand;
                            $addData['totalScore'] = $totalScore;
                            $addData['getScore'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_elbow')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlElbow(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_elbow')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','backPocket','perinealCare','armpit','utensils','riseChair','hairCombing','armSide','dressUp','pullingObject','throwing','routineWork','sports','palmDown','palmUp','telephoneHand','totalScore','getScore','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->getScore.'/'.$value->totalScore;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Elbow data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlShoulder(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('backPocket') && Input::has('perinealCare') && Input::has('armpit') && Input::has('utensils') && Input::has('combhair') && Input::has('handUse') && Input::has('armSide') && Input::has('dressUp') && Input::has('sleep') && Input::has('pulling') && Input::has('handOverhead') && Input::has('throwing') && Input::has('lifting') && Input::has('usualWork') && Input::has('usualSports') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $backPocket = $request->backPocket;
                    $perinealCare = $request->perinealCare;
                    $armpit = $request->armpit;
                    $utensils = $request->utensils;
                    $combhair = $request->combhair;
                    $handUse = $request->handUse;
                    $armSide = $request->armSide;
                    $dressUp = $request->dressUp;
                    $sleep = $request->sleep;
                    $pulling = $request->pulling;
                    $handOverhead = $request->handOverhead;
                    $throwing = $request->throwing;
                    $lifting = $request->lifting;
                    $usualWork = $request->usualWork;
                    $usualSports = $request->usualSports;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($backPocket) && !empty($perinealCare) && !empty($armpit) && !empty($utensils) && !empty($combhair) && !empty($handUse) && !empty($armSide) && !empty($dressUp) && !empty($sleep) && !empty($pulling) && !empty($handOverhead) && !empty($throwing) && !empty($lifting) && !empty($usualWork) && !empty($usualSports) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['backPocket'] = $backPocket;
                                $addData['perinealCare'] = $perinealCare;
                                $addData['armpit'] = $armpit;
                                $addData['utensils'] = $utensils;
                                $addData['combhair'] = $combhair;
                                $addData['handUse'] = $handUse;
                                $addData['armSide'] = $armSide;
                                $addData['dressUp'] = $dressUp;
                                $addData['sleep'] = $sleep;
                                $addData['pulling'] = $pulling;
                                $addData['handOverhead'] = $handOverhead;
                                $addData['throwing'] = $throwing;
                                $addData['lifting'] = $lifting;
                                $addData['usualWork'] = $usualWork;
                                $addData['usualSports'] = $usualSports;
                                $addData['totalScore'] = $totalScore;
                                $addData['getScore'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_shoulder')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('backPocket') && Input::has('perinealCare') && Input::has('armpit') && Input::has('utensils') && Input::has('combhair') && Input::has('handUse') && Input::has('armSide') && Input::has('dressUp') && Input::has('sleep') && Input::has('pulling') && Input::has('handOverhead') && Input::has('throwing') && Input::has('lifting') && Input::has('usualWork') && Input::has('usualSports') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $appId = $request->appId;
                    $backPocket = $request->backPocket;
                    $perinealCare = $request->perinealCare;
                    $armpit = $request->armpit;
                    $utensils = $request->utensils;
                    $combhair = $request->combhair;
                    $handUse = $request->handUse;
                    $armSide = $request->armSide;
                    $dressUp = $request->dressUp;
                    $sleep = $request->sleep;
                    $pulling = $request->pulling;
                    $handOverhead = $request->handOverhead;
                    $throwing = $request->throwing;
                    $lifting = $request->lifting;
                    $usualWork = $request->usualWork;
                    $usualSports = $request->usualSports;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($backPocket) && !empty($perinealCare) && !empty($armpit) && !empty($utensils) && !empty($combhair) && !empty($handUse) && !empty($armSide) && !empty($dressUp) && !empty($sleep) && !empty($pulling) && !empty($handOverhead) && !empty($throwing) && !empty($lifting) && !empty($usualWork) && !empty($usualSports) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['backPocket'] = $backPocket;
                            $addData['perinealCare'] = $perinealCare;
                            $addData['armpit'] = $armpit;
                            $addData['utensils'] = $utensils;
                            $addData['combhair'] = $combhair;
                            $addData['handUse'] = $handUse;
                            $addData['armSide'] = $armSide;
                            $addData['dressUp'] = $dressUp;
                            $addData['sleep'] = $sleep;
                            $addData['pulling'] = $pulling;
                            $addData['handOverhead'] = $handOverhead;
                            $addData['throwing'] = $throwing;
                            $addData['lifting'] = $lifting;
                            $addData['usualWork'] = $usualWork;
                            $addData['usualSports'] = $usualSports;
                            $addData['totalScore'] = $totalScore;
                            $addData['getScore'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_shoulder')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlShoulder(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_shoulder')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','backPocket','perinealCare','armpit','utensils','combhair','handUse','armSide','dressUp','sleep','pulling','handOverhead','throwing','lifting','usualWork','usualSports','totalScore','getScore','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->getScore.'/'.$value->totalScore;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Shoulder data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlWristAndHand(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('handWork') && Input::has('fingersMove') && Input::has('wristMove') && Input::has('strengthHand') && Input::has('sensation') && Input::has('doorKnob') && Input::has('pickCoin') && Input::has('holdGlass') && Input::has('turnKey') && Input::has('fryingPan') && Input::has('jar') && Input::has('hardBlouse') && Input::has('knifeFork') && Input::has('groceryBag') && Input::has('dishes') && Input::has('hair') && Input::has('knot') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $handWork = $request->handWork;
                    $fingersMove = $request->fingersMove;
                    $wristMove = $request->wristMove;
                    $strengthHand = $request->strengthHand;
                    $sensation = $request->sensation;
                    $doorKnob = $request->doorKnob;
                    $pickCoin = $request->pickCoin;
                    $holdGlass = $request->holdGlass;
                    $turnKey = $request->turnKey;
                    $fryingPan = $request->fryingPan;
                    $jar = $request->jar;
                    $hardBlouse = $request->hardBlouse;
                    $knifeFork = $request->knifeFork;
                    $groceryBag = $request->groceryBag;
                    $dishes = $request->dishes;
                    $hair = $request->hair;
                    $knot = $request->knot;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($handWork) && !empty($fingersMove) && !empty($wristMove) && !empty($strengthHand) && !empty($sensation) && !empty($doorKnob) && !empty($pickCoin) && !empty($holdGlass) && !empty($turnKey) && !empty($fryingPan) && !empty($jar) && !empty($hardBlouse) && !empty($knifeFork) && !empty($groceryBag) && !empty($dishes) && !empty($hair) && !empty($knot) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['handWork'] = $handWork;
                                $addData['fingersMove'] = $fingersMove;
                                $addData['wristMove'] = $wristMove;
                                $addData['strengthHand'] = $strengthHand;
                                $addData['sensation'] = $sensation;
                                $addData['doorKnob'] = $doorKnob;
                                $addData['pickCoin'] = $pickCoin;
                                $addData['holdGlass'] = $holdGlass;
                                $addData['turnKey'] = $turnKey;
                                $addData['fryingPan'] = $fryingPan;
                                $addData['jar'] = $jar;
                                $addData['hardBlouse'] = $hardBlouse;
                                $addData['knifeFork'] = $knifeFork;
                                $addData['groceryBag'] = $groceryBag;
                                $addData['dishes'] = $dishes;
                                $addData['hair'] = $hair;
                                $addData['knot'] = $knot;
                                $addData['totalScore'] = $totalScore;
                                $addData['getScore'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_wrist_and_hand')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('handWork') && Input::has('fingersMove') && Input::has('wristMove') && Input::has('strengthHand') && Input::has('sensation') && Input::has('doorKnob') && Input::has('pickCoin') && Input::has('holdGlass') && Input::has('turnKey') && Input::has('fryingPan') && Input::has('jar') && Input::has('hardBlouse') && Input::has('knifeFork') && Input::has('groceryBag') && Input::has('dishes') && Input::has('hair') && Input::has('knot') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $appId = $request->appId;
                    $handWork = $request->handWork;
                    $fingersMove = $request->fingersMove;
                    $wristMove = $request->wristMove;
                    $strengthHand = $request->strengthHand;
                    $sensation = $request->sensation;
                    $doorKnob = $request->doorKnob;
                    $pickCoin = $request->pickCoin;
                    $holdGlass = $request->holdGlass;
                    $turnKey = $request->turnKey;
                    $fryingPan = $request->fryingPan;
                    $jar = $request->jar;
                    $hardBlouse = $request->hardBlouse;
                    $knifeFork = $request->knifeFork;
                    $groceryBag = $request->groceryBag;
                    $dishes = $request->dishes;
                    $hair = $request->hair;
                    $knot = $request->knot;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($handWork) && !empty($fingersMove) && !empty($wristMove) && !empty($strengthHand) && !empty($sensation) && !empty($doorKnob) && !empty($pickCoin) && !empty($holdGlass) && !empty($turnKey) && !empty($fryingPan) && !empty($jar) && !empty($hardBlouse) && !empty($knifeFork) && !empty($groceryBag) && !empty($dishes) && !empty($hair) && !empty($knot) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['handWork'] = $handWork;
                            $addData['fingersMove'] = $fingersMove;
                            $addData['wristMove'] = $wristMove;
                            $addData['strengthHand'] = $strengthHand;
                            $addData['sensation'] = $sensation;
                            $addData['doorKnob'] = $doorKnob;
                            $addData['pickCoin'] = $pickCoin;
                            $addData['holdGlass'] = $holdGlass;
                            $addData['turnKey'] = $turnKey;
                            $addData['fryingPan'] = $fryingPan;
                            $addData['jar'] = $jar;
                            $addData['hardBlouse'] = $hardBlouse;
                            $addData['knifeFork'] = $knifeFork;
                            $addData['groceryBag'] = $groceryBag;
                            $addData['dishes'] = $dishes;
                            $addData['hair'] = $hair;
                            $addData['knot'] = $knot;
                            $addData['totalScore'] = $totalScore;
                            $addData['getScore'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_wrist_and_hand')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlWristAndHand(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_wrist_and_hand')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','handWork','fingersMove','wristMove','strengthHand','sensation','doorKnob','pickCoin','holdGlass','turnKey','fryingPan','jar','hardBlouse','knifeFork','groceryBag','dishes','hair','knot','totalScore','getScore','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->getScore.'/'.$value->totalScore;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Wrist & Hand get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlAnkeAndFoot(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('standing') && Input::has('walking') && Input::has('walkingWithoutShoes') && Input::has('walkingUpSlope') && Input::has('walkingDownSlope') && Input::has('climbingUp') && Input::has('goingDown') && Input::has('walkingOnUneven') && Input::has('stepping') && Input::has('squatting') && Input::has('toes') && Input::has('walkingInitially') && Input::has('walking5Min') && Input::has('walking10Min') && Input::has('walking15Min') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $visitId = $request->visitId;
                    $therapistId = $request->userId;
                    $standing = $request->standing;
                    $walking = $request->walking;
                    $walkingWithoutShoes = $request->walkingWithoutShoes;
                    $walkingUpSlope = $request->walkingUpSlope;
                    $walkingDownSlope = $request->walkingDownSlope;
                    $climbingUp = $request->climbingUp;
                    $goingDown = $request->goingDown;
                    $walkingOnUneven = $request->walkingOnUneven;
                    $stepping = $request->stepping;
                    $squatting = $request->squatting;
                    $toes = $request->toes;
                    $walkingInitially = $request->walkingInitially;
                    $walking5Min = $request->walking5Min;
                    $walking10Min = $request->walking10Min;
                    $walking15Min = $request->walking15Min;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($visitId) && !empty($therapistId) && !empty($standing) && !empty($walking) && !empty($walkingWithoutShoes) && !empty($walkingUpSlope) && !empty($walkingDownSlope) && !empty($climbingUp) && !empty($goingDown) && !empty($walkingOnUneven) && !empty($stepping) && !empty($squatting) && !empty($toes) && !empty($walkingInitially) && !empty($walking5Min) && !empty($walking10Min) && !empty($walking15Min) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['standing'] = $standing;
                                $addData['walking'] = $walking;
                                $addData['walkingWithoutShoes'] = $walkingWithoutShoes;
                                $addData['walkingUpSlope'] = $walkingUpSlope;
                                $addData['walkingDownSlope'] = $walkingDownSlope;
                                $addData['climbingUp'] = $climbingUp;
                                $addData['goingDown'] = $goingDown;
                                $addData['walkingOnUneven'] = $walkingOnUneven;
                                $addData['stepping'] = $stepping;
                                $addData['squatting'] = $squatting;
                                $addData['toes'] = $toes;
                                $addData['walkingInitially'] = $walkingInitially;
                                $addData['walking5Min'] = $walking5Min;
                                $addData['walking10Min'] = $walking10Min;
                                $addData['walking15Min'] = $walking15Min;
                                $addData['totalScore'] = $totalScore;
                                $addData['getScore'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_anke_and_foot')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('standing') && Input::has('walking') && Input::has('walkingWithoutShoes') && Input::has('walkingUpSlope') && Input::has('walkingDownSlope') && Input::has('climbingUp') && Input::has('goingDown') && Input::has('walkingOnUneven') && Input::has('stepping') && Input::has('squatting') && Input::has('toes') && Input::has('walkingInitially') && Input::has('walking5Min') && Input::has('walking10Min') && Input::has('walking15Min') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $appId = $request->appId;
                    $standing = $request->standing;
                    $walking = $request->walking;
                    $walkingWithoutShoes = $request->walkingWithoutShoes;
                    $walkingUpSlope = $request->walkingUpSlope;
                    $walkingDownSlope = $request->walkingDownSlope;
                    $climbingUp = $request->climbingUp;
                    $goingDown = $request->goingDown;
                    $walkingOnUneven = $request->walkingOnUneven;
                    $stepping = $request->stepping;
                    $squatting = $request->squatting;
                    $toes = $request->toes;
                    $walkingInitially = $request->walkingInitially;
                    $walking5Min = $request->walking5Min;
                    $walking10Min = $request->walking10Min;
                    $walking15Min = $request->walking15Min;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($standing) && !empty($walking) && !empty($walkingWithoutShoes) && !empty($walkingUpSlope) && !empty($walkingDownSlope) && !empty($climbingUp) && !empty($goingDown) && !empty($walkingOnUneven) && !empty($stepping) && !empty($squatting) && !empty($toes) && !empty($walkingInitially) && !empty($walking5Min) && !empty($walking10Min) && !empty($walking15Min) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['standing'] = $standing;
                            $addData['walking'] = $walking;
                            $addData['walkingWithoutShoes'] = $walkingWithoutShoes;
                            $addData['walkingUpSlope'] = $walkingUpSlope;
                            $addData['walkingDownSlope'] = $walkingDownSlope;
                            $addData['climbingUp'] = $climbingUp;
                            $addData['goingDown'] = $goingDown;
                            $addData['walkingOnUneven'] = $walkingOnUneven;
                            $addData['stepping'] = $stepping;
                            $addData['squatting'] = $squatting;
                            $addData['toes'] = $toes;
                            $addData['walkingInitially'] = $walkingInitially;
                            $addData['walking5Min'] = $walking5Min;
                            $addData['walking10Min'] = $walking10Min;
                            $addData['walking15Min'] = $walking15Min;
                            $addData['totalScore'] = $totalScore;
                            $addData['getScore'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_anke_and_foot')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlAnkeAndFoot(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_anke_and_foot')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','standing','walking','walkingWithoutShoes','walkingUpSlope','walkingDownSlope','climbingUp','goingDown','walkingOnUneven','stepping','squatting','toes','walkingInitially','walking5Min','walking10Min','walking15Min','totalScore','getScore','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->getScore.'/'.$value->totalScore;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Anke & Foot get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlBack(Request $request){
        try{
            if(Input::has('visitId') && !empty($request->visitId)){
                // for therapist app
                if(Input::has('patientId') && Input::has('userId') && Input::has('backComfortable') && Input::has('walkSlowly') && Input::has('job') && Input::has('handrail') && Input::has('lieDown') && Input::has('holdSomething') && Input::has('otherPeople') && Input::has('dressingUp') && Input::has('standingUp') && Input::has('bendingKneeling') && Input::has('backPainful') && Input::has('turnover') && Input::has('sock') && Input::has('shortDistance') && Input::has('sleepDisturbance') && Input::has('heavyJobs') && Input::has('irritableBadlyTempered') && Input::has('upstairs') && Input::has('laughingSneezing') && Input::has('travellingDriving') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $therapistId = $request->userId;
                    $visitId = $request->visitId;
                    $backComfortable = $request->backComfortable;
                    $walkSlowly = $request->walkSlowly;
                    $job = $request->job;
                    $handrail = $request->handrail;
                    $lieDown = $request->lieDown;
                    $holdSomething = $request->holdSomething;
                    $otherPeople = $request->otherPeople;
                    $dressingUp = $request->dressingUp;
                    $standingUp = $request->standingUp;
                    $bendingKneeling = $request->bendingKneeling;
                    $backPainful = $request->backPainful;
                    $turnover = $request->turnover;
                    $sock = $request->sock;
                    $shortDistance = $request->shortDistance;
                    $sleepDisturbance = $request->sleepDisturbance;
                    $heavyJobs = $request->heavyJobs;
                    $irritableBadlyTempered = $request->irritableBadlyTempered;
                    $upstairs = $request->upstairs;
                    $laughingSneezing = $request->laughingSneezing;
                    $travellingDriving = $request->travellingDriving;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($therapistId) && !empty($visitId) && !empty($backComfortable) && !empty($walkSlowly) && !empty($job) && !empty($handrail) && !empty($lieDown) && !empty($holdSomething) && !empty($otherPeople) && !empty($dressingUp) && !empty($standingUp) && !empty($bendingKneeling) && !empty($backPainful) && !empty($turnover) && !empty($sock) && !empty($shortDistance) && !empty($sleepDisturbance) && !empty($heavyJobs) && !empty($irritableBadlyTempered) && !empty($upstairs) && !empty($laughingSneezing) && !empty($travellingDriving) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                            if($checkTherapist){
                                $addData = array();
                                $addData['patient_id'] = $patientId;
                                $addData['visit_id'] = $visitId;
                                $addData['appointment_id'] = '';
                                $addData['therapist_id'] = $therapistId;
                                $addData['backComfortable'] = $backComfortable;
                                $addData['walkSlowly'] = $walkSlowly;
                                $addData['job'] = $job;
                                $addData['handrail'] = $handrail;
                                $addData['lieDown'] = $lieDown;
                                $addData['holdSomething'] = $holdSomething;
                                $addData['otherPeople'] = $otherPeople;
                                $addData['dressingUp'] = $dressingUp;
                                $addData['standingUp'] = $standingUp;
                                $addData['bendingKneeling'] = $bendingKneeling;
                                $addData['backPainful'] = $backPainful;
                                $addData['turnover'] = $turnover;
                                $addData['sock'] = $sock;
                                $addData['shortDistance'] = $shortDistance;
                                $addData['sleepDisturbance'] = $sleepDisturbance;
                                $addData['heavyJobs'] = $heavyJobs;
                                $addData['irritableBadlyTempered'] = $irritableBadlyTempered;
                                $addData['upstairs'] = $upstairs;
                                $addData['laughingSneezing'] = $laughingSneezing;
                                $addData['travellingDriving'] = $travellingDriving;
                                $addData['totalScore'] = $totalScore;
                                $addData['getScore'] = $getScore;
                                $addData['created_at'] = date('Y-m-d H:i:s');
                                DB::table('adl_back')->insert($addData);

                                $response['message'] = 'Successfully Saved!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Therapist not exist!';
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
            }else if(Input::has('appId') && !empty($request->appId)){
                // for patient app
                if(Input::has('patientId') && Input::has('backComfortable') && Input::has('walkSlowly') && Input::has('job') && Input::has('handrail') && Input::has('lieDown') && Input::has('holdSomething') && Input::has('otherPeople') && Input::has('dressingUp') && Input::has('standingUp') && Input::has('bendingKneeling') && Input::has('backPainful') && Input::has('turnover') && Input::has('sock') && Input::has('shortDistance') && Input::has('sleepDisturbance') && Input::has('heavyJobs') && Input::has('irritableBadlyTempered') && Input::has('upstairs') && Input::has('laughingSneezing') && Input::has('travellingDriving') && Input::has('totalScore') && Input::has('getScore')){
                    $patientId = $request->patientId;
                    $appId = $request->appId;
                    $backComfortable = $request->backComfortable;
                    $walkSlowly = $request->walkSlowly;
                    $job = $request->job;
                    $handrail = $request->handrail;
                    $lieDown = $request->lieDown;
                    $holdSomething = $request->holdSomething;
                    $otherPeople = $request->otherPeople;
                    $dressingUp = $request->dressingUp;
                    $standingUp = $request->standingUp;
                    $bendingKneeling = $request->bendingKneeling;
                    $backPainful = $request->backPainful;
                    $turnover = $request->turnover;
                    $sock = $request->sock;
                    $shortDistance = $request->shortDistance;
                    $sleepDisturbance = $request->sleepDisturbance;
                    $heavyJobs = $request->heavyJobs;
                    $irritableBadlyTempered = $request->irritableBadlyTempered;
                    $upstairs = $request->upstairs;
                    $laughingSneezing = $request->laughingSneezing;
                    $travellingDriving = $request->travellingDriving;
                    $totalScore = $request->totalScore;
                    $getScore = $request->getScore;
                    if(!empty($patientId) && !empty($backComfortable) && !empty($walkSlowly) && !empty($job) && !empty($handrail) && !empty($lieDown) && !empty($holdSomething) && !empty($otherPeople) && !empty($dressingUp) && !empty($standingUp) && !empty($bendingKneeling) && !empty($backPainful) && !empty($turnover) && !empty($sock) && !empty($shortDistance) && !empty($sleepDisturbance) && !empty($heavyJobs) && !empty($irritableBadlyTempered) && !empty($upstairs) && !empty($laughingSneezing) && !empty($travellingDriving) && !empty($totalScore) && !empty($getScore)){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['visit_id'] = '';
                            $addData['appointment_id'] = $appId;
                            $addData['therapist_id'] = '';
                            $addData['backComfortable'] = $backComfortable;
                            $addData['walkSlowly'] = $walkSlowly;
                            $addData['job'] = $job;
                            $addData['handrail'] = $handrail;
                            $addData['lieDown'] = $lieDown;
                            $addData['holdSomething'] = $holdSomething;
                            $addData['otherPeople'] = $otherPeople;
                            $addData['dressingUp'] = $dressingUp;
                            $addData['standingUp'] = $standingUp;
                            $addData['bendingKneeling'] = $bendingKneeling;
                            $addData['backPainful'] = $backPainful;
                            $addData['turnover'] = $turnover;
                            $addData['sock'] = $sock;
                            $addData['shortDistance'] = $shortDistance;
                            $addData['sleepDisturbance'] = $sleepDisturbance;
                            $addData['heavyJobs'] = $heavyJobs;
                            $addData['irritableBadlyTempered'] = $irritableBadlyTempered;
                            $addData['upstairs'] = $upstairs;
                            $addData['laughingSneezing'] = $laughingSneezing;
                            $addData['travellingDriving'] = $travellingDriving;
                            $addData['totalScore'] = $totalScore;
                            $addData['getScore'] = $getScore;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('adl_back')->insert($addData);

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
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
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAdlBack(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('adl_back')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','backComfortable','walkSlowly','job','handrail','lieDown','holdSomething','otherPeople','dressingUp','standingUp','bendingKneeling','backPainful','turnover','sock','shortDistance','sleepDisturbance','heavyJobs','irritableBadlyTempered','upstairs','laughingSneezing','travellingDriving','totalScore','getScore','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                }else{
                                    $value->therapistName = '';
                                }
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                $value->finalScore = $value->getScore.'/'.$value->totalScore;
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All Adl Anke & Foot get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }
    
    public function attendVisit(Request $request){
        try{
            if(Input::has('userId') && Input::has('visitId')){
                $therapistId = $request->userId;
                $visitId = $request->visitId;
                $todayDate = date('Y-m-d');
                if(!empty($therapistId) && !empty($visitId)){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                            $dailyEntryDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                            if($dailyEntryDetails){
                                $appId = $dailyEntryDetails->appointment_id;
                                // check previous visit complete or not
                                $checkAppointment = Appointment::where('id',$appId)->first();
                                $appServiceType = $checkAppointment->app_service_type;
                                if(($appServiceType != 1) && ($appServiceType != 8) && ($appServiceType != 9)){
                                    // without private home care service type
                                    $checkAttendance = DB::table('attendance')->where('attendance.therapist_id',$therapistId)->where('attendance.date',$todayDate)->join('users','users.id','=','attendance.therapist_id')->where('attendance.status','present')->select('attendance.id','users.service_type')->first();
                                    if(($checkAttendance != null) && !empty($checkAttendance)){
                                        if($checkAppointment->status == 'approved'){
                                            if(!empty($checkAppointment->payment_method)){
                                                if(($dailyEntryDetails->status == 'pending') && ($dailyEntryDetails->in_time != Null)){
                                                    $response['message'] = 'Visit already attend!';
                                                    $response['status'] = '0';
                                                }else{
                                                    $visitType = $dailyEntryDetails->type;
                                                    $appId = $dailyEntryDetails->appointment_id;
                                                    if($visitType == 2){
                                                    // for package visit inTime entry
                                                        $appId = $dailyEntryDetails->appointment_id;
                                                        $therapistId = $dailyEntryDetails->therapist_id;
                                                        $base_salary = userDetails($therapistId)->base_commision;
                                                        $appDetails = appointmentDetails($appId);
                                                        $patientId = $appDetails->user_id;

                                                        //if package update then due days of package entries
                                                        $appointmentDueDays = $appDetails->due_package_days;
                                                        if(($appointmentDueDays != 0) && ($appointmentDueDays != '')){
                                                            $packageId = $appDetails->package_type - 1;
                                                        }else{
                                                            $packageId = $appDetails->package_type;
                                                        }
                                                        $packageDetails = packageDetails($packageId);
                                                        $pAmt = $packageDetails->package_amount;
                                                        $days = $packageDetails->days;
                                                        $extra_amt = $dailyEntryDetails->extra_amount;
                                                        if(!empty($extra_amt)){
                                                            $extAmount = $extra_amt;
                                                        }else{
                                                            $extAmount = 0;
                                                        }
                                                        $amount = ($pAmt / $days);
                                                        $currentDate = date('Y-m-d');
                                                        $time = date("H:i:s");
                                                        $app_booked_time = $dailyEntryDetails->app_booked_time;
                                                        $firstDate = $dailyEntryDetails->app_booked_date;
                                                        $updateData = array();
                                                        $updateData['in_time'] = $time;
                                                        $updateData['amount'] = $amount;
                                                        $updateData['status'] = 'pending';
                                                        $updateData['total_seats'] = totalPackageDueDays($appId) + 1;
                                                        $updateData['no_of_seats'] = packageSitting($appId,$packageId) + 1;
                                                        // IPD Case
                                                        if(!empty($appServiceType) && ($appServiceType == 7)){
                                                            // For only IPD Patient
                                                            $updateData['visit_type'] = 'ipd';
                                                            DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                            $response['message'] = 'Successfully Attend!';
                                                            $response['status'] = '1';
                                                        }elseif(!empty($appServiceType) && (($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1))){
                                                            // for Home care patient only
                                                            // check patient AV or AW
                                                            $userDetails = userDetails($patientId);
                                                            $patientCreatedDate = $userDetails->created_at;
                                                            $pCreatedDate = explode(' ', $patientCreatedDate);
                                                            $patCreatedDate = $pCreatedDate[0];
                                                            $visitCreatedDate = $dailyEntryDetails->app_booked_date;
                                                            $vCreatedTime = $dailyEntryDetails->created_at;
                                                            $vCrDate = explode(' ', $vCreatedTime);
                                                            $vCreatedDate = $vCrDate[0];
                                                            $visitCreatedTime = $vCrDate[1];
                                                            $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));

                                                            if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                $visitType = 'AW';
                                                            }else{
                                                                if($currentDate == $firstDate){
                                                                    $nextTime = strtotime("+10 minutes", strtotime($app_booked_time));
                                                                    if($nextTime <= strtotime($time)){    
                                                                        $visitType = 'AW';
                                                                    }else{
                                                                        $visitType = 'AV';
                                                                    }
                                                                }else{
                                                                    $visitType = 'AW';
                                                                }
                                                            }
                                                            $updateData['visit_type'] = $visitType;
                                                            // if 1st package not completed in it's limited days it will be converted into again 1st package, they will not be converted into 2nd package.
                                                            
                                                            $packageValidity = $packageDetails->validity;
                                                            $nextValidityDate = date('Y-m-d', strtotime($firstDate. ' + '.$packageValidity.' days'));
                                                            if(strtotime($currentDate) <= strtotime($nextValidityDate)){
                                                                // update due days in daily
                                                                $totalDay = DB::table('package')->where('id',$packageId)->first();
                                                                $totalDays = $totalDay->days;
                                                                //$workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('amount','!=','')->where('type',2)->count('id');
                                                                $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
                                                                if($workingDays === 0){
                                                                    $dueDays = $totalDays - 1;
                                                                }else{
                                                                    $dueDays = $totalDays - ($workingDays + 1);
                                                                }
                                                                $updateData['due_days'] = $dueDays;
                                                                $updateData['flag'] = '';
                                                                // dd($dueDays,$workingDays,$totalDays);
                                                            }else{
                                                                // limited days cross in package daily entry (validity cross convert it into back package)
                                                                // update package with 1 before package
                                                                $updateAppData = array();
                                                                if($packageId == 1){
                                                                    $changePackageId = 1;
                                                                }else{
                                                                    $changePackageId = 1;
                                                                }
                                                                $updateAppData['package_type'] = $changePackageId;
                                                                Appointment::where('id',$appId)->update($updateAppData);

                                                                // change daily entry with before 1 package details
                                                                $newDays = packageDetails($changePackageId)->days;
                                                                $totalDays = $newDays;
                                                                //$workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('amount','!=','')->where('type',2)->count('id');
                                                                $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
                                                                if($workingDays == 0){
                                                                    $dueDays = $totalDays - 1;
                                                                }else{
                                                                    $dueDays = $totalDays - ($workingDays + 1);
                                                                }
                                                                $updateData['due_days'] = $dueDays;
                                                                $updateData['flag'] = 'limited_days_cross';
                                                                $checkData = $this->setExpirePackageStatus($appId,$lastVisitId);
                                                            }
                                                            DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                            
                                                            $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                                                            $packId = $dailyEntryPackageDetails->package_id;
                                                            if(packageDetails($packId)->name == '1st Package'){
                                                                $privateBaseSalary = 70;            //only for home care patients
                                                            }else{
                                                                if(!empty($packageCommission)){
                                                                    $privateBaseSalary = 70 + $packageCommission;   //add insentive if first package completed
                                                                }else{
                                                                    $privateBaseSalary = 70;            //only for home care patients
                                                                }
                                                            }
                                                            $amount = $amount + $extAmount;     //if extra amount add in package visit
                                                            $therapistAmt = ($privateBaseSalary / 100) * $amount;
                                                            $capriAmt = $amount - $therapistAmt;
                                                            $addAmountAcc = array();
                                                            $addAmountAcc['visit_id'] = $visitId;
                                                            $addAmountAcc['therapist_id'] = $therapistId;
                                                            $addAmountAcc['appointment_id'] = $appId;
                                                            $addAmountAcc['user_id'] = $patientId;
                                                            $addAmountAcc['capri_account'] = $capriAmt;
                                                            $addAmountAcc['therapist_account'] = $therapistAmt;
                                                            $addAmountAcc['total_amount'] = $amount;
                                                            $addAmountAcc['flag'] = 'package';
                                                            $addAmountAcc['transection_status'] = '';
                                                            $addAmountAcc['transection_id'] = '';
                                                            $addAmountAcc['payment_date'] = date("Y-m-d");
                                                            $addAmountAcc['created_by'] = $therapistId;
                                                            DB::table('account')->insert($addAmountAcc);
                                                            // account history 
                                                            $appHistory = array();
                                                            $appHistory['visit_id'] = $visitId;
                                                            $appHistory['appointment_id'] = $appId;
                                                            $appHistory['therapist_id'] = $therapistId;
                                                            $appHistory['capri_account'] = $capriAmt;
                                                            $appHistory['therapist_account'] = $therapistAmt;
                                                            $appHistory['total_amount'] = $amount;
                                                            $appHistory['remark'] = 'package entry';
                                                            $appHistory['created_by'] = $therapistId;
                                                            $appHistory['transection_status'] = '';
                                                            $appHistory['transection_id'] = '';
                                                            $appHistory['payment_date'] = date("Y-m-d");
                                                            DB::table('account_history')->insert($appHistory);
                                                            $response['message'] = 'Successfully Attend!';
                                                            $response['status'] = '1';
                                                        }else{
                                                            // For OPD Patient only
                                                            // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
                                                            // check patient AV or AW
                                                            $userDetails = userDetails($patientId);
                                                            $patientCreatedDate = $userDetails->created_at;
                                                            $pCreatedDate = explode(' ', $patientCreatedDate);
                                                            $patCreatedDate = $pCreatedDate[0];
                                                            $visitCreatedDate = $dailyEntryDetails->app_booked_date;
                                                            $vCreatedTime = $dailyEntryDetails->created_at;
                                                            $vCrDate = explode(' ', $vCreatedTime);
                                                            $vCreatedDate = $vCrDate[0];
                                                            $visitCreatedTime = $vCrDate[1];
                                                            $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));

                                                            if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                $visitType = 'AW';
                                                            }else{
                                                                if($currentDate == $firstDate){
                                                                    $nextTime = strtotime("+10 minutes", strtotime($app_booked_time));
                                                                    if($nextTime <= strtotime($time)){    
                                                                        $visitType = 'AW';
                                                                    }else{
                                                                        $visitType = 'AV';
                                                                    }
                                                                }else{
                                                                    $visitType = 'AW';
                                                                }
                                                            }
                                                            $updateData['visit_type'] = $visitType;
                                                            // if 1st package not completed in it's limited days it will be converted into again 1st package, they will not be converted into 2nd package.
                                                            
                                                            $packageValidity = $packageDetails->validity;
                                                            $nextValidityDate = date('Y-m-d', strtotime($firstDate. ' + '.$packageValidity.' days'));
                                                            if(strtotime($currentDate) <= strtotime($nextValidityDate)){
                                                                // update due days in daily
                                                                $totalDays = $days;
                                                                // $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('amount','!=','')->where('type',2)->count('id');
                                                                $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
                                                                if($workingDays == 0){
                                                                    $dueDays = $totalDays - 1;
                                                                }else{
                                                                    $dueDays = $totalDays - ($workingDays + 1);
                                                                }
                                                                $updateData['due_days'] = $dueDays;
                                                                $updateData['flag'] = '';
                                                            }else{
                                                                // limited days cross in package daily entry (validity cross convert it into back package)
                                                                // update package with 1 before package
                                                                $updateAppData = array();
                                                                if($packageId == 1){
                                                                    $changePackageId = 1;
                                                                }else{
                                                                    $changePackageId = 1;
                                                                }
                                                                $updateAppData['package_type'] = $changePackageId;
                                                                Appointment::where('id',$appId)->update($updateAppData);

                                                                // change daily entry with before 1 package details
                                                                $newDays = packageDetails($changePackageId)->days;
                                                                $totalDays = $newDays;
                                                                //$workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('amount','!=','')->where('type',2)->count('id');
                                                                $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('status','complete')->where('type',2)->count('id');
                                                                if($workingDays == 0){
                                                                    $dueDays = $totalDays - 1;
                                                                }else{
                                                                    $dueDays = $totalDays - ($workingDays + 1);
                                                                }
                                                                $updateData['due_days'] = $dueDays;
                                                                $updateData['flag'] = 'limited_days_cross';
                                                            }
                                                            DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                            // if 1st package complete after that on 2nd package incentive share to therapist
                                                            $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                                                            $packId = $dailyEntryPackageDetails->package_id;
                                                            $packageCommission = packageDetails($packId)->commission;
                                                            if(packageDetails($packId)->name == '1st Package'){
                                                                $base_salaries = $base_salary;
                                                            }else{
                                                                if(!empty($packageCommission) && ($packageCommission != 0)){
                                                                    $base_salaries = $base_salary + $packageCommission;   //add insentive to therapist if first package completed
                                                                }else{
                                                                    $base_salaries = $base_salary;
                                                                }
                                                            }
                                                            // for package wise base % share to therapist account and due share to capri account
                                                            $amount = $amount + $extAmount;     //if extra amount add in package visit
                                                            $therapistAmt = ($base_salaries / 100) * $amount;
                                                            $capriAmt = $amount - $therapistAmt;
                                                            
                                                            $addAmountAcc = array();
                                                            $addAmountAcc['visit_id'] = $visitId;
                                                            $addAmountAcc['therapist_id'] = $therapistId;
                                                            $addAmountAcc['appointment_id'] = $appId;
                                                            $addAmountAcc['user_id'] = $patientId;
                                                            $addAmountAcc['capri_account'] = $capriAmt;
                                                            $addAmountAcc['therapist_account'] = $therapistAmt;
                                                            $addAmountAcc['total_amount'] = $amount;
                                                            $addAmountAcc['flag'] = 'package';
                                                            $addAmountAcc['payment_date'] = date('Y-m-d');
                                                            DB::table('account')->insert($addAmountAcc);
                                                            // account history 
                                                            $appHistory = array();
                                                            $appHistory['visit_id'] = $visitId;
                                                            $appHistory['appointment_id'] = $appId;
                                                            $appHistory['therapist_id'] = $therapistId;
                                                            $appHistory['capri_account'] = $capriAmt;
                                                            $appHistory['therapist_account'] = $therapistAmt;
                                                            $appHistory['total_amount'] = $amount;
                                                            $appHistory['remark'] = 'package entry';
                                                            $appHistory['created_by'] = $therapistId;
                                                            DB::table('account_history')->insert($appHistory);

                                                            $visitCount = DB::table('daily_entry')->where('appointment_id',$appId)->where('status','complete')->count('id');
                                                            if(($visitCount > 0) && ($visitCount % 7 == 0)){
                                                                $response['message'] = 'Please Reassessment your visits';
                                                            }else{
                                                                $response['message'] = 'Successfully Attend!';
                                                            }
                                                            $response['status'] = '1';
                                                        }
                                                    }else if($visitType == 3){
                                                        $checkAmountVisit = DB::table('daily_entry')->where('id',$visitId)->where('type',3)->first();
                                                        if($checkAmountVisit){
                                                            // for complimentary visits only
                                                            $therapistId = $checkAmountVisit->therapist_id;
                                                            $booked_date = $dailyEntryDetails->app_booked_date;
                                                            $booked_time = $checkAmountVisit->app_booked_time;
                                                            $time = date("H:i:s");
                                                            $currentDate = date('Y-m-d');
                                                            $firstDate = $checkAmountVisit->app_booked_date;
                                                            $appointmentDetails = appointmentDetails($appId);
                                                            $appDate = $appointmentDetails->appointment_date;
                                                            $patientId = $appointmentDetails->user_id;
                                                            $updateData = array();
                                                            $updateData['in_time'] = $time;
                                                            $updateData['total_seats'] = totalPackageDueDays($appId);
                                                            $updateData['no_of_seats'] = 1;
                                                            $updateData['due_days'] = 0;
                                                            $updateData['status'] = 'pending';
                                                            $userDetails = userDetails($patientId);
                                                            $patientCreatedDate = $userDetails->created_at;
                                                            $pCreatedDate = explode(' ', $patientCreatedDate);
                                                            $patCreatedDate = $pCreatedDate[0];
                                                            $visitCreatedDate = $checkAmountVisit->app_booked_date;
                                                            $vCreatedTime = $checkAmountVisit->created_at;
                                                            $vCrDate = explode(' ', $vCreatedTime);
                                                            $vCreatedDate = $vCrDate[0];
                                                            $visitCreatedTime = $vCrDate[1];
                                                            $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));
                                                            if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                $visitType = 'AW';
                                                            }else{
                                                                if($currentDate == $firstDate){
                                                                    $nextTime = strtotime("+10 minutes", strtotime($booked_time));
                                                                    if($nextTime <= strtotime($time)){    
                                                                        $visitType = 'AW';
                                                                    }else{
                                                                        $visitType = 'AV';
                                                                    }
                                                                }else{
                                                                    $visitType = 'AW';
                                                                }
                                                            }
                                                            $updateData['visit_type'] = $visitType;
                                                            DB::table('daily_entry')->where('id',$visitId)->update($updateData);
    
                                                            $response['message'] = 'Successfully Attend!';
                                                            $response['status'] = '1';
                                                        }else{
                                                            $response['message'] = 'Visit not exist!';
                                                            $response['status'] = '0';
                                                        }
                                                    }else{
                                                        // for per day visit inTime entry
                                                        // check amount update or not
                                                        $checkAmountVisit = DB::table('daily_entry')->where('id',$visitId)->where('type',1)->first();
                                                        if($checkAmountVisit){
                                                            if(($appServiceType != 7) && (($checkAmountVisit->amount == null) || ($checkAmountVisit->amount == '') || empty($checkAmountVisit->amount))){
                                                                $response['message'] = 'Please pay amount for these perday visit!';
                                                                $response['status'] = 'fail';
                                                            }else{
                                                                // for per day visit inTime entry
                                                                $therapistId = $checkAmountVisit->therapist_id;
                                                                $booked_date = $dailyEntryDetails->app_booked_date;
                                                                $booked_time = $checkAmountVisit->app_booked_time;
                                                                $time = date("H:i:s");
                                                                $currentDate = date('Y-m-d');
                                                                $firstDate = $checkAmountVisit->app_booked_date;
                                                                $appointmentDetails = appointmentDetails($appId);
                                                                $appDate = $appointmentDetails->appointment_date;
                                                                $patientId = $appointmentDetails->user_id;

                                                                $updateData = array();
                                                                $updateData['in_time'] = $time;
                                                                $updateData['total_seats'] = totalPackageDueDays($appId);
                                                                $updateData['no_of_seats'] = 1;
                                                                $updateData['due_days'] = 0;
                                                                $updateData['status'] = 'pending';
                                                                $userDetails = userDetails($patientId);
                                                                $patientCreatedDate = $userDetails->created_at;
                                                                $pCreatedDate = explode(' ', $patientCreatedDate);
                                                                $patCreatedDate = $pCreatedDate[0];
                                                                $visitCreatedDate = $checkAmountVisit->app_booked_date;
                                                                $vCreatedTime = $checkAmountVisit->created_at;
                                                                $vCrDate = explode(' ', $vCreatedTime);
                                                                $vCreatedDate = $vCrDate[0];
                                                                $visitCreatedTime = $vCrDate[1];
                                                                // For IPD Case
                                                                if(!empty($appServiceType) && ($appServiceType == 7)){
                                                                    // For IPD Patient only
                                                                    $updateData['visit_type'] = 'ipd';
                                                                    DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                                    $response['message'] = 'Successfully Attend!';
                                                                    $response['status'] = '1';
                                                                }elseif(!empty($appServiceType) && (($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1))){
                                                                    // for home care patient only
                                                                    // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
                                                                    $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));
                                                                    if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                        $visitType = 'AW';
                                                                    }else{
                                                                        if($currentDate == $firstDate){
                                                                            $nextTime = strtotime("+10 minutes", strtotime($booked_time));
                                                                            if($nextTime <= strtotime($time)){    
                                                                                $visitType = 'AW';
                                                                            }else{
                                                                                $visitType = 'AV';
                                                                            }
                                                                        }else{
                                                                            $visitType = 'AW';
                                                                        }
                                                                    }

                                                                    $updateData['visit_type'] = $visitType;
                                                                    DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                                    $privateBaseSalary = 70;            //only for home care patients
                                                                    $amount = $dailyEntryDetails->amount;
                                                                    $extAmount = $dailyEntryDetails->extra_amount;
                                                                    $amount = $amount + $extAmount;     //if extra amount add in package visit
                                                                    $therapistAmt = ($privateBaseSalary / 100) * $amount;
                                                                    $capriAmt = $amount - $therapistAmt;
                                                                    $addAmountAcc = array();
                                                                    $addAmountAcc['visit_id'] = $visitId;
                                                                    $addAmountAcc['therapist_id'] = $therapistId;
                                                                    $addAmountAcc['appointment_id'] = $appId;
                                                                    $addAmountAcc['user_id'] = $patientId;
                                                                    $addAmountAcc['capri_account'] = $capriAmt;
                                                                    $addAmountAcc['therapist_account'] = $therapistAmt;
                                                                    $addAmountAcc['total_amount'] = $amount;
                                                                    $addAmountAcc['flag'] = 'perday';
                                                                    $addAmountAcc['transection_status'] = '';
                                                                    $addAmountAcc['transection_id'] = '';
                                                                    $addAmountAcc['payment_date'] = date("Y-m-d");
                                                                    $addAmountAcc['created_by'] = $therapistId;
                                                                    DB::table('account')->insert($addAmountAcc);
                                                                    // account history 
                                                                    $appHistory = array();
                                                                    $appHistory['visit_id'] = $visitId;
                                                                    $appHistory['appointment_id'] = $appId;
                                                                    $appHistory['therapist_id'] = $therapistId;
                                                                    $appHistory['capri_account'] = $capriAmt;
                                                                    $appHistory['therapist_account'] = $therapistAmt;
                                                                    $appHistory['total_amount'] = $amount;
                                                                    $appHistory['remark'] = 'perday entry';
                                                                    $appHistory['created_by'] = $therapistId;
                                                                    $appHistory['transection_status'] = '';
                                                                    $appHistory['transection_id'] = '';
                                                                    $appHistory['payment_date'] = date("Y-m-d");
                                                                    DB::table('account_history')->insert($appHistory);
                                                                    $response['message'] = 'Successfully Attend!';
                                                                    $response['status'] = '1';
                                                                }else{
                                                                    // For OPD Patient only
                                                                    // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
                                                                    $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));
                                                                    if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                        $visitType = 'AW';
                                                                    }else{
                                                                        if($currentDate == $firstDate){
                                                                            $nextTime = strtotime("+10 minutes", strtotime($booked_time));
                                                                            if($nextTime <= strtotime($time)){    
                                                                                $visitType = 'AW';
                                                                            }else{
                                                                                $visitType = 'AV';
                                                                            }
                                                                        }else{
                                                                            $visitType = 'AW';
                                                                        }
                                                                    }

                                                                    $updateData['visit_type'] = $visitType;
                                                                    DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                                    $amount = $checkAmountVisit->amount;
                                                                    $baseAmount = userDetails($therapistId)->base_commision;
                                                                    // on per day entry, total amount is greater then 1500 then share % in therapist account within 4 days
                                                                    if(!empty($booked_date) && !empty($amount)){
                                                                        $next15Day = date('Y-m-d', strtotime($appDate. ' + 4 days'));
                                                                        $totalVisitAmount = DB::table('daily_entry')->where('appointment_id',$appId)->where('type',1)->sum('amount');
                                                                        if((strtotime($currentDate) <= strtotime($next15Day)) && ($totalVisitAmount >= '1500')){
                                                                            // only consultation amount send in capri account nd In therapist account of base amount % share
                                                                            $addAmountAcc = array();
                                                                            $addAmountAcc['visit_id'] = $visitId;
                                                                            $addAmountAcc['therapist_id'] = $therapistId;
                                                                            $addAmountAcc['appointment_id'] = $appId;
                                                                            $addAmountAcc['user_id'] = $patientId;
                                                                            $finalAmt = $totalVisitAmount;
                                                                            $percentage = $baseAmount;
                                                                            $amtamt = ($percentage / 100) * $finalAmt;
                                                                            $addAmountAcc['therapist_account'] = $amtamt;
                                                                            $addAmountAcc['capri_account'] = $totalVisitAmount - $amtamt;
                                                                            $addAmountAcc['total_amount'] = $totalVisitAmount;
                                                                            $addAmountAcc['flag'] = 'perday';
                                                                            $addAmountAcc['payment_date'] = date('Y-m-d');
                                                                            DB::table('account')->insert($addAmountAcc);

                                                                            // appointment history
                                                                            $appId = $checkAmountVisit->appointment_id;
                                                                            $appHistory = array();
                                                                            $appHistory['visit_id'] = $visitId;
                                                                            $appHistory['appointment_id'] = $appId;
                                                                            $appHistory['therapist_id'] = $therapistId;
                                                                            $appHistory['capri_account'] = $totalVisitAmount - $amtamt;
                                                                            $appHistory['therapist_account'] = $amtamt;
                                                                            $appHistory['total_amount'] = $totalVisitAmount;
                                                                            $appHistory['remark'] = 'Daily per day entry';
                                                                            $appHistory['created_by'] = $therapistId;
                                                                            DB::table('account_history')->insert($appHistory);
                                                                        }elseif((strtotime($currentDate) <= strtotime($next15Day)) && ($totalVisitAmount <= '1500')){
                                                                            // send all amount in capri account
                                                                            $addAmountAcc = array();
                                                                            $addAmountAcc['visit_id'] = $visitId;
                                                                            $addAmountAcc['therapist_id'] = $therapistId;
                                                                            $addAmountAcc['appointment_id'] = $appId;
                                                                            $addAmountAcc['user_id'] = $patientId;
                                                                            $addAmountAcc['capri_account'] = $totalVisitAmount;
                                                                            $addAmountAcc['total_amount'] = $totalVisitAmount;
                                                                            $addAmountAcc['flag'] = 'perday';
                                                                            $addAmountAcc['payment_date'] = date('Y-m-d');
                                                                            DB::table('account')->insert($addAmountAcc);

                                                                            // appointment history
                                                                            $appHistory = array();
                                                                            $appHistory['visit_id'] = $visitId;
                                                                            $appHistory['appointment_id'] = $appId;
                                                                            $appHistory['therapist_id'] = $therapistId;
                                                                            $appHistory['capri_account'] = $totalVisitAmount;
                                                                            $appHistory['therapist_account'] = '';
                                                                            $appHistory['total_amount'] = $totalVisitAmount;
                                                                            $appHistory['remark'] = 'Daily per day entry';
                                                                            $appHistory['created_by'] = $therapistId;
                                                                            DB::table('account_history')->insert($appHistory);
                                                                        }else{
                                                                            // send all amount in capri account
                                                                            $addAmountAcc = array();
                                                                            $addAmountAcc['visit_id'] = $visitId;
                                                                            $addAmountAcc['therapist_id'] = $therapistId;
                                                                            $addAmountAcc['appointment_id'] = $appId;
                                                                            $addAmountAcc['user_id'] = $patientId;
                                                                            $addAmountAcc['capri_account'] = $amount;
                                                                            $addAmountAcc['total_amount'] = $amount;
                                                                            $addAmountAcc['flag'] = 'perday';
                                                                            $addAmountAcc['transection_status'] = '';
                                                                            $addAmountAcc['transection_id'] = '';
                                                                            $addAmountAcc['payment_date'] = date("Y-m-d");
                                                                            $addAmountAcc['created_by'] = $therapistId;
                                                                            DB::table('account')->insert($addAmountAcc);

                                                                            // appointment history
                                                                            $appHistory = array();
                                                                            $appHistory['visit_id'] = $visitId;
                                                                            $appHistory['appointment_id'] = $appId;
                                                                            $appHistory['therapist_id'] = $therapistId;
                                                                            $appHistory['capri_account'] = $amount;
                                                                            $appHistory['therapist_account'] = '';
                                                                            $appHistory['total_amount'] = $amount;
                                                                            $appHistory['remark'] = 'Daily per day entry';
                                                                            $appHistory['created_by'] = $therapistId;
                                                                            $appHistory['transection_status'] = '';
                                                                            $appHistory['transection_id'] = '';
                                                                            $appHistory['payment_date'] = date("Y-m-d");
                                                                            DB::table('account_history')->insert($appHistory);
                                                                        }
                                                                    }
                                                                    $visitCount = DB::table('daily_entry')->where('appointment_id',$appId)->where('status','complete')->count('id');
                                                                    if(($visitCount > 0) && ($visitCount % 7 == 0)){
                                                                        $response['message'] = 'Please Reassessment your visits';
                                                                    }else{
                                                                        $response['message'] = 'Successfully Attend!';
                                                                    }
                                                                    $response['status'] = '1';
                                                                }
                                                            }
                                                        }else{
                                                            $response['message'] = 'Visit not exist!';
                                                            $response['status'] = '0';
                                                        }                                
                                                    }
                                                }
                                            }else{
                                                $response['message'] = 'Please Update Package or Perday Visit!';
                                                $response['status'] = '0';
                                            }
                                        }else{
                                            $response['message'] = 'Your appointment is in pending status, contact to CapriSpine Team!';
                                            $response['status'] = '0';
                                        }
                                    }else{
                                        $response['message'] = 'Therapist not present today!';
                                        $response['status'] = 'fail';
                                    }
                                }else{
                                    // for only private home care serive type
                                    if($checkAppointment->status == 'approved'){
                                        if(!empty($checkAppointment->payment_method)){
                                            if(($dailyEntryDetails->status == 'pending') && ($dailyEntryDetails->in_time != Null)){
                                                $response['message'] = 'Visit already attend!';
                                                $response['status'] = '0';
                                            }else{
                                                $visitType = $dailyEntryDetails->type;
                                                $appId = $dailyEntryDetails->appointment_id;
                                                if($visitType == 2){
                                                // for package visit inTime entry
                                                    $appId = $dailyEntryDetails->appointment_id;
                                                    $therapistId = $dailyEntryDetails->therapist_id;
                                                    $base_salary = userDetails($therapistId)->base_commision;
                                                    $appDetails = appointmentDetails($appId);
                                                    $patientId = $appDetails->user_id;

                                                    //if package update then due days of package entries
                                                    $appointmentDueDays = $appDetails->due_package_days;
                                                    if(($appointmentDueDays != 0) && ($appointmentDueDays != '')){
                                                        $packageId = $appDetails->package_type - 1;
                                                    }else{
                                                        $packageId = $appDetails->package_type;
                                                    }
                                                    $packageDetails = packageDetails($packageId);
                                                    $pAmt = $packageDetails->package_amount;
                                                    $days = $packageDetails->days;
                                                    $extra_amt = $dailyEntryDetails->extra_amount;
                                                    if(!empty($extra_amt)){
                                                        $extAmount = $extra_amt;
                                                    }else{
                                                        $extAmount = 0;
                                                    }
                                                    $amount = ($pAmt / $days);
                                                    $currentDate = date('Y-m-d');
                                                    $time = date("H:i:s");
                                                    $app_booked_time = $dailyEntryDetails->app_booked_time;
                                                    $firstDate = $dailyEntryDetails->app_booked_date;
                                                    $updateData = array();
                                                    $updateData['in_time'] = $time;
                                                    $updateData['amount'] = $amount;
                                                    $updateData['status'] = 'pending';
                                                    $updateData['total_seats'] = totalPackageDueDays($appId) + 1;
                                                    $updateData['no_of_seats'] = packageSitting($appId,$packageId) + 1;
                                                    // IPD Case
                                                    if(!empty($appServiceType) && ($appServiceType == 7)){
                                                        // For only IPD Patient
                                                        $updateData['visit_type'] = 'ipd';
                                                        DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                        $response['message'] = 'Successfully Attend!';
                                                        $response['status'] = '1';
                                                    }elseif(!empty($appServiceType) && (($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1))){
                                                        // for Home care patient only
                                                        // check patient AV or AW
                                                        $userDetails = userDetails($patientId);
                                                        $patientCreatedDate = $userDetails->created_at;
                                                        $pCreatedDate = explode(' ', $patientCreatedDate);
                                                        $patCreatedDate = $pCreatedDate[0];
                                                        $visitCreatedDate = $dailyEntryDetails->app_booked_date;
                                                        $vCreatedTime = $dailyEntryDetails->created_at;
                                                        $vCrDate = explode(' ', $vCreatedTime);
                                                        $vCreatedDate = $vCrDate[0];
                                                        $visitCreatedTime = $vCrDate[1];
                                                        $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));

                                                        if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                            $visitType = 'AW';
                                                        }else{
                                                            if($currentDate == $firstDate){
                                                                $nextTime = strtotime("+10 minutes", strtotime($app_booked_time));
                                                                if($nextTime <= strtotime($time)){    
                                                                    $visitType = 'AW';
                                                                }else{
                                                                    $visitType = 'AV';
                                                                }
                                                            }else{
                                                                $visitType = 'AW';
                                                            }
                                                        }
                                                        $updateData['visit_type'] = $visitType;
                                                        // if 1st package not completed in it's limited days it will be converted into again 1st package, they will not be converted into 2nd package.
                                                        
                                                        $packageValidity = $packageDetails->validity;
                                                        $nextValidityDate = date('Y-m-d', strtotime($firstDate. ' + '.$packageValidity.' days'));
                                                        if(strtotime($currentDate) <= strtotime($nextValidityDate)){
                                                            // update due days in daily
                                                            $totalDay = DB::table('package')->where('id',$packageId)->first();
                                                            $totalDays = $totalDay->days;
                                                            //$workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('amount','!=','')->where('type',2)->count('id');
                                                            $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
                                                            if($workingDays === 0){
                                                                $dueDays = $totalDays - 1;
                                                            }else{
                                                                $dueDays = $totalDays - ($workingDays + 1);
                                                            }
                                                            $updateData['due_days'] = $dueDays;
                                                            $updateData['flag'] = '';
                                                            // dd($dueDays,$workingDays,$totalDays);
                                                        }else{
                                                            // limited days cross in package daily entry (validity cross convert it into back package)
                                                            // update package with 1 before package
                                                            $updateAppData = array();
                                                            if($packageId == 1){
                                                                $changePackageId = 1;
                                                            }else{
                                                                $changePackageId = 1;
                                                            }
                                                            $updateAppData['package_type'] = $changePackageId;
                                                            Appointment::where('id',$appId)->update($updateAppData);

                                                            // change daily entry with before 1 package details
                                                            $newDays = packageDetails($changePackageId)->days;
                                                            $totalDays = $newDays;
                                                            //$workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('amount','!=','')->where('type',2)->count('id');
                                                            $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
                                                            if($workingDays == 0){
                                                                $dueDays = $totalDays - 1;
                                                            }else{
                                                                $dueDays = $totalDays - ($workingDays + 1);
                                                            }
                                                            $updateData['due_days'] = $dueDays;
                                                            $updateData['flag'] = 'limited_days_cross';
                                                        }
                                                        DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                        
                                                        $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                                                        $packId = $dailyEntryPackageDetails->package_id;
                                                        if(packageDetails($packId)->name == '1st Package'){
                                                            $privateBaseSalary = 70;            //only for home care patients
                                                        }else{
                                                            if(!empty($packageCommission)){
                                                                $privateBaseSalary = 70 + $packageCommission;   //add insentive if first package completed
                                                            }else{
                                                                $privateBaseSalary = 70;            //only for home care patients
                                                            }
                                                        }
                                                        $amount = $amount + $extAmount;     //if extra amount add in package visit
                                                        $therapistAmt = ($privateBaseSalary / 100) * $amount;
                                                        $capriAmt = $amount - $therapistAmt;
                                                        $addAmountAcc = array();
                                                        $addAmountAcc['visit_id'] = $visitId;
                                                        $addAmountAcc['therapist_id'] = $therapistId;
                                                        $addAmountAcc['appointment_id'] = $appId;
                                                        $addAmountAcc['user_id'] = $patientId;
                                                        $addAmountAcc['capri_account'] = $capriAmt;
                                                        $addAmountAcc['therapist_account'] = $therapistAmt;
                                                        $addAmountAcc['total_amount'] = $amount;
                                                        $addAmountAcc['flag'] = 'package';
                                                        $addAmountAcc['transection_status'] = '';
                                                        $addAmountAcc['transection_id'] = '';
                                                        $addAmountAcc['payment_date'] = date("Y-m-d");
                                                        $addAmountAcc['created_by'] = $therapistId;
                                                        DB::table('account')->insert($addAmountAcc);
                                                        // account history 
                                                        $appHistory = array();
                                                        $appHistory['visit_id'] = $visitId;
                                                        $appHistory['appointment_id'] = $appId;
                                                        $appHistory['therapist_id'] = $therapistId;
                                                        $appHistory['capri_account'] = $capriAmt;
                                                        $appHistory['therapist_account'] = $therapistAmt;
                                                        $appHistory['total_amount'] = $amount;
                                                        $appHistory['remark'] = 'package entry';
                                                        $appHistory['created_by'] = $therapistId;
                                                        $appHistory['transection_status'] = '';
                                                        $appHistory['transection_id'] = '';
                                                        $appHistory['payment_date'] = date("Y-m-d");
                                                        DB::table('account_history')->insert($appHistory);
                                                        $response['message'] = 'Successfully Attend!';
                                                        $response['status'] = '1';
                                                    }else{
                                                        // For OPD Patient only
                                                        // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
                                                        // check patient AV or AW
                                                        $userDetails = userDetails($patientId);
                                                        $patientCreatedDate = $userDetails->created_at;
                                                        $pCreatedDate = explode(' ', $patientCreatedDate);
                                                        $patCreatedDate = $pCreatedDate[0];
                                                        $visitCreatedDate = $dailyEntryDetails->app_booked_date;
                                                        $vCreatedTime = $dailyEntryDetails->created_at;
                                                        $vCrDate = explode(' ', $vCreatedTime);
                                                        $vCreatedDate = $vCrDate[0];
                                                        $visitCreatedTime = $vCrDate[1];
                                                        $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));

                                                        if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                            $visitType = 'AW';
                                                        }else{
                                                            if($currentDate == $firstDate){
                                                                $nextTime = strtotime("+10 minutes", strtotime($app_booked_time));
                                                                if($nextTime <= strtotime($time)){    
                                                                    $visitType = 'AW';
                                                                }else{
                                                                    $visitType = 'AV';
                                                                }
                                                            }else{
                                                                $visitType = 'AW';
                                                            }
                                                        }
                                                        $updateData['visit_type'] = $visitType;
                                                        // if 1st package not completed in it's limited days it will be converted into again 1st package, they will not be converted into 2nd package.
                                                        
                                                        $packageValidity = $packageDetails->validity;
                                                        $nextValidityDate = date('Y-m-d', strtotime($firstDate. ' + '.$packageValidity.' days'));
                                                        if(strtotime($currentDate) <= strtotime($nextValidityDate)){
                                                            // update due days in daily
                                                            $totalDays = $days;
                                                            // $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('amount','!=','')->where('type',2)->count('id');
                                                            $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
                                                            if($workingDays == 0){
                                                                $dueDays = $totalDays - 1;
                                                            }else{
                                                                $dueDays = $totalDays - ($workingDays + 1);
                                                            }
                                                            $updateData['due_days'] = $dueDays;
                                                            $updateData['flag'] = '';
                                                        }else{
                                                            // limited days cross in package daily entry (validity cross convert it into back package)
                                                            // update package with 1 before package
                                                            $updateAppData = array();
                                                            if($packageId == 1){
                                                                $changePackageId = 1;
                                                            }else{
                                                                $changePackageId = 1;
                                                            }
                                                            $updateAppData['package_type'] = $changePackageId;
                                                            Appointment::where('id',$appId)->update($updateAppData);

                                                            // change daily entry with before 1 package details
                                                            $newDays = packageDetails($changePackageId)->days;
                                                            $totalDays = $newDays;
                                                            //$workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('amount','!=','')->where('type',2)->count('id');
                                                            $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('status','complete')->where('type',2)->count('id');
                                                            if($workingDays == 0){
                                                                $dueDays = $totalDays - 1;
                                                            }else{
                                                                $dueDays = $totalDays - ($workingDays + 1);
                                                            }
                                                            $updateData['due_days'] = $dueDays;
                                                            $updateData['flag'] = 'limited_days_cross';
                                                        }
                                                        DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                        // if 1st package complete after that on 2nd package incentive share to therapist
                                                        $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                                                        $packId = $dailyEntryPackageDetails->package_id;
                                                        $packageCommission = packageDetails($packId)->commission;
                                                        if(packageDetails($packId)->name == '1st Package'){
                                                            $base_salaries = $base_salary;
                                                        }else{
                                                            if(!empty($packageCommission) && ($packageCommission != 0)){
                                                                $base_salaries = $base_salary + $packageCommission;   //add insentive to therapist if first package completed
                                                            }else{
                                                                $base_salaries = $base_salary;
                                                            }
                                                        }
                                                        // for package wise base % share to therapist account and due share to capri account
                                                        $amount = $amount + $extAmount;     //if extra amount add in package visit
                                                        $therapistAmt = ($base_salaries / 100) * $amount;
                                                        $capriAmt = $amount - $therapistAmt;
                                                        
                                                        $addAmountAcc = array();
                                                        $addAmountAcc['visit_id'] = $visitId;
                                                        $addAmountAcc['therapist_id'] = $therapistId;
                                                        $addAmountAcc['appointment_id'] = $appId;
                                                        $addAmountAcc['user_id'] = $patientId;
                                                        $addAmountAcc['capri_account'] = $capriAmt;
                                                        $addAmountAcc['therapist_account'] = $therapistAmt;
                                                        $addAmountAcc['total_amount'] = $amount;
                                                        $addAmountAcc['flag'] = 'package';
                                                        $addAmountAcc['payment_date'] = date('Y-m-d');
                                                        DB::table('account')->insert($addAmountAcc);
                                                        // account history 
                                                        $appHistory = array();
                                                        $appHistory['visit_id'] = $visitId;
                                                        $appHistory['appointment_id'] = $appId;
                                                        $appHistory['therapist_id'] = $therapistId;
                                                        $appHistory['capri_account'] = $capriAmt;
                                                        $appHistory['therapist_account'] = $therapistAmt;
                                                        $appHistory['total_amount'] = $amount;
                                                        $appHistory['remark'] = 'package entry';
                                                        $appHistory['created_by'] = $therapistId;
                                                        DB::table('account_history')->insert($appHistory);

                                                        $visitCount = DB::table('daily_entry')->where('appointment_id',$appId)->where('status','complete')->count('id');
                                                        if(($visitCount > 0) && ($visitCount % 7 == 0)){
                                                            $response['message'] = 'Please Reassessment your visits';
                                                        }else{
                                                            $response['message'] = 'Successfully Attend!';
                                                        }
                                                        $response['status'] = '1';
                                                    }
                                                }else{
                                                    // for per day visit inTime entry
                                                    // check amount update or not
                                                    $checkAmountVisit = DB::table('daily_entry')->where('id',$visitId)->where('type',1)->first();
                                                    if($checkAmountVisit){
                                                        if(($appServiceType != 7) && (($checkAmountVisit->amount == null) || ($checkAmountVisit->amount == '') || empty($checkAmountVisit->amount))){
                                                            $response['message'] = 'Please pay amount for these perday visit!';
                                                            $response['status'] = 'fail';
                                                        }else{
                                                            // for per day visit inTime entry
                                                            $therapistId = $checkAmountVisit->therapist_id;
                                                            $booked_date = $dailyEntryDetails->app_booked_date;
                                                            $booked_time = $checkAmountVisit->app_booked_time;
                                                            $time = date("H:i:s");
                                                            $currentDate = date('Y-m-d');
                                                            $firstDate = $checkAmountVisit->app_booked_date;
                                                            $appointmentDetails = appointmentDetails($appId);
                                                            $appDate = $appointmentDetails->appointment_date;
                                                            $patientId = $appointmentDetails->user_id;

                                                            $updateData = array();
                                                            $updateData['in_time'] = $time;
                                                            $updateData['total_seats'] = totalPackageDueDays($appId);
                                                            $updateData['no_of_seats'] = 1;
                                                            $updateData['due_days'] = 0;
                                                            $updateData['status'] = 'pending';
                                                            $userDetails = userDetails($patientId);
                                                            $patientCreatedDate = $userDetails->created_at;
                                                            $pCreatedDate = explode(' ', $patientCreatedDate);
                                                            $patCreatedDate = $pCreatedDate[0];
                                                            $visitCreatedDate = $checkAmountVisit->app_booked_date;
                                                            $vCreatedTime = $checkAmountVisit->created_at;
                                                            $vCrDate = explode(' ', $vCreatedTime);
                                                            $vCreatedDate = $vCrDate[0];
                                                            $visitCreatedTime = $vCrDate[1];
                                                            // For IPD Case
                                                            if(!empty($appServiceType) && ($appServiceType == 7)){
                                                                // For IPD Patient only
                                                                $updateData['visit_type'] = 'ipd';
                                                                DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                                $response['message'] = 'Successfully Attend!';
                                                                $response['status'] = '1';
                                                            }elseif(!empty($appServiceType) && (($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1))){
                                                                // for home care patient only
                                                                // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
                                                                $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));
                                                                if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                    $visitType = 'AW';
                                                                }else{
                                                                    if($currentDate == $firstDate){
                                                                        $nextTime = strtotime("+10 minutes", strtotime($booked_time));
                                                                        if($nextTime <= strtotime($time)){    
                                                                            $visitType = 'AW';
                                                                        }else{
                                                                            $visitType = 'AV';
                                                                        }
                                                                    }else{
                                                                        $visitType = 'AW';
                                                                    }
                                                                }

                                                                $updateData['visit_type'] = $visitType;
                                                                DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                                $privateBaseSalary = 70;            //only for home care patients
                                                                $amount = $dailyEntryDetails->amount;
                                                                $extAmount = $dailyEntryDetails->extra_amount;
                                                                $amount = $amount + $extAmount;     //if extra amount add in package visit
                                                                $therapistAmt = ($privateBaseSalary / 100) * $amount;
                                                                $capriAmt = $amount - $therapistAmt;
                                                                $addAmountAcc = array();
                                                                $addAmountAcc['visit_id'] = $visitId;
                                                                $addAmountAcc['therapist_id'] = $therapistId;
                                                                $addAmountAcc['appointment_id'] = $appId;
                                                                $addAmountAcc['user_id'] = $patientId;
                                                                $addAmountAcc['capri_account'] = $capriAmt;
                                                                $addAmountAcc['therapist_account'] = $therapistAmt;
                                                                $addAmountAcc['total_amount'] = $amount;
                                                                $addAmountAcc['flag'] = 'perday';
                                                                $addAmountAcc['transection_status'] = '';
                                                                $addAmountAcc['transection_id'] = '';
                                                                $addAmountAcc['payment_date'] = date("Y-m-d");
                                                                $addAmountAcc['created_by'] = $therapistId;
                                                                DB::table('account')->insert($addAmountAcc);
                                                                // account history 
                                                                $appHistory = array();
                                                                $appHistory['visit_id'] = $visitId;
                                                                $appHistory['appointment_id'] = $appId;
                                                                $appHistory['therapist_id'] = $therapistId;
                                                                $appHistory['capri_account'] = $capriAmt;
                                                                $appHistory['therapist_account'] = $therapistAmt;
                                                                $appHistory['total_amount'] = $amount;
                                                                $appHistory['remark'] = 'perday entry';
                                                                $appHistory['created_by'] = $therapistId;
                                                                $appHistory['transection_status'] = '';
                                                                $appHistory['transection_id'] = '';
                                                                $appHistory['payment_date'] = date("Y-m-d");
                                                                DB::table('account_history')->insert($appHistory);
                                                                $response['message'] = 'Successfully Attend!';
                                                                $response['status'] = '1';
                                                            }else{
                                                                // For OPD Patient only
                                                                // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
                                                                $next4hrTime = date('H:i:s',strtotime('+4 hour',strtotime($visitCreatedTime)));
                                                                if((strtotime($currentDate) == strtotime($vCreatedDate)) && (strtotime($time) < strtotime($next4hrTime)) && (strtotime($currentDate) != strtotime($patCreatedDate))){
                                                                    $visitType = 'AW';
                                                                }else{
                                                                    if($currentDate == $firstDate){
                                                                        $nextTime = strtotime("+10 minutes", strtotime($booked_time));
                                                                        if($nextTime <= strtotime($time)){    
                                                                            $visitType = 'AW';
                                                                        }else{
                                                                            $visitType = 'AV';
                                                                        }
                                                                    }else{
                                                                        $visitType = 'AW';
                                                                    }
                                                                }

                                                                $updateData['visit_type'] = $visitType;
                                                                DB::table('daily_entry')->where('id',$visitId)->update($updateData);
                                                                $amount = $checkAmountVisit->amount;
                                                                $baseAmount = userDetails($therapistId)->base_commision;
                                                                // on per day entry, total amount is greater then 1500 then share % in therapist account within 4 days
                                                                if(!empty($booked_date) && !empty($amount)){
                                                                    $next15Day = date('Y-m-d', strtotime($appDate. ' + 4 days'));
                                                                    $totalVisitAmount = DB::table('daily_entry')->where('appointment_id',$appId)->where('type',1)->sum('amount');
                                                                    if((strtotime($currentDate) <= strtotime($next15Day)) && ($totalVisitAmount >= '1500')){
                                                                        // only consultation amount send in capri account nd In therapist account of base amount % share
                                                                        $addAmountAcc = array();
                                                                        $addAmountAcc['visit_id'] = $visitId;
                                                                        $addAmountAcc['therapist_id'] = $therapistId;
                                                                        $addAmountAcc['appointment_id'] = $appId;
                                                                        $addAmountAcc['user_id'] = $patientId;
                                                                        $finalAmt = $totalVisitAmount;
                                                                        $percentage = $baseAmount;
                                                                        $amtamt = ($percentage / 100) * $finalAmt;
                                                                        $addAmountAcc['therapist_account'] = $amtamt;
                                                                        $addAmountAcc['capri_account'] = $totalVisitAmount - $amtamt;
                                                                        $addAmountAcc['total_amount'] = $totalVisitAmount;
                                                                        $addAmountAcc['flag'] = 'perday';
                                                                        $addAmountAcc['payment_date'] = date('Y-m-d');
                                                                        DB::table('account')->insert($addAmountAcc);

                                                                        // appointment history
                                                                        $appId = $checkAmountVisit->appointment_id;
                                                                        $appHistory = array();
                                                                        $appHistory['visit_id'] = $visitId;
                                                                        $appHistory['appointment_id'] = $appId;
                                                                        $appHistory['therapist_id'] = $therapistId;
                                                                        $appHistory['capri_account'] = $totalVisitAmount - $amtamt;
                                                                        $appHistory['therapist_account'] = $amtamt;
                                                                        $appHistory['total_amount'] = $totalVisitAmount;
                                                                        $appHistory['remark'] = 'Daily per day entry';
                                                                        $appHistory['created_by'] = $therapistId;
                                                                        DB::table('account_history')->insert($appHistory);
                                                                    }elseif((strtotime($currentDate) <= strtotime($next15Day)) && ($totalVisitAmount <= '1500')){
                                                                        // send all amount in capri account
                                                                        $addAmountAcc = array();
                                                                        $addAmountAcc['visit_id'] = $visitId;
                                                                        $addAmountAcc['therapist_id'] = $therapistId;
                                                                        $addAmountAcc['appointment_id'] = $appId;
                                                                        $addAmountAcc['user_id'] = $patientId;
                                                                        $addAmountAcc['capri_account'] = $totalVisitAmount;
                                                                        $addAmountAcc['total_amount'] = $totalVisitAmount;
                                                                        $addAmountAcc['flag'] = 'perday';
                                                                        $addAmountAcc['payment_date'] = date('Y-m-d');
                                                                        DB::table('account')->insert($addAmountAcc);

                                                                        // appointment history
                                                                        $appHistory = array();
                                                                        $appHistory['visit_id'] = $visitId;
                                                                        $appHistory['appointment_id'] = $appId;
                                                                        $appHistory['therapist_id'] = $therapistId;
                                                                        $appHistory['capri_account'] = $totalVisitAmount;
                                                                        $appHistory['therapist_account'] = '';
                                                                        $appHistory['total_amount'] = $totalVisitAmount;
                                                                        $appHistory['remark'] = 'Daily per day entry';
                                                                        $appHistory['created_by'] = $therapistId;
                                                                        DB::table('account_history')->insert($appHistory);
                                                                    }else{
                                                                        // send all amount in capri account
                                                                        $addAmountAcc = array();
                                                                        $addAmountAcc['visit_id'] = $visitId;
                                                                        $addAmountAcc['therapist_id'] = $therapistId;
                                                                        $addAmountAcc['appointment_id'] = $appId;
                                                                        $addAmountAcc['user_id'] = $patientId;
                                                                        $addAmountAcc['capri_account'] = $amount;
                                                                        $addAmountAcc['total_amount'] = $amount;
                                                                        $addAmountAcc['flag'] = 'perday';
                                                                        $addAmountAcc['transection_status'] = '';
                                                                        $addAmountAcc['transection_id'] = '';
                                                                        $addAmountAcc['payment_date'] = date("Y-m-d");
                                                                        $addAmountAcc['created_by'] = $therapistId;
                                                                        DB::table('account')->insert($addAmountAcc);

                                                                        // appointment history
                                                                        $appHistory = array();
                                                                        $appHistory['visit_id'] = $visitId;
                                                                        $appHistory['appointment_id'] = $appId;
                                                                        $appHistory['therapist_id'] = $therapistId;
                                                                        $appHistory['capri_account'] = $amount;
                                                                        $appHistory['therapist_account'] = '';
                                                                        $appHistory['total_amount'] = $amount;
                                                                        $appHistory['remark'] = 'Daily per day entry';
                                                                        $appHistory['created_by'] = $therapistId;
                                                                        $appHistory['transection_status'] = '';
                                                                        $appHistory['transection_id'] = '';
                                                                        $appHistory['payment_date'] = date("Y-m-d");
                                                                        DB::table('account_history')->insert($appHistory);
                                                                    }
                                                                }
                                                                $visitCount = DB::table('daily_entry')->where('appointment_id',$appId)->where('status','complete')->count('id');
                                                                if(($visitCount > 0) && ($visitCount % 7 == 0)){
                                                                    $response['message'] = 'Please Reassessment your visits';
                                                                }else{
                                                                    $response['message'] = 'Successfully Attend!';
                                                                }
                                                                $response['status'] = '1';
                                                            }
                                                        }
                                                    }else{
                                                        $response['message'] = 'Visit not exist!';
                                                        $response['status'] = '0';
                                                    }                                
                                                }
                                            }
                                        }else{
                                            $response['message'] = 'Please Update Package or Perday Visit!';
                                            $response['status'] = '0';
                                        }
                                    }else{
                                        $response['message'] = 'Your appointment is in pending status, contact to CapriSpine Team!';
                                        $response['status'] = '0';
                                    }
                                }
                            }else{
                                $response['message'] = 'Visit not exist!';
                                $response['status'] = '0';
                            }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }
    
    public function setExpirePackageStatus($appId,$lastVisitId){
        // try{
            $allData = DailyEntry::where('appointment_id',$appId)->where('type',2)->where('id','<=',$lastVisitId)->get();
            if(count($allData) > 0){
                foreach ($allData as $allVal) {
                    $dataUp = array();
                    $dataUp['secondFlag'] = 'expireVisit';
                    DailyEntry::where('id',$allVal->id)->update($dataUp);
                }
                return true;
            }else{
                return true;
            }
        // }catch(\Exception $e){
        //     $response['message'] = 'Something went wrong!';
        //     $response['status'] = '0';
        // }
    }

    public function addMotorExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('userId') && Input::has('visitId') && Input::has('flag')){
                $patientId = $request->patientId;
                $therapistId = $request->userId;
                $visitId = $request->visitId;
                $flag = $request->flag;
                $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                if($checkPatient){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        if(!empty($patientId) && !empty($therapistId) && !empty($visitId) && !empty($flag)){
                            if($flag == 'combinedSpine'){
                                if(Input::has('cervical_spine') || !empty($request->cervical_spine) || Input::has('thoracic_spine') || Input::has('lumbar_spine')){
                                    $combinedSpine = array();
                                    $combinedSpine['patient_id'] = $patientId;
                                    $combinedSpine['visit_id'] = $visitId;
                                    $combinedSpine['therapist_id'] = $therapistId;
                                    $combinedSpine['cervical_spine'] = $request->cervical_spine;
                                    $combinedSpine['thoracic_spine'] = $request->thoracic_spine;
                                    $combinedSpine['lumbar_spine'] = $request->lumbar_spine;
                                    $combinedSpine['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_combined_spine')->insert($combinedSpine);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'cervicalSpine'){
                                if(Input::has('flexion') && !empty($request->flexion) && Input::has('extension') && Input::has('sideFlexionLeft') && Input::has('sideFlexionRight') && Input::has('rotationLeft') && Input::has('rotationRight')){
                                    $cervicalSpine = array();
                                    $cervicalSpine['flag'] = 'cervicalSpine';
                                    $cervicalSpine['patient_id'] = $patientId;
                                    $cervicalSpine['therapist_id'] = $therapistId;
                                    $cervicalSpine['visit_id'] = $visitId;
                                    $cervicalSpine['flexion'] = $request->flexion;
                                    $cervicalSpine['extension'] = $request->extension;
                                    $cervicalSpine['sideFlexionLeft'] = $request->sideFlexionLeft;
                                    $cervicalSpine['sideFlexionRight'] = $request->sideFlexionRight;
                                    $cervicalSpine['rotationLeft'] = $request->rotationLeft;
                                    $cervicalSpine['rotationRight'] = $request->rotationRight;
                                    $cervicalSpine['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_cervical_spine')->insert($cervicalSpine);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'thoracicSpine'){
                                if(Input::has('flexion') && !empty($request->flexion) && Input::has('extension') && Input::has('sideFlexionLeft') && Input::has('sideFlexionRight') && Input::has('rotationLeft') && Input::has('rotationRight')){
                                    $cervicalSpine = array();
                                    $cervicalSpine['flag'] = 'thoracicSpine';
                                    $cervicalSpine['patient_id'] = $patientId;
                                    $cervicalSpine['therapist_id'] = $therapistId;
                                    $cervicalSpine['visit_id'] = $visitId;
                                    $cervicalSpine['flexion'] = $request->flexion;
                                    $cervicalSpine['extension'] = $request->extension;
                                    $cervicalSpine['sideFlexionLeft'] = $request->sideFlexionLeft;
                                    $cervicalSpine['sideFlexionRight'] = $request->sideFlexionRight;
                                    $cervicalSpine['rotationLeft'] = $request->rotationLeft;
                                    $cervicalSpine['rotationRight'] = $request->rotationRight;
                                    $cervicalSpine['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_cervical_spine')->insert($cervicalSpine);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'lumbarSpine'){
                                if(Input::has('flexion') && !empty($request->flexion) && Input::has('extension') && Input::has('sideFlexionLeft') && Input::has('sideFlexionRight') && Input::has('rotationLeft') && Input::has('rotationRight')){
                                    $cervicalSpine = array();
                                    $cervicalSpine['flag'] = 'lumbarSpine';
                                    $cervicalSpine['patient_id'] = $patientId;
                                    $cervicalSpine['therapist_id'] = $therapistId;
                                    $cervicalSpine['visit_id'] = $visitId;
                                    $cervicalSpine['flexion'] = $request->flexion;
                                    $cervicalSpine['extension'] = $request->extension;
                                    $cervicalSpine['sideFlexionLeft'] = $request->sideFlexionLeft;
                                    $cervicalSpine['sideFlexionRight'] = $request->sideFlexionRight;
                                    $cervicalSpine['rotationLeft'] = $request->rotationLeft;
                                    $cervicalSpine['rotationRight'] = $request->rotationRight;
                                    $cervicalSpine['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_cervical_spine')->insert($cervicalSpine);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'hip'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('extRotationLeftTone') && Input::has('extRotationLeftPower') && Input::has('extRotationLeftROM') && Input::has('intRotationLeftTone') && Input::has('intRotationLeftPower') && Input::has('intRotationLeftROM') && Input::has('abductionLeftTone') && Input::has('abductionLeftPower') && Input::has('abductionLeftROM') && Input::has('adductionLeftTone') && Input::has('adductionLeftPower') && Input::has('adductionLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM') && Input::has('extRotationRightTone') && Input::has('extRotationRightPower') && Input::has('extRotationRightROM') && Input::has('intRotationRightTone') && Input::has('intRotationRightPower') && Input::has('intRotationRightROM') && Input::has('abductionRightTone') && Input::has('abductionRightPower') && Input::has('abductionRightROM') && Input::has('adductionRightTone') && Input::has('adductionRightPower') && Input::has('adductionRightROM')){
                                    $addHipExam = array();
                                    $addHipExam['flag'] = 'hip';
                                    $addHipExam['patient_id'] = $patientId;
                                    $addHipExam['therapist_id'] = $therapistId;
                                    $addHipExam['visit_id'] = $visitId;
                                    $addHipExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addHipExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addHipExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addHipExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addHipExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addHipExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addHipExam['extRotationLeftTone'] = $request->extRotationLeftTone;
                                    $addHipExam['extRotationLeftPower'] = $request->extRotationLeftPower;
                                    $addHipExam['extRotationLeftROM'] = $request->extRotationLeftROM;
                                    $addHipExam['intRotationLeftTone'] = $request->intRotationLeftTone;
                                    $addHipExam['intRotationLeftPower'] = $request->intRotationLeftPower;
                                    $addHipExam['intRotationLeftROM'] = $request->intRotationLeftROM;
                                    $addHipExam['abductionLeftTone'] = $request->abductionLeftTone;
                                    $addHipExam['abductionLeftPower'] = $request->abductionLeftPower;
                                    $addHipExam['abductionLeftROM'] = $request->abductionLeftROM;
                                    $addHipExam['adductionLeftTone'] = $request->adductionLeftTone;
                                    $addHipExam['adductionLeftPower'] = $request->adductionLeftPower;
                                    $addHipExam['adductionLeftROM'] = $request->adductionLeftROM;
                                    $addHipExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addHipExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addHipExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addHipExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addHipExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addHipExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addHipExam['extRotationRightTone'] = $request->extRotationRightTone;
                                    $addHipExam['extRotationRightPower'] = $request->extRotationRightPower;
                                    $addHipExam['extRotationRightROM'] = $request->extRotationRightROM;
                                    $addHipExam['intRotationRightTone'] = $request->intRotationRightTone;
                                    $addHipExam['intRotationRightPower'] = $request->intRotationRightPower;
                                    $addHipExam['intRotationRightROM'] = $request->intRotationRightROM;
                                    $addHipExam['abductionRightTone'] = $request->abductionRightTone;
                                    $addHipExam['abductionRightPower'] = $request->abductionRightPower;
                                    $addHipExam['abductionRightROM'] = $request->abductionRightROM;
                                    $addHipExam['adductionRightTone'] = $request->adductionRightTone;
                                    $addHipExam['adductionRightPower'] = $request->adductionRightPower;
                                    $addHipExam['adductionRightROM'] = $request->adductionRightROM;
                                    $addHipExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addHipExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'knee'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM')){
                                    $addKneeExam = array();
                                    $addKneeExam['flag'] = 'knee';
                                    $addKneeExam['patient_id'] = $patientId;
                                    $addKneeExam['therapist_id'] = $therapistId;
                                    $addKneeExam['visit_id'] = $visitId;
                                    $addKneeExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addKneeExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addKneeExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addKneeExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addKneeExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addKneeExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addKneeExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addKneeExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addKneeExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addKneeExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addKneeExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addKneeExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addKneeExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addKneeExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'ankle'){
                                if(Input::has('plantFlexLeftTone') && !empty($request->plantFlexLeftTone) && Input::has('plantFlexLeftPower') && Input::has('plantFlexLeftROM') && Input::has('dorsiFlexLeftTone') && Input::has('dorsiFlexLeftPower') && Input::has('dorsiFlexLeftROM') && Input::has('eversionLeftTone') && Input::has('eversionLeftPower') && Input::has('eversionLeftROM') && Input::has('inversionLeftTone') && Input::has('inversionLeftPower') && Input::has('inversionLeftROM') && Input::has('plantFlexRightTone') && Input::has('plantFlexRightPower') && Input::has('plantFlexRightROM') && Input::has('dorsiFlexRightTone') && Input::has('dorsiFlexRightPower') && Input::has('dorsiFlexRightROM') && Input::has('eversionRightTone') && Input::has('eversionRightPower') && Input::has('eversionRightROM') && Input::has('inversionRightTone') && Input::has('inversionRightPower') && Input::has('inversionRightROM')){
                                    $addAnkleExam = array();
                                    $addAnkleExam['patient_id'] = $patientId;
                                    $addAnkleExam['therapist_id'] = $therapistId;
                                    $addAnkleExam['visit_id'] = $visitId;
                                    $addAnkleExam['plantFlexLeftTone'] = $request->plantFlexLeftTone;
                                    $addAnkleExam['plantFlexLeftPower'] = $request->plantFlexLeftPower;
                                    $addAnkleExam['plantFlexLeftROM'] = $request->plantFlexLeftROM;
                                    $addAnkleExam['dorsiFlexLeftTone'] = $request->dorsiFlexLeftTone;
                                    $addAnkleExam['dorsiFlexLeftPower'] = $request->dorsiFlexLeftPower;
                                    $addAnkleExam['dorsiFlexLeftROM'] = $request->dorsiFlexLeftROM;
                                    $addAnkleExam['eversionLeftTone'] = $request->eversionLeftTone;
                                    $addAnkleExam['eversionLeftPower'] = $request->eversionLeftPower;
                                    $addAnkleExam['eversionLeftROM'] = $request->eversionLeftROM;
                                    $addAnkleExam['inversionLeftTone'] = $request->inversionLeftTone;
                                    $addAnkleExam['inversionLeftPower'] = $request->inversionLeftPower;
                                    $addAnkleExam['inversionLeftROM'] = $request->inversionLeftROM;
                                    $addAnkleExam['plantFlexRightTone'] = $request->plantFlexRightTone;
                                    $addAnkleExam['plantFlexRightPower'] = $request->plantFlexRightPower;
                                    $addAnkleExam['plantFlexRightROM'] = $request->plantFlexRightROM;
                                    $addAnkleExam['dorsiFlexRightTone'] = $request->dorsiFlexRightTone;
                                    $addAnkleExam['dorsiFlexRightPower'] = $request->dorsiFlexRightPower;
                                    $addAnkleExam['dorsiFlexRightROM'] = $request->dorsiFlexRightROM;
                                    $addAnkleExam['eversionRightTone'] = $request->eversionRightTone;
                                    $addAnkleExam['eversionRightPower'] = $request->eversionRightPower;
                                    $addAnkleExam['eversionRightROM'] = $request->eversionRightROM;
                                    $addAnkleExam['inversionRightTone'] = $request->inversionRightTone;
                                    $addAnkleExam['inversionRightPower'] = $request->inversionRightPower;
                                    $addAnkleExam['inversionRightROM'] = $request->inversionRightROM;
                                    $addAnkleExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_ankle_exam')->insert($addAnkleExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag === 'toes'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM')){
                                    $addKneeExam = array();
                                    $addKneeExam['flag'] = 'toes';
                                    $addKneeExam['patient_id'] = $patientId;
                                    $addKneeExam['therapist_id'] = $therapistId;
                                    $addKneeExam['visit_id'] = $visitId;
                                    $addKneeExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addKneeExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addKneeExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addKneeExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addKneeExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addKneeExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addKneeExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addKneeExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addKneeExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addKneeExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addKneeExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addKneeExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addKneeExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addKneeExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'shoulder'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('extRotationLeftTone') && Input::has('extRotationLeftPower') && Input::has('extRotationLeftROM') && Input::has('intRotationLeftTone') && Input::has('intRotationLeftPower') && Input::has('intRotationLeftROM') && Input::has('abductionLeftTone') && Input::has('abductionLeftPower') && Input::has('abductionLeftROM') && Input::has('adductionLeftTone') && Input::has('adductionLeftPower') && Input::has('adductionLeftROM') && Input::has('hrAbdLeftTone') && Input::has('hrAbdLeftPower') && Input::has('hrAbdLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM') && Input::has('extRotationRightTone') && Input::has('extRotationRightPower') && Input::has('extRotationRightROM') && Input::has('intRotationRightTone') && Input::has('intRotationRightPower') && Input::has('intRotationRightROM') && Input::has('abductionRightTone') && Input::has('abductionRightPower') && Input::has('abductionRightROM') && Input::has('adductionRightTone') && Input::has('adductionRightPower') && Input::has('adductionRightROM') && Input::has('hrAbdRightTone') && Input::has('hrAbdRightPower') && Input::has('hrAbdRightROM')){
                                    $addHipExam = array();
                                    $addHipExam['flag'] = 'shoulder';
                                    $addHipExam['patient_id'] = $patientId;
                                    $addHipExam['therapist_id'] = $therapistId;
                                    $addHipExam['visit_id'] = $visitId;
                                    $addHipExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addHipExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addHipExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addHipExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addHipExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addHipExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addHipExam['extRotationLeftTone'] = $request->extRotationLeftTone;
                                    $addHipExam['extRotationLeftPower'] = $request->extRotationLeftPower;
                                    $addHipExam['extRotationLeftROM'] = $request->extRotationLeftROM;
                                    $addHipExam['intRotationLeftTone'] = $request->intRotationLeftTone;
                                    $addHipExam['intRotationLeftPower'] = $request->intRotationLeftPower;
                                    $addHipExam['intRotationLeftROM'] = $request->intRotationLeftROM;
                                    $addHipExam['abductionLeftTone'] = $request->abductionLeftTone;
                                    $addHipExam['abductionLeftPower'] = $request->abductionLeftPower;
                                    $addHipExam['abductionLeftROM'] = $request->abductionLeftROM;
                                    $addHipExam['adductionLeftTone'] = $request->adductionLeftTone;
                                    $addHipExam['adductionLeftPower'] = $request->adductionLeftPower;
                                    $addHipExam['adductionLeftROM'] = $request->adductionLeftROM;
                                    $addHipExam['hrAbdLeftTone'] = $request->hrAbdLeftTone;
                                    $addHipExam['hrAbdLeftPower'] = $request->hrAbdLeftPower;
                                    $addHipExam['hrAbdLeftROM'] = $request->hrAbdLeftROM;
                                    $addHipExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addHipExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addHipExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addHipExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addHipExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addHipExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addHipExam['extRotationRightTone'] = $request->extRotationRightTone;
                                    $addHipExam['extRotationRightPower'] = $request->extRotationRightPower;
                                    $addHipExam['extRotationRightROM'] = $request->extRotationRightROM;
                                    $addHipExam['intRotationRightTone'] = $request->intRotationRightTone;
                                    $addHipExam['intRotationRightPower'] = $request->intRotationRightPower;
                                    $addHipExam['intRotationRightROM'] = $request->intRotationRightROM;
                                    $addHipExam['abductionRightTone'] = $request->abductionRightTone;
                                    $addHipExam['abductionRightPower'] = $request->abductionRightPower;
                                    $addHipExam['abductionRightROM'] = $request->abductionRightROM;
                                    $addHipExam['adductionRightTone'] = $request->adductionRightTone;
                                    $addHipExam['adductionRightPower'] = $request->adductionRightPower;
                                    $addHipExam['adductionRightROM'] = $request->adductionRightROM;
                                    $addHipExam['hrAbdRightTone'] = $request->hrAbdRightTone;
                                    $addHipExam['hrAbdRightPower'] = $request->hrAbdRightPower;
                                    $addHipExam['hrAbdRightROM'] = $request->hrAbdRightROM;
                                    $addHipExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addHipExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'elbow'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM')){
                                    $addKneeExam = array();
                                    $addKneeExam['flag'] = 'elbow';
                                    $addKneeExam['patient_id'] = $patientId;
                                    $addKneeExam['therapist_id'] = $therapistId;
                                    $addKneeExam['visit_id'] = $visitId;
                                    $addKneeExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addKneeExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addKneeExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addKneeExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addKneeExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addKneeExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addKneeExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addKneeExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addKneeExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addKneeExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addKneeExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addKneeExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addKneeExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addKneeExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'forearm'){
                                if(Input::has('supinationLeftTone') && !empty($request->supinationLeftTone) && Input::has('supinationLeftPower') && Input::has('supinationLeftROM') && Input::has('pronationLeftTone') && Input::has('pronationLeftPower') && Input::has('pronationLeftROM') && Input::has('supinationRightTone') && Input::has('supinationRightPower') && Input::has('supinationRightROM') && Input::has('pronationRightTone') && Input::has('pronationRightPower') && Input::has('pronationRightROM')){
                                    $addKneeExam = array();
                                    $addKneeExam['flag'] = 'forearm';
                                    $addKneeExam['patient_id'] = $patientId;
                                    $addKneeExam['therapist_id'] = $therapistId;
                                    $addKneeExam['visit_id'] = $visitId;
                                    $addKneeExam['supinationLeftTone'] = $request->supinationLeftTone;
                                    $addKneeExam['supinationLeftPower'] = $request->supinationLeftPower;
                                    $addKneeExam['supinationLeftROM'] = $request->supinationLeftROM;
                                    $addKneeExam['pronationLeftTone'] = $request->pronationLeftTone;
                                    $addKneeExam['pronationLeftPower'] = $request->pronationLeftPower;
                                    $addKneeExam['pronationLeftROM'] = $request->pronationLeftROM;
                                    $addKneeExam['supinationRightTone'] = $request->supinationRightTone;
                                    $addKneeExam['supinationRightPower'] = $request->supinationRightPower;
                                    $addKneeExam['supinationRightROM'] = $request->supinationRightROM;
                                    $addKneeExam['pronationRightTone'] = $request->pronationRightTone;
                                    $addKneeExam['pronationRightPower'] = $request->pronationRightPower;
                                    $addKneeExam['pronationRightROM'] = $request->pronationRightROM;
                                    $addKneeExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addKneeExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'wrist'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM')){
                                    $addKneeExam = array();
                                    $addKneeExam['flag'] = 'wrist';
                                    $addKneeExam['patient_id'] = $patientId;
                                    $addKneeExam['therapist_id'] = $therapistId;
                                    $addKneeExam['visit_id'] = $visitId;
                                    $addKneeExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addKneeExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addKneeExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addKneeExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addKneeExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addKneeExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addKneeExam['radialDevLeftTone'] = $request->radialDevLeftTone;
                                    $addKneeExam['radialDevLeftPower'] = $request->radialDevLeftPower;
                                    $addKneeExam['radialDevLeftROM'] = $request->radialDevLeftROM;
                                    $addKneeExam['ulnarDevLeftTone'] = $request->ulnarDevLeftTone;
                                    $addKneeExam['ulnarDevLeftPower'] = $request->ulnarDevLeftPower;
                                    $addKneeExam['ulnarDevLeftROM'] = $request->ulnarDevLeftROM;
                                    $addKneeExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addKneeExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addKneeExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addKneeExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addKneeExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addKneeExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addKneeExam['radialDevRightTone'] = $request->radialDevRightTone;
                                    $addKneeExam['radialDevRightPower'] = $request->radialDevRightPower;
                                    $addKneeExam['radialDevRightROM'] = $request->radialDevRightROM;
                                    $addKneeExam['ulnarDevRightTone'] = $request->ulnarDevRightTone;
                                    $addKneeExam['ulnarDevRightPower'] = $request->ulnarDevRightPower;
                                    $addKneeExam['ulnarDevRightROM'] = $request->ulnarDevRightROM;
                                    $addKneeExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addKneeExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'fingers'){
                                if(Input::has('flexionLeftTone') && !empty($request->flexionLeftTone) && Input::has('flexionLeftPower') && Input::has('flexionLeftROM') && Input::has('extensionLeftTone') && Input::has('extensionLeftPower') && Input::has('extensionLeftROM') && Input::has('abductionLeftTone') && Input::has('abductionLeftPower') && Input::has('abductionLeftROM') && Input::has('adductionLeftTone') && Input::has('adductionLeftPower') && Input::has('adductionLeftROM') && Input::has('flexionRightTone') && Input::has('flexionRightPower') && Input::has('flexionRightROM') && Input::has('extensionRightTone') && Input::has('extensionRightPower') && Input::has('extensionRightROM') && Input::has('abductionRightTone') && Input::has('abductionRightPower') && Input::has('abductionRightROM') && Input::has('adductionRightTone') && Input::has('adductionRightPower') && Input::has('adductionRightROM')){
                                    $addKneeExam = array();
                                    $addKneeExam['flag'] = 'fingers';
                                    $addKneeExam['patient_id'] = $patientId;
                                    $addKneeExam['therapist_id'] = $therapistId;
                                    $addKneeExam['visit_id'] = $visitId;
                                    $addKneeExam['flexionLeftTone'] = $request->flexionLeftTone;
                                    $addKneeExam['flexionLeftPower'] = $request->flexionLeftPower;
                                    $addKneeExam['flexionLeftROM'] = $request->flexionLeftROM;
                                    $addKneeExam['extensionLeftTone'] = $request->extensionLeftTone;
                                    $addKneeExam['extensionLeftPower'] = $request->extensionLeftPower;
                                    $addKneeExam['extensionLeftROM'] = $request->extensionLeftROM;
                                    $addKneeExam['abductionLeftTone'] = $request->abductionLeftTone;
                                    $addKneeExam['abductionLeftPower'] = $request->abductionLeftPower;
                                    $addKneeExam['abductionLeftROM'] = $request->abductionLeftROM;
                                    $addKneeExam['adductionLeftTone'] = $request->adductionLeftTone;
                                    $addKneeExam['adductionLeftPower'] = $request->adductionLeftPower;
                                    $addKneeExam['adductionLeftROM'] = $request->adductionLeftROM;
                                    $addKneeExam['flexionRightTone'] = $request->flexionRightTone;
                                    $addKneeExam['flexionRightPower'] = $request->flexionRightPower;
                                    $addKneeExam['flexionRightROM'] = $request->flexionRightROM;
                                    $addKneeExam['extensionRightTone'] = $request->extensionRightTone;
                                    $addKneeExam['extensionRightPower'] = $request->extensionRightPower;
                                    $addKneeExam['extensionRightROM'] = $request->extensionRightROM;
                                    $addKneeExam['abductionRightTone'] = $request->abductionRightTone;
                                    $addKneeExam['abductionRightPower'] = $request->abductionRightPower;
                                    $addKneeExam['abductionRightROM'] = $request->abductionRightROM;
                                    $addKneeExam['adductionRightTone'] = $request->adductionRightTone;
                                    $addKneeExam['adductionRightPower'] = $request->adductionRightPower;
                                    $addKneeExam['adductionRightROM'] = $request->adductionRightROM;
                                    $addKneeExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_hip_exam')->insert($addKneeExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else if($flag == 'sacrollicJoint'){
                                if(Input::has('antInnominateLeft') && !empty($request->antInnominateLeft) && Input::has('postInnominateLeft') && Input::has('upSlipLeft') && Input::has('downSlipLeft') && Input::has('antTiltLeft') && Input::has('postTiltLeft') && Input::has('nutationLeft') && Input::has('counterNutationLeft') && Input::has('antInnominateRight') && Input::has('postInnominateRight') && Input::has('upSlipRight') && Input::has('downSlipRight') && Input::has('antTiltRight') && Input::has('postTiltRight') && Input::has('nutationRight') && Input::has('counterNutationRight')){
                                    $addSacrollicExam = array();
                                    $addSacrollicExam['patient_id'] = $patientId;
                                    $addSacrollicExam['therapist_id'] = $therapistId;
                                    $addSacrollicExam['visit_id'] = $visitId;
                                    $addSacrollicExam['antInnominateLeft'] = $request->antInnominateLeft;
                                    $addSacrollicExam['postInnominateLeft'] = $request->postInnominateLeft;
                                    $addSacrollicExam['upSlipLeft'] = $request->upSlipLeft;
                                    $addSacrollicExam['downSlipLeft'] = $request->downSlipLeft;
                                    $addSacrollicExam['antTiltLeft'] = $request->antTiltLeft;
                                    $addSacrollicExam['postTiltLeft'] = $request->postTiltLeft;
                                    $addSacrollicExam['nutationLeft'] = $request->nutationLeft;
                                    $addSacrollicExam['counterNutationLeft'] = $request->counterNutationLeft;
                                    $addSacrollicExam['antInnominateRight'] = $request->antInnominateRight;
                                    $addSacrollicExam['postInnominateRight'] = $request->postInnominateRight;
                                    $addSacrollicExam['upSlipRight'] = $request->upSlipRight;
                                    $addSacrollicExam['downSlipRight'] = $request->downSlipRight;
                                    $addSacrollicExam['antTiltRight'] = $request->antTiltRight;
                                    $addSacrollicExam['postTiltRight'] = $request->postTiltRight;
                                    $addSacrollicExam['nutationRight'] = $request->nutationRight;
                                    $addSacrollicExam['counterNutationRight'] = $request->counterNutationRight;
                                    $addSacrollicExam['created_at'] = date('Y-m-d H:i:s');
                                    DB::table('mt_sacrollic_exam')->insert($addSacrollicExam);
                                    $response['message'] = 'Successfully Saved!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Fields are mandatory!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid flag!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Mandatory Fields cant be empty!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Patient not exist!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allMotorExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('flag')){
                $patientId = $request->patientId;
                $flag = $request->flag;
                if(!empty($patientId) && !empty($flag)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        if($flag == 'cervicalSpine'){
                            $allData = DB::table('mt_cervical_spine')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexion','extension','sideFlexionLeft','sideFlexionRight','rotationLeft','rotationRight','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'combinedSpine'){
                            $allData = DB::table('mt_combined_spine')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','cervical_spine','thoracic_spine','lumbar_spine','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'thoracicSpine'){
                            $allData = DB::table('mt_cervical_spine')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexion','extension','sideFlexionLeft','sideFlexionRight','rotationLeft','rotationRight','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'lumbarSpine'){
                            $allData = DB::table('mt_cervical_spine')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexion','extension','sideFlexionLeft','sideFlexionRight','rotationLeft','rotationRight','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'hip'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','extRotationLeftTone','extRotationLeftPower','extRotationLeftROM','intRotationLeftTone','intRotationLeftPower','intRotationLeftROM','abductionLeftTone','abductionLeftPower','abductionLeftROM','adductionLeftTone','adductionLeftPower','adductionLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','extRotationRightTone','extRotationRightPower','extRotationRightROM','intRotationRightTone','intRotationRightPower','intRotationRightROM','abductionRightTone','abductionRightPower','abductionRightROM','adductionRightTone','adductionRightPower','adductionRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'knee'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'ankle'){
                            $allData = DB::table('mt_ankle_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','plantFlexLeftTone','plantFlexLeftPower','plantFlexLeftROM','dorsiFlexLeftTone','dorsiFlexLeftPower','dorsiFlexLeftROM','eversionLeftTone','eversionLeftPower','eversionLeftROM','inversionLeftTone','inversionLeftPower','inversionLeftROM','plantFlexRightTone','plantFlexRightPower','plantFlexRightROM','dorsiFlexRightTone','dorsiFlexRightPower','dorsiFlexRightROM','eversionRightTone','eversionRightPower','eversionRightROM','inversionRightTone','inversionRightPower','inversionRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'toes'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'shoulder'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','extRotationLeftTone','extRotationLeftPower','extRotationLeftROM','intRotationLeftTone','intRotationLeftPower','intRotationLeftROM','abductionLeftTone','abductionLeftPower','abductionLeftROM','adductionLeftTone','adductionLeftPower','adductionLeftROM','hrAbdLeftTone','hrAbdLeftPower','hrAbdLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','extRotationRightTone','extRotationRightPower','extRotationRightROM','intRotationRightTone','intRotationRightPower','intRotationRightROM','abductionRightTone','abductionRightPower','abductionRightROM','adductionRightTone','adductionRightPower','adductionRightROM','hrAbdRightTone','hrAbdRightPower','hrAbdRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'elbow'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'forearm'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','supinationLeftTone','supinationLeftPower','supinationLeftROM','pronationLeftTone','pronationLeftPower','pronationLeftROM','supinationRightTone','supinationRightPower','supinationRightROM','pronationRightTone','pronationRightPower','pronationRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'wrist'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','radialDevLeftTone','radialDevLeftPower','radialDevLeftROM','ulnarDevLeftTone','ulnarDevLeftPower','ulnarDevLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','radialDevRightTone','radialDevRightPower','radialDevRightROM','ulnarDevRightTone','ulnarDevRightPower','ulnarDevRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'fingers'){
                            $allData = DB::table('mt_hip_exam')->where('patient_id',$patientId)->where('flag',$flag)->select('visit_id','therapist_id','patient_id','flexionLeftTone','flexionLeftPower','flexionLeftROM','extensionLeftTone','extensionLeftPower','extensionLeftROM','abductionLeftTone','abductionLeftPower','abductionLeftROM','adductionLeftTone','adductionLeftPower','adductionLeftROM','flexionRightTone','flexionRightPower','flexionRightROM','extensionRightTone','extensionRightPower','extensionRightROM','abductionRightTone','abductionRightPower','abductionRightROM','adductionRightTone','adductionRightPower','adductionRightROM','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'sacrollicJoint'){
                            $allData = DB::table('mt_sacrollic_exam')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','antInnominateLeft','postInnominateLeft','upSlipLeft','downSlipLeft','antTiltLeft','postTiltLeft','nutationLeft','counterNutationLeft','antInnominateRight','postInnominateRight','upSlipRight','downSlipRight','antTiltRight','postTiltRight','nutationRight','counterNutationRight','created_at')->orderBy('id','DESC')->get();
                            if(count($allData) > 0){
                                foreach($allData as $value){
                                    $thName = User::where('id',$value->therapist_id)->first();
                                    $value->therapistName = $thName->name;
                                    $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                    array_walk_recursive($value, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Data fetch successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allMachineNames(Request $request){   //Procedure Auto suggestion API
        try{
            $allData = DB::table('machine')->select('name')->groupBy('name')->get();
            if(count($allData) > 0){
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allData;
            }else{
                $response['message'] = 'Machines not available!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function doseAutoSuggestion(Request $request){
        try{
            $allData = DB::table('dose')->select('name')->groupBy('name')->get();
            if(count($allData) > 0){
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allData;
            }else{
                $response['message'] = 'Dose not available!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function goalAutoSuggestion(Request $request){
        try{
            $allData = DB::table('goal')->select('name')->groupBy('name')->get();
            if(count($allData) > 0){
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allData;
            }else{
                $response['message'] = 'Goal not available!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function treatmentGoal(Request $request){
        if(Input::has('patientId') && Input::has('visitId') && Input::has('userId') && Input::has('shortGoal') && Input::has('shortMachine') && Input::has('shortDose') && Input::has('longGoal') && Input::has('longMachine') && Input::has('longDose')){
            $patientId = $request->patientId;
            $therapistId = $request->userId;
            $visitId = $request->visitId;
            $shortGoal = rtrim($request->shortGoal, ',');
            $shortMachine = rtrim($request->shortMachine, ',');
            $shortDose = rtrim($request->shortDose, ',');
            $longGoal = rtrim($request->longGoal, ',');
            $longMachine = rtrim($request->longMachine, ',');
            $longDose = rtrim($request->longDose, ',');
            if(!empty($patientId) && !empty($therapistId) && !empty($visitId) && !empty($shortGoal) && !empty($shortMachine) && !empty($shortDose) && !empty($longGoal) && !empty($longMachine) && !empty($longDose)){
                $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $addData = array();
                            $addData['visit_id'] = $visitId;
                            $addData['patient_id'] = $patientId;
                            $addData['therapist_id'] = $therapistId;
                            $addData['shortGoal'] = $shortGoal;
                            $addData['shortMachine'] = $shortMachine;
                            $addData['shortDose'] = $shortDose;
                            $addData['longGoal'] = $longGoal;
                            $addData['longMachine'] = $longMachine;
                            $addData['longDose'] = $longDose;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('treatment_goal')->insert($addData);

                            $shortM = explode(',', $shortMachine);
                            if(count($shortM) > 0){
                                foreach ($shortM as $smvalue) {
                                    $smvalue = trim($smvalue,' ');
                                    $machineSCheck = DB::table('machine')->where('name',$smvalue)->first();
                                    if(!$machineSCheck){
                                        $addMachine = array();
                                        $addMachine['name'] = $smvalue;
                                        DB::table('machine')->insert($addMachine);
                                    }
                                }
                            }

                            $longM = explode(',', $longMachine);
                            if(count($longM) > 0){
                                foreach ($longM as $lmvalue) {
                                    $lmvalue = trim($lmvalue,' ');
                                    $machineLCheck = DB::table('machine')->where('name',$lmvalue)->first();
                                    if(!$machineLCheck){
                                        $addLMachine = array();
                                        $addLMachine['name'] = $lmvalue;
                                        DB::table('machine')->insert($addLMachine);
                                    }
                                }
                            }
                            
                            $shortD = explode(',', $shortDose);
                            if(count($shortD) > 0){
                                foreach ($shortD as $sdvalue) {
                                    $sdvalue = trim($sdvalue,' ');
                                    $doseSCheck = DB::table('dose')->where('name',$sdvalue)->first();
                                    if(!$doseSCheck){
                                        $addSDose = array();
                                        $addSDose['name'] = $sdvalue;
                                        DB::table('dose')->insert($addSDose);
                                    }
                                }
                            }
                            
                            $longD = explode(',', $longDose);
                            if(count($longD) > 0){
                                foreach ($longD as $ldvalue) {
                                    $ldvalue = trim($ldvalue,' ');
                                    $doseLCheck = DB::table('dose')->where('name',$ldvalue)->first();
                                    if(!$doseLCheck){
                                        $addLDose = array();
                                        $addLDose['name'] = $ldvalue;
                                        DB::table('dose')->insert($addLDose);
                                    }
                                }
                            }
                            
                            $shortG = explode(',', $shortGoal);
                            if(count($shortG) > 0){
                                foreach ($shortG as $sgvalue) {
                                    $sgvalue = trim($sgvalue,' ');
                                    $goalSCheck = DB::table('goal')->where('name',$sgvalue)->first();
                                    if(!$goalSCheck){
                                        $addSGoal = array();
                                        $addSGoal['name'] = $sgvalue;
                                        DB::table('goal')->insert($addSGoal);
                                    }
                                }
                            }
                            
                            $longG = explode(',', $longGoal);
                            if(count($longG) > 0){
                                foreach ($longG as $lgvalue) {
                                    $lgvalue = trim($lgvalue,' ');
                                    $goalLCheck = DB::table('goal')->where('name',$lgvalue)->first();
                                    if(!$goalLCheck){
                                        $addLGoal = array();
                                        $addLGoal['name'] = $lgvalue;
                                        DB::table('goal')->insert($addLGoal);
                                    }
                                }
                            }

                            $response['message'] = 'Successfully Saved!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        return response()->json($response);
    }

    public function allTreatmentGoal(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('treatment_goal')->where('patient_id',$patientId)->select('visit_id','therapist_id','patient_id','shortGoal','shortMachine','shortDose','longGoal','longMachine','longDose','created_at')->orderBy('id','DESC')->get();
                        if(count($allData) > 0){
                            foreach($allData as $value){
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                                $value->created_at = date("d-M-Y", strtotime($value->created_at));
                                array_walk_recursive($value, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addTreatmentGiven(Request $request){
        try{
            if(Input::has('patientId') && Input::has('visitId') && Input::has('userId') && Input::has('comments') && Input::has('signature') && Input::has('rating')){
                $patientId = $request->patientId;
                $therapistId = $request->userId;
                $visitId = $request->visitId;
                $visitDetails = dailyEntryDetails($visitId);
                $appId = $visitDetails->appointment_id;
                $appsDetails = appointmentDetails($appId);
                $packageDetails = packageDetails($visitDetails->package_id);
                $comments = $request->comments;
                $reveiw = $request->emoji;
                $signature = $request->signature;
                $ratings = $request->rating;
                if($ratings == 1.5){
                    $rating = 2;
                }else if($ratings == 2.5){
                    $rating = 3;
                }else if($ratings == 3.5){
                    $rating = 4;
                }else if($ratings == 4.5){
                    $rating = 5;
                }else{
                    $rating = 5;
                }
                $todayDate = date('Y-m-d');
                if(!empty($patientId) && !empty($therapistId) && !empty($visitId) && !empty($comments) && !empty($signature) && !empty($rating)){

                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $checkVisit = DB::table('daily_entry')->where('id',$visitId)->first();
                            if($checkVisit){
                                $checkConsentData = DB::table('consent_record')->where('appId',$appId)->orderBy('id','DESC')->first();
                                if(($appsDetails->app_service_type != 7) && (empty($checkConsentData) || ($checkConsentData == null))){
                                    $response['message'] = 'Please filled Consent paper!';
                                    $response['status'] = '0';
                                }else{
                                    // check service type
                                    $checkServiceType = DB::table('appointment')->where('id',$checkVisit->appointment_id)->first();
                                    $serviceTypeData = $checkServiceType->app_service_type;
                                    if(!empty($serviceTypeData) && (($serviceTypeData == 7) || ($serviceTypeData == 9) || ($serviceTypeData == 8) || ($serviceTypeData == 1))){
                                        // ortho/neuro should be add compalsary
                                        $orthoData = DB::table('ortho_case')->where('visit_id',$visitId)->first();
                                        $neuroData = DB::table('neuro_case')->where('visit_id',$visitId)->first();
                                        if(!empty($orthoData) || !empty($neuroData)){
                                            $visitType = $checkVisit->type;
                                            $insertData = array();
                                            $insertData['visit_id'] = $visitId;
                                            $insertData['patient_id'] = $patientId;
                                            $insertData['therapist_id'] = $therapistId;
                                            $insertData['comments'] = $comments;
                                            $insertData['reveiw'] = $reveiw;
                                            if($signature){
                                                $img = $signature;
                                                $basePath = 'upload/signature/';
                                                $frontImgName = $this->uploadImage($img, $basePath);
                                                if($frontImgName){
                                                    $insertData['signature'] = $frontImgName;
                                                }else{
                                                    $insertData['signature'] = '';
                                                }
                                            }
                                            $insertData['created_at'] = date('Y-m-d H:i:s');
                                            DB::table('treatment_given')->insert($insertData);
                                            // Complete visit processing
                                            if($visitType == 2){
                                                // package type visit complete
                                                $dailyEntryDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                                                $appId = $dailyEntryDetails->appointment_id;
                                                $therapistId = $dailyEntryDetails->therapist_id;
                                                $appDetails = DB::table('appointment')->where('id',$appId)->first();
                                                $userDetails = userDetails($appDetails->user_id);
                                                $patientReferBy = $userDetails->refer_by;
                                                $therapistReferCode = userDetails($therapistId)->refer_code;
                                                $serviceType = $appDetails->app_service_type;
                                                $packageId = $appDetails->package_type;
                                                $jointName = $appDetails->joints;
                                                
                                                $id = $visitId;
                                                $amount = $dailyEntryDetails->amount;
                                                $inTime = $dailyEntryDetails->in_time;
                                                $outTime = date('H:i:s');
                                                $checkVisitCount = DailyEntry::where('appointment_id',$appId)->where('status','complete')->count('id');

                                                if(!empty($inTime) && !empty($outTime) && ($serviceType != '7') && ($serviceType != '9') && ($serviceType != '8') && ($serviceType != '1')){
                                                    $jointName = $appDetails->joints;
                                                    if($checkVisitCount > 1){
                                                        if($jointName == 'one_joint'){
                                                            $ntTime = strtotime("+70 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                        }else if($jointName == 'two_joint'){
                                                            $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 90 min
                                                        }else if($jointName == 'three_joint'){
                                                            $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 120 min
                                                        }else if($jointName == 'neuro'){
                                                            $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                        }
                                                    }else{
                                                        // add 30 min extra for 1st visit patients for filling capri file
                                                        if($jointName == 'one_joint'){
                                                            $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                        }else if($jointName == 'two_joint'){
                                                            $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 90 min
                                                        }else if($jointName == 'three_joint'){
                                                            $ntTime = strtotime("+160 minutes", strtotime($inTime));    //10 minutes extra of 120 min
                                                        }else if($jointName == 'neuro'){
                                                            $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                        }
                                                    }
                                                    $nextTime = date('H:i:s', $ntTime);
                                                    $time1 = new DateTime($outTime);
                                                    $time2 = new DateTime($nextTime);
                                                    $interval = $time2->diff($time1);
                                                    $diff = $interval->format('%h:%i:%s');

                                                    if($time1 < $time2){
                                                        $penalty = '';
                                                        $visitType = 'AV';
                                                    }else{
                                                        if(strtotime($diff) <= strtotime('0:10:0')){
                                                            $penalty = '25';
                                                            $visitType = 'AW';
                                                        }else if(strtotime($diff) <= strtotime('0:20:0')){
                                                            $penalty = '50';
                                                            $visitType = 'AW';
                                                        }else if(strtotime($diff) <= strtotime('0:30:0')){
                                                            $penalty = '75';
                                                            $visitType = 'AW';
                                                        }else if(strtotime($diff) > strtotime('0:30:0')){
                                                            //get 50% of extra amount for penalty
                                                            $percentage = 50;
                                                            $penalty = ($percentage / 100) * $amount;
                                                            $visitType = 'AW';
                                                        }else{
                                                            $penalty = '';
                                                            $visitType = 'AV';
                                                        }
                                                    }
                                                }else{
                                                    $penalty = '';
                                                    $visitType = '';
                                                }

                                                $appointmentDueDays = $appDetails->due_package_days;
                                                if(($appointmentDueDays != 0) && ($appointmentDueDays != '') && ($dailyEntryDetails->type == 2)){
                                                    //if package update then due days of package entries
                                                    $updateAppData = array();
                                                    $updateAppData['due_package_days'] = $appointmentDueDays - 1;
                                                    Appointment::where('id',$appId)->update($updateAppData);
                                                }
                                                $outTime = date('H:i:s');
                                                // daily entry update
                                                $data = array();
                                                $data['rating'] = $rating;
                                                $data['status'] = 'complete';
                                                $data['out_time'] = $outTime;
                                                $data['penalty'] = $penalty;
                                                DB::table('daily_entry')->where('id',$id)->update($data);

                                                // add capri point if patient is referred by another person and package is copmpleted
                                                $checkPointGetStatus = DB::table('capri_point')->where('appId',$appId)->first();
                                                if($checkPointGetStatus){
                                                    $response['message'] = 'Successfully Saved!';
                                                    $response['status'] = '1';
                                                }else{
                                                    $dailyEntryPackDetails = DailyEntry::where('appointment_id',$appId)->where('status','complete')->whereNull('secondFlag')->where('type',2)->orderBy('id','ASC')->first();
                                                    if($dailyEntryPackDetails){
                                                        $checkpVisitCount = DailyEntry::where('appointment_id',$appId)->where('package_id',$dailyEntryPackDetails->package_id)->where('status','complete')->whereNull('secondFlag')->where('type',2)->count('id');
                                                        if(($checkpVisitCount > 0) && ($checkpVisitCount == $packageDetails->days) && !empty($patientReferBy) && !empty($therapistReferCode) && ($patientReferBy == $therapistReferCode)){
                                                            // add refer points
                                                            $cpPoint = DB::table('cpoint')->where('name','Package Complete')->first();
                                                            $cAmt = $cpPoint->amount;
                                                            $cPoint = $cpPoint->point;
                                                            $cPointId = $cpPoint->id;
                                                            $cpUserId = $userDetails->therapist_id;
                                                            $cpData = array();
                                                            $cpData['user_id'] = $cpUserId;
                                                            $cpData['other_user_id'] = $patientId;
                                                            $cpData['cpoint_id'] = $cPointId;
                                                            $cpData['cp_point'] = $cPoint;
                                                            $cpData['cp_amount'] = $cAmt;
                                                            $cpData['type'] = 'credit';
                                                            $cpData['remark'] = 'Package Complete Therapist';
                                                            $cpData['appId'] = $appId;
                                                            $cpData['created_at'] = date('Y-m-d H:i:s');
                                                            DB::table('capri_point')->insert($cpData);

                                                            $response['message'] = 'Successfully Saved!';
                                                            $response['status'] = '1';
                                                        }else{
                                                            $response['message'] = 'Successfully Saved!';
                                                            $response['status'] = '1';
                                                        }
                                                    }else{
                                                        $response['message'] = 'Successfully Saved!';
                                                        $response['status'] = '1';
                                                    }
                                                }
                                            }else{
                                                // per day type visit complete
                                                date_default_timezone_set('Asia/Kolkata');
                                                $data = array();
                                                $data['rating'] = $rating;
                                                $data['status'] = 'complete';
                                                $data['out_time'] = date('H:i:s');
                                                DB::table('daily_entry')->where('id',$visitId)->update($data);
                                                
                                                $response['message'] = 'Successfully Saved!';
                                                $response['status'] = '1';
                                            }
                                            // // send notification
                                            // $tokenId = $checkPatient->token_id;
                                            // if(!empty($tokenId)){
                                            //     $title = 'Notification Testing';
                                            //     $sendsms = $this->notification($tokenId,$title);
                                            // }
                                        }else{
                                            $response['message'] = 'Ortho case/Neuro case is Compalsary, Please fill it!';
                                            $response['status'] = '0';
                                        }
                                    }else{
                                        // Case note add Compalsary
                                        $checkCaseNote = DB::table('treatment_note')->where('visit_id',$visitId)->where(DB::raw("(DATE_FORMAT(created_date,'%Y-%m-%d'))"), $todayDate)->first();
                                        if(!empty($checkCaseNote) || ($checkCaseNote != null)){
                                            // On Reassessment ADL Exam Compalsary
                                            $visitCount = DB::table('daily_entry')->where('appointment_id',$visitDetails->appointment_id)->where('status','complete')->count('id');
                                            $adlExam1 = DB::table('adl_anke_and_foot')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam2 = DB::table('adl_back')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam3 = DB::table('adl_elbow')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam4 = DB::table('adl_hip')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam5 = DB::table('adl_knee')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam6 = DB::table('adl_neck')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam7 = DB::table('adl_shoulder')->where('patient_id',$patientId)->select('id')->first();
                                            $adlExam8 = DB::table('adl_wrist_and_hand')->where('patient_id',$patientId)->select('id')->first();
                                            if(($visitCount > 0) && ($visitCount % 7 == 0) && (empty($adlExam1) && empty($adlExam2) && empty($adlExam3) && empty($adlExam4) && empty($adlExam5) && empty($adlExam6) && empty($adlExam7) && empty($adlExam8))){
                                                $response['message'] = 'On Reassessment of visit ADL Exam is compalsary';
                                                $response['status'] = '0';
                                            }else{
                                                $visitType = $checkVisit->type;
                                                $insertData = array();
                                                $insertData['visit_id'] = $visitId;
                                                $insertData['patient_id'] = $patientId;
                                                $insertData['therapist_id'] = $therapistId;
                                                $insertData['comments'] = $comments;
                                                if($signature){
                                                    $img = $signature;
                                                    $basePath = 'upload/signature/';
                                                    $frontImgName = $this->uploadImage($img, $basePath);
                                                    if($frontImgName){
                                                        $insertData['signature'] = $frontImgName;
                                                    }else{
                                                        $insertData['signature'] = '';
                                                    }
                                                }
                                                $insertData['reveiw'] = $reveiw;
                                                $insertData['created_at'] = date('Y-m-d H:i:s');
                                                DB::table('treatment_given')->insert($insertData);
                                                // Complete visit processing
                                                if($visitType == 2){
                                                    // package type visit complete
                                                    $dailyEntryDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                                                    $appId = $dailyEntryDetails->appointment_id;
                                                    $therapistId = $dailyEntryDetails->therapist_id;
                                                    $appDetails = DB::table('appointment')->where('id',$appId)->first();
                                                    $serviceType = $appDetails->app_service_type;
                                                    $packageId = $appDetails->package_type;
                                                    $jointName = $appDetails->joints;
                                                    
                                                    $id = $visitId;
                                                    $amount = $dailyEntryDetails->amount;
                                                    $inTime = $dailyEntryDetails->in_time;
                                                    $outTime = date('H:i:s');
                                                    $checkVisitCount = DailyEntry::where('appointment_id',$appId)->where('status','complete')->count('id');

                                                    if(!empty($inTime) && !empty($outTime) && ($serviceType != '7') && ($serviceType != '9')){
                                                        $jointName = $appDetails->joints;
                                                        if($checkVisitCount > 1){
                                                            if($jointName == 'one_joint'){
                                                                $ntTime = strtotime("+70 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                            }else if($jointName == 'two_joint'){
                                                                $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 90 min
                                                            }else if($jointName == 'three_joint'){
                                                                $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 120 min
                                                            }else if($jointName == 'neuro'){
                                                                $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                            }
                                                        }else{
                                                            // add 30 min extra for 1st visit patients for filling capri file
                                                            if($jointName == 'one_joint'){
                                                                $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                            }else if($jointName == 'two_joint'){
                                                                $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 90 min
                                                            }else if($jointName == 'three_joint'){
                                                                $ntTime = strtotime("+160 minutes", strtotime($inTime));    //10 minutes extra of 120 min
                                                            }else if($jointName == 'neuro'){
                                                                $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                                                            }
                                                        }
                                                        $nextTime = date('H:i:s', $ntTime);
                                                        $time1 = new DateTime($outTime);
                                                        $time2 = new DateTime($nextTime);
                                                        $interval = $time2->diff($time1);
                                                        $diff = $interval->format('%h:%i:%s');

                                                        if($time1 < $time2){
                                                            $penalty = '';
                                                            $visitType = 'AV';
                                                        }else{
                                                            if(strtotime($diff) <= strtotime('0:10:0')){
                                                                $penalty = '25';
                                                                $visitType = 'AW';
                                                            }else if(strtotime($diff) <= strtotime('0:20:0')){
                                                                $penalty = '50';
                                                                $visitType = 'AW';
                                                            }else if(strtotime($diff) <= strtotime('0:30:0')){
                                                                $penalty = '75';
                                                                $visitType = 'AW';
                                                            }else if(strtotime($diff) > strtotime('0:30:0')){
                                                                //get 50% of extra amount for penalty
                                                                $percentage = 50;
                                                                $penalty = ($percentage / 100) * $amount;
                                                                $visitType = 'AW';
                                                            }else{
                                                                $penalty = '';
                                                                $visitType = 'AV';
                                                            }
                                                        }
                                                    }else{
                                                        $penalty = '';
                                                        $visitType = '';
                                                    }

                                                    $appointmentDueDays = $appDetails->due_package_days;
                                                    if(($appointmentDueDays != 0) && ($appointmentDueDays != '') && ($dailyEntryDetails->type == 2)){
                                                        //if package update then due days of package entries
                                                        $updateAppData = array();
                                                        $updateAppData['due_package_days'] = $appointmentDueDays - 1;
                                                        Appointment::where('id',$appId)->update($updateAppData);
                                                    }
                                                    $outTime = date('H:i:s');
                                                    // daily entry update
                                                    $data = array();
                                                    $data['rating'] = $rating;
                                                    $data['status'] = 'complete';
                                                    $data['out_time'] = $outTime;
                                                    $data['penalty'] = $penalty;
                                                    DB::table('daily_entry')->where('id',$id)->update($data);
                                                }else{
                                                    // per day type visit complete
                                                    date_default_timezone_set('Asia/Kolkata');
                                                    $data = array();
                                                    $data['rating'] = $rating;
                                                    $data['status'] = 'complete';
                                                    $data['out_time'] = date('H:i:s');
                                                    DB::table('daily_entry')->where('id',$visitId)->update($data);
                                                }
                                                $response['message'] = 'Successfully Saved!';
                                                $response['status'] = '1';
                                            }
                                        }else{
                                            $response['message'] = 'Please filled Treatment Case Note!';
                                            $response['status'] = '0';
                                        }
                                    }
                                }
                            }else{
                                $response['message'] = 'Visit not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addNewVisit(Request $request){
        try{
            if(Input::has('userId') && Input::has('patientId') && Input::has('appointmentId') && Input::has('date') && Input::has('time')){
                $therapistId = $request->userId;
                $patientId = $request->patientId;
                $userDetails = userDetails($patientId);
                $appointmentId = $request->appointmentId;
                $currentDate = date('Y-m-d');
                $date = $request->date;
                $time = $request->time;
                if(!empty($therapistId) && !empty($patientId) && !empty($appointmentId) && !empty($date) && !empty($time)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                        if($checkTherapist){
                            $checkTherapistAssign = $checkPatient->therapist_id;
                            if(!empty($checkTherapistAssign)){
                                $checkAppointment = Appointment::where('id',$appointmentId)->where('status','approved')->first();
                                if($checkAppointment){
                                    $appServiceType = $checkAppointment->app_service_type;
                                    $twoMoreCondition = DB::table('daily_entry')->where('appointment_id',$appointmentId)->where(DB::raw("(DATE_FORMAT(created_date,'%Y-%m-%d'))"),$currentDate)->get();
                                    if(count($twoMoreCondition) <= 3){
                                        $checkTimeAvailable = DB::table('daily_entry')->where('appointment_id',$appointmentId)->where('therapist_id',$therapistId)->where('app_booked_date',$date)->where('app_booked_time',$time)->first();
                                        if($checkTimeAvailable){
                                            $response['message'] = 'Appointment already booked with someone!';
                                            $response['status'] = '0';
                                        }else{
                                            if($checkAppointment->payment_method == 'package_wise'){
                                                $checkPackageLimit = DB::table('daily_entry')->where('appointment_id',$appointmentId)->where('package_id',$checkAppointment->package_type)->where('status','complete')->orderBy('id','DESC')->first();
                                                if($checkPackageLimit){
                                                    if($checkPackageLimit->due_days == 0){
                                                        $cflag = 'false';
                                                    }else{
                                                        $cflag = 'true';
                                                    }
                                                }else{
                                                    $cflag = 'true';
                                                }
                                            }else{
                                                $cflag = 'true';
                                            }
                                            if($cflag == 'false'){
                                                $response['message'] = 'Please purchase new package!';
                                                $response['status'] = '0';
                                            }else{
                                                $appointmentType = $checkAppointment->payment_method;
                                                if($appointmentType == 'per_day_visit'){
                                                    $appType = 1;
                                                    $packageId = '';
                                                }else if($appointmentType == 'package_wise'){
                                                    $appType = 2;
                                                    $packageId = '';
                                                }else if($appointmentType == 'complimentary'){
                                                    $appType = 3;
                                                    $packageId = '';
                                                }
                                                $dailyEntry = new DailyEntry();
                                                $dailyEntry['appointment_id'] = $appointmentId;
                                                $appDetails = Appointment::where('id',$appointmentId)->first();
                                                $packageId = $appDetails->package_type;
                                                $dailyEntry['package_id'] = $packageId;
                                                $dailyEntry['therapist_id'] = $therapistId;
                                                $dailyEntry['package_id'] = $packageId;
                                                $dailyEntry['app_booked_date'] = $date;
                                                $dailyEntry['app_booked_time'] = $time;
                                                $dailyEntry['service_type'] = $appDetails->app_service_type;
                                                $dailyEntry['type'] = $appType;
                                                if($appointmentType == 'complimentary'){
                                                    $dailyEntry['amount'] = 0;
                                                }else{
                                                    if($appType == 1){
                                                        // for perday
                                                        if(!empty($appServiceType) && ($appServiceType == '7')){
                                                            $dailyEntry['amount'] = 0;
                                                        }else{
                                                            $dailyEntry['amount'] = 0;
                                                        }
                                                    }else{
                                                        // for package
                                                        if(!empty($appServiceType) && ($appServiceType == '7')){
                                                            $dailyEntry['amount'] = 0;
                                                        }else{
                                                            $packDetails = packageDetails($packageId);
                                                            $packDays = $packDetails->days;
                                                            $pacAmt = $packDetails->package_amount;
                                                            $packAmount = $pacAmt / $packDays;
                                                            $dailyEntry['amount'] = $packAmount;
                                                        }
                                                    }
                                                }
                                                $dailyEntry['created_date'] = date('Y-m-d');
                                                $dailyEntry['created_at'] = date('Y-m-d H:i:s');
                                                $dailyEntry->save();
                                                $dailyEntryId = $dailyEntry->id;

                                                $mobileNo = $userDetails->mobile;
                                                $name = $userDetails->name;
                                                if(!empty($mobileNo)){
                                                    if($appServiceType == 6){
                                                        $message = 'Dear '.$name.', Your physio session dated '.$date.', time '.$time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                                                    }else if($appServiceType == 7){
                                                        $message = 'Dear '.$name.', Your physio session dated '.$date.', time '.$time.' has been booked by Physio Team of Sant Parmanand Hospital. Thanks, Team Physio, SPH';
                                                    }else if(($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1)){
                                                        $message = 'Dear '.$name.', Your home physio session dated '.$date.', time '.$time.' has been booked by Team Capri. Thanks, CapriSpine Team';
                                                    }else{
                                                        $message = 'Dear '.$name.', Your physio session dated '.$date.', time '.$time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                                                    }
                                                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                                                }

                                                //add history
                                                $history = new AppointmentHistory();
                                                $history['appointment_id'] = $appointmentId;
                                                $history['new_therapist'] = $therapistId;
                                                $history['item_id'] = $dailyEntryId;
                                                $history['reason'] = 'Per day visit entry for appointment';
                                                if(!empty($appServiceType) && ($appServiceType == '7')){
                                                    $history['amount'] = 0;
                                                }
                                                $history['created_by'] = 'app';
                                                $history->save();
                                                // send notification
                                                $tokenId = $checkPatient->token_id;
                                                if(!empty($tokenId)){
                                                    $title = 'Your Appointment Visit add Successfully at '.$date.' on '.$time;
                                                    $sendnot = $this->SendNotification($tokenId,$title);
                                                    // add notification
                                                    $addNot = array();
                                                    $addNot['user_id'] = $patientId;
                                                    $addNot['title'] = $title;
                                                    $addNot['token_id'] = $tokenId;
                                                    $addNot['date'] = date('Y-m-d');
                                                    $addNot['time'] = date('H:i:s');
                                                    DB::table('patient_notification')->insert($addNot);
                                                }
                                                $response['message'] = 'Successfully Booked!';
                                                $response['status'] = '1';
                                            }
                                        }
                                    }else{
                                        $response['message'] = 'You cant create two more visit at Today!';
                                        $response['status'] = '0';
                                    }
                                }else{
                                    $response['message'] = 'Your appointment not approved, Please contact to Caprispine help center!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Firstly you need to Assign Therapist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function adlGraph(Request $request){
        try{
            if(Input::has('flag') && Input::has('patientId')){
                $flag = $request->flag;
                $patientId = $request->patientId;
                if(!empty($flag) && !empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        if($flag == 'anke'){
                            $getData = DB::table('adl_anke_and_foot')->where('patient_id',$patientId)->select('getScore as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'back'){
                            $getData = DB::table('adl_back')->where('patient_id',$patientId)->select('getScore as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'elbow'){
                            $getData = DB::table('adl_elbow')->where('patient_id',$patientId)->select('getScore as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'hip'){
                            $getData = DB::table('adl_hip')->where('patient_id',$patientId)->select('get_score as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'knee'){
                            $getData = DB::table('adl_knee')->where('patient_id',$patientId)->select('get_score as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'neck'){
                            $getData = DB::table('adl_neck')->where('patient_id',$patientId)->select('get_score as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'shoulder'){
                            $getData = DB::table('adl_shoulder')->where('patient_id',$patientId)->select('getScore as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'wrist'){
                            $getData = DB::table('adl_wrist_and_hand')->where('patient_id',$patientId)->select('getScore as score', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Invalid Flag!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function painGraph(Request $request){
        try{
            if(Input::has('patientId')){
                $flag = $request->flag;
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $getData = DB::table('pain_exam')->where('patient_id',$patientId)->select('intensity_of_pain', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                        if(count($getData) > 0){
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $getData;
                        }else{
                            $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allAppointmentForTherapist(Request $request){
        try{
            if(Input::has('userId')){
                $therapistId = $request->userId;
                if(!empty($therapistId)){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        $allData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$therapistId)->select('appointment.id','users.name as patientName','appointment.user_id as patient_id','users.therapist_id as therapistName','appointment.joints','appointment.appointment_date','appointment.appointment_time','appointment.appointment_time','appointment.status','appointment.payment_method','appointment.reference_type','users.profile_pic','users.service_type')->orderBy('appointment.appointment_date','DESC')->get();
                        if(count($allData) > 0){
                            foreach ($allData as $value) {
                                if(!empty($value->payment_method)){
                                    if($value->payment_method == 'package_wise'){
                                        $value->payment_method = 'Package wise Visit';
                                    }else if($value->payment_method == 'per_day_visit'){
                                        $value->payment_method = 'Per Day Visit';
                                    }
                                }
                                if(!empty($value->therapistName)){
                                    $userDetails = userDetails($value->therapistName);
                                    $value->therapistName = $userDetails->name;
                                }
                                if(!empty($value->appointment_time)){
                                    $timeData = DB::table('time_slot')->where('id',$value->appointment_time)->first();
                                    $value->appointment_time = $timeData->time;
                                }
                                if(!empty($value->reference_type)){
                                    $referenceData = DB::table('reference')->where('id',$value->reference_type)->first();
                                    $value->reference_type = $referenceData->name;
                                }
                                if(!empty($value->service_type)){
                                    $serviceData = DB::table('service')->where('id',$value->service_type)->first();
                                    $value->service_type = $serviceData->name;
                                }
                                if(!empty($value->joints)){
                                    if($value->joints == 'one_joint'){
                                        $value->joints = 'One Joint';
                                    }else if($value->joints == 'two_joint'){
                                        $value->joints = 'Two Joint';
                                    }else if($value->joints == 'three_joint'){
                                        $value->joints = 'Three Joint';
                                    }else if($value->joints = 'neuro'){
                                        $value->joints = 'Neuro';
                                    }else{
                                        $value->joints = '';
                                    }
                                }
                                // profile picture
                                if(!empty($value->profile_pic)){
                                    $value->profile_pic = API_PROFILE_PIC.$value->profile_pic;
                                }else{
                                    $value->profile_pic = API_FOR_DEFAULT_IMG;
                                }

                                array_walk_recursive($value, function (&$item, $key){
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addOrthoCase(Request $request){
        try{
            if(Input::has('userId') && Input::has('patientId') && Input::has('visitId') && Input::has('chest_pt_postural') && Input::has('chest_pt_breathing') && Input::has('cpm') && Input::has('rom_ex') && Input::has('strengthening_ex') && Input::has('stretching_ex') && Input::has('sitting') && Input::has('standing') && Input::has('walking_no_of_step') && Input::has('stairs_no_of_step') && Input::has('w_sitting') && Input::has('electotherapy') && Input::has('hot_cold_pack')){
                $therapistId = $request->userId;
                $patientId = $request->patientId;
                $visitId = $request->visitId;
                $chest_pt_postural = $request->chest_pt_postural;
                $chest_pt_breathing = $request->chest_pt_breathing;
                $cpm = $request->cpm;
                $rom_ex = $request->rom_ex;
                $strengthening_ex = $request->strengthening_ex;
                $stretching_ex = $request->stretching_ex;
                $sitting = $request->sitting;
                $standing = $request->standing;
                $walking_no_of_step = rtrim($request->walking_no_of_step, ',');
                $stairs_no_of_step = rtrim($request->stairs_no_of_step, ',');
                $w_sitting = rtrim($request->w_sitting, ',');
                $electotherapy = rtrim($request->electotherapy, ',');
                $hot_cold_pack = rtrim($request->hot_cold_pack, ',');
                if(!empty($therapistId) && !empty($patientId) && !empty($visitId) && (!empty($chest_pt_postural) || !empty($chest_pt_breathing) || !empty($cpm) || !empty($rom_ex) || !empty($strengthening_ex) || !empty($stretching_ex) || !empty($sitting) || !empty($standing) || !empty($walking_no_of_step) || !empty($stairs_no_of_step) || !empty($w_sitting) || !empty($electotherapy))){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['therapist_id'] = $therapistId;
                            $addData['visit_id'] = $visitId;
                            $addData['chest_pt_postural'] = $chest_pt_postural;
                            $addData['chest_pt_breathing'] = $chest_pt_breathing;
                            $addData['cpm'] = $cpm;
                            $addData['rom_ex'] = $rom_ex;
                            $addData['strengthening_ex'] = $strengthening_ex;
                            $addData['stretching_ex'] = $stretching_ex;
                            $addData['sitting'] = $sitting;
                            $addData['standing'] = $standing;
                            $addData['walking_no_of_step'] = $walking_no_of_step;
                            $addData['stairs_no_of_step'] = $stairs_no_of_step;
                            $addData['w_sitting'] = $w_sitting;
                            $addData['electotherapy'] = $electotherapy;
                            $addData['hot_cold_pack'] = $hot_cold_pack;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('ortho_case')->insert($addData);

                            $response['message'] = 'Add Successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Patient not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allOrthoCase(Request $request){
        try{
            if(Input::has('userId')){
                $patientId = $request->userId;
                if(!empty($patientId)){
                    $allData = DB::table('ortho_case')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','patient_id','chest_pt_postural','chest_pt_breathing','cpm','rom_ex','strengthening_ex','stretching_ex','sitting','standing','walking_no_of_step','stairs_no_of_step','w_sitting','electotherapy','hot_cold_pack','created_at')->orderBy('id','DESC')->get();
                    if(count($allData) > 0){
                        foreach($allData as $value) {
                            if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                            }else{
                                $value->therapistName = '';
                            }
                            if(($value->patient_id != 0) || !empty($value->patient_id)){
                                $patName = User::where('id',$value->patient_id)->first();
                                $value->patientName = $patName->name;
                            }else{
                                $value->therapistName = '';
                            }
                            $value->created_at = date("d-M-Y", strtotime($value->created_at));
                            array_walk_recursive($value, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }
                        $response['message'] = 'Ortho Case get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allData;
                    }else{
                        $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addNeuroCase(Request $request){
        try{
            if(Input::has('userId') && Input::has('patientId') && Input::has('visitId') && Input::has('chest_pt_postural') && Input::has('chest_pt_breathing') && Input::has('positioning') && Input::has('sustained') && Input::has('weight_bearing') && Input::has('rom_ex') && Input::has('strengthening_ex') && Input::has('balance_ex') && Input::has('sitting') && Input::has('standing') && Input::has('walking') && Input::has('stairs') && Input::has('w_sitting') && Input::has('electrotherapy') && Input::has('hot_cold_pack')){
                $therapistId = $request->userId;
                $patientId = $request->patientId;
                $visitId = $request->visitId;
                $chest_pt_postural = $request->chest_pt_postural;
                $chest_pt_breathing = $request->chest_pt_breathing;
                $positioning = $request->positioning;
                $sustained = $request->sustained;
                $weight_bearing = $request->weight_bearing;
                $rom_ex = $request->rom_ex;
                $strengthening_ex = $request->strengthening_ex;
                $balance_ex = $request->balance_ex;
                $sitting = $request->sitting;
                $standing = $request->standing;
                $walking = rtrim($request->walking, ',');
                $stairs = rtrim($request->stairs, ',');
                $w_sitting = $request->w_sitting;
                $electrotherapy = rtrim($request->electrotherapy, ',');
                $hot_cold_pack = rtrim($request->hot_cold_pack, ',');
                if(!empty($therapistId) && !empty($patientId) && !empty($visitId) && (!empty($chest_pt_postural) || !empty($chest_pt_breathing) || !empty($positioning) || !empty($sustained) || !empty($weight_bearing) || !empty($rom_ex) || !empty($strengthening_ex) || !empty($balance_ex) || !empty($sitting) || !empty($standing) || !empty($walking) || !empty($stairs) || !empty($w_sitting) || !empty($electrotherapy) || !empty($hot_cold_pack))){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                        if($checkPatient){
                            $addData = array();
                            $addData['patient_id'] = $patientId;
                            $addData['therapist_id'] = $therapistId;
                            $addData['visit_id'] = $visitId;
                            $addData['chest_pt_postural'] = $chest_pt_postural;
                            $addData['chest_pt_breathing'] = $chest_pt_breathing;
                            $addData['positioning'] = $positioning;
                            $addData['sustained'] = $sustained;
                            $addData['weight_bearing'] = $weight_bearing;
                            $addData['rom_ex'] = $rom_ex;
                            $addData['strengthening_ex'] = $strengthening_ex;
                            $addData['balance_ex'] = $balance_ex;
                            $addData['sitting'] = $sitting;
                            $addData['standing'] = $standing;
                            $addData['walking'] = $walking;
                            $addData['stairs'] = $stairs;
                            $addData['w_sitting'] = $w_sitting;
                            $addData['electrotherapy'] = $electrotherapy;
                            $addData['hot_cold_pack'] = $hot_cold_pack;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('neuro_case')->insert($addData);

                            $response['message'] = 'Add Successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Patient not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allNeuroCase(Request $request){
        try{
            if(Input::has('userId')){
                $patientId = $request->userId;
                if(!empty($patientId)){
                    $allData = DB::table('neuro_case')->where('patient_id',$patientId)->select('id','visit_id','therapist_id','patient_id','chest_pt_postural','chest_pt_breathing','positioning','sustained','weight_bearing','rom_ex','strengthening_ex','balance_ex','sitting','standing','walking','stairs','w_sitting','electrotherapy','hot_cold_pack','created_at')->orderBy('id','DESC')->get();
                    if(count($allData) > 0){
                        foreach($allData as $value) {
                            if(($value->therapist_id != 0) || !empty($value->therapist_id)){
                                $thName = User::where('id',$value->therapist_id)->first();
                                $value->therapistName = $thName->name;
                            }else{
                                $value->therapistName = '';
                            }
                            if(($value->patient_id != 0) || !empty($value->patient_id)){
                                $patName = User::where('id',$value->patient_id)->first();
                                $value->patientName = $patName->name;
                            }else{
                                $value->therapistName = '';
                            }
                            $value->created_at = date("d-M-Y", strtotime($value->created_at));
                            array_walk_recursive($value, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }
                        $response['message'] = 'Neuro Case get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allData;
                    }else{
                        $response['message'] = 'Data does not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function withdrawalCapriPointRequest(Request $request){
        try{
            if(Input::has('userId')){
                $therapistId = $request->userId;
                if(!empty($therapistId)){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        $totalCreditPoint = DB::table('capri_point')->where('user_id',$therapistId)->where('type','credit')->sum('cp_amount');
                        $totalDebitPoint = DB::table('capri_point')->where('user_id',$therapistId)->where('type','debit')->sum('cp_amount');
                        $totalDueAmt = $totalCreditPoint - $totalDebitPoint;
                        if($totalDueAmt > 0){
                            $pointData = array();
                            $pointData['user_id'] = $therapistId;
                            $pointData['cpoint_id'] = 0;
                            $pointData['cp_point'] = 0;
                            $pointData['cp_amount'] = $totalDueAmt;
                            $pointData['type'] = 'pendingForDebit';
                            $pointData['remark'] = 'Wallet Request';
                            DB::table('capri_point')->insert($pointData);

                            $response['message'] = 'Request send successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'You have not a valid amount to withdrawl!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function referralWithdrawl(Request $request){
        try{
            if(Input::has('userid') && Input::has('payment_type') && Input::has('paytm_nm') && Input::has('paytm_no') && Input::has('bank_nm') && Input::has('acnt_nm') && Input::has('acnt_no') && Input::has('ifsc_code') && Input::has('remarks')){
                $therapistId = $request->userid;
                $paymentType = $request->payment_type;
                $paytmName = $request->paytm_nm;
                $paytmNo = $request->paytm_no;
                $bankName = $request->bank_nm;
                $accName = $request->acnt_nm;
                $accNo = $request->acnt_no;
                $ifscCode = $request->ifsc_code;
                $remarks = $request->remarks;
                if(!empty($therapistId) && !empty($paymentType)){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        $totalCreditPoint = DB::table('capri_point')->where('user_id',$therapistId)->where('type','credit')->sum('cp_amount');
                        $totalDebitPoint = DB::table('capri_point')->where('user_id',$therapistId)->where('type','debit')->sum('cp_amount');
                        $totalDueAmt = $totalCreditPoint - $totalDebitPoint;
                        if($totalDueAmt > 0){
                            $pointData = array();
                            if($paymentType == 'paytm'){
                                $pointData = array();
                                $pointData['paytm_name'] = $paytmName;
                                $pointData['paytm_no'] = $paytmNo;
                                $pointData['note'] = $remarks;
                            }else if($paymentType == 'bank'){
                                $pointData = array();
                                $pointData['bank_name'] = $bankName;
                                $pointData['account_name'] = $accName;
                                $pointData['account_no'] = $accNo;
                                $pointData['ifsc_code'] = $ifscCode;
                                $pointData['note'] = $remarks;
                            }
                            $pointData['user_id'] = $therapistId;
                            $pointData['cpoint_id'] = 0;
                            $pointData['cp_point'] = 0;
                            $pointData['cp_amount'] = $totalDueAmt;
                            $pointData['type'] = 'pendingForDebit';
                            $pointData['remark'] = 'Wallet Request';
                            DB::table('capri_point')->insert($pointData);

                            $response['message'] = 'Request send successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'You have not a valid amount for withdrawl!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addConsentSignature(Request $request){
        try{
            if(Input::has('visitId') && Input::has('flag') && Input::has('patientSign') && Input::has('therapistSign') && Input::has('relation') && Input::has('rName')){
                $visitId = $request->visitId;
                $flag = $request->flag;
                $patientSign = $request->patientSign;
                $therapistSign = $request->therapistSign;
                $relation = $request->relation;
                $rName = $request->rName;
                if(!empty($visitId) && !empty($flag)){
                    if($flag == 1){
                        // for 0 flag display all details of visit
                        $allDetails = DailyEntry::where('id',$visitId)->first();
                        if($allDetails){
                            $appId = $allDetails->appointment_id;
                            $appDetails = Appointment::where('id',$appId)->first();
                            $userId = $appDetails->user_id;
                            $userDetails = User::where('id',$userId)->first();
                            $getData = array();
                            $getData['patientName'] = $userDetails->name;
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
                            $getData['age'] = $age;
                            $getData['gender'] = ucfirst($userDetails->gender);
                            $getData['date'] = date('Y-m-d');
                            if($allDetails->amount){
                                $getData['amount'] = $allDetails->amount;
                            }else{
                                $getData['amount'] = '';
                            }
                            if(!empty($userDetails->branch)){
                                $getData['branch'] = branchDetails($userDetails->branch)->name;
                            }else{
                                $getData['branch'] = '';
                            }
                            if(!empty(userDetails($userDetails->therapist_id))){
                                $therapistName = userDetails($userDetails->therapist_id)->name;
                                $getData['therapistName'] = $therapistName;
                            }else{
                                $getData['therapistName'] = '';
                            }
                            $response['message'] = 'Request send successfully!';
                            $response['status'] = '1';
                            $response['getData'] = $getData;
                        }else{
                            $response['message'] = 'Data does not found!';
                            $response['status'] = '0';
                        }
                    }else if($flag == 2){
                        $allDetails = DailyEntry::where('id',$visitId)->first();
                        $appId = $allDetails->appointment_id;
                        // for 1 flag add signature with its details
                        if(!empty($patientSign) && !empty($therapistSign) && !empty($relation) && !empty($rName)){
                            $consentData = array();
                            $consentData['visit_id'] = $visitId;
                            if($patientSign){
                                $img = $patientSign;
                                $basePath = 'upload/signature/';
                                $frontImgName = $this->uploadImage($img, $basePath);
                                if($frontImgName){
                                    $consentData['patient_sign'] = $frontImgName;
                                }else{
                                    $consentData['patient_sign'] = '';
                                }
                            }
                            if($patientSign){
                                $img = $patientSign;
                                $basePath = 'upload/signature/';
                                $frontImgName1 = $this->uploadImage($img, $basePath);
                                if($frontImgName1){
                                    $consentData['therapist_sign'] = $frontImgName1;
                                }else{
                                    $consentData['therapist_sign'] = '';
                                }
                            }
                            $consentData['relation'] = $relation;
                            $consentData['r_nane'] = $rName;
                            $consentData['created_at'] = date('Y-m-d H:i:s');
                            $consentData['appId'] = $appId;
                            DB::table('consent_record')->insert($consentData);
                            
                            $response['message'] = 'Save Successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Fields cant be empty!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Invalid Flag!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function motorGraph(Request $request){
        try{
            if(Input::has('patientId') && Input::has('flag') && Input::has('subflag')){
                $patientId = $request->patientId;
                $flag = $request->flag;
                $subflag = $request->subflag;
                if(!empty($patientId) && !empty($flag) && !empty($subflag)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        if($flag === 'hip'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','hip')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','hip')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extRotation'){
                                $getData = DB::table('mt_hip_exam')->where('flag','hip')->orderBy('id','ASC')->select('extRotationLeftROM as left','extRotationRightROM as right','extRotationLeftPower as powerLeft','extRotationRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'intRotation'){
                                $getData = DB::table('mt_hip_exam')->where('flag','hip')->orderBy('id','ASC')->select('intRotationLeftROM as left','intRotationRightROM as right','intRotationLeftPower as powerLeft','intRotationRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'abduction'){
                                $getData = DB::table('mt_hip_exam')->where('flag','hip')->orderBy('id','ASC')->select('abductionLeftROM as left','abductionRightROM as right','abductionLeftPower as powerLeft','abductionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'adduction'){
                                $getData = DB::table('mt_hip_exam')->where('flag','hip')->orderBy('id','ASC')->select('adductionLeftROM as left','adductionRightROM as right','adductionLeftPower as powerLeft','adductionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'knee'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','knee')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','knee')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'ankle'){
                            if($subflag == 'plantFlex'){
                                $getData = DB::table('mt_ankle_exam')->orderBy('id','ASC')->select('plantFlexLeftROM as left','plantFlexRightROM as right','plantFlexLeftPower as powerLeft','plantFlexRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'dorsiFlex'){
                                $getData = DB::table('mt_ankle_exam')->orderBy('id','ASC')->select('dorsiFlexLeftROM as left','dorsiFlexRightROM as right','dorsiFlexLeftPower as powerLeft','dorsiFlexRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'eversion'){
                                $getData = DB::table('mt_ankle_exam')->orderBy('id','ASC')->select('eversionLeftROM as left','eversionRightROM as right','eversionLeftPower as powerLeft','eversionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'inversion'){
                                $getData = DB::table('mt_ankle_exam')->orderBy('id','ASC')->select('inversionLeftROM as left','inversionRightROM as right','inversionLeftPower as powerLeft','inversionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'toes'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','toes')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','toes')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'shoulder'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extRotation'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('extRotationLeftROM as left','extRotationRightROM as right','extRotationLeftPower as powerLeft','extRotationRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'intRotation'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('intRotationLeftROM as left','intRotationRightROM as right','intRotationLeftPower as powerLeft','intRotationRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'abduction'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('abductionLeftROM as left','abductionRightROM as right','abductionLeftPower as powerLeft','abductionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'adduction'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('adductionLeftROM as left','adductionRightROM as right','adductionLeftPower as powerLeft','adductionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'hrAbd'){
                                $getData = DB::table('mt_hip_exam')->where('flag','shoulder')->orderBy('id','ASC')->select('hrAbdLeftROM as left','hrAbdRightROM as right','hrAbdLeftPower as powerLeft','hrAbdRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'elbow'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','elbow')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','elbow')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'forearm'){
                            if($subflag == 'supination'){
                                $getData = DB::table('mt_hip_exam')->where('flag','forearm')->orderBy('id','ASC')->select('supinationLeftROM as left','supinationRightROM as right','supinationLeftPower as powerLeft','supinationRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'pronation'){
                                $getData = DB::table('mt_hip_exam')->where('flag','forearm')->orderBy('id','ASC')->select('pronationLeftROM as left','pronationRightROM as right','pronationLeftPower as powerLeft','pronationRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'wrist'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','wrist')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','wrist')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'radialDev'){
                                $getData = DB::table('mt_hip_exam')->where('flag','wrist')->orderBy('id','ASC')->select('radialDevLeftROM as left','radialDevRightROM as right','radialDevLeftPower as powerLeft','radialDevRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'ulnarDev'){
                                $getData = DB::table('mt_hip_exam')->where('flag','wrist')->orderBy('id','ASC')->select('ulnarDevLeftROM as left','ulnarDevRightROM as right','ulnarDevLeftPower as powerLeft','ulnarDevRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else if($flag === 'fingers'){
                            if($subflag == 'flexion'){
                                $getData = DB::table('mt_hip_exam')->where('flag','fingers')->orderBy('id','ASC')->select('flexionLeftROM as left','flexionRightROM as right','flexionLeftPower as powerLeft','flexionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'extension'){
                                $getData = DB::table('mt_hip_exam')->where('flag','fingers')->orderBy('id','ASC')->select('extensionLeftROM as left','extensionRightROM as right','extensionLeftPower as powerLeft','extensionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'abduction'){
                                $getData = DB::table('mt_hip_exam')->where('flag','fingers')->orderBy('id','ASC')->select('abductionLeftROM as left','abductionRightROM as right','abductionLeftPower as powerLeft','abductionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else if($subflag == 'adduction'){
                                $getData = DB::table('mt_hip_exam')->where('flag','fingers')->orderBy('id','ASC')->select('adductionLeftROM as left','adductionRightROM as right','adductionLeftPower as powerLeft','adductionRightPower as powerRight',DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                                if(count($getData) > 0){
                                    foreach ($getData as $gData) {
                                        array_walk_recursive($gData, function (&$item, $key) {
                                            $item = null === $item ? '0' : $item;
                                        });
                                    }
                                    $response['message'] = 'Data get successfully!';
                                    $response['status'] = '1';
                                    $response['allData'] = $getData;
                                }else{
                                    $response['message'] = 'Data does not exist!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $response['message'] = 'Invalid Sub Flag!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Invalid Flag!';
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function addExerciseCalender(Request $request){
        try{
            if(Input::has('therapistId') && Input::has('visitId') && Input::has('time') && Input::has('exerciseId') && Input::has('duration')){
                $therapistId = $request->therapistId;
                $visitId = $request->visitId;
                $time = $request->time;
                $exerciseId = $request->exerciseId;
                $duration = $request->duration;
                $repetition = $request->repetition;
                $hold = $request->hold;
                if(!empty($therapistId) && !empty($visitId) && !empty($time) && !empty($exerciseId)){
                    $checkTherapist = User::where('id',$therapistId)->where('user_type',5)->where('status','active')->first();
                    if($checkTherapist){
                        $checkVisitId = DB::table('daily_entry')->where('id',$visitId)->first();
                        if($checkVisitId){
                            $visitDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                            $appId = $visitDetails->appointment_id;
                            $appDetails = appointmentDetails($appId);
                            $patientId = $appDetails->user_id;
                            $addData = array();
                            $addData['visitId'] = $visitId;
                            $addData['appId'] = $appId;
                            $addData['exerciseId'] = $exerciseId;
                            $addData['duration'] = $duration;
                            $addData['repetition'] = $repetition;
                            $addData['hold'] = $hold;
                            $addData['patient_id'] = $patientId;
                            $addData['therapist_id'] = $therapistId;
                            $addData['date'] = date('Y-m-d');
                            $addData['time'] = $time;
                            $addData['created_at'] = date('Y-m-d H:i:s');
                            DB::table('exercise_calender')->insert($addData);

                            $response['message'] = 'Save Successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Visit not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All Fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allExerciseCalender(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $getData = DB::table('exercise_calender')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
                        if(count($getData) > 0){
                            $exDetails = array();
                            foreach($getData as $gData){
                                $exData = array();
                                $givenExerciseId = explode(',', $gData->exerciseId);
                                foreach($givenExerciseId as $gExe){
                                    $gsData = array();
                                    $gsData['id'] = $gData->id;
                                    $gsData['visitId'] = $gData->visitId;
                                    $gsData['appId'] = $gData->appId;
                                    $gsData['exerciseId'] = $gData->exerciseId;
                                    $gsData['duration'] = $gData->duration;
                                    $gsData['date'] = $gData->date;
                                    $gsData['time'] = $gData->time;
                                    $gsData['therapist_id'] = $gData->therapist_id;
                                    $gsData['patient_id'] = $gData->patient_id;
                                    $gsData['created_at'] = $gData->created_at;
                                    if(!empty($gExe)){
                                        $exerDetails = exerciseDetials($gExe);
                                        $gsData['selectedExerciseId'] = $gExe;
                                        $gsData['exerciseName'] = $exerDetails->name;
                                        $gsData['description'] = $exerDetails->description;
                                    }
                                    if(!empty($gData->therapist_id)){
                                        $therapistName = userDetails($gData->therapist_id)->name;
                                        $gsData['therapistName'] = $therapistName;
                                    }
                                    if(!empty($gData->patient_id)){
                                        $patientDetails = userDetails($gData->patient_id);
                                        $patientName = $patientDetails->name;
                                        if(!empty($patientDetails->profile_pic)){
                                            $patientProfile = API_PROFILE_PIC.$patientDetails->profile_pic;
                                        }else{
                                            $patientProfile = API_FOR_DEFAULT_IMG;
                                        }
                                        $gsData['patientName'] = $patientName;
                                        $gsData['patientProfile'] = $patientProfile;
                                    }
                                    array_push($exDetails, $gsData);
                                }
                            }
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['getData'] = $exDetails;
                        }else{
                            $response['message'] = 'Data not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Patient not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All Fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }
    
    public function searchPatientDetails(Request $request){
        try{
            if(Input::has('mobileNo')){
                $mobileNo = $request->mobileNo;
                if(!empty($mobileNo)){
                    $checkPatient = User::where('mobile',$mobileNo)->where('user_type',3)->where('status','active')->select('id','registration_no','refer_code','name','email','gender','therapist_id','mobile','profile_pic')->first();
                    if($checkPatient){
                        // foreach ($checkPatient as $patientData){
                            if(!empty($checkPatient->therapist_id)){
                                $checkPatient->therapist_id = userDetails($checkPatient->therapist_id)->name;
                            }else{
                                $checkPatient->therapist_id = '';
                            }
                            // profile picture
                            if(!empty($checkPatient->profile_pic)){
                                $checkPatient->profile_pic = API_PROFILE_PIC.$checkPatient->profile_pic;
                            }else{
                                $checkPatient->profile_pic = API_FOR_DEFAULT_IMG;
                            }
                            // array_walk_recursive($patientData, function (&$item, $key) {
                            //     $item = null === $item ? '' : $item;
                            // });
                        // }
                        $response['message'] = 'Data get successfully!';
                        $response['status'] = '1';
                        $response['getData'] = $checkPatient;
                    }else{
                        $response['message'] = 'Patient not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All Fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }
    
    public function addExtraTreatmentGiven(Request $request){
        try{
            if(Input::has('visitId') && Input::has('therapistId') && Input::has('flag')){
                $visitId = $request->visitId;
                $therapistId = $request->therapistId;
                $flag = $request->flag;
                if(!empty($visitId) && !empty($therapistId) && !empty($flag)){
                    $checkTherapist = User::where('id',$therapistId)->where('status','active')->first();
                    if($checkTherapist){
                        $checkVisit = DB::table('daily_entry')->where('id',$visitId)->first();
                        if($checkVisit){
                            $visitDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                            $appId = $visitDetails->appointment_id;
                            $patientId = appointmentDetails($appId)->user_id;
                            if(($flag == 'HP') || ($flag == 'CP') || ($flag == 'CHC') || ($flag == 'HCH')){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['time'] = $request->time;
                                $addData['place'] = $request->place;
                                $addData['no'] = $request->no;
                                $addData['patient_position'] = $request->patient_position;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else if(($flag == 'IFT') || ($flag == 'TENS') || ($flag == 'MFSurge')){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['pattern'] = $request->pattern;
                                $addData['time'] = $request->time;
                                $addData['patient_position'] = $request->patient_position;
                                $addData['electrode_placement'] = $request->electrode_placement;
                                $addData['frequency'] = $request->frequency;
                                $addData['dose'] = $request->dose;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else if($flag == 'US'){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['intensity'] = $request->intensity;
                                $addData['frequency'] = $request->frequency;
                                $addData['time'] = $request->time;
                                $addData['place'] = $request->place;
                                $addData['position_of_area_treated'] = $request->position_of_area_treated;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else if($flag == 'Longwave'){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['intensity'] = $request->intensity;
                                $addData['frequency'] = $request->frequency;
                                $addData['time'] = $request->time;
                                $addData['place'] = $request->place;
                                $addData['position_of_area_treated'] = $request->position_of_area_treated;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else if($flag == 'Traction'){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['intensity'] = $request->intensity;
                                $addData['frequency'] = $request->frequency;
                                $addData['time'] = $request->time;
                                $addData['place'] = $request->place;
                                $addData['position_of_area_treated'] = $request->position_of_area_treated;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else if($flag == 'Manual'){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['manual'] = $request->manual;
                                $addData['unlbi'] = $request->unlbi;
                                $addData['areajims'] = $request->areajims;
                                $addData['patient_position'] = $request->patient_position;
                                $addData['description'] = $request->description;
                                $addData['rep_hold'] = $request->rep_hold;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else if(($flag == 'Extremity') || ($flag == 'Trunk')){
                                $addData = array();
                                $addData['visit_id'] = $visitId;
                                $addData['therapist_id'] = $therapistId;
                                $addData['patient_id'] = $patientId;
                                $addData['flag'] = $flag;
                                // $addData['flagMsg'] = $request->flagMsg;
                                $addData['extremity_trunk'] = $request->extremity_trunk;
                                $addData['muscle'] = $request->muscle;
                                $addData['patient_position'] = $request->patient_position;
                                $addData['stretch_strength'] = $request->stretch_strength;
                                $addData['pa_aa_a_r'] = $request->pa_aa_a_r;
                                $addData['hold'] = $request->hold;
                                $addData['rep'] = $request->rep;
                                $addData['wt_cutt_theraband'] = $request->wt_cutt_theraband;
                                $addData['created_at'] = date('Y-m-d');
                                DB::table('extra_treatment_data')->insert($addData);

                                $response['message'] = 'Add Successfully!';
                                $response['status'] = '1';
                            }else{
                                $response['message'] = 'Invalid flag!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Visit not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Therapist not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All Fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }
    
    public function allExtraTreatmentGiven(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $getData = DB::table('extra_treatment_data')->where('patient_id',$patientId)->orderBy('id','DESC')->get();
                    if(count($getData) > 0){
                        foreach ($getData as $gData){
                            $gData->created_at = date("d-M-Y", strtotime($gData->created_at));
                            if(!empty($gData->therapist_id)){
                                $therapistName = userDetails($gData->therapist_id)->name;
                                $gData->therapist_id = $therapistName;
                            }
                            if(!empty($gData->patient_id)){
                                $patientName = userDetails($gData->patient_id)->name;
                                $gData->patient_id = $patientName;
                            }
                            array_walk_recursive($gData, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }
                        $response['message'] = 'Data get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $getData;
                    }else{
                        $response['message'] = 'Data not Exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Fields can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All Fields are Mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }
}