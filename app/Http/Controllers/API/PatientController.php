<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App;
use DB;
use File;
use Mail;
use App\User;
use App\Appointment;
use App\AppointmentHistory;
use App\DailyEntry;
use App\CapriPoint;
use DateTime;
use App\Helper\SendNotification;
use App\Helper\SendSMS;

class PatientController extends Controller
{
    use SendNotification;
    use SendSMS;
    public function randomValue($length = 10) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function quickRandom($length = 10) {
        $pool = '0123456789';
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    public function sendSMSMessage($message, $numbers){
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=CAPRIS&mobiles=$numbers&authkey=275077ABU34gWQkd9v5ccc8f67&message=$message&country=91",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        return $response;
    }

    public function register(Request $request){
        try{
            if(Input::has('phone') && Input::has('email') && Input::has('password') && Input::has('referBy')){
        		$phone = $request->phone;
                $email = $request->email;
                $password = $request->password;
                $referBy = $request->referBy;
        		if(!empty($phone) && !empty($email) && !empty($password)){
        			$checkDuplicacy = User::where('mobile',$phone)->first();
                    if($checkDuplicacy){
                        if($checkDuplicacy->status == 'active'){
                            // Already exist user
                            $response['message'] = 'This phone no is already exist!';
                            $response['status'] = '0';
                        }else if($checkDuplicacy->status == 'inactive'){
                            // Inactive User, send otp again
                            $patientData = array();
                            $otp = $this->quickRandom(6);
                            $patientData['otp'] = $otp;
                            $result = User::where('mobile',$phone)->update($patientData);
                            if($result){
                                $message = 'Your otp is '.$otp.', please verify your contact no!!';
                                $sendsms = $this->sendSMSMessage($message,$phone);
                                $response['message'] = 'Please verify your Contact no!';
                                $response['status'] = '1';
                            }
                        }
                    }else{
                        // New registration
                        $noCheck = ctype_digit($phone);
                        if($noCheck == 'true'){
                            $patientData = new User();
                            $patientData['mobile'] = $phone;
                            $patientData['email'] = $email;
                            $patientData['password'] = bcrypt($password);
                            $patientData['confirmpassword'] = $password;
                            $patientData['user_type'] = 3;
                            $patientData['status'] = 'inactive';
                            $otp = $this->quickRandom(6);
                            $patientData['otp'] = $otp;
                            // Add Capri points code 
                            if(!empty($referBy)){
                                $checkCode = User::where('refer_code',$referBy)->first();
                                if($checkCode){
                                    $result = $patientData->save();
                                    $latestUserId = $patientData->id;                                    
                                    $updateReferData = array();
                                    $updateReferData['refer_by'] = $referBy;
                                    User::where('id',$latestUserId)->update($updateReferData);
                                    $message = 'Your otp is '.$otp.', please verify your contact no!!';
                                    $sendsms = $this->sendSMSMessage($message,$phone);
                                    
                                    // Add Capri points for refer by
                                    $getReferByPoint = DB::table('cpoint')->where('name','Refer By')->first();
                                    if($getReferByPoint){
                                        $referByPoint = $getReferByPoint->point;
                                        $referByData = array();
                                        $referByData['user_id'] = $checkCode->id;
                                        $referByData['cpoint_id'] = $getReferByPoint->id;
                                        $referByData['cp_point'] = $getReferByPoint->point;
                                        $referByData['cp_amount'] = $getReferByPoint->amount;
                                        $referByData['type'] = 'credit';
                                        $referByData['remark'] = 'refer by point add';
                                        $referByData['created_at'] = date('Y-m-d H:i:s');
                                        DB::table('capri_point')->insert($referByData);
                                    }
                                    // Add Capri points for refer to
                                    $getReferToPoint = DB::table('cpoint')->where('name','Refer To')->first();
                                    if($getReferToPoint){
                                        $referToPoint = $getReferToPoint->point;
                                        $referToPoint = array();
                                        $referToPoint['user_id'] = $latestUserId;
                                        $referToPoint['cpoint_id'] = $getReferToPoint->id;
                                        $referToPoint['cp_point'] = $getReferToPoint->point;
                                        $referToPoint['cp_amount'] = $getReferToPoint->amount;
                                        $referToPoint['type'] = 'credit';
                                        $referToPoint['remark'] = 'refer to point add';
                                        DB::table('capri_point')->insert($referToPoint);
                                    }
                                    
                                    $response['message'] = 'Successfully Registered!';
                                    $response['status'] = '1';
                                }else{
                                    $response['message'] = 'Refer code is Invalid!';
                                    $response['status'] = '0';
                                }
                            }else{
                                $result = $patientData->save();
                                $message = 'Your otp is '.$otp.', please verify your contact no!!';
                                $sendsms = $this->sendSMSMessage($message,$phone);
                                $response['message'] = 'Successfully Registered!';
                                $response['status'] = '1';
                            }
                        }else{
                            $response['message'] = 'Please enter valid phone no!';
                            $response['status'] = '0';
                        }
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

    public function sendMailMessage($email,$message){
        $subject = $message;
        $data = array(
          'email' => $email,
          'message' => $message,
        );
        // dd($data, $subject);
       //  Mail::send('email.otp', $data, function ($msg) use ($data){
       //     $msg->to($data['email'],'Caprispine');
       //     $msg->subject('OTP Verification');
       // });
        Mail::send('email.otp', $data, function($message)
        {
            $message->from($data['email'], 'Caprispine');

            $message->to('info.caprispine@gmail.com')->cc('info.caprispine@gmail.com');
        });
        return true;
    }

    public function verifyOtp(Request $request){
        try{
            if(Input::has('phone') && Input::has('otp')){
                $phone = $request->phone;
                $otp = $request->otp;
                if(!empty($phone) && !empty($otp)){
                    $noCheck = ctype_digit($phone);
                    if($noCheck == 'true'){
                        $userDetails = User::where('mobile',$phone)->first();
                        $userOtp = $userDetails->otp;
                        $password = $userDetails->confirmpassword;
                        if($userOtp == $otp){
                            $updateData = array();
                            $updateData['status'] = 'active';
                            $username = $this->randomValue(4);
                            $updateData['username'] = 'user_'.$username;
                            $result = User::where('mobile',$phone)->update($updateData);
                            if($result){
                                $message = 'You have successfully registered in Caprispine Patient app. Your password is '.$password.'. Please update your profile. Wish you a fast recovery.';
                                $sendsms = $this->sendSMSMessage($message,$phone);
                                // Refer code update in user details
                                $dbReferCode = $userDetails->refer_code;
                                if(empty($dbReferCode)){
                                    $patientName = $userDetails->email;
                                    $patientNam = substr($patientName, 0, 3);
                                    $userDetailsData = array();
                                    $randomValue = 100000 + $userDetails->id;
                                    $referCode = $patientNam."_".$randomValue;
                                    $userDetailsData['refer_code'] = strtoupper($referCode);
                                    User::where('id',$userDetails->id)->update($userDetailsData);
                                }
                            }
                            $response['message'] = 'Successfully Verified!';
                            $response['status'] = '1';
                            $response['patientId'] = $userDetails->id;
                        }else{
                            $response['message'] = 'Incorrect OTP!';
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

    public function loginPatient(Request $request){
        try{
            if(Input::has('phone') && Input::has('password')){
                $phone = $request->phone;
                $password = $request->password;
                $tokenId = $request->tokenId;
                if(!empty($phone) && !empty($password)){
                    $noCheck = ctype_digit($phone);
                    if($noCheck == 'true'){
                        $userDetails = User::where('mobile',$phone)->where('status','active')->where('user_type',3)->first();
                        if($userDetails){
                            $userPhone = $userDetails->mobile;
                            $userPassword = $userDetails->confirmpassword;
                            if($userPassword == $password){
                                // Token Id update
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
                                    User::where('id',$userDetails->id)->update($userDetailsData);
                                }
                                $response['message'] = 'Successfully Login!';
                                $response['status'] = '1';
                                $response['userId'] = $userDetails->id;
                                $response['userType'] = $userDetails->user_type;
                                $response['referCode'] = $userDetails->refer_code;
                            }else{
                                $response['message'] = 'Incorrect phone no and password';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Patient not exist!';
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

    public function patientProfile(Request $request){
        try{
            if(Input::has('userId') && Input::has('flag')){
                $userId = $request->userId;
                $flag = $request->flag;
                if(!empty($userId) && !empty($flag)){
                    $userDetails = User::where('id',$userId)->where('status','active')->select('id','registration_no','name','email','gender','therapist_id','username','address','mobile','dob','user_type','profile_pic','status','state','city','occupation')->where('user_type',3)->first();
                    if($userDetails){
                        if($flag == 1){
                            // view profile
                            //Convert null value to empty string 
                            array_walk_recursive($userDetails,function(&$item){$item=strval($item);});
                            // state and city name
                            if(!empty($userDetails->state)){
                                $stateName = DB::table('states')->where('id',$userDetails->state)->first();
                                $state = $stateName->name;
                            }else{
                                $state = '';
                            }
                            if(!empty($userDetails->city)){
                               $cityName = DB::table('cities')->where('id',$userDetails->city)->first();
                               $city = $cityName->name;
                            }else{
                                $city = '';
                            }
                            $userDetails['stateName'] = $state;
                            $userDetails['cityName'] = $city;
                            if(!empty($userDetails->profile_pic)){
                                $userDetails['profile_pic'] = API_PROFILE_PIC.$userDetails->profile_pic;
                            }else{
                                $userDetails['profile_pic'] = API_FOR_DEFAULT_IMG;
                            }
                            // if(!empty($userDetails->branch)){
                            //     $bName = DB::table('location')->where('id',$userDetails->branch)->first();
                            //     $userDetails['branchName'] = $bName->name;
                            // }
                            $response['message'] = 'Profile view successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $userDetails;
                        }else if($flag == 2){
                            // edit profile
                            $updateData = array();
                            if($request->get('name')){
                                $updateData['name'] = $request->get('name');
                            }
                            if($request->get('gender')){
                                $updateData['gender'] = $request->get('gender');
                            }
                            if($request->get('address')){
                                $updateData['address'] = $request->get('address');
                            }
                            if($request->get('state')){
                                $updateData['state'] = $request->get('state');
                            }
                            if($request->get('city')){
                                $updateData['city'] = $request->get('city');
                            }
                            if($request->get('dob')){
                                $updateData['dob'] = $request->get('dob');
                            }
                            if($request->get('mobile')){
                                $updateData['mobile'] = $request->get('mobile');
                            }
                            if($request->get('email')){
                                $updateData['email'] = $request->get('email');
                            }
                            if($request->get('occupation')){
                                $updateData['occupation'] = $request->get('occupation');
                            }
                            // if($request->get('branch')){
                            //     $updateData['branch'] = $request->get('branch');
                            //     $regNo = 100000 + $userId;
                            //     $branchDetails = DB::table('location')->where('id',$request->get('branch'))->first();
                            //     $branch_key = $branchDetails->b_key;
                            //     $updateData['registration_no'] = $regNo.'_'.$branch_key.'_PT';
                            // }
                            if($request->profile_pic){
                                $profile_pic = $request->profile_pic;
                                $profile_pic = str_replace('data:image/png;base64,', '', $profile_pic); 
                                $profile_pic = str_replace(' ', '+', $profile_pic);
                                $imageName = $this->randomValue(10).'.'.'png';
                                $basePath = 'upload/profile_pic/';
                                $sendFile = File::put($basePath.$imageName, base64_decode($profile_pic));
                                if($sendFile){
                                    $updateData['profile_pic'] = $imageName;
                                }else{
                                    $updateData['profile_pic'] = '';
                                }
                            }

                            User::where('id',$userId)->update($updateData);

                            $response['message'] = 'Profile updated successfully!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Please select valid flag 1 or 2!';
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

    public function resendOtp(Request $request){
        try{
            if(Input::has('phone')){
                $phone = $request->phone;
                if(!empty($phone)){
                    $userDetails = User::where('mobile',$phone)->where('status','inactive')->first();
                    if($userDetails){
                        $otp = $this->quickRandom(6);
                        $patientData = array();
                        $patientData['otp'] = $otp;
                        $result = DB::table('users')->where('mobile',$phone)->update($patientData);
                        if($result){
                            $message = 'Your otp is '.$otp.', please verify your contact no!!';
                            $sendsms = $this->sendSMSMessage($message,$phone);

                            $response['message'] = 'Successfully Send!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Something is wrong!';
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

    public function forgetPassword(Request $request){
        try{
            if(Input::has('phone')){
                $phone = $request->phone;
                if(!empty($phone)){
                    $noCheck = ctype_digit($phone);
                    if($noCheck == 'true'){
                        $userDetails = User::where('mobile',$phone)->where('status','active')->where('user_type','=',3)->first();
                        if($userDetails){
                            $password = $userDetails->confirmpassword;
                            if($password){
                                $message = 'Your password is '.$password.'!';
                                $sendsms = $this->sendSMSMessage($message,$phone);    
                            }
                            $response['message'] = 'Successfully send password in your mentioned mobile no!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Patient not exist!';
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

    public function resetPassword(Request $request){
        try{
            if(Input::has('userId') && Input::has('oldPassword') && Input::has('newPassword')){
                $userId = $request->userId;
                $oldPassword = $request->oldPassword;
                $newPassword = $request->newPassword;
                if(!empty($userId) && !empty($oldPassword) && !empty($newPassword)){
                    $userDetails = User::where('id',$userId)->where('status','active')->first();
                    if($userDetails){
                        $oldRegisteredPassword = $userDetails->confirmpassword;
                        if($oldRegisteredPassword == $oldPassword){
                            $updateData = array();
                            $updateData['password'] = bcrypt($newPassword);
                            $updateData['confirmpassword'] = $newPassword;
                            User::where('id',$userId)->update($updateData);
                            
                            $response['message'] = 'Password successfully reset!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Your old password is incorrect!';
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

    public function patientPendingVisit(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allPendingData = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->where('appointment.user_id',$patientId)->where('daily_entry.status','!=','complete')->orderBy('daily_entry.app_booked_date','DESC')->select('daily_entry.id','daily_entry.appointment_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.in_time','daily_entry.out_time','daily_entry.amount','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','daily_entry.type','daily_entry.package_id','daily_entry.therapist_id','appointment.user_id as patientId','appointment.joints','daily_entry.rating','daily_entry.status')->get();
                        if(count($allPendingData) > 0){
                            foreach ($allPendingData as $pendingValue){
                                if(!empty($pendingValue->patientId)){
                                    $userDetails = DB::table('users')->where('id',$pendingValue->patientId)->first();
                                    $pendingValue->patientId = $userDetails->name;
                                    $serviceType = $userDetails->service_type;
                                    if(!empty($serviceType)){
                                        $serviceData = DB::table('service')->where('id',$serviceType)->first();
                                        $pendingValue->serviceName = $serviceData->name;
                                    }else{
                                        $pendingValue->serviceName = '';
                                    }
                                    if($pendingValue->status == 'approval_pending'){
                                        $pendingValue->status = 'Pending for Approval';
                                    }else{
                                        $pendingValue->status = $pendingValue->status;
                                    }
                                    if($userDetails->profile_pic){
                                        $pendingValue->profilePicture = API_PROFILE_PIC.$userDetails->profile_pic;
                                    }else{
                                        $pendingValue->profilePicture = API_FOR_DEFAULT_IMG;
                                    }
                                }
                                if(!empty($pendingValue->therapist_id)){
                                    $userDetails = DB::table('users')->where('id',$pendingValue->therapist_id)->first();
                                    $pendingValue->therapist_id = $userDetails->name;
                                }
                                if(!empty($pendingValue->type)){
                                    if($pendingValue->type == 1){
                                        $pendingValue->type = 'Perday';
                                        $pendingValue->pendingAmount = '';
                                    }else if($pendingValue->type == 2){
                                        $pendingValue->type = 'Package';
                                        $pendingValue->pendingAmount = 0;
                                    }else if($pendingValue->type == 3){
                                        $pendingValue->type = 'Complimentary';
                                        $pendingValue->pendingAmount = 0;
                                    }
                                }
                                if(!empty($pendingValue->package_id)){
                                    $packageDetails = DB::table('package')->where('id',$pendingValue->package_id)->first();
                                    $pendingValue->package_id = $packageDetails->name;
                                }
                                array_walk_recursive($pendingValue, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allPendingData;
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

    public function patientCompleteVisit(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allPendingData = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->where('appointment.user_id',$patientId)->where('daily_entry.status','complete')->orderBy('daily_entry.app_booked_date','DESC')->select('daily_entry.id','daily_entry.appointment_id','daily_entry.visit_type','daily_entry.app_booked_date','daily_entry.app_booked_time','daily_entry.in_time','daily_entry.out_time','daily_entry.amount','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days','daily_entry.type','daily_entry.package_id','daily_entry.therapist_id','appointment.user_id as patientId','appointment.joints','daily_entry.rating','daily_entry.total_seats','daily_entry.no_of_seats','daily_entry.due_days')->get();
                        // dd($allPendingData);
                        if(count($allPendingData) > 0){
                            foreach ($allPendingData as $pendingValue){
                                if(!empty($pendingValue->patientId)){
                                    $userDetails = DB::table('users')->where('id',$pendingValue->patientId)->first();
                                    $pendingValue->patientId = $userDetails->name;
                                    $serviceType = $userDetails->service_type;
                                    if(!empty($serviceType)){
                                        $serviceData = DB::table('service')->where('id',$serviceType)->first();
                                        $pendingValue->serviceName = $serviceData->name;
                                    }else{
                                        $pendingValue->serviceName = '';
                                    }
                                    if($userDetails->profile_pic){
                                        $pendingValue->profilePicture = API_PROFILE_PIC.$userDetails->profile_pic;
                                    }else{
                                        $pendingValue->profilePicture = API_FOR_DEFAULT_IMG;
                                    }
                                }
                                if(!empty($pendingValue->therapist_id)){
                                    $userDetails = DB::table('users')->where('id',$pendingValue->therapist_id)->first();
                                    $pendingValue->therapist_id = $userDetails->name;
                                }
                                if(!empty($pendingValue->type)){
                                    if($pendingValue->type == 1){
                                        $pendingValue->type = 'Perday';
                                    }else if($pendingValue->type == 2){
                                        $pendingValue->type = 'Package';
                                    }else if($pendingValue->type == 3){
                                        $pendingValue->type = 'Complimentary';
                                    }
                                }
                                if(!empty($pendingValue->package_id)){
                                    $packageDetails = DB::table('package')->where('id',$pendingValue->package_id)->first();
                                    $pendingValue->package_id = $packageDetails->name;
                                }
                                array_walk_recursive($pendingValue, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'All data get successfully!';
                            $response['status'] = '1';
                            $response['allData'] = $allPendingData;
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

    public function allAppointmentForPatient(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('appointment')->where('appointment.user_id',$patientId)->join('users','users.id','=','appointment.user_id')->where('appointment.status','!=','cancel')->select('appointment.id as appointment_id','appointment.user_id','users.therapist_id as therapist_id','appointment.joints','appointment.appointment_date','appointment.appointment_time','appointment.payment_method','appointment.amount','appointment.reference_type','appointment.status','appointment.package_type','appointment.app_service_type','users.profile_pic')->orderBy('appointment.appointment_date','DESC')->get();
                        if(count($allData) > 0){
                            foreach ($allData as $allVal) {
                                if($allVal->user_id){
                                    $checkUser = User::where('id',$allVal->user_id)->select('name')->first();
                                    $allVal->patientName = $checkUser->name;
                                }else{
                                    $allVal->patientName = '';
                                }
                                if($allVal->therapist_id){
                                    $checkTherapist = User::where('id',$allVal->therapist_id)->select('name')->first();
                                    $allVal->therapistName = $checkTherapist->name;
                                }else{
                                    $allVal->therapistName = '';
                                }
                                if($allVal->package_type){
                                    $packageData = DB::table('package')->where('id',$allVal->package_type)->first();
                                    $allVal->package_type = $packageData->name.'('.$packageData->package_amount.')';
                                }else{
                                    $allVal->package_type = '';
                                }
                                if($allVal->reference_type){
                                    $referenceData = DB::table('reference')->where('id',$allVal->reference_type)->first();
                                    $allVal->reference_type = $referenceData->name;
                                }else{
                                    $allVal->reference_type = '';
                                }
                                if($allVal->app_service_type){
                                    $serviceData = DB::table('service')->where('id',$allVal->app_service_type)->first();

                                    $allVal->serviceType = $serviceData->name;
                                }else{
                                    $allVal->serviceType = '';
                                }
                                if($allVal->profile_pic){
                                    $allVal->patientProfilePicture = API_PROFILE_PIC.$allVal->profile_pic;
                                }else{
                                    $allVal->patientProfilePicture = API_FOR_DEFAULT_IMG;
                                }
                                if($allVal->appointment_time){
                                    $appTime = DB::table('time_slot')->where('id',$allVal->appointment_time)->first();
                                    $allVal->appointment_time = $appTime->time;
                                }else{
                                    $allVal->reference_type = '';
                                }
                                if($allVal->payment_method){
                                    if($allVal->payment_method == 'per_day_visit'){
                                        $allVal->payment_method = 'Perday';
                                    }else if($allVal->payment_method == 'package_wise'){
                                        $allVal->payment_method = 'Package';
                                    }else if($allVal->payment_method == 'complimentary'){
                                        $allVal->payment_method = 'Complimentary';
                                    }else{
                                        $allVal->payment_method = '';
                                    }
                                }else{
                                    $allVal->payment_method = '';
                                }
                                array_walk_recursive($allVal, function (&$item, $key) {
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

    public function bookAppointment(Request $request){
        try{
            if(!empty($request->therapistId)){
                // For Therapist App
                if(Input::has('flag') && Input::has('therapistId') && Input::has('date') && Input::has('time') && Input::has('joint') && Input::has('referenceType') && Input::has('patientType') && Input::has('branch') && Input::has('name') && Input::has('email') && Input::has('phone')){
                    $therapistId = $request->therapistId;
                    $date = $request->date;
                    $time = $request->time;
                    if($request->joint == 'One joint'){
                        $fixjoint = 'one_joint';
                    }else if($request->joint == 'Two joint'){
                        $fixjoint = 'two_joint';
                    }else if($request->joint == 'Three joint'){
                        $fixjoint = 'three_joint';
                    }else{
                        $fixjoint = 'neuro';
                    }
                    $joint = $fixjoint;
                    $referenceType = $request->referenceType;
                    $patientType = $request->patientType;
                    $branch = $request->branch;
                    $name = $request->name;
                    $phone = $request->phone;
                    $email = $request->email;
                    $flag = $request->flag;
                    if(!empty($flag) && !empty($therapistId) && !empty($joint) && !empty($date) && !empty($time) && !empty($referenceType) && !empty($patientType) && !empty($branch)){
                        $appData = new Appointment();
                        if($flag == 'new'){
                            if(!empty($name) && !empty($phone) && !empty($email)){
                                $checkUser = User::where('mobile',$phone)->first();
                                if(!empty($checkUser)){
                                    if($checkUser->status == 'inactive'){
                                        $response['message'] = 'User not active right now!';
                                        $response['status'] = '0';
                                    }else{
                                        // $user_id = $checkUser->id;
                                        $response['message'] = 'These Contact no already exist, Please use another unique no!';
                                        $response['status'] = '0';
                                    }
                                }else{
                                    $userData = new User();
                                    $userData['name'] = $name;
                                    $userData['therapist_id'] = $therapistId;
                                    $userData['mobile'] = $phone;
                                    $userData['email'] = $email;
                                    $randomPass = quickRandom(6);
                                    $userData['password'] = bcrypt($randomPass);
                                    $userData['confirmpassword'] = $randomPass;
                                    $usename = quickRandom(4);
                                    $userData['username'] = 'user_'.$usename;
                                    $userData['branch'] = $branch;
                                    $userData['service_type'] = $patientType;
                                    // $userData['service_type'] = '';

                                    // if(!empty($patientType)){
                                    //     $pName = DB::table('service')->where('id',$patientType)->first();
                                    //     $pType = $pName->name;
                                    // }else{
                                    //     $pType = '';
                                    // }
                                    $userData['patient_type'] = 6;  //till now send static value change it after app update
                                    $userData['status'] = 'active';
                                    $userData['user_type'] = 3;
                                    $userData['created_by'] = $therapistId;
                                    $userData->save();
                                    // send another user message with link and password
                                    
                                    $user_id = $userData->id;
                                    // add registration id
                                    $regData = array();
                                    $regNo = 100000 + $user_id;
                                    $branch_key = branchDetails($branch)->b_key;
                                    $regData = array();
                                    $regData['registration_no'] = $regNo.'_'.$branch_key.'_PT';
                                    $saveData = User::where('id',$user_id)->update($regData);
                                    // appointment book
                                    $appData['user_id'] = $user_id;
                                    $appData['joints'] = $joint;
                                    $appData['patient_type'] = $flag;
                                    $appData['appointment_type'] = 'manual';
                                    $appData['appointment_time'] = $time;
                                    $appData['appointment_date'] = $date;
                                    $appData['reference_type'] = $referenceType;
                                    $appData['status'] = 'pending';
                                    $appData['payment_method'] = 'per_day_visit';
                                    $appData['created_by'] = $therapistId;
                                    $appData['app_service_type'] = $patientType;
                                    $appData->save();
                                    $appId = $appData->id;
                                    $response['message'] = 'Appointment booked successfully!';
                                    $response['status'] = '1';
                                    $response['appointmentId'] = $appId;
                                }
                            }else{
                                $response['message'] = 'Field cant be empty!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Please select valid flag!';
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
            }else{
                // For Patient App
                if(Input::has('flag') && Input::has('patientId') && Input::has('date') && Input::has('time') && Input::has('joint') && Input::has('referenceType') && Input::has('patientType') && Input::has('branch') && Input::has('name') && Input::has('email') && Input::has('phone')){
                    $patientId = $request->patientId;
                    $date = $request->date;
                    $time = $request->time;
                    if($request->joint == 'One joint'){
                        $fixjoint = 'one_joint';
                    }else if($request->joint == 'Two joint'){
                        $fixjoint = 'two_joint';
                    }else if($request->joint == 'Three joint'){
                        $fixjoint = 'three_joint';
                    }else{
                        $fixjoint = 'neuro';
                    }
                    $joint = $fixjoint;
                    $referenceType = $request->referenceType;
                    $patientType = $request->patientType;
                    $branch = $request->branch;
                    $name = $request->name;
                    $phone = $request->phone;
                    $email = $request->email;
                    $flag = $request->flag;
                    if(!empty($flag) && !empty($patientId) && !empty($joint) && !empty($date) && !empty($time) && !empty($referenceType) && !empty($patientType) && !empty($branch)){
                        $appData = new Appointment();
                        if($flag == 'old'){
                            $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                            if($checkPatient){
                                $appData['user_id'] = $patientId;
                                $useData = array();
                                $useData['service_type'] = $patientType;
                                User::where('id',$patientId)->update($useData);
                            }else{
                                $response['message'] = 'Patient not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'new'){
                            if(!empty($name) && !empty($phone) && !empty($email)){
                                $userData = new User();
                                $userData['name'] = $name;
                                $userData['mobile'] = $phone;
                                $userData['email'] = $email;
                                $randomPass = quickRandom(6);
                                $userData['password'] = bcrypt($randomPass);
                                $userData['confirmpassword'] = $randomPass;
                                $usename = quickRandom(4);
                                $userData['username'] = 'user_'.$usename;
                                $userData['branch'] = $branch;
                                $userData['patient_type'] = $patientType;
                                $userData['service_type'] = $patientType;
                                $userData['status'] = 'active';
                                $userData['user_type'] = 3;
                                $userData['created_by'] = $patientId;
                                $userData->save();
                                // send another user message with link and password
                                
                                $user_id = $userData->id;
                                // add registration id
                                $regData = array();
                                $regNo = 100000 + $user_id;
                                $branch_key = branchDetails($branch)->b_key;
                                $regData = array();
                                $regData['registration_no'] = $regNo.'_'.$branch_key.'_PT';
                                $saveData = User::where('id',$user_id)->update($regData);
                                $appData['user_id'] = $user_id;
                            }else{
                                $response['message'] = 'Field cant be empty!';
                                $response['status'] = '0';
                            }
                        }
                        
                        $appData['joints'] = $joint;
                        $appData['patient_type'] = $flag;
                        $appData['appointment_type'] = 'manual';
                        $appData['appointment_time'] = $time;
                        $appData['appointment_date'] = $date;
                        $appData['reference_type'] = $referenceType;
                        $appData['status'] = 'pending';
                        $appData['app_service_type'] = $patientType;
                        $appData['created_by'] = $patientId;
                        $appData->save();
                        $appId = $appData->id;
                        $response['message'] = 'Appointment booked successfully!';
                        $response['status'] = '1';
                        $response['appointmentId'] = $appId;
                        if($branch == '4'){
                            $response['branch'] = 'civilLine';
                        }else{
                            $response['branch'] = '';
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

    public function patientAddNewVisit(Request $request){
        try{
            if(Input::has('patientId') && Input::has('appId') && Input::has('date') && Input::has('time')){
                $patientId = $request->patientId;
                $userDetails = userDetails($patientId);
                $appId = $request->appId;
                $date = $request->date;
                $time = $request->time;
                $currentDate = date('Y-m-d');
                if(!empty($patientId) && !empty($appId) && !empty($date) && !empty($time)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        if(empty($checkPatient->therapist_id)){
                            $response['message'] = 'Please contact to Caprispine Team, they will assign you to therapist!';
                            $response['status'] = '0';
                        }else{
                            $checkAppointment = Appointment::where('id',$appId)->where('status','approved')->first();
                            $therapistId = $checkPatient->therapist_id;
                            if($checkAppointment){
                                $twoMoreCondition = DB::table('daily_entry')->where('appointment_id',$appId)->where(DB::raw("(DATE_FORMAT(created_date,'%Y-%m-%d'))"),$currentDate)->get();
                                if(count($twoMoreCondition) <= 3){
                                    $checkTimeAvailable = DB::table('daily_entry')->where('appointment_id',$appId)->where('therapist_id',$therapistId)->where('app_booked_date',$date)->where('app_booked_time',$time)->first();
                                    if($checkTimeAvailable){
                                        $response['message'] = 'Appointment already booked with someone!';
                                        $response['status'] = '0';
                                    }else{
                                        if($checkAppointment->payment_method == 'package_wise'){
                                            $checkPackageLimit = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$checkAppointment->package_type)->where('status','complete')->orderBy('id','DESC')->first();
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
                                            $appServiceType = $checkAppointment->app_service_type;
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
                                            $dailyEntry['appointment_id'] = $appId;
                                            $appDetails = Appointment::where('id',$appId)->first();
                                            $packageId = $appDetails->package_type;
                                            $dailyEntry['package_id'] = $packageId;
                                            $dailyEntry['therapist_id'] = $therapistId;
                                            $dailyEntry['package_id'] = $packageId;
                                            $dailyEntry['app_booked_date'] = $date;
                                            $dailyEntry['app_booked_time'] = $time;
                                            $dailyEntry['type'] = $appType;
                                            $dailyEntry['service_type'] = $appDetails->app_service_type;
                                            $dailyEntry['status'] = 'approval_pending';
                                            // if(!empty($appServiceType) && ($appServiceType == '7')){
                                            //     $dailyEntry['amount'] = 0;
                                            // }
                                            if($appType == 1){
                                                // for perday
                                                if(!empty($appServiceType) && ($appServiceType == '7')){
                                                    $dailyEntry['amount'] = 0;
                                                }else{
                                                    $dailyEntry['amount'] = 0;
                                                }
                                            }else if($appType == 2){

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
                                            }else if($appType == 3){
                                                $dailyEntry['amount'] = 0;
                                            }else{
                                                $dailyEntry['amount'] = 0;
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
                                            $history['appointment_id'] = $appId;
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
                                                $title = 'Appointment booked successfully!';
                                                $sendnot = $this->SendNotification($tokenId,$title);
                                                // add notification
                                                if($sendnot){
                                                    $addNot = array();
                                                    $addNot['user_id'] = $patientId;
                                                    $addNot['title'] = $title;
                                                    $addNot['token_id'] = $tokenId;
                                                    $addNot['date'] = date('Y-m-d');
                                                    $addNot['time'] = date('H:i:s');
                                                    DB::table('patient_notification')->insert($addNot);
                                                }
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

    public function bookAppThroughCallback(Request $request){
        try{
            if(Input::has('patientId') && Input::has('patientType')){
                $patientId = $request->patientId;
                $patientType = $request->patientType;
                if(!empty($patientId) && !empty($patientType)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $appData = new Appointment();
                        $appData['user_id'] = $patientId;
                        $appData['appointment_type'] = 'callback';
                        $appData['status'] = 'pending';
                        $appData['appointment_date'] = date('Y-m-d');
                        $appData['created_by'] = $patientId;
                        $appData->save();

                        $patientData = array();
                        $patientData['patient_type'] = $patientType;
                        User::where('id',$patientId)->update($patientData);
                        // send notification
                        $tokenId = $checkPatient->token_id;
                        if(!empty($tokenId)){
                            $title = 'Appointment booked successfully!';
                            $sendnot = $this->SendNotification($tokenId,$title);
                        }
                        $response['message'] = 'Appointment booked successfully!';
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

    public function allReferByList(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $patientReferCode = $checkPatient->refer_code;
                        if(!empty($patientReferCode)){
                            $allData = User::where('refer_by',$patientReferCode)->select('name','profile_pic')->orderBy('name','ASC')->get();
                            if(count($allData) > 0){
                                foreach ($allData as $referVal) {
                                    if($referVal->profile_pic){
                                        $referVal->profile_pic = API_PROFILE_PIC.$referVal->profile_pic;
                                    }else{
                                        $referVal->profile_pic = API_FOR_DEFAULT_IMG;
                                    }
                                }
                                $response['message'] = 'All data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $allData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Refer code not exist!';
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

    public function patientAddChiefComplaint(Request $request){
        try{
            if(Input::has('patientId') && Input::has('appId') && Input::has('chiefComplaint') && Input::has('problemTime') && Input::has('problemBefore')){
                $patientId = $request->patientId;
                $appId = $request->appId;
                $chiefComplaint = $request->chiefComplaint;
                $problemTime = $request->problemTime;
                $problemBefore = $request->problemBefore;
                if(!empty($patientId) && !empty($appId) && (!empty($chiefComplaint) || !empty($problemTime) || !empty($problemBefore))){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $insertData = array();
                        $insertData['patient_id'] = $patientId;
                        $insertData['appointment_id'] = $appId;
                        $insertData['chief_complaint'] = $chiefComplaint;
                        $insertData['problem_time'] = $problemTime;
                        $insertData['problem_before'] = $problemBefore;
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function patientAddHistoryExam(Request $request){
        try{
            if(Input::has('patientId') && Input::has('appId') && Input::has('causeOfProblem') && Input::has('medicalProblem') && Input::has('anySurgery') && Input::has('anyTreatment') && Input::has('smoking') && Input::has('alcoholic') && Input::has('feverAndChill') && Input::has('diabetes') && Input::has('bloodPressure') && Input::has('heartDiseases') && Input::has('bleedingDisorder') && Input::has('recentInfection') && Input::has('anyRegFlags') && Input::has('AnyYellowFlags') && Input::has('limitations') && Input::has('pastSurgery') && Input::has('allergies') && Input::has('osteoporotic') && Input::has('anyImplants') && Input::has('hereditaryDisease') && Input::has('remark')){
                $patientId = $request->patientId;
                $appId = $request->appId;
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
                if(!empty($patientId) && !empty($appId) && !empty($causeOfProblem) && !empty($medicalProblem) && !empty($anySurgery) && !empty($anyTreatment)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $examdata = array();
                        $examdata['patient_id'] = $patientId;
                        $examdata['appointment_id'] = $appId;
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
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function patientComplaint(Request $request){
        try{
            if(Input::has('patientId') && Input::has('description')){
                $patientId = $request->patientId;
                $description = $request->description;
                if(!empty($patientId) && !empty($description)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $addData = array();
                        $addData['patientId'] = $patientId;
                        $addData['description'] = $description;
                        DB::table('patient_complaint')->insert($addData);

                        $response['message'] = 'Thanks for your valuable compliment!';
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

    public function totalWalletAmount(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('status','active')->first();
                    if($checkPatient){
                        $totalCreditAmt = DB::table('capri_point')->where('user_id',$patientId)->where('type','credit')->sum('cp_amount');
                        $totalDebitAmt = DB::table('capri_point')->where('user_id',$patientId)->where('type','debit')->sum('cp_amount');
                        $totalDueAmt = $totalCreditAmt - $totalDebitAmt;
                        $allHistory = DB::table('capri_point')->where('user_id',$patientId)->get();
                        if(count($allHistory) > 0){
                            foreach ($allHistory as $histData) {
                                if($histData->user_id){
                                    $userData = User::where('id',$histData->user_id)->first();
                                    $histData->user_id = $userData->name;
                                }
                                $histData->created_at = date("d-M-Y", strtotime($histData->created_at));
                            }
                            $response['message'] = 'Successfully get data!';
                            $response['status'] = '1';
                            $response['totalAmount'] = $totalDueAmt;
                            $response['name'] = $checkPatient->name;
                            if(!empty($checkPatient->profile_pic)){
                                $profile_pics = API_PROFILE_PIC.$checkPatient->profile_pic;
                            }else{
                                $profile_pics = API_FOR_DEFAULT_IMG;
                            }
                            $response['pic'] = $profile_pics;
                            $response['allHistory'] = $allHistory;
                        }else{
                            $response['message'] = 'Successfully get data!';
                            $response['status'] = '1';
                            $response['totalAmount'] = $totalDueAmt;
                            $response['name'] = $checkPatient->name;
                            if(!empty($checkPatient->profile_pic)){
                                $profile_pics = API_PROFILE_PIC.$checkPatient->profile_pic;
                            }else{
                                $profile_pics = API_FOR_DEFAULT_IMG;
                            }
                            $response['pic'] = $profile_pics;
                            $response['allHistory'] = '';
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

    public function invoiceHistory(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        if(!empty($checkPatient->registration_no)){
                            $invoiceData = DB::table('invoice')->where('registration_no',$checkPatient->registration_no)->get();
                            if(count($invoiceData) > 0){
                                foreach ($invoiceData as $iData){
                                    if($iData->branch_id){
                                        $bData = DB::table('location')->where('id',$iData->branch_id)->first();
                                        $iData->branch_id = $bData->name;
                                    }

                                    array_walk_recursive($iData, function (&$item, $key) {
                                        $item = null === $item ? '' : $item;
                                    });
                                }
                                $response['message'] = 'Successfully get data!';
                                $response['status'] = '1';
                                $response['getData'] = $invoiceData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Registration not exist!';
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

    public function allNotifications(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allNotification = DB::table('patient_notification')->where('user_id',$patientId)->select('id','nofication_id','user_id','title as message','date','time')->orderBy('date','DESC')->get();
                        if(count($allNotification) > 0){
                            foreach ($allNotification as $allNot) {
                                if(!empty($allNot->nofication_id)){
                                    $getNotificationData = DB::table('notification')->where('id',$allNot->nofication_id)->first();
                                    $allNot->message = $getNotificationData->title.', '.$getNotificationData->message;
                                }
                                if(!empty($allNot->user_id)){
                                    $userDetails = DB::table('users')->where('id',$allNot->user_id)->first();
                                    $allNot->user_id = $userDetails->name;
                                    if(!empty($userDetails->profile_pic)){
                                        $profile_pics = API_PROFILE_PIC.$userDetails->profile_pic;
                                    }else{
                                        $profile_pics = API_FOR_DEFAULT_IMG;
                                    }
                                    $allNot->profilePic = $profile_pics;
                                }
                                if(!empty($allNot->nofication_id)){
                                    $getNData = DB::table('notification')->where('id',$allNot->nofication_id)->first();
                                    if(!empty($getNData->image)){
                                        $notificationImage = API_NOTIFICATION_IMG.$getNData->image;
                                    }else{
                                        $notificationImage = '';
                                    }
                                }else{
                                    $notificationImage = '';
                                }
                                $nImage = $notificationImage;
                                $allNot->notificationImage = $nImage;
                                $allNot->date = date("d-M-Y", strtotime($allNot->date));
                                array_walk_recursive($allNot, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Successfully get data!';
                            $response['status'] = '1';
                            $response['getData'] = $allNotification;
                        }else{
                            $response['msg'] = 'Data does not exist!';
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

    public function makePackagePayment(Request $request){
        try{
            if(Input::has('patientId') && Input::has('appId') && Input::has('packageId') && Input::has('transection_id') && Input::has('transection_status') && Input::has('amount')){
                $patientId = $request->patientId;
                $appId = $request->appId;
                $packageId = $request->packageId;
                $transectionId = $request->transection_id;
                $transectionStatus = $request->transection_status;
                $amount = $request->amount;
                $date = date('Y-m-d');
                if(!empty($patientId) && !empty($appId) && !empty($packageId) && !empty($transectionId) && !empty($transectionStatus)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $appointmentCheck = DB::table('appointment')->where('id',$appId)->first();
                        if($appointmentCheck){
                            $addData = array();
                            $addData['appointment_id'] = $appId;
                            $addData['user_id'] = $patientId;
                            $addData['package_id'] = $packageId;
                            $addData['amount'] = $amount;
                            $addData['transection_id'] = $transectionId;
                            $addData['transection_status'] = $transectionStatus;
                            $addData['date'] = $date;
                            $addData['amountType'] = 'package';
                            $addData['created_by'] = $patientId;
                            $branchId = userDetails($patientId)->branch;
                            if(!empty($branchId)){
                                $addData['branch_id'] = $branchId;
                            }
                            if(!empty($appId)){
                                $appDetails = appointmentDetails($appId);
                                $addData['joint'] = $appDetails->joints;
                            }
                            if(!empty($patientId)){
                                $userDetails = userDetails($patientId);
                                $patientName = $userDetails->name;
                                $regNo = $userDetails->registration_no;
                            }
                            DB::table('invoice')->insert($addData);

                            // Update package in appointment table
                            $updateApp = array();
                            $updateApp['package_type'] = $packageId;
                            DB::table('appointment')->where('id',$appId)->update($updateApp);

                            $response['message'] = 'Successfully add!';
                            $response['status'] = '1';
                        }else{
                            $response['message'] = 'Appointment not exist!';
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

    public function allPackageHistory(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $getData = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id')->where('appointment.user_id',$patientId)->orderBy('daily_entry.app_booked_date','DESC')->where('daily_entry.type',2)->groupBy('daily_entry.package_id')->select('daily_entry.id as visitId','appointment.id as appId','daily_entry.package_id','appointment.user_id','daily_entry.app_booked_date')->get();
                        foreach ($getData as $allData){
                            if(!empty($allData->user_id)){
                                $userDetails = userDetails($allData->user_id);
                                $allData->user_id  = $userDetails->name;
                                if(!empty($userDetails->profile_pic)){
                                    $profile_pics = API_PROFILE_PIC.$userDetails->profile_pic;
                                }else{
                                    $profile_pics = API_FOR_DEFAULT_IMG;
                                }
                                $allData->profile_pic = $profile_pics;
                            }
                            if(!empty($allData->package_id)){
                                $packageDetails = packageDetails($allData->package_id);
                                $allData->packageName = $packageDetails->name.'( '.$packageDetails->package_amount.' )';
                            }
                            $appDetails = appointmentDetails($allData->appId);
                            if(intval($appDetails->package_type) == intval($allData->package_id)){
                                $allData->status = 'Processing';
                            }else{
                                $allData->status = 'Complete';
                            }
                            $allData->joint = $appDetails->joints;
                            $allData->app_booked_date = date("d-M-Y", strtotime($allData->app_booked_date));
                            array_walk_recursive($allData, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }

                        $response['message'] = 'Successfully get data!';
                        $response['status'] = '1';
                        $response['getData'] = $getData;
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

    public function ipdGraph(Request $request){
        try{
            if(Input::has('patientId') && Input::has('flag')){
                $patientId = $request->patientId;
                $flag = $request->flag;
                if(!empty($patientId) && !empty($flag)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        if($flag == 'cpm'){
                            $getData = DB::table('ortho_case')->where('patient_id',$patientId)->select('cpm', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $aa = array();
                                foreach ($getData as $value) {
                                    $cpmdata = explode(', ', $value->cpm);
                                    if(!empty($cpmdata[0])){
                                        $value->value = $cpmdata[0];
                                    }else{
                                        $value->value = "0";
                                    }
                                }
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'orwalk'){
                            $getData = DB::table('ortho_case')->where('patient_id',$patientId)->select('walking_no_of_step', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $aa = array();
                                foreach ($getData as $value) {
                                    $cpmdata = explode(', ', $value->walking_no_of_step);
                                    if(!empty($cpmdata[0])){
                                        $value->value = $cpmdata[0];
                                    }else{
                                        $value->value = "0";
                                    }
                                }
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'orstair'){
                            $getData = DB::table('ortho_case')->where('patient_id',$patientId)->select('stairs_no_of_step', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $aa = array();
                                foreach ($getData as $value) {
                                    $cpmdata = explode(', ', $value->stairs_no_of_step);
                                    if(!empty($cpmdata[0])){
                                        $value->value = $cpmdata[0];
                                    }else{
                                        $value->value = "0";
                                    }
                                }
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'newalk'){
                            $getData = DB::table('neuro_case')->where('patient_id',$patientId)->select('walking', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $aa = array();
                                foreach ($getData as $value) {
                                    $cpmdata = explode(', ', $value->walking);
                                    if(!empty($cpmdata[0])){
                                        $value->value = $cpmdata[0];
                                    }else{
                                        $value->value = "0";
                                    }
                                }
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else if($flag == 'nestair'){
                            $getData = DB::table('neuro_case')->where('patient_id',$patientId)->select('stairs', DB::raw("DATE_FORMAT(created_at, '%d-%M-%Y') date"))->get();
                            if(count($getData) > 0){
                                $aa = array();
                                foreach ($getData as $value) {
                                    $cpmdata = explode(', ', $value->stairs);
                                    if(!empty($cpmdata[0])){
                                        $value->value = $cpmdata[0];
                                    }else{
                                        $value->value = "0";
                                    }
                                }
                                $response['message'] = 'Data get successfully!';
                                $response['status'] = '1';
                                $response['allData'] = $getData;
                            }else{
                                $response['message'] = 'Data does not exist!';
                                $response['status'] = '0';
                            }
                        }else{
                            $response['message'] = 'Invalid flag!';
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
    
    public function dailyExerciseActivity(Request $request){
        try{
            if(Input::has('activityId') && Input::has('status')){
                $activityId = $request->activityId;
                $status = $request->status;
                if(!empty($activityId) && !empty($status)){
                    $checkActivity = DB::table('exercise_calender')->where('id',$activityId)->first();
                    if($checkActivity){
                        $activityDetails = DB::table('exercise_calender')->where('id',$activityId)->first();

                        $addData = array();
                        $addData['status'] = $status;
                        $addData['activity_id'] = $activityId;
                        $addData['appointment_id'] = $activityDetails->appId;
                        $addData['patient_id'] = $activityDetails->patient_id;
                        $addData['therapist_id'] = $activityDetails->therapist_id;
                        $addData['exerciseId'] = $activityDetails->exerciseId;
                        $addData['date'] = date("Y-m-d");
                        $addData['time'] = date("H:i:s");
                        DB::table('daily_exercise_activity')->insert($addData);

                        $response['message'] = 'Successfully Save!';
                        $response['status'] = '1';
                    }else{
                        $response['message'] = 'Activity Id not exist!';
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
    
    public function allExerciseActivity(Request $request){
        try{
            if(Input::has('patientId')){
                $patientId = $request->patientId;
                // $exerciseId = $request->exerciseId;
                if(!empty($patientId)){
                    $checkPatient = User::where('id',$patientId)->where('user_type',3)->where('status','active')->first();
                    if($checkPatient){
                        $allData = DB::table('daily_exercise_activity')->where('patient_id',$patientId)->get();
                        if(count($allData) > 0){
                            foreach ($allData as $allD) {
                                if(!empty($allD->patient_id)){
                                    $patientDetails = userDetails($allD->patient_id);
                                    $patientName = $patientDetails->name;
                                    if(!empty($patientDetails->profile_pic)){
                                        $patientProfile = API_PROFILE_PIC.$patientDetails->profile_pic;
                                    }else{
                                        $patientProfile = API_FOR_DEFAULT_IMG;
                                    }
                                    $allD->patientName = $patientName;
                                    $allD->patientProfile = $patientProfile;
                                }
                                if(!empty($allD->exerciseId)){
                                    $exerDetails = exerciseDetials($allD->exerciseId);
                                    $allD->exerciseName = $exerDetails->name;
                                    $allD->description = $exerDetails->description;
                                }
                                array_walk_recursive($allD, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['getData'] = $allData;
                        }else{
                            $response['message'] = 'Data does not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Patient does not exist!';
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
}
