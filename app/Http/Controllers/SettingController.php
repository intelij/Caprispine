<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Notification;
use DB;
use file;
use Auth;
use Redirect;
use DateTime;
use App\Helper\FileUpload;
use App\Helper\SendNotification;
use App\Helper\SendSMS;

class SettingController extends Controller
{
    use FileUpload;
    use SendNotification;
    use SendSMS;
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('admin');
        // $this->middleware('reception');
        // $this->middleware('hod');
        // $this->middleware('manager');
        // $this->middleware('patient');
        // $this->middleware('suppstaff');
        // $this->middleware('therapist');
    }
    
    public function location(){
    	$data = array();
    	$data['title'] = 'Location';
    	$allData = DB::table('location')->orderBy('created_at','ASC')->get();
    	$data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'location';
    	return view('setting.location',$data)->with('no',1);
    }

    public function addLocation(Request $request){
    	$data = array();
    	$data['name'] = $request->locationName;
        $data['b_key'] = $request->b_key;
    	DB::table('location')->insert($data);
    	return redirect()->back();
    }

    public function editLocation($id){
    	$data = array();
    	$data['title'] = 'Location';
    	$allData = DB::table('location')->orderBy('created_at','ASC')->get();
    	$data['allData'] = $allData;
    	$getData = DB::table('location')->where('id',$id)->first();
    	$data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'location';
    	return view('setting.location',$data)->with('no',1);
    }

    public function updateLocation(Request $request,$id){
        $data = array();
        $data['name'] = $request->locationName;
        $data['b_key'] = $request->b_key;
        DB::table('location')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteLocation($id){
    	$deletedata = DB::table('location')->where('id',$id)->delete();
    	return redirect()->back();
    }

    public function service(){
        $data = array();
        $data['title'] = 'Service';
        $allData = DB::table('service')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'service';
        return view('setting.service',$data)->with('no',1);
    }

    public function saveService(Request $request){
        $data = array();
        $data['name'] = $request->serviceName;
        DB::table('service')->insert($data);
        return redirect()->back();
    }

    public function editService($id){
        $data = array();
        $data['title'] = 'Service';
        $allData = DB::table('service')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('service')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'service';
        return view('setting.service',$data)->with('no',1);
    }

    public function updateService(Request $request,$id){
        $data = array();
        $data['name'] = $request->serviceName;
        DB::table('service')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteService($id){
        $deletedata = DB::table('service')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function referenceType(){
        $data = array();
        $data['title'] = 'Reference Type';
        $allData = DB::table('reference')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'reference';
        return view('setting.referenceType',$data)->with('no',1);
    }

    public function addReference(Request $request){
        $data = array();
        $data['name'] = $request->referenceName;
        DB::table('reference')->insert($data);
        return redirect()->back();
    }

    public function editReference($id){
        $data = array();
        $data['title'] = 'Reference';
        $allData = DB::table('reference')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('reference')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'reference';
        return view('setting.referenceType',$data)->with('no',1);
    }

    public function updateReference(Request $request,$id){
        $data = array();
        $data['name'] = $request->referenceName;
        DB::table('reference')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteReference($id){
        $deletedata = DB::table('reference')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function userType(){
        $data = array();
        $data['title'] = 'User Type';
        $allData = DB::table('user_type')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'usertype';
        return view('setting.usertype',$data)->with('no',1);
    }

    public function addUserType(Request $request){
        $data = array();
        $data['name'] = $request->usertypeName;
        DB::table('user_type')->insert($data);
        return redirect()->back();
    }

    public function editUserType($id){
        $data = array();
        $data['title'] = 'Location';
        $allData = DB::table('user_type')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('user_type')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'usertype';
        return view('setting.usertype',$data)->with('no',1);
    }

    public function updateUserType(Request $request,$id){
        $data = array();
        $data['name'] = $request->usertypeName;
        DB::table('user_type')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteUserType($id){
        $deletedata = DB::table('user_type')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function modules(){
        $data = array();
        $data['title'] = 'Module';
        $allData = DB::table('modules')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'module';
        return view('setting.module',$data)->with('no',1);
    }

    public function addModule(Request $request){
        $data = array();
        $data['name'] = $request->moduleName;
        DB::table('modules')->insert($data);
        return redirect()->back();
    }

    public function editModule($id){
        $data = array();
        $data['title'] = 'Module';
        $allData = DB::table('modules')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('modules')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'module';
        return view('setting.module',$data)->with('no',1);
    }

    public function updateModule(Request $request,$id){
        $data = array();
        $data['name'] = $request->moduleName;
        DB::table('modules')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteModule($id){
        $deletedata = DB::table('modules')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function appointmentTimeSlot(){
        $data = array();
        $data['title'] = 'Appointment Time Slot';
        $allData = DB::table('appointment_time_slot')->get();
        $data['allData'] = $allData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'apptime';
        return view('setting.appointmentTimeSlot',$data)->with('no',1);
    }

    public function addAppointmentTimeSlot(Request $request){
        $data = array();
        $data['appointment_time'] = $request->appointmentTime;
        DB::table('appointment_time_slot')->insert($data);
        return redirect()->back();
    }

    public function editAppointmentTimeSlot($id){
        $data = array();
        $data['title'] = 'Module';
        $allData = DB::table('appointment_time_slot')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('appointment_time_slot')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'apptime';
        return view('setting.appointmentTimeSlot',$data)->with('no',1);
    }

    public function updateAppointmentTimeSlot(Request $request,$id){
        $data = array();
        $data['appointment_time'] = $request->appointmentTime;
        DB::table('appointment_time_slot')->where('id',$id)->update($data);
        return view('setting.appointmentTimeSlot',$data);
    }

    public function deleteAppointmentTimeSlot($id){
        $deletedata = DB::table('appointment_time_slot')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function cmsManagement(){
        $data = array();
        $data['title'] = 'CMS Management';
        $getData = DB::table('cms')->where('status','active')->first();
        $data['getData'] = $getData;
        $data['masterclass'] = '';
        $data['class'] = 'cms';
        return view('cms.view',$data);
    }

    public function addCMS(Request $request){
        $data = array();
        $data['privacy_policy'] = $request->privacy_policy;
        $data['term_condition'] = $request->term_condition;
        $data['rule_regulation'] = $request->rule_regulation;
        $data['contact_us'] = $request->contact_us;
        $data['about_us'] = $request->about_us;
        $data['status'] = 'active';
        DB::table('cms')->insert($data);
        return redirect()->back();
    }

    public function updateCMS(Request $request){
        $data = array();
        $data['privacy_policy'] = $request->privacy_policy;
        $data['term_condition'] = $request->term_condition;
        $data['rule_regulation'] = $request->rule_regulation;
        $data['contact_us'] = $request->contact_us;
        $data['about_us'] = $request->about_us;
        DB::table('cms')->update($data);
        return redirect()->back();
    }

    public function contactUs(){
        $data = array();
        $data['title'] = 'Contact Us';
        $data['class'] = 'contactus';
        $data['masterclass'] = '';
        return view('setting.contact_us',$data);
    }

    public function saveContactUs(Request $request){
        $data = array();
        $data['name'] = $request->uname;
        $data['email'] = $request->email;
        $data['mobile'] = $request->mobile;
        $data['description'] = $request->description;
        DB::table('contact_us')->insert($data);
        return redirect()->back();
    }

    public function submodule(){
        $data = array();
        $data['title'] = 'Sub Module';
        $allModules = DB::table('modules')->get();
        $data['allModules'] = $allModules;
        $allData = DB::table('sub_module')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'submodule';
        // dd($data);
        return view('setting.submodule',$data)->with('no',1);
    }

    public function saveSubModule(Request $request){
        $data = array();
        $data['module_id'] = $request->module_name;
        $data['name'] = $request->subModuleName;
        DB::table('sub_module')->insert($data);
        return redirect()->back();
    }

    public function editSubModule($id){
        $data = array();
        $data['title'] = 'Sub Module';
        $allData = DB::table('sub_module')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $allModules = DB::table('modules')->get();
        $data['allModules'] = $allModules;
        $getData = DB::table('sub_module')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'submodule';
        return view('setting.submodule',$data)->with('no',1);
    }

    public function updateSubModule(Request $request,$id){
        $data = array();
        $data['module_id'] = $request->module_name;
        $data['name'] = $request->subModuleName;
        DB::table('sub_module')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteSubModule($id){
        $deletedata = DB::table('sub_module')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function amount(){
        $data = array();
        $data['title'] = 'Amount';
        $allData = DB::table('amount')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'amount';
        return view('setting.amount',$data)->with('no',1);
    }

    public function saveAmount(Request $request){
        $data = array();
        $data['amount'] = $request->amount;
        DB::table('amount')->insert($data);
        return redirect()->back();
    }

    public function editAmount($id){
        $data = array();
        $data['title'] = 'Amount';
        $allData = DB::table('amount')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('amount')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'amount';
        return view('setting.amount',$data)->with('no',1);
    }

    public function updateAmount(Request $request,$id){
        $data = array();
        $data['amount'] = $request->amount;
        DB::table('amount')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteAmount($id){
        $deletedata = DB::table('amount')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function package(){
        $data = array();
        $data['title'] = 'Package';
        $allData = DB::table('package')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'package';
        return view('setting.package',$data)->with('no',1);
    }

    public function savePackage(Request $request){
        $data = array();
        $data['name'] = $request->package;
        $data['type'] = $request->type;
        $data['validity'] = $request->validity;
        $data['package_amount'] = $request->amount;
        $data['per_amount'] = $request->per_amount;
        $data['joints'] = $request->joints;
        $data['commission'] = $request->commission;
        $data['days'] = $request->days;
        DB::table('package')->insert($data);
        return redirect()->back();
    }

    public function editPackage($id){
        $data = array();
        $data['title'] = 'Package';
        $allData = DB::table('package')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('package')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'package';
        return view('setting.package',$data)->with('no',1);
    }

    public function updatePackage(Request $request,$id){
        $data = array();
        $data['name'] = $request->package;
        $data['validity'] = $request->validity;
        $data['package_amount'] = $request->amount;
        $data['per_amount'] = $request->per_amount;
        $data['joints'] = $request->joints;
        $data['commission'] = $request->commission;
        $data['days'] = $request->days;
        DB::table('package')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deletePackage($id){
        $deletedata = DB::table('package')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function penalty(){
        $data = array();
        $data['title'] = 'Penalty';
        $allData = DB::table('penalty')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $data['getData'] = '';
        $data['masterclass'] = 'setting';
        $data['class'] = 'penalty';
        return view('setting.penalty',$data)->with('no',1);
    }

    public function addPenalty(Request $request){
        $data = array();
        $data['name'] = $request->penaltyName;
        $data['amount'] = $request->penaltyAmount;
        DB::table('penalty')->insert($data);
        return redirect()->back();
    }

    public function editPenalty($id){
        $data = array();
        $data['title'] = 'Penalty';
        $allData = DB::table('penalty')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('penalty')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'penalty';
        return view('setting.penalty',$data)->with('no',1);
    }

    public function updatePenalty(Request $request,$id){
        $data = array();
        $data['name'] = $request->penaltyName;
        $data['amount'] = $request->penaltyAmount;
        DB::table('penalty')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deletePenalty($id){
        $deletedata = DB::table('penalty')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function IPDCalender(){
        $data = array();
        $data['title'] = 'IPD Calender';
        $data['masterclass'] = 'setting';
        $data['class'] = 'ipd';
        $allData = DB::table('ipd_calendar')->get();
        $data['allData'] = $allData; 
        return view('setting.ipd',$data)->with('no',1);
    }

    public function addIPDCalender(Request $request){
        $data = array();
        $data['date'] = $request->date;
        $data['remark'] = $request->remark;
        DB::table('ipd_calendar')->insert($data);
        return redirect()->back();
    }

    public function editIPDCalender($id){
        $data = array();
        $data['title'] = 'IPD Calender';
        $allData = DB::table('ipd_calendar')->orderBy('created_at','ASC')->get();
        $data['allData'] = $allData;
        $getData = DB::table('ipd_calendar')->where('id',$id)->first();
        $data['getData'] = $getData;
        $data['masterclass'] = 'setting';
        $data['class'] = 'ipd';
        return view('setting.ipd',$data)->with('no',1);
    }

    public function updateIPDCalender(Request $request,$id){
        $data = array();
        $data['date'] = $request->date;
        $data['remark'] = $request->remark;
        DB::table('ipd_calendar')->where('id',$id)->update($data);
        return redirect()->back();
    }

    public function deleteIPDCalender($id){
        $deletedata = DB::table('ipd_calendar')->where('id',$id)->delete();
        return redirect()->back();
    }

    public function allAttendance(Request $request){
        try{
            $data = array();
            $data['title'] = 'All Attendance';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'attendance';
    
            $name = $request->nameId;
            $status = $request->status;
            $from_date = $request->from_date;
            $to_date = $request->to_date;
    
            if(Auth::User()->user_type == 'superadmin'){
                $allTherapist = User::where('user_type',5)->get();
                if(!empty($name) || !empty($status) || !empty($from_date) || !empty(($to_date))){
                    $query = DB::table('attendance');
                    if(!empty($name)){
                        $query = $query->where('attendance.therapist_id',$name);
                    }
                    if(!empty($status)) {
                        $query = $query->where('attendance.status',$status);
                    }
                    if(!empty($from_date)) {
                        $query = $query->where('attendance.created_at', '>=', $from_date);
                    }
                    if(!empty($to_date)) {
                        $query = $query->where('attendance.created_at', '<=', $to_date);
                    }
                    $results = $query->select('attendance.therapist_id as therapist_id','attendance.status as status','attendance.date as date','attendance.attendance_time as attendance_time','attendance.late_coming_min as late_coming_min')->get()->toArray();
                    $allData = $results;
                }else{
                    $allData = DB::table('attendance')->get();
                }
            }else{
                $allTherapist = User::where('user_type',5)->where('branch',Auth::User()->branch)->get();
                if(!empty($name) || !empty($status) || !empty($from_date) || !empty(($to_date))){
                    $query = DB::table('attendance')->join('users','users.id','=','attendance.therapist_id')->where('users.branch',Auth::User()->branch);
                    if(!empty($name)){
                        $query = $query->where('attendance.therapist_id',$name);
                    }
                    if(!empty($status)) {
                        $query = $query->where('attendance.status',$status);
                    }
                    if(!empty($from_date)) {
                        $query = $query->where('attendance.created_at', '>=', $from_date);
                    }
                    if(!empty($to_date)) {
                        $query = $query->where('attendance.created_at', '<=', $to_date);
                    }
                    $results = $query->select('attendance.therapist_id as therapist_id','attendance.status as status','attendance.date as date','attendance.attendance_time as attendance_time','attendance.late_coming_min as late_coming_min')->get()->toArray();
                    $allData = $results;
                }else{
                    $allData = DB::table('attendance')->join('users','users.id','=','attendance.therapist_id')->where('users.branch',Auth::User()->branch)->select('attendance.*')->get();
                }
            }
            
            $data['allData'] = $allData;
            $data['allTherapist'] = $allTherapist;
            return view('setting.attendance',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function notification(){
        $data = array();
        $data['title'] = 'Notification';
        $allData = DB::table('notification')->orderBy('created_at','DESC')->get();
        $data['allData'] = $allData;
        $allTime = DB::table('time_slot')->get();
        $data['allTime'] = $allTime;
        $data['masterclass'] = '';
        $data['class'] = 'notification';
        return view('notification.list',$data)->with('no',1);
    }

    public function saveSendNotification(Request $request){
        try{
            $title = $request->title;
            $description = $request->description;
            $serviceType = $request->serviceType;
            $dataNot = new Notification();
            $type = $request->type;
            $date = date('Y-m-d');
            $time = date('H:i:s');
            $dataNot['title'] = $title;
            $dataNot['message'] = $description;
            $dataNot['flag'] = $type;
            $dataNot['date'] = $date;
            $dataNot['time'] = $time;
            $dataNot['type'] = $serviceType;
            if($request->hasfile('image')){
                $folder = "/upload/notification_img/";
                $file = $this->upload_file($request->image, $folder);
                if($file)
                {
                    $dataNot['image'] = $file;
                }else{
                    $dataNot['image'] = '';
                }
            }
            $dataNot->save();
            $notificationId = $dataNot->id;
            if($type === 'therapist'){
                $allUsers = DB::table('users')->where('status','active')->where('service_type',$serviceType)->where('user_type',5)->get();
                if(count($allUsers) > 0){
                    foreach ($allUsers as $userVal) {
                        $mobileNo = $userVal->mobile;
                        $tokenId = $userVal->token_id;
                        if(!empty($tokenId) && ($tokenId != 'null')){
                            $data = array();
                            $data['nofication_id'] = $notificationId;
                            $data['user_id'] = $userVal->id;
                            $data['token_id'] = $tokenId;
                            $data['flag'] = $type;
                            $data['date'] = $date;
                            $data['time'] = $time;
                            $data['created_at'] = date('Y-m-d H:i:s');
                            DB::table('patient_notification')->insert($data);
                            $sendnot = $this->SendNotification($tokenId,$title);
                        }
                        if($mobileNo){
                            $message = $title.', '.$description;
                            $sendsms = $this->sendSMSMessage($message,$mobileNo);
                        }
                    }
                }else{
                    return redirect()->back();
                }
            }else if($type === 'patient'){
                $allUsers = DB::table('users')->where('status','active')->where('service_type',$serviceType)->where('user_type',3)->get();
                if(count($allUsers) > 0){
                    foreach ($allUsers as $userVal) {
                        $mobileNo = $userVal->mobile;
                        $tokenId = $userVal->token_id;
                        if(!empty($tokenId) && ($tokenId != 'null')){
                            $data = array();
                            $data['nofication_id'] = $notificationId;
                            $data['user_id'] = $userVal->id;
                            $data['token_id'] = $tokenId;
                            $data['flag'] = $type;
                            $data['date'] = $date;
                            $data['time'] = $time;
                            $data['created_at'] = date('Y-m-d H:i:s');
                            DB::table('patient_notification')->insert($data);
                            $sendnot = $this->SendNotification($tokenId,$title);
                        }
                        if($mobileNo){
                            $message = $title.', '.$description;
                            $sendsms = $this->sendSMSMessage($message,$mobileNo);
                        }
                    }
                }else{
                    return redirect()->back();
                }
            }else{
                return redirect()->back();
            }
            return redirect()->back(); 
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function getNotificationDetails($id){
        $getData = DB::table('notification')->where('id',$id)->select('title','message')->first();
        if($getData){
            $response = $getData;
        }else{
            $response = 'false';
        }
        return json_encode($response);
    }

    public function updateNotifications(Request $request){
        try{
            $notId = $request->notificationId;
            $data = array();
            $data['title'] = $request->title;
            $data['message'] = $request->description;
            DB::table('notification')->where('id',$notId)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function machine(){
        try{
            $data = array();
            $data['title'] = 'Treatment Machine';
            $allData = DB::table('machine')->orderBy('created_at','ASC')->get();
            $data['allData'] = $allData;
            $data['masterclass'] = 'setting';
            $data['class'] = 'machine';
            return view('setting.machine',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
        
    }

    public function saveMachine(Request $request){
        try{
            $name = $request->machineName;
            $data = array();
            $data['name'] = $name;
            DB::table('machine')->insert($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editMachine($id){
        try{
            $data = array();
            $data['title'] = 'Edit Treatment Machie';
            $allData = DB::table('machine')->orderBy('created_at','ASC')->get();
            $data['allData'] = $allData;
            $getData = DB::table('machine')->where('id',$id)->first();
            $data['getData'] = $getData;
            $data['masterclass'] = 'setting';
            $data['class'] = 'machine';
            return view('setting.machine',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateMachine(Request $request,$id){
        try{
            $data = array();
            $data['name'] = $request->machineName;
            DB::table('machine')->where('id',$id)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
        
    }

    public function banner(Request $request){
        try{
            $data = array();
            $data['title'] = 'Banner';
            $data['masterclass'] = 'setting';
            $data['class'] = 'banner';
            $allData = DB::table('banners')->get();
            $data['allData'] = $allData;
            return view('banner.list',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveBanner(Request $request){
        try{
            $data = array();
            $bannerImg = $request->bannerImg;
            if($request->hasfile('bannerImg')){
                $folder = "/upload/banner/";
                $file = $this->upload_file($request->bannerImg, $folder);
                if($file)
                {
                    $data['banner_name'] = $file;
                }else{
                    $data['banner_name'] = '';
                }
            }
            $data['status'] = 'active';
            DB::table('banners')->insert($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateBannerStatus($id){
        try{
            $bannerDetails = DB::table('banners')->where('id',$id)->first();
            if($bannerDetails){
                $status = $bannerDetails->status;
                $updateData = array();
                if($status == 'active'){
                    $updateData['status'] = 'inactive';
                }else{
                    $updateData['status'] = 'active';
                }
                DB::table('banners')->where('id',$id)->update($updateData);
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function capriPoint(Request $request){
        try{
            $data = array();
            $data['title'] = 'Capri Privilege Points';
            $data['masterclass'] = 'setting';
            $data['class'] = 'point';
            $allData = DB::table('cpoint')->get();
            $data['allData'] = $allData;
            return view('setting.cpoint',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveCPoint(Request $request){
        try{
            $data = array();
            $data['name'] = $request->cname;
            $data['point'] = $request->cpoint;
            $data['amount'] = $request->camount;
            $data['status'] = 'active';
            DB::table('cpoint')->insert($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editCPoint($id){
        try{
            $data = array();
            $allData = DB::table('cpoint')->get();
            $data['allData'] = $allData;
            $getData = DB::table('cpoint')->where('id',$id)->first();
            $data['getData'] = $getData;
            $data['title'] = 'Capri Privilege Point';
            $data['masterclass'] = 'setting';
            $data['class'] = 'point';
            return view('setting.cpoint',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateCpoint(Request $request){
        try{
            $data = array();
            $id = $request->id;
            $data['name'] = $request->cname;
            $data['point'] = $request->cpoint;
            $data['amount'] = $request->camount;
            DB::table('cpoint')->where('id',$id)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allTherapistPenalty(Request $request){
        try{
            $data = array();
            $data['title'] = 'Therapist Penalty';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'penalties';
            if(!empty($request->therapistId) || !empty($request->to_date) || !empty($request->from_date)){
                $query = User::where('user_type','5')->where('status','active');
                if(!empty($request->therapistId)) {
                    $query = $query->where('id',$request->therapistId);
                }
                $results = $query->get();
                $allTherapist = $results;
                if(!empty($request->to_date) && !empty($request->from_date)){
                    foreach ($allTherapist as $value){
                        // $allAttendance = DB::table('daily_penalty')->where('therapist_id',$value->id)->whereBetween('date', [$request->to_date, $request->from_date])->sum('amount');
                        $allAttendance = DB::table('daily_penalty')->where('therapist_id',$value->id)->select(DB::raw('date BETWEEN "' . $request->from_date . '" AND "' . $request->to_date))->sum('amount');
                        $allAppPenalty = DB::table('daily_entry')->where('therapist_id',$value->id)->whereBetween('app_booked_date', [$request->to_date, $request->from_date])->sum('penalty');
                        $value->allAttendance = $allAttendance;
                        $value->allAppPenalty = $allAppPenalty;
                    }
                }
                if(!empty($request->to_date) && empty($request->from_date)){
                    foreach ($allTherapist as $value) {
                        $allAttendance = DB::table('daily_penalty')->where('therapist_id',$value->id)->where('date', '<=', $request->to_date)->sum('amount');
                        $allAppPenalty = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '<=', $request->to_date)->sum('penalty');
                        $value->allAttendance = $allAttendance;
                        $value->allAppPenalty = $allAppPenalty;
                    }
                }
                if(!empty($request->from_date) && empty($request->to_date)){
                    foreach ($allTherapist as $value) {
                        $allAttendance = DB::table('daily_penalty')->where('therapist_id',$value->id)->where('date', '>=', $request->from_date)->sum('amount');
                        $allAppPenalty = DB::table('daily_entry')->where('therapist_id',$value->id)->where('app_booked_date', '>=', $request->from_date)->sum('penalty');
                        $value->allAttendance = $allAttendance;
                        $value->allAppPenalty = $allAppPenalty;
                    }
                }
            }else{
                $allTherapist = User::where('user_type',5)->where('status','active')->get();
                foreach ($allTherapist as $allTh) {
                    $allAttendance = DB::table('daily_penalty')->where('therapist_id',$allTh->id)->sum('amount');
                    $allAppPenalty = DB::table('daily_entry')->where('therapist_id',$allTh->id)->sum('penalty');
                    $allTh->allAttendance = $allAttendance;
                    $allTh->allAppPenalty = $allAppPenalty;
                }
            }
            $data['allTherapist'] = $allTherapist;
            return view('setting.therapistPenalty',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allAttendanceReport($id){
        try{
            $data = array();
            $data['title'] = 'All Attendance Report';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'penalties';
            $allData = DB::table('daily_penalty')->where('therapist_id',$id)->where('penalty_id','late_comming')->get();
            $data['allData'] = $allData;
            $totalAmt = DB::table('daily_penalty')->where('therapist_id',$id)->where('penalty_id','late_comming')->sum('amount');
            $data['totalAmt'] = $totalAmt;
            $getTherapist = userDetails($id)->name;
            $data['therapistName'] = $getTherapist;
            return view('setting.allAttendanceReport',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allAppointmentPenalty($id){
        try{
            $data = array();
            $data['title'] = 'All Appointment Penalty Report';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'penalties';
            $allData = DB::table('daily_entry')->where('therapist_id',$id)->where('status','complete')->where('penalty','>',0)->select('id','appointment_id','therapist_id','visit_type','app_booked_date','penalty','type','app_booked_date')->get();
            $data['allData'] = $allData;
            $totalAmt = DB::table('daily_entry')->where('therapist_id',$id)->sum('penalty');
            $data['totalAmt'] = $totalAmt;
            $getTherapist = userDetails($id)->name;
            $data['therapistName'] = $getTherapist;
            return view('setting.allAppointmentReport',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function displayAllUser(){
        try{
            $data = array();
            $data['title'] = 'Select User';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'changePassword';
            $allUserType = DB::table('user_type')->select('id','name')->get();
            $data['allUserType'] = $allUserType;
            return view('changePassword.selectUser',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function selectUserAccordingToUserType($id){
        $data = DB::table("users")
                    ->select('id','name','mobile')
                    ->where("user_type",$id)
                    ->get();
        return json_encode($data);
    }

    public function selectUserForChangePassword(Request $request){
        try{
            $data = array();
            $data['title'] = 'Change Password';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'changePassword';
            $data['userType'] = $request->userType;
            $data['userId'] = $request->userId;
            $userData = User::where('id',$request->userId)->first();
            $data['userData'] = $userData;
            return view('changePassword.form',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function resetPasswordForUser(Request $request){
        try{
            $userId = $request->userId;
            $userDetails = userDetails($userId);
            $oldPassword = $request->old_password;
            $newPassword = $request->new_password;
            $confirmPassword = $request->confirm_password;
            if($newPassword == $confirmPassword){
                if($oldPassword == $userDetails->confirmpassword){
                    $data =array();
                    $data['password'] = bcrypt($newPassword);
                    $data['confirmpassword'] = $newPassword;
                    User::where('id', $userId)->update($data);
                    return Redirect::to('select-user-change-password');
                }else{
                    return Redirect()->back();
                }
            }else{
                return Redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allExercise(){
        try{
            $data = array();
            $data['title'] = 'Exercise';
            $data['masterclass'] = 'setting';
            $data['class'] = 'exercise';
            $allData = DB::table('exercise')->get();
            $data['allData'] = $allData;
            return view('exercise.list',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function addExercise(){
        try{
            $data = array();
            $data['title'] = 'Add Exercise';
            $data['masterclass'] = 'setting';
            $data['class'] = 'exercise';
            return view('exercise.add',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveExercise(Request $request){
        try{
            $data = array();
            $data['name'] = $request->exName;
            $data['description'] = $request->description;
            $data['status'] = 'active';
            $data['created_at'] = date("Y-m-d H:i:s");
            DB::table('exercise')->insert($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editExercise($id){
        $getData = DB::table('exercise')->where('id',$id)->select('name','description')->first();
        if($getData){
            $response = $getData;
        }else{
            $response = 'false';
        }
        return json_encode($response);
    }

    public function updateExercise(Request $request){
        try{
            $exeId = $request->exeId;
            $data = array();
            $data['name'] = $request->exName;
            $data['description'] = $request->exeDesc;
            DB::table('exercise')->where('id',$exeId)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allExerciseVideo($id){
        try{
            $data = array();
            $data['title'] = 'Add Exercise Videos';
            $data['masterclass'] = 'setting';
            $data['class'] = 'exercise';
            $data['id'] = $id;
            $getData = DB::table('exercise_videos')->where('exerciseId',$id)->get();
            $data['getData'] = $getData;
            return view('exercise.addVideo',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateExerciseStatus($id){
        try{
            $Details = DB::table('exercise')->where('id',$id)->first();
            if($Details){
                $status = $Details->status;
                $updateData = array();
                if($status == 'active'){
                    $updateData['status'] = 'inactive';
                }else{
                    $updateData['status'] = 'active';
                }
                DB::table('exercise')->where('id',$id)->update($updateData);
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function addExerciseVideo(Request $request){
        try{
            $data = array();
            $data['exerciseId'] = $request->exerciseId;
            $data['description'] = $request->videoDesc;
            $data['status'] = 'active';
            $data['created_at'] = date("Y-m-d H:i:s");
            if($request->hasfile('exVideo')){
                $folder = "upload/exercise_video/";
                $file = $this->upload_file($request->exVideo, $folder);
                if($file)
                {
                    $data['video'] = $file;
                }
            }else{
                $data['video'] = '';
            }
            DB::table('exercise_videos')->insert($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateExerciseVideoStatus($id){
        try{
            $Details = DB::table('exercise_videos')->where('id',$id)->first();
            if($Details){
                $status = $Details->status;
                $updateData = array();
                if($status == 'active'){
                    $updateData['status'] = 'inactive';
                }else{
                    $updateData['status'] = 'active';
                }
                DB::table('exercise_videos')->where('id',$id)->update($updateData);
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editExerciseVideos($id){
        $getData = DB::table('exercise_videos')->where('id',$id)->first();
        if($getData){
            $response = $getData;
        }else{
            $response = 'false';
        }
        return json_encode($response);
    }

    public function updateExerciseVideo(Request $request){
        try{
            $data = array();
            $exeId = $request->exerciseId;
            $oldVideoData = $request->oldVideoData;
            $data['description'] = $request->videoDesc;
            if($request->hasfile('exVideo')){
                $folder = "upload/exercise_video/";
                $file = $this->upload_file($request->exVideo, $folder);
                if($file)
                {
                    $data['video'] = $file;
                }
            }else{
                $data['video'] = $oldVideoData;
            }
            DB::table('exercise_videos')->where('id',$exeId)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allSelfExerciseList(Request $request){
        try{
            $currentDate = date('Y-m-d');
            $data = array();
            $data['title'] = 'Self Exercise';
            $data['masterclass'] = 'generalDetails';
            $data['class'] = 'selfExercise';
            if(Auth::User()->user_type == 'superadmin'){
                $allPatient = User::where('user_type',3)->where('status','active')->get();
                $allTherapist = User::where('user_type',5)->where('status','active')->get();
            }else{
                $allPatient = User::where('user_type',3)->where('status','active')->where('branch',Auth::User()->branch)->get();
                $allTherapist = User::where('user_type',5)->where('status','active')->where('branch',Auth::User()->branch)->get();
            }
            $data['allPatient'] = $allPatient;
            $data['allTherapist'] = $allTherapist;

            if(!empty($request->patientName) || !empty($request->therapistName) || !empty($request->to_date) || !empty($request->from_date)){
                $query = DB::table('daily_exercise_activity');
                if(!empty($request->therapistName)){
                    $query = $query->where('therapist_id',$request->therapistName);
                }
                if(!empty($request->patientName)){
                    $query = $query->where('patient_id',$request->patientName);
                }
                if(!empty($request->from_date)){
                    $query = $query->where('date', '>=', $request->from_date);
                }
                if(!empty($request->to_date)){
                    $query = $query->where('date', '<=', $request->to_date);
                }
                $query = $query->get();
                $allData = $query;
            }else{
                $allData = DB::table('daily_exercise_activity')->where('date',$currentDate)->get();
            }
            $data['allData'] = $allData;
            return view('exercise.dailySelfExercise',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }
}
