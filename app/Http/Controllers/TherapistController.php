<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use file;
use Redirect;
use App\User;
use App\Helper\FileUpload;
use DateTime;

class TherapistController extends Controller
{
	use FileUpload;
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function allTherapist(){
        try{
        	$data = array();
        	$data['title'] = 'All Therapist';
            if(Auth::User()->user_type == 'superadmin'){
                $allData = User::where('user_type','5')->get();
            }else{
                $allData = User::where('user_type','5')->where('branch',Auth::User()->branch)->where('id','!=',Auth::User()->id)->get();
            }
        	$data['allData'] = $allData;
            $allPenalty = DB::table('penalty')->get();
            $data['penalty'] = $allPenalty;
            $data['masterclass'] = 'therapist';
            $data['class'] = 'allthe';
        	return view('therapist.list',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function addTherapist(){
        try{
        	$data = array();
        	$data['title'] = 'Add Therapist';
        	$state = DB::table('states')->get();
            $data['states'] = $state;
            if(Auth::user()->user_type == 'superadmin'){
                $branch = DB::table('location')->get();
            }else{
                $branch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
            }
            $data['branch'] = $branch;
            $service = DB::table('service')->get();
            $data['service'] = $service;
            $data['masterclass'] = 'therapist';
            $data['class'] = 'addthe';
        	return view('therapist.add',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveTherapist(Request $request){
        try{
            $duplicateData = User::where('mobile',$request->mobile)->first();
            if($duplicateData){
                echo "<script>alert('Mobile no and Email already exist');</script>";
            }else{
                $data = new User();
                $branch = $request->branch;
                $data['name'] = $request->uname;
                $data['email'] = $request->email;
                $data['mobile'] = $request->mobile;
                $data['gender'] = $request->gender;
                $data['status'] = $request->status;
                $data['state'] = $request->state;
                $data['city'] = $request->city;
                $data['timing'] = $request->toTime.' to '.$request->fromTime;
                $data['base_commision'] = $request->base_commision;
                $data['adhar_card'] = $request->doc1;
                $data['degree'] = $request->doc2;
                $data['dob'] = $request->dob;
                $data['user_type'] = 5;
                $data['service_type'] = $request->service;
                $data['area_of_home_visit'] = $request->areaHomeVisit;
                $data['branch'] = $branch;
                $username = quickRandom(4);
                $data['username'] = 'user_'.$username;
                $data['password'] = bcrypt($request->password);
                $data['confirmpassword'] = $request->password;
                if($request->hasfile('profile_pic')){
                    $folder = "upload/profile_pic/";
                    $file = $this->upload_file($request->profile_pic, $folder);
                    if($file)
                    {
                        $data['profile_pic'] = $file;
                    }else{
                        $data['profile_pic'] = '';
                    }
                }
                if($request->hasfile('doc1')){
                    $folder = "upload/therapist_doc/";
                    $file = $this->upload_file($request->doc1, $folder);
                    if($file)
                    {
                        $data['adhar_card'] = $file;
                    }else{
                        $data['adhar_card'] = '';
                    }
                }
                if($request->hasfile('doc2')){
                    $folder = "upload/therapist_doc/";
                    $file = $this->upload_file($request->doc2, $folder);
                    if($file)
                    {
                        $data['degree'] = $file;
                    }else{
                        $data['degree'] = '';
                    }
                }
                $data->save();

                $userId = $data->id;
                $regData = array();
                $regNo = 100000 + $userId;
                $branch_key = branchDetails($branch)->b_key;
                $regData = array();
                $regData['registration_no'] = $regNo.'_'.$branch_key.'_TH';
                User::where('id',$userId)->update($regData);
            }
        	return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function viewTherapist($id){
        try{
        	$data = array();
        	$data['title'] = 'Therapist Details';
        	$allData = User::where('id',$id)->where('user_type',5)->first();
        	$data['allData'] = $allData;
            $data['masterclass'] = 'therapist';
            $data['class'] = 'allthe';
        	return view('therapist.view',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editTherapist(Request $request, $id){
        try{
        	$data = array();
        	$data['title'] = 'Edit Therapist';
        	$state = DB::table('states')->get();
            $data['states'] = $state;
            $city = DB::table('cities')->get();
            $data['cities'] = $city;
            $allData = User::where('id',$id)->where('user_type',5)->first();
            $data['allData'] = $allData;
            if(Auth::user()->user_type == 'superadmin'){
                $branch = DB::table('location')->get();
            }else{
                $branch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
            }
            $data['branch'] = $branch;
            $allUserType = DB::table('user_type')->get();
            $data['allUserType'] = $allUserType;
            $allService = DB::table('service')->get();
            $data['allService'] = $allService;
            $data['masterclass'] = 'therapist';
            $data['class'] = 'allthe';
        	return view('therapist.edit',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateTherapist($id, Request $request){
        try{
            $data = array();
            $data['name'] = $request->uname;
            $data['email'] = $request->email;
            $data['mobile'] = $request->mobile;
            $data['gender'] = $request->gender;
            $data['state'] = $request->state;
            $data['city'] = $request->city;
            $data['status'] = $request->status;
            $data['timing'] = $request->timing;
            $data['base_commision'] = $request->base_commision;
            $data['dob'] = $request->dob;
            $data['branch'] = $request->branch;
            $data['user_type'] = $request->userType;
            $data['service_type'] = $request->serviceType;
            if($request->hasfile('profile_pic')){
                $folder = "upload/profile_pic/";
                $file = $this->upload_file($request->profile_pic, $folder);
                if($file)
                {
                    $data['profile_pic'] = $file;
                }
            }else{
                $data['profile_pic'] = $request->old_Profile_pic;
            }

            if($request->hasfile('doc1')){
                $folder = "upload/therapist_doc/";
                $file = $this->upload_file($request->doc1, $folder);
                if($file)
                {
                    $data['adhar_card'] = $file;
                }
            }else{
                $data['adhar_card'] = $request->old_adhar_card;
            }

            if($request->hasfile('doc2')){
                $folder = "upload/therapist_doc/";
                $file = $this->upload_file($request->doc2, $folder);
                if($file)
                {
                    $data['degree'] = $file;
                }
            }else{
                $data['degree'] = $request->old_degree;
            }

            User::where('id',$id)->where('user_type',5)->update($data);
            return Redirect::to('all-therapist');
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function penaltyAmount($penaltyId){
        $getData = DB::table('penalty')->select('amount')->where('id',$penaltyId)->first();
        return json_encode($getData);
    }

    public function therapistPenalty(Request $request){
        try{
            $data = array();
            $data['therapist_id'] = $request->therapistId;
            $data['penalty_id'] = $request->penaltyId;
            $data['late_time'] = $request->late_minutes;
            $data['date'] = date('Y-m-d');
            $data['time'] = date('H:i:s');
            $data['amount'] = $request->amount;
            DB::table('daily_penalty')->insert($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allPenaltyHistory(Request $request, $id){
        try{
            $data = array();
            $data['title'] = 'All Penalty History';
            $data['masterclass'] = 'therapist';
            $data['class'] = 'allthe';
            $data['id'] = $id;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            if(!empty($from_date) || !empty($to_date)){
                $allPenalty = DB::table('daily_penalty')->where('therapist_id',$id);
                $query = DB::table('daily_penalty')->where('therapist_id',$id);
                if (!empty($from_date)) {
                    $query = $query->where('date', '>=', $from_date);
                    $allPenalty = $allPenalty->where('date', '>=', $from_date);
                }
                if (!empty($to_date)) {
                    $query = $query->where('date', '<=', $to_date);
                    $allPenalty = $allPenalty->where('date', '<=', $to_date);
                }
                // $allPenalty = $query->sum('amount');
                $allData = $query->get();
                $allPenalty = $allPenalty->sum('amount');
                
            }else{
                $allData = DB::table('daily_penalty')->where('therapist_id',$id)->get();
                $allPenalty = DB::table('daily_penalty')->where('therapist_id',$id)->sum('amount');
            }
            $data['allData'] = $allData;
            $data['allPenalty'] = $allPenalty;
            $data['therapistId'] = $id;
            $data['getData'] = '';
            return view('therapist.allPenalty',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function markAttandance($id){
        try{
            $userId = $id;
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i');
            $checkData = User::where('id',$userId)->where('status','active')->first();
                $lastPresentDate = DB::table('attendance')->where('therapist_id',$userId)->where('status','present')->orderBy('date','DESC')->first();
                $appServiceId = $checkData->service_type;
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
                            $attandanceData['created_by'] = Auth::User()->id;
                            DB::table('attendance')->insert($attandanceData);
                        }
                    }
                
                    // Late comming penalty
                    $workTime = $checkData->timing;
                    $wwTime = explode(' to ', $workTime);
                    $fromWork = $wwTime[0];
                    $toWork = $wwTime[1];
                    $workingInTime = date("H:i", strtotime($fromWork));
                    $workingOutTime = date("H:i", strtotime($toWork));
                    $currentMonth = date('m');
                    if(!empty($fromWork) && !empty($toWork) && (strtotime($workingInTime) < time())){
                    // if(!empty($fromWork) && !empty($toWork) && (strtotime($currentTime) > strtotime($workingInTime))){
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
                    $newattandanceData['created_by'] = Auth::User()->id;
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
                    $workingInTime = date("H:i:s", strtotime($fromWork));
                    $workingOutTime = date("H:i:s", strtotime($toWork));
                    $currentMonth = date('m');
                    if(!empty($fromWork) && !empty($toWork) && (strtotime($currentTime) > strtotime($workingInTime))){
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
                    $newattandanceData['created_by'] = Auth::User()->id;
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
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function checkTherapistAttendance($therapistId){
        $date = date('Y-m-d');
        $getData = DB::table('attendance')->where('therapist_id',$therapistId)->where('status','present')->where('date',$date)->first();
        if($getData){
            $result = 'true';
        }else{
            $result = 'false';
        }
        return json_encode($result);
    }
}