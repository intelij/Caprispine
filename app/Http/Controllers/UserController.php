<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use DB;
use Redirect;
use Auth;
use DateTime;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function allUsers(){
        try{
        	$data = array();
        	$data['title'] = 'All Staff';
            if(Auth::User()->user_type == 'superadmin'){
                $allData = User::where('user_type','!=','superadmin')->where('user_type','!=',3)->where('user_type','!=',5)->orderBy('created_at','DESC')->get();
            }else{
                $allData = User::where('user_type','!=','superadmin')->where('branch',Auth::User()->branch)->where('user_type','!=',3)->where('user_type','!=',5)->orderBy('created_at','DESC')->get();
            }
        	$data['allData'] = $allData;
            $data['masterclass'] = 'users';
            $data['class'] = 'user';
        	return view('users.list',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function addUser(){
        try{
            $data = array();
            $data['title'] = 'Add Staff';
            $allUserType = DB::table('user_type')->where('id','!=',3)->where('id','!=',4)->get();
            $data['allUserType'] = $allUserType;
            $state = DB::table('states')->get();
            $data['states'] = $state;
            if(Auth::user()->user_type == 'superadmin'){
                $branch = DB::table('location')->get();
            }else{
                $branch = DB::table('location')->where('location.id',Auth::user()->branch)->get();
            }
            $data['branch'] = $branch;
            $data['masterclass'] = 'users';
            $data['class'] = 'adduser';
            return view('users.add',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveUser(Request $request){
        try{
            $duplicateData = User::where('mobile',$request->mobile)->first();
            if($duplicateData){
                echo "<script>alert('Mobile no and Email already exist');</script>";
                return redirect()->back();
            }else{
                $branch = $request->branch;
                $userType = $request->userType;
                $userdata = new User();
                $userdata['name'] = $request->uname;
                $userdata['email'] = $request->uemail;
                $userdata['user_type'] = $userType;
                $userdata['mobile'] = $request->mobile;
                $userdata['gender'] = $request->gender;
                $userdata['status'] = $request->status;
                $userdata['state'] = $request->state;
                $userdata['city'] = $request->city;
                $userdata['address'] = $request->address;
                $userdata['timing'] = $request->toTime.' to '.$request->fromTime;
                $userdata['branch'] = $branch;
                $username = quickRandom(4);
                $userdata['username'] = 'user_'.$username;
                $userdata['password'] = bcrypt($request->password);
                $userdata['confirmpassword'] = $request->password;
                $userdata->save();
                
                $userId = $userdata->id;
                $regData = array();
                $regNo = 100000 + $userId;
                $branch_key = branchDetails($branch)->b_key;
                $regData = array();
                $userTypeDetails = DB::table('user_type')->where('id',$userType)->first();
                $userTypeName = $userTypeDetails->name;
                $userTypeChar = mb_substr($userTypeName, 0, 2);
                $userTypeChar = strtoupper($userTypeChar);
                $regData['registration_no'] = $regNo.'_'.$branch_key.'_'.$userTypeChar;
                
                $saveData = User::where('id',$userId)->update($regData);
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function viewUser($id){
        try{
        	$data = array();
        	$data['title'] = 'Staff Details';
        	$allData = User::where('id',$id)->first();
        	$data['allData'] = $allData;
            $data['masterclass'] = 'users';
            $data['class'] = 'user';
        	return view('users.view',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editUser($id){
        try{
        	$data = array();
        	$data['title'] = 'Edit Staff Details';
        	$getData = User::where('id',$id)->first();
        	$data['allData'] = $getData;
        	$allUserType = DB::table('user_type')->where('id','!=',3)->where('id','!=',4)->get();
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
            $data['masterclass'] = 'users';
            $data['class'] = 'user';
        	return view('users.edit',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateUser(Request $request, $id){
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
            $userData['timing'] = $request->timing;
        	User::where('id',$id)->update($userData);
        	return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    // public function markAttandance($id){
    //     try{
    //         $userId = $id;
    //         $currentDate = date('Y-m-d');
    //         $currentTime = date('H:i:s');
    //         $lastPresentDate = DB::table('attendance')->where('therapist_id',$userId)->where('status','present')->orderBy('date','DESC')->first();
    //         if($lastPresentDate){
    //             $today = $lastPresentDate->date;
    //             $currPrevDate = date('Y-m-d', strtotime($currentDate. ' - 1 days'));
    //             $diff = abs(strtotime($currPrevDate) - strtotime($today));
    //             $years = floor($diff / (365*60*60*24));
    //             $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    //             $day = floor(($diff - $years*365*60*60*24 - $months*30*60*60*24) / (60*60*24));

    //             if($day > 0){
    //                 for($i=1; $i<=$day; $i++)
    //                 {
    //                     $repeat = strtotime("+1 day",strtotime($today));
    //                     $today = date('Y-m-d',$repeat);

    //                     $attandanceData = array();
    //                     $attandanceData['flag'] = 'not_ipd';
    //                     $attandanceData['therapist_id'] = $userId;
    //                     $attandanceData['date'] = $today;
    //                     $attandanceData['attendance_time'] = $currentTime;
    //                     $attandanceData['status'] = 'apsent';
    //                     $attandanceData['created_by'] = Auth::user()->id;
    //                     DB::table('attendance')->insert($attandanceData);
    //                 }
    //             }
    //             $newattandanceData = array();
    //             $checkIPD = DB::table('ipd_calendar')->where('date',$currentDate)->first();
    //             if($checkIPD){
    //                 $newattandanceData['flag'] = 'ipd';
    //             }else{
    //                 $newattandanceData['flag'] = 'not_ipd';
    //             }
    //             $newattandanceData['therapist_id'] = $userId;
    //             $newattandanceData['date'] = $currentDate;
    //             $newattandanceData['attendance_time'] = $currentTime;
    //             $newattandanceData['status'] = 'present';
    //             $newattandanceData['created_by'] = Auth::user()->id;
    //             DB::table('attendance')->insert($newattandanceData);
    //         }else{
    //             $newattandanceData = array();
    //             $checkIPD = DB::table('ipd_calendar')->where('date',$currentDate)->first();
    //             if($checkIPD){
    //                 $newattandanceData['flag'] = 'ipd';
    //             }else{
    //                 $newattandanceData['flag'] = 'not_ipd';
    //             }
    //             $newattandanceData['therapist_id'] = $userId;
    //             $newattandanceData['date'] = $currentDate;
    //             $newattandanceData['attendance_time'] = $currentTime;
    //             $newattandanceData['status'] = 'present';
    //             $newattandanceData['created_by'] = Auth::user()->id;
    //             DB::table('attendance')->insert($newattandanceData);
    //         }
    //         return redirect()->back();
    //     }catch(\Exception $e){
    //         return view('common.503');
    //     }
    // }

    public function markAttandance($id){
        try{
            $userId = $id;
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            $checkData = User::where('id',$userId)->where('status','active')->first();
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
                        $attandanceData['status'] = 'apsent';
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
                    $newattandanceData['flag'] = 'ipd';
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
                    $newattandanceData['flag'] = 'ipd';
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
}
