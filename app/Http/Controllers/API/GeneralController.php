<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App;
use DB;
use File;
use App\User;
use DateTime;

class GeneralController extends Controller
{
    public function allBranch(Request $request){
        try{
            // if(Input::has('id')){
                $therapistId = $request->id;
                if(empty($therapistId)){
                    $allBranch = DB::table('location')->select('id','name')->get();
                    if($allBranch){
                        $response['message'] = 'Branch get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allBranch;
                    }else{
                        $response['message'] = 'Branch not available!';
                        $response['status'] = '0';
                    }
                }else{
                    $userDetials = DB::table('users')->where('id',$therapistId)->first();
                    $allBranch = DB::table('location')->where('id',$userDetials->branch)->select('id','name')->get();
                    if($allBranch){
                        $response['message'] = 'Branch get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $allBranch;
                    }else{
                        $response['message'] = 'Branch not available!';
                        $response['status'] = '0';
                    }
                }
            // }else{
            //     $response['message'] = 'All fields are Mandatory!';
            //     $response['status'] = '0';
            // }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
    	return response()->json($response);
    }

    public function allService(Request $request){
        try{
        	$allService = DB::table('service')->select('id','name')->get();
        	if($allService){
        		$response['message'] = 'Services get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allService;
        	}else{
        		$response['message'] = 'Services not available!';
                $response['status'] = '0';
        	}
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
    	return response()->json($response);
    }

    public function ruleOfCapri(Request $request){
        try{
            $getData = DB::table('cms')->select('rule_regulation')->first();
            if($getData){
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['getData'] = strip_tags($getData->rule_regulation);
            }else{
                $response['message'] = 'Data not found!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);   
    }

    public function allReference(Request $request){
        try{
            $allReference = DB::table('reference')->select('id','name')->get();
            if($allReference){
                $response['message'] = 'Reference get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allReference;
            }else{
                $response['message'] = 'Reference not available!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allState(Request $request){
        try{
            $allState = DB::table('states')->select('id','name')->get();
            if($allState){
                $response['message'] = 'States get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allState;
            }else{
                $response['message'] = 'States not available!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allCities(Request $request){
        try{
            if(Input::has('stateId')){
                $stateId = $request->stateId;
                if(!empty($stateId)){
                    $getData = DB::table('cities')->where('state_id',$stateId)->select('id','name')->get();
                    if($getData){
                        $response['message'] = 'Cities get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $getData;
                    }else{
                        $response['message'] = 'Cities not available!';
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

    public function allTimeSlot(Request $request){
        try{
            $allData = DB::table('time_slot')->select('id','time')->orderBy('time','ASC')->get();
            if($allData){
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allData;
            }else{
                $response['message'] = 'Data not exist!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allBanners(Request $request){
        try{
            $allData = DB::table('banners')->select('banner_name as banner')->where('status','active')->get();
            if(count($allData) > 0){
                foreach ($allData as $allvalue) {
                    if(!empty($allvalue->banner)){
                        $allvalue->banner = API_BANNER_IMG.$allvalue->banner;
                    }else{
                        $allvalue->banner = '';
                    }
                }
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['allData'] = $allData;
            }else{
                $response['message'] = 'Data not exist!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function cms(Request $request){
        try{
            $getData = DB::table('cms')->select('privacy_policy','term_condition','contact_us','about_us')->first();
            if($getData){
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $getData->privacy_policy = strip_tags($getData->privacy_policy);
                $getData->privacy_policy=str_replace("\r\n"," ",$getData->privacy_policy);
                $getData->privacy_policy=str_replace("&nbsp;"," ",$getData->privacy_policy);
                $getData->privacy_policy=str_replace("&amp;","&",$getData->privacy_policy);
                $getData->privacy_policy=str_replace("&#39;","'",$getData->privacy_policy);
                
                $getData->term_condition = strip_tags($getData->term_condition);
                $getData->term_condition=str_replace("\r\n"," ",$getData->term_condition);
                $getData->term_condition=str_replace("&nbsp;"," ",$getData->term_condition);
                $getData->term_condition=str_replace("&amp;","&",$getData->term_condition);
                $getData->term_condition=str_replace("&#39;","'",$getData->term_condition);


                $getData->contact_us = strip_tags($getData->contact_us);
                $getData->contact_us=str_replace("\r\n"," ",$getData->contact_us);
                $getData->contact_us=str_replace("&nbsp;"," ",$getData->contact_us);
                $getData->contact_us=str_replace("&amp;","&",$getData->contact_us);
                $getData->contact_us=str_replace("&#39;","'",$getData->contact_us);

                
                $getData->about_us = strip_tags($getData->about_us);
                $getData->about_us=str_replace("\r\n"," ",$getData->about_us);
                $getData->about_us=str_replace("&nbsp;"," ",$getData->about_us);
                $getData->about_us=str_replace("&amp;","&",$getData->about_us);
                $getData->about_us=str_replace("&#39;","'",$getData->about_us);
                
                
                $response['getData'] = $getData;
            }else{
                $response['message'] = 'Data not found!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allExercise(Request $request){
        try{
            $getData = DB::table('exercise')->where('status','active')->orderBy('id','DESC')->get();
            if(count($getData) > 0){
                foreach ($getData as $allVal) {
                    // if(!empty($allVal->description)){
                    //     $allVal->description = strip_tags($allVal->description);
                    // }
                    if(!empty($allVal->created_at)){
                        $allVal->created_at = date("d-M-Y", strtotime($allVal->created_at));
                    }
                    array_walk_recursive($allVal, function (&$item, $key) {
                        $item = null === $item ? '' : $item;
                    });
                }
                $response['message'] = 'Data get successfully!';
                $response['status'] = '1';
                $response['allData'] = $getData;
            }else{
                $response['message'] = 'Data not exist!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function exerciseVideo(Request $request){
        try{
            if(Input::has('exerciseId')){
                $exerciseId = $request->exerciseId;
                if(!empty($exerciseId)){
                    $checkExercise = DB::table('exercise')->where('id',$exerciseId)->where('status','active')->first();
                    if($checkExercise){
                        $checkExercise->description = strip_tags($checkExercise->description);
                        $allVideos = DB::table('exercise_videos')->where('exerciseId',$exerciseId)->where('status','active')->orderBy('id','DESC')->get();
                        if(count($allVideos)>0){
                            foreach ($allVideos as $vid){
                                if(!empty($vid->video)){
                                    $vid->video = API_EXERCISE_VIDEOS.$vid->video;
                                }else{
                                    $vid->video = '';
                                }
                                $vid->created_at = date("d-M-Y", strtotime($vid->created_at));
                                array_walk_recursive($vid, function (&$item, $key){
                                    $item = null === $item ? '' : $item;
                                });
                            }
                            $response['message'] = 'Data get successfully!';
                            $response['status'] = '1';
                            $response['exerciseData'] = $checkExercise;
                            $response['allData'] = $allVideos;
                        }else{
                            $response['message'] = 'Data not exist!';
                            $response['status'] = '0';
                        }
                    }else{
                        $response['message'] = 'Exercise not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Field can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All fields are mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function allPackage(Request $request){
        try{
            if(Input::has('joint')){
                $joint = $request->joint;
                if(!empty($joint)){
                    $getData = DB::table('package')->where('joints',$joint)->orderBy('name','ASC')->select('id','name','package_amount','validity','days','joints','commission')->get();
                    if($getData){
                        foreach ($getData as $allVal) {
                            if(!empty($allVal->name)){
                                $allVal->name = $allVal->name.'( '.$allVal->package_amount.' Rs. )';
                            }
                            array_walk_recursive($allVal, function (&$item, $key) {
                                $item = null === $item ? '' : $item;
                            });
                        }
                        $response['message'] = 'Data get successfully!';
                        $response['status'] = '1';
                        $response['allData'] = $getData;
                    }else{
                        $response['message'] = 'Data not exist!';
                        $response['status'] = '0';
                    }
                }else{
                    $response['message'] = 'Field can not be empty!';
                    $response['status'] = '0';
                }
            }else{
                $response['message'] = 'All fields are mandatory!';
                $response['status'] = '0';
            }
        }catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
        return response()->json($response);
    }

    public function testingCronHit(Request $request){
      $currentDate = date('Y-m-d');
      $outTimes = date('H:i:s');
      $status = 'complete';
      $rating = 1;
      $getTodaysVisit = DB::table('daily_entry')->where('app_booked_date',$currentDate)->where('status','pending')->get();
      if(count($getTodaysVisit) > 0){
        foreach($getTodaysVisit as $allVisit){
            $visitId = $allVisit->id;
            $visitType = $allVisit->type;
            $amount = $allVisit->amount;
            if($visitType == 1){
              // for per day visit
              $data = array();
              $data['rating'] = $rating;
              $data['status'] = $status;
              $data['out_time'] = $outTimes;
              DB::table('daily_entry')->where('id',$visitId)->where('app_booked_date',$currentDate)->update($data);
            }else{
                $visitDetails = DB::table('daily_entry')->where('id',$allVisit->id)->first();
                $inTime = $visitDetails->in_time;
                $outTime = $visitDetails->out_time;
                $appId = $allVisit->appointment_id;
                $appDetails = DB::table('appointment')->where('id',$appId)->first();
                $serviceType = $appDetails->app_service_type;
                // for package wise visit
                $checkVisitCount = DB::table('daily_entry')->where('appointment_id',$appId)->where('app_booked_date',$currentDate)->where('status','complete')->count('id');
                if(!empty($inTime) && !empty($outTime) && ($serviceType != '7') && ($serviceType != '9')){
                    $jointName = $appDetails->joints;
                    if($checkVisitCount > 1){
                        if($jointName == 'one_joint'){
                            $ntTime = strtotime("+70 minutes", strtotime($inTime));     //10 minutes extra of 60 min
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
                            $ntTime = strtotime("+100 minutes", strtotime($inTime));     //10 minutes extra of 60 min
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
                    DB::table('appointment')->where('id',$appId)->update($updateAppData);
                }
                // daily entry update
                $data = array();
                $data['rating'] = $rating;
                $data['status'] = 'complete';
                $data['out_time'] = $outTime;
                $data['penalty'] = $penalty;
                DB::table('daily_entry')->where('id',$visitId)->where('app_booked_date',$currentDate)->update($data);
            }
        }
      }
    }

    public function birthdayWishes(Request $request){
        $todayDate = date('Y-m-d');
        $userData = DB::table('users')->where('status','active')->select('id','name','dob','mobile','token_id')->get();
        if(count($userData) > 0){
            foreach($userData as $users) {
                $dob = $users->dob;
                $token = $users->token_id;
                if(!empty($dob) && !empty($token)){
                    if(date("m-d", strtotime($dob)) == date("m-d", strtotime($todayDate))){
                        $title = "Dear ".$users->name.", Wish you a very Happy Birthday";
                        // send birthday notification
                        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                        $notification = [
                            'title' => $title,
                            'sound' => true,
                        ];
                        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
                        $fcmNotification = [
                            //'registration_ids' => $tokenList, //multple token array
                            'to'        => $token, //single token
                            'notification' => $notification,
                            'data' => $extraNotificationData
                        ];
                        $headers = [
                            'Authorization: key=AIzaSyCmo-dbPyBmkqEVotMmcRsvuRcQWo3iXXY',
                            'Content-Type: application/json'
                        ];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                        $result = curl_exec($ch);
                        curl_close($ch);

                        // send birthday message alert
                         $message = "Dear ".$users->name.", Capri Spine Clinic wishes you a very happy birthday! May God bless you with all the happiness, good health and a healthy, mobile spine. Regards, Team Capri";
                         $numbers = $users->mobile;
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
                                // return $response;
                    }
                }
            }
        }
    }

    public function visitReminder(Request $request){
        $currentDate = date('Y-m-d');
        $outTimes = date('H:i:s');
        $visitDetails = DB::table('daily_entry')->where('app_booked_date',$currentDate)->where('status','!=','approval_pending')->where('status','!=','pending')->where('status','!=','complete')->get();
        if(count($visitDetails) > 0){
            foreach($visitDetails as $visitVal) {
                if(($visitVal->status == '') || ($visitVal->status == null)){
                    $appDate = $visitVal->app_booked_date;
                    $appTime = $visitVal->app_booked_time;
                    $appDetails = DB::table('appointment')->where('id',$visitVal->appointment_id)->first();
                    $userDetials = DB::table('users')->where('id',$appDetails->user_id)->first();
                    $patientName = $userDetials->name;
                    $mobile = $userDetials->mobile;
                    $token = $userDetials->token_id;
                    $hourbeforeTime = date('H:i:s',strtotime('-1 hour',strtotime($appTime)));
                    if($hourbeforeTime == $outTimes){
                        $title = "Dear ".$patientName.", Gentle reminder: Your physio session is scheduled at Today.";
                        if(!empty($token) && !empty($title)){
                            // send notification
                            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                            $token=$token;

                            $notification = [
                                'title' => $title,
                                'sound' => true,
                            ];
                            
                            $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

                            $fcmNotification = [
                                //'registration_ids' => $tokenList, //multple token array
                                'to'        => $token, //single token
                                'notification' => $notification,
                                'data' => $extraNotificationData
                            ];

                            $headers = [
                                'Authorization: key=AIzaSyCmo-dbPyBmkqEVotMmcRsvuRcQWo3iXXY',
                                'Content-Type: application/json'
                            ];


                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                            $result = curl_exec($ch);
                            curl_close($ch);

                            return true;
                        }
                        if(!empty($mobile) && !empty($patientName)){
                            // send SMS Message 
                            $message = "Dear ".$patientName.", Gentle reminder: Your physio session dated ".$appDate.", time ".$appTime."is scheduled at Capri Spine. Regards, Team Capri.";
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
                            // return $response;
                        }
                        // return true;
                    }
                }
            }
        }
    }
}
