<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use file;
use App\User;
use App\Helper\SendSMS;
use Redirect;

class WelcomeController extends Controller
{
    use SendSMS;

    public function sendSMSs($numbers,$otp){
        $username = 'Manojg';
        $password = '49668';

        $sender = 'ROCARE';
        $message = 'Capri Verification Code : '.$otp;
        $message = urlencode($message);
        $url = "http://sms.truevaluemobi.com/api/pushsms.php?username=".urlencode($username)."&password=".urlencode($password)."&sender=ROCARE&message=". $message."&numbers=".$numbers;

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_PORT, 80);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        // Allowing cUrl funtions 20 second to execute
        curl_setopt ($ch, CURLOPT_TIMEOUT, 20);
        // Waiting 20 seconds while trying to connect
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
        $response = curl_exec($ch);
        return $response;
   }

    public function signUpPatient(){
    	$data = array();
    	$data['title'] = 'Add Patient';
        $locationData = DB::table('location')->get();
        $data['locationData'] = $locationData;
        $serviceData = DB::table('service')->get();
        $data['serviceData'] = $serviceData;
        $referenceData = DB::table('reference')->get();
        $data['referenceData'] = $referenceData;
    	return view('patient.add',$data);
    }

    public function savePatient(Request $request){
        $number = $request->phone;
        $duplicateData = User::where('mobile',$number)->orWhere('email',$request->email)->first();
        if($duplicateData){
            echo "<script>alert('Mobile no and Email already exist');</script>";
            return redirect()->back();
        }else{
        	$data = new User();
        	$data->name = $request->firstName.' '.$request->lastName;
        	$data->gender = $request->gender;
        	$data->email = $request->email;
        	$data->mobile = $number;
        	$data->confirmpassword = $request->password;
        	$data->password = bcrypt($request->password);
        	$data->problem = $request->problem;
        	$data->branch = $request->branch_location;
        	$data->service = $request->service;
            $data->user_type = 3;
        	$data->reference = $request->reference;
        	$data->status = 'inactive';
        	$otp = genRand();
        	$data->otp = $otp;
            $usename = quickRandom(4);
            $data->username = 'user_'.$usename;

        	$result = $data->save();
            if($result){
                $message = 'Capri Verification Code : '.$otp;
                $response = $this->sendSMSMessage($message,$number);
                $id = $data->id;
                return redirect::to('patient-otp-verification/'.$id);
            }else{
                return redirect()->back();
            }
        }
    }

    public function getOtp($id){
        $patientId = $id;
        $data = array();
        $data['patientId'] = $patientId;
        $data['title'] = 'Otp Verification';
        return view('patient.otp_verification',$data);
    }

    public function patientOtpVerification(Request $request){
        $patientId = $request->patientId;
        $originalOtp = userDetails($patientId)->otp;
        if($originalOtp == $request->otp){
            $updateData = array();
            $updateData['status'] = 'active';
            User::where('id',$patientId)->update($updateData);
            return redirect::to('/');
        }else{
            echo "<script>alert('Please type correct OTP!!');</script>";
            return redirect()->back();
        }
    }

    public function resendOTP($id){
        $mobileNo = userDetails($id)->mobile;
        $otp = userDetails($id)->otp;
        if($mobileNo){
            $message = 'Capri Verification Code : '.$otp;
            $response = $this->sendSMSMessage($message,$mobileNo);
            return redirect::to('patient-otp-verification/'.$id);
        }
    }
}
