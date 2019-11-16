<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use file;
use Auth;
use Redirect;
use Excel;
use Carbon\Carbon;
use App\User;
use DateTime;
use App\Appointment;
use App\AppointmentHistory;
use App\DailyEntry;
use App\Helper\FileUpload;
use App\Helper\SendSMS;
use App\Helper\SendNotification;

class AppointmentController extends Controller
{
    use FileUpload;
    use SendSMS;
    use SendNotification;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function therapistPatientWise($patientId){
        $getData = User::where('id',$patientId)->first();
        return json_encode($getData);
    }

    public function checkAppointmentAvailability($therapistId,$bookedDate,$bookedTime){
        $currentDate = date('Y-m-d');
        $currentTime = date('h:i:s');
        $checkData = DailyEntry::where('therapist_id',$therapistId)->where('app_booked_date',$bookedDate)->where('app_booked_time',$bookedTime)->first();
        if(!empty($checkData)){
            $getData = 'false';
        }else{
            $getData = 'true';
        }
        return json_encode($getData);
    }

    public function addAppointment(){
        try{
            $data = array();
            $data['title'] = 'Add New Appointment';
            $appointmentTimeSlot = DB::table('appointment_time_slot')->get();
            $data['appointmentTimeSlot'] = $appointmentTimeSlot;
            $referenceType = DB::table('reference')->get();
            $data['referenceType'] = $referenceType;
            if(Auth::User()->user_type == 'superadmin'){
                $allTherapist = User::where('user_type',5)->where('status','active')->get();
                $allPatient = User::where('user_type',3)->get();
            }else{
                $allTherapist = User::where('user_type',5)->where('status','active')->where('branch',Auth::User()->branch)->get();
                $allPatient = User::where('user_type',3)->where('branch',Auth::User()->branch)->get();
            }
            $data['allTherapist'] = $allTherapist;
            $data['allPatient'] = $allPatient;
            $patientService = DB::table('service')->get();
            $data['patientService'] = $patientService;
            $timeSlot = DB::table('time_slot')->orderBy('time','ASC')->get();
            $data['timeSlot'] = $timeSlot;
            $allAmount = DB::table('amount')->orderBy('amount','ASC')->get();
            $data['allAmount'] = $allAmount;
            $allBranch = DB::table('location')->get();
            $data['allBranch'] = $allBranch;
            $data['masterclass'] = 'appointment';
            $data['class'] = 'addapp';
            return view('appointment.add',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveAppointment(Request $request){
        try{
            $patientType = $request->patientType;
            $branch = Auth::user()->branch;
            $patientId = $request->patientId;
            $patientName = $request->patientName;
            $patientMobile = $request->patientMobile;
            $pGender = $request->gender;
            $email = $request->email;
            $password = $request->password;
            $joint = $request->joints;
            $appointment_date = $request->appDate;
            $appointment_time = $request->appTime;
            $serviceType = $request->service_type;
            $selectBranch = $request->branch;
            $assignTherapist = $request->assignTherapist;
            $referenceType = $request->reference_type;
            $payment_method = $request->payment_method;
            $consultation_fees = $request->consultation_fees;
            $ipd_no = $request->ipd_no;
            $ipmr_no = $request->ipmr_no;
            $room_no = $request->room_no;
            $ipdcase = $request->ipdcase;
            $consultant = $request->consultant;
            $surgery_day = $request->surgery_day;
            $address = $request->address;
            $dob = $request->dob;
            $maritalStatus = $request->maritalStatus;
            $vegNonVeg = $request->vegNonVeg;
            $duplicateData = User::where('mobile',$patientMobile)->first();
            if($duplicateData){
                echo "<script>alert('Mobile no and Email already exist');</script>";
                return redirect()->back();
            }else{
                $appData = new Appointment();
                if($patientType == 'new'){
                    $userData = new User();
                    $userData['name'] = $patientName;
                    $userData['therapist_id'] = $assignTherapist;
                    $userData['mobile'] = $patientMobile;
                    $userData['gender'] = $pGender;
                    $userData['email'] = $email;
                    $userData['password'] = bcrypt($password);
                    $userData['confirmpassword'] = $password;
                    $usename = quickRandom(4);
                    $userData['username'] = 'user_'.$usename;
                    $userData['service_type'] = $serviceType;
                    if(($serviceType == 1) || ($serviceType == 8) || ($serviceType == 9)){
                        $userData['branch'] = $selectBranch;
                    }else{
                        $userData['branch'] = $branch;
                    }
                    
                    $userData['status'] = 'active';
                    $userData['user_type'] = 3;
                    $userData['address'] = $address;
                    $userData['dob'] = $dob;
                    $userData['marital_status'] = $maritalStatus;
                    $userData['food_habit'] = $vegNonVeg;
                    $userData->save();
                    $user_id = $userData->id;
                    $regData = array();
                    $regNo = 100000 + $user_id;
                    $branch_key = branchDetails($branch)->b_key;
                    $regData = array();
                    $regData['registration_no'] = $regNo.'_'.$branch_key.'_PT';
                    $saveData = User::where('id',$user_id)->update($regData);
                    $appData['app_service_type'] = $serviceType;
                }else{
                    $user_id = $patientId;
                    $userDetails = userDetails($user_id);
                    $sType = $userDetails->service_type;
                    $appData['app_service_type'] = $sType;
                }
                
                $appData['user_id'] = $user_id;
                $appData['patient_type'] = $patientType;
                $appData['joints'] = $joint;
                $appData['appointment_date'] = $appointment_date;
                $appData['appointment_time'] = $appointment_time;
                if($serviceType == 7){
                    $appData['payment_method'] = 'per_day_visit';
                }else{
                    $appData['payment_method'] = $payment_method;
                }
                $appData['consultation_fees'] = $consultation_fees;
                $appData['consultation_name'] = $request->consultation_name;
                $appData['created_by'] = Auth::User()->id;
                $appData['reference_type'] = $referenceType;
                $appData['ipd_no'] = $ipd_no;
                $appData['ipmr_no'] = $ipmr_no;
                $appData['room_no'] = $room_no;
                $appData['ipd_case'] = $ipdcase;
                $appData['consultant'] = $consultant;
                $appData['surgery_day'] = $surgery_day;
                $appData['status'] = 'pending';
                if($payment_method == 'package_wise'){
                    $appData['payment_status'] = 'pending';
                }else{
                    $appData['payment_status'] = '';
                }
                $appData->save();
                $appId = $appData->id;
                //History
                $history = new AppointmentHistory();
                $history['appointment_id'] = $appId;
                $history['reason'] = 'Booked Appointment';
                $history['created_by'] = Auth::User()->id;
                $history->save();
            }
            return Redirect::to('all-appointment');
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allAppointment(Request $request){
        try{
            $todayDate = date('Y-m-d');
            $data = array();
            $data['title'] = 'All Appointment';
            if(Auth::User()->user_type == 'superadmin'){
                if(!empty($request->therapistName) || !empty($request->status) || !empty($request->to_date) || !empty($request->from_date) || !empty($request->appointmentType) || !empty($request->serviceName) || !empty($request->monthName) || !empty($request->yearName)){
                    $query = DB::table('appointment');
                    if (!empty($request->therapistName)) {
                        $query = $query->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$request->therapistName);
                    }
                    if(!empty($request->status)){
                        $query = $query->where('appointment.status',$request->status);
                    }
                    if(!empty($request->from_date)){
                        $query = $query->where('appointment.appointment_date', '>=', $request->from_date);
                    }
                    if(!empty($request->monthName)){
                        $query = $query->whereMonth('appointment.appointment_date',$request->monthName);
                    }
                    if(!empty($request->yearName)){
                        $query = $query->whereYear('appointment.appointment_date',$request->yearName);
                    }
                    if(!empty($request->to_date)){
                        $query = $query->where('appointment.appointment_date', '<=', $request->to_date);
                    }
                    if(!empty($request->appointmentType)){
                        $query = $query->where('appointment.payment_method',$request->appointmentType);
                    }
                    if(!empty($request->serviceName)){
                        $query = $query->where('appointment.app_service_type',$request->serviceName);
                    }
                    $results = $query->select('appointment.id as id','appointment.user_id as user_id','appointment.reference_type as reference_type','appointment.payment_status as payment_status','appointment.status as status','appointment.appointment_type as appointment_type','appointment.payment_method as payment_method','appointment.package_type as package_type','appointment.app_service_type as app_service_type','appointment.created_at as created_at','appointment.appointment_date')->orderBy('appointment.id','DESC')->get();
                    $allData = $results;
                }else{
                    $allData = DB::table('appointment')->where('appointment_date',$todayDate)->orderBy('id','DESC')->select('id','user_id','reference_type','payment_status','status','appointment_type','payment_method','package_type','app_service_type','created_at','appointment_date')->get();
                }
                $allTherapist = User::where('user_type',5)->where('status','active')->get();
                $allBranch = DB::table('location')->get();
            }else{
                if(!empty($request->therapistName) || !empty($request->status) || !empty($request->to_date) || !empty($request->from_date) || !empty($request->appointmentType) || !empty($request->monthName) || !empty($request->yearName)){
                    $query = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.branch',Auth::User()->branch);
                    if (!empty($request->therapistName)) {
                        $query = $query->where('users.therapist_id',$request->therapistName);
                    }
                    if(!empty($request->status)){
                        $query = $query->where('appointment.status',$request->status);
                    }
                    if(!empty($request->monthName)){
                        $query = $query->whereMonth('appointment.appointment_date',$request->monthName);
                    }
                    if(!empty($request->yearName)){
                        $query = $query->whereYear('appointment.appointment_date',$request->yearName);
                    }
                    if(!empty($request->from_date)){
                        $query = $query->where('appointment.appointment_date', '>=', $request->from_date);
                    }
                    if(!empty($request->to_date)){
                        $query = $query->where('appointment.appointment_date', '<=', $request->to_date);
                    }
                    if(!empty($request->appointmentType)){
                        $query = $query->where('appointment.payment_method',$request->appointmentType);
                    }
                    if(!empty($request->serviceName)){
                        $query = $query->where('appointment.app_service_type',$request->serviceName);
                    }
                    $results = $query->select('appointment.id as id','appointment.user_id as user_id','appointment.reference_type as reference_type','appointment.payment_status as payment_status','appointment.status as status','appointment.appointment_type as appointment_type','appointment.payment_method as payment_method','appointment.package_type as package_type','appointment.app_service_type as app_service_type','appointment.created_at as created_at','appointment.appointment_date')->orderBy('appointment.id','DESC')->get();
                    $allData = $results;
                }else{
                    $allData = DB::table('appointment')->select('appointment.id as id','appointment.user_id as user_id','appointment.reference_type as reference_type','appointment.payment_status as payment_status','appointment.status as status','appointment.appointment_type as appointment_type','appointment.payment_method as payment_method','appointment.package_type as package_type','appointment.app_service_type as app_service_type','appointment.created_at as created_at','appointment.appointment_date')->join('users','appointment.user_id','=','users.id')->where('appointment_date',$todayDate)->where('users.user_type','!=','superadmin')->where('users.branch',Auth::User()->branch)->orderBy('appointment.id','DESC')->get();
                }
                $allTherapist = User::where('user_type',5)->where('status','active')->where('branch',Auth::User()->branch)->get();
                $allBranch = DB::table('location')->where('id',Auth::user()->branch)->get();
            }
            if(count($allData) > 0){
                foreach($allData as $allVal) {
                    if(!empty($allVal->user_id)){
                        $userDetails = userDetails($allVal->user_id);
                        $allVal->patientName = $userDetails->name;
                        $allVal->phoneNo = $userDetails->mobile;
                        if(!empty($userDetails->therapist_id)){
                            $allVal->therapistName = userDetails($userDetails->therapist_id)->name;
                        }else{
                            $allVal->therapistName = '';
                        }
                        if(!empty($userDetails->branch)){
                            $allVal->branchName = branchDetails($userDetails->branch)->name;
                        }else{
                            $allVal->branchName = '';
                        }
                    }else{
                        $allVal->patientName = '';
                        $allVal->phoneNo = '';
                        $allVal->therapistName = '';
                        $allVal->branchName = '';
                    }
                    if(!empty($allVal->reference_type)){
                        $allVal->reference_type = referenceTypeDetails($allVal->reference_type)->name;
                    }else{
                        $allVal->reference_type = '';
                    }
                    if(!empty($allVal->payment_method)){
                        $allVal->payment_method = $allVal->payment_method;
                    }else{
                        $allVal->payment_method = '';
                    }
                    if(!empty($allVal->payment_status)){
                        $allVal->payment_status = ucfirst($allVal->payment_status);
                    }else{
                        $allVal->payment_status = '';
                    }
                }
            }else{
                $allData = '';
            }
            $data['allData'] = $allData;
            $data['allTherapist'] = $allTherapist;
            $data['allBranch'] = $allBranch;
            $allService = DB::table('service')->get();
            $data['allService'] = $allService;
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            return view('appointment.list',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function getConsentRecord($id){
        try{
            $getData = DB::table('consent_record')->join('appointment','appointment.id','=','consent_record.appId')->join('users','users.id','=','appointment.user_id')->where('consent_record.appId',$id)->orderBy('id','DESC')->select('consent_record.id','consent_record.appId','consent_record.visit_id','consent_record.relation','consent_record.r_nane','consent_record.patient_sign','consent_record.therapist_sign','consent_record.created_at','users.name','users.gender','users.dob','users.therapist_id','users.branch')->first();
            if($getData){
                $appDetails = appointmentDetails($id);
                if($appDetails->payment_method == 'per_day_visit'){
                    // for perday
                    $visitData = DB::table('daily_entry')->where('appointment_id',$id)->where('status','complete')->orderBy('id','DESC')->first();
                    if($visitData){
                        $visitAmount = $visitData->amount;
                        $visitDays = 1;
                    }else{
                        $visitAmount = '';
                        $visitDays = '';
                    }
                }else{
                    // for package
                    if(!empty($appDetails->package_type)){
                        $packageDetails = packageDetails($appDetails->package_type);
                        $packAmt = $packageDetails->package_amount;
                        $visitAmount = $packAmt;
                        $visitDays = $packageDetails->days;
                    }else{
                        $visitAmount = '';
                        $visitDays = '';
                    }
                }
                $getData->visitAmount = $visitAmount;
                $getData->visitDays = $visitDays;
                $userDetails = userDetails($appDetails->user_id);
                $getData->gender = $userDetails->gender;
                if(!empty($getData->dob) && ($getData->dob != "0000-00-00")){
                    $dd1 = date("d-m-Y", strtotime($getData->dob));
                    $today = date("Y-m-d");
                    $diff = date_diff(date_create($dd1), date_create($today));
                    $age = $diff->format('%y');
                    $age = $age.' Years';
                }else{
                    $age = '';
                }
                if(!empty($getData->branch)){
                    $branchName = DB::table('location')->where('id',$getData->branch)->first();
                    $getData->branch = $branchName->name;
                }else{
                    $getData->branch = '';
                }
                $getData->age = $age;
                if(!empty($getData->therapist_id)){
                    $getData->therapistName = userDetails($getData->therapist_id)->name;
                }else{
                    $getData->therapistName = '';
                }
                return view('report.consentPaper', compact('getData'));
            }else{
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function myAllAppointmentDetails($id){
        try{
            $data = array();
            $data['title'] = 'Appointment Details';
            $allData = Appointment::where('id',$id)->first();
            $data['allData'] = $allData;
            $allImages = DB::table('appointment_images')->where('app_id',$id)->get();
            $data['allImages'] = $allImages;
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            return view('appointment.view',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function approveAppointment($id){
        try{
            $data = array();
            $data['status'] = 'approved';
            Appointment::where('id',$id)->update($data);
            $appDetails = appointmentDetails($id);
            $userId = $appDetails->user_id;
            $userDetails = userDetails($userId);
            $mobileNo = $userDetails->mobile;
            // send sms
            if($mobileNo){
                $message = 'Your Booked Appointment Successfully Approved by CapriSpine Team';
                $sendsms = $this->sendSMSMessage($message,$mobileNo);
            }
            // send notification
            $tokenId = $userDetails->token_id;
            if(!empty($tokenId)){
                $title = 'Your Appointment Successfully Approved by CapriSpine Team';
                $sendnot = $this->SendNotification($tokenId,$title);
                // add notification
                $addNot = array();
                $addNot['user_id'] = $userId;
                $addNot['title'] = $title;
                $addNot['token_id'] = $tokenId;
                $addNot['date'] = date('Y-m-d');
                $addNot['time'] = date('H:i:s');
                DB::table('patient_notification')->insert($addNot);
            }
            // history
            $history = new AppointmentHistory();
            $history['appointment_id'] = $id;
            $history['reason'] = 'Approved Appointment';
            $history['created_by'] = Auth::User()->id;
            $history->save();
            if($appDetails->payment_method == 'per_day_visit'){
                return Redirect::to('per-day-daily-entry'.'/'.$id);
            }else if($appDetails->payment_method == 'package_wise'){
                return Redirect::to('edit-appointment'.'/'.$id);
            }else{
                return Redirect::to('edit-appointment'.'/'.$id);
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function cancelAppointment($id){
        $data = array();
        $data['status'] = 'cancel';
        Appointment::where('id',$id)->update($data);

        //history
        $history = new AppointmentHistory();
        $history['appointment_id'] = $id;
        $history['reason'] = 'Cancel Appointment';
        $history['created_by'] = Auth::User()->id;
        $res = $history->save();
        if($res){
            $data = true;
        }else{
            $data = false;
        }
        return json_encode($data);
    }

    public function completeAppointment(Request $request){
        try{
            $appId = $request->AppontmentId_complete;
            $remark = $request->remark;
            $dataAppointment = array();
            $dataAppointment['status'] = 'complete';
            $dataAppointment['complete_remark'] = $remark;
            Appointment::where('id',$appId)->update($dataAppointment);

            //also remove therapist id from timeslot table.

            $dataHistory = array();
            $dataHistory['appointment_id'] = $appId;
            $dataHistory['reason'] = 'Appointment Complete';
            $dataHistory['created_by'] = Auth::User()->id;
            DB::table('appointment_history')->update($dataHistory);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editAppointment($id){
        try{
            $data = array();
            $data['title'] = 'Edit Appointment';
            $getData = Appointment::where('id',$id)->first();
            $data['getData'] = $getData;
            if(($getData->app_service_type == 9) || ($getData->app_service_type == 1) || ($getData->app_service_type == 8)){
                if($getData->joints != ''){
                    $allPackage = DB::table("package")->where('type','homeCare')->select('id','name','days','package_amount','per_amount','joints','commission')->where('joints',$getData->joints)->get();
                }else{
                    $allPackage = DB::table("package")->where('type','homeCare')->select('id','name','days','package_amount','per_amount','joints','commission')->get();
                }
            }else{
                if($getData->joints != ''){
                    $allPackage = DB::table("package")->where('type','opd')->select('id','name','days','package_amount','per_amount','joints','commission')->where('joints',$getData->joints)->get();
                }else{
                    $allPackage = DB::table("package")->where('type','opd')->select('id','name','days','package_amount','per_amount','joints','commission')->get();
                }
            }
            $data['allPackage'] = $allPackage;
            if(Auth::User()->user_type == 'superadmin'){
                $allTherapists = User::where('user_type',5)->get();
                $consultationName = User::where('user_type',5)->get();
            }else{
                $allTherapists = User::where('user_type',5)->where('branch',Auth::user()->branch)->get();
                $consultationName = User::where('user_type',5)->where('branch',Auth::user()->branch)->get();
            }
            $data['allTherapists'] = $allTherapists;
            $data['consultationName'] = $consultationName;
            $timeSlot = DB::table('time_slot')->orderBy('time','ASC')->get();
            $data['timeSlot'] = $timeSlot;
            $allAmount = DB::table('amount')->orderBy('amount','ASC')->get();
            $data['allAmount'] = $allAmount;
            $invoiceData = DB::table('invoice')->where('appointment_id',$id)->where('package_id',$getData->package_type)->orderBy('id','DESC')->first();
            if(!empty($invoiceData)){
                $data['invoiceData'] = $invoiceData;
            }else{
                $data['invoiceData'] = '';
            }
            $serviceType = DB::table('service')->get();
            $data['serviceType'] = $serviceType;
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            return view('appointment.edit',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateAppointment(Request $request, $id){
        try{
            $data = array();
            $data['joints'] = $request->joints;
            $data['status'] = $request->status;
            $data['payment_method'] = $request->payment_method;
            $data['appointment_time'] = $request->appTime;
            $data['consultation_fees'] = $request->consultation_fees;
            $data['consultation_name'] = $request->consultation_name;
            $data['app_service_type'] = $request->service_type;
            $appointmentDetails = appointmentDetails($id);
            $appServiceType = $appointmentDetails->app_service_type;
            $fixedJoint = $appointmentDetails->joints;
            //update due days
            $oldPackageId = $appointmentDetails->package_type;
            $package_type = $request->package_type;
            if(($fixedJoint == $request->joints) && ($oldPackageId < $package_type)){
                $totalDueDays = DB::table('daily_entry')->where('appointment_id',$id)->orderBy('created_at','DESC')->first();
                if($totalDueDays){
                    $data['due_package_days'] = $totalDueDays->due_days;
                }else{
                    $data['due_package_days'] = '';
                }
            }
            // $checkHomeCare = DB::table('daily_entry')->where('')->orderBy('id','DESC')->first();

            if(($package_type != '') && (($appServiceType != 9) || ($appServiceType != 1) || ($appServiceType != 8))){
                $data['package_type'] = $package_type;
            }elseif(($package_type != '') && (($appServiceType == 9) || ($appServiceType == 1) || ($appServiceType == 8))){
                $data['package_type'] = 62;
            }else{
                $data['package_type'] = '';
            }
            Appointment::where('id',$id)->update($data);

            // 30% consultation fees shared to therapist account
            $consultationDate = $appointmentDetails->appointment_date;
            $consultation_fees = $appointmentDetails->consultation_fees;
            $therapistId = $appointmentDetails->consultation_name;
            $cDate = $consultationDate;
            $ctDate = date('Y-m-d');
            $currDate = date('Y-m-d', strtotime($ctDate. ' + 2 days'));
            if(($request->payment_method == 'package_wise') && ($appointmentDetails->payment_method == 'per_day_visit') && ($cDate <= $currDate)){
                $amountData = array();
                $amountData['therapist_id'] = $therapistId;
                $percentage = 30;
                $shareAmt = ($percentage / 100) * $consultation_fees;
                $amountData['therapist_account'] = $shareAmt;
                $capriAcc = $consultation_fees - $shareAmt;
                $amountData['capri_account'] = $capriAcc;
                $amountData['total_amount'] = $consultation_fees;
                $amountData['flag'] = 'perday to package convert';
                $amountData['created_by'] = Auth::user()->id;
                $amountData['transection_status'] = '';
                $amountData['transection_id'] = '';
                $amountData['payment_date'] = date("Y-m-d");
                DB::table('account')->insert($amountData);

                $accountHistory = array();
                $accountHistory['appointment_id'] = $id;
                $accountHistory['therapist_id'] = $therapistId;
                $accountHistory['capri_account'] = $capriAcc;
                $accountHistory['therapist_account'] = $shareAmt;
                $accountHistory['total_amount'] = $consultation_fees;
                $accountHistory['remark'] = 'Update package commission';
                $accountHistory['created_by'] = Auth::user()->id;
                $accountHistory['transection_status'] = '';
                $accountHistory['transection_id'] = '';
                $accountHistory['payment_date'] = date("Y-m-d");
                DB::table('account_history')->insert($accountHistory);
            }

            $userData = array();
            $userId = $request->patientId;
            $userData['therapist_id'] = $request->therapistId;
            User::where('id',$userId)->update($userData);
            
            //History
            $history = new AppointmentHistory();
            $history['appointment_id'] = $id;
            $history['reason'] = 'Update Appointment';
            $history['created_by'] = Auth::User()->id;
            $history->save();
            if($request->payment_method == 'per_day_visit'){
                return Redirect::to('per-day-daily-entry/'.$id);
            }else if($request->payment_method == 'package_wise'){
                return Redirect::to('package-wise-entry/'.$id);
            }else{
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function myAppointment(){
        try{
            $data = array();
            $data['title'] = 'My Appointment';
            if(Auth::user()->user_type == 'superadmin'){
                $allData = DB::table('appointment')->select('id','user_id','appointment_type','status','payment_method','package_type','created_at')->get();
            }else{
                $allData = DB::table('appointment')->select('appointment.id as id','appointment.user_id as user_id','appointment.appointment_type as appointment_type','appointment.status as status','appointment.payment_method as payment_method','appointment.package_type as package_type','appointment.created_at as created_at')->join('users','appointment.user_id','=','users.id')->where('users.user_type','!=','superadmin')->where('appointment.created_by',Auth::user()->id)->where('users.branch',Auth::User()->branch)->get();
            }
            $data['allData'] = $allData;
            $data['masterclass'] = 'appointment';
            $data['class'] = 'myapp';
            return view('appointment.myList',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function reminderAppointment($id){
        try{
            $appDetails = Appointment::where('id',$id)->where('status','approved')->first();
            if($appDetails->user_id){
                $mobileNo = userDetails($appDetails->user_id)->mobile;
                $userName = userDetails($appDetails->user_id)->name;
                if($mobileNo){
                    $message = 'Hello '.$userName.', this is reminder that you have an appointment with therapist - Thanks, CapriSpine Team';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }
                //history
                $history = new AppointmentHistory();
                $history['appointment_id'] = $id;
                $history['reason'] = 'Reminder Appointment';
                $history['created_by'] = Auth::User()->id;
                $history->save();
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allPackageList($serviceId){
        // dd($serviceId);
        if(($serviceId == 1) || ($serviceId == 8) || ($serviceId == 9)) {
            $data = DB::table("package")->where('type','homeCare')->select('id','name','days','package_amount','per_amount','joints','commission')->get();
        }else{
            $data = DB::table("package")->where('type','opd')->select('id','name','days','package_amount','per_amount','joints','commission')->get();
        }
        return json_encode($data);
    }

    public function allVisitHistory($id){
        try{
            $data = array();
            $data['title'] = 'Visit History';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            $allData = DB::table('daily_entry')->where('appointment_id',$id)->get();
            $totalAmt = DB::table('daily_entry')->where('appointment_id',$id)->sum('amount');
            $totalPenalty = DB::table('daily_entry')->where('appointment_id',$id)->sum('penalty');
            $data['allData'] = $allData;
            $data['totalAmt'] = $totalAmt;
            $data['totalPenalty'] = $totalPenalty;
            return view('appointment.history',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function perDayDailyEntry($id){
        try{
            $data = array();
            $data['title'] = 'Per day daily Entry';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            $data['appointmentId'] = $id;
            $appDetails = appointmentDetails($id);
            $appServiceType = $appDetails->app_service_type;
            $userId = $appDetails->user_id;
            $userDetails = userDetails($userId);
            $userTherapist = $userDetails->therapist_id;
            $data['appServiceType'] = $appServiceType;
            $data['userTherapist'] = $userTherapist;
            $allData = DB::table('daily_entry')->where('appointment_id',$id)->where('type','1')->orderBy('app_booked_date','DESC')->get();
            $data['allData'] = $allData;
            $allAmt = DB::table('amount')->orderBy('amount','ASC')->get();
            $data['allAmt'] = $allAmt;
            $allTime = DB::table('time_slot')->orderBy('time','ASC')->get();
            $data['allTime'] = $allTime;
            $allPackages = DB::table('package')->where('joints',$appDetails->joints)->get();
            $data['allPackage'] = $allPackages;
            return view('appointment.perDayEntry',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }
 
    public function perDayVisitAppointment(Request $request){
        try{
            $appId = $request->Appontment_id_day;
            $appointmentDetails = appointmentDetails($appId);
            $appDate = $appointmentDetails->appointment_date;
            $patientId = $appointmentDetails->user_id;
            $userDetails = userDetails($patientId);
            $booked_date = $request->booked_date;
            $booked_time = $request->booked_time;
            $appServiceType = $appointmentDetails->app_service_type;
            if($request->therapist_id){
                $therapistId = $request->therapist_id;
            }else{
                $therapistId = userDetails($patientId)->therapist_id;
            }
            $baseAmount = userDetails($therapistId)->base_commision;
            $packageId = $appointmentDetails->package_type;
            $amount = $request->amount;
            $extraAmt = $request->extraAmt;
            $penalty = '';

            //Daily visit entry 
            $dailyEntry = new DailyEntry();
            $dailyEntry['appointment_id'] = $appId;
            $dailyEntry['therapist_id'] = $therapistId;
            $dailyEntry['package_id'] = $packageId;
            $dailyEntry['app_booked_date'] = $booked_date;
            $dailyEntry['app_booked_time'] = $booked_time;
            if(!empty($appServiceType) && ($appServiceType == '7')){
                $dailyEntry['amount'] = 0;
                $dailyEntry['extra_amount'] = 0;
            }else{
                $dailyEntry['amount'] = $amount;
                $dailyEntry['extra_amount'] = $extraAmt;
            }
            $dailyEntry['created_date'] = date('Y-m-d');
            $dailyEntry['penalty'] = $penalty;
            $dailyEntry['type'] = 1;
            $dailyEntry['service_type'] = $appointmentDetails->app_service_type;
            $dailyEntry['created_at'] = date('Y-m-d H:i:s');
            $dailyEntry->save();
            $dailyEntryId = $dailyEntry->id;

            $mobileNo = $userDetails->mobile;
            $name = $userDetails->name;
            if(!empty($mobileNo)){
                if($appServiceType == 6){
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                }else if($appServiceType == 7){
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Physio Team of Sant Parmanand Hospital. Thanks, Team Physio, SPH';
                }else if(($appServiceType == 9) || ($appServiceType == 1) || ($appServiceType == 8)){
                    $message = 'Dear '.$name.', Your home physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Team Capri. Thanks, CapriSpine Team';
                }else{
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                }
                
                $sendsms = $this->sendSMSMessage($message,$mobileNo);
            }

            //history
            $history = new AppointmentHistory();
            $history['appointment_id'] = $appId;
            $history['new_therapist'] = $therapistId;
            $history['item_id'] = $dailyEntryId;
            $history['reason'] = 'Per day visit entry for appointment';
            $history['amount'] = $amount;
            $history['created_by'] = Auth::User()->id;
            $history->save();
            
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editPerDayDailyEntry(Request $request){
        try{
            $id = $request->perDayId;
            $appId = $request->appointmentId;
            $appointmentDetails = appointmentDetails($appId);
            $appDate = $appointmentDetails->appointment_date;
            $patientId = $appointmentDetails->user_id;
            $booked_date = $request->booked_date;
            $booked_time = $request->booked_time;
            $appServiceType = $appointmentDetails->app_service_type;
            if($request->therapist_id){
                $therapistId = $request->therapist_id;
            }else{
                $therapistId = userDetails($patientId)->therapist_id;
            }
            $amount = $request->amount;
            $extraAmt = $request->extraAmt;
            $baseAmount = userDetails($therapistId)->base_commision;
            $data = array();
            $data['app_booked_date'] = $booked_date;
            $data['app_booked_time'] = $booked_time;
            $data['therapist_id'] = $therapistId;
            if(!empty($appServiceType) && ($appServiceType == '7')){
                $data['amount'] = 0;
                $data['extra_amount'] = 0;
            }else{
                if(!empty($amount)){
                    $data['amount'] = $amount;
                }
                if(!empty($extraAmt)){
                    $data['extra_amount'] = $extraAmt;
                }
            }
            DB::table('daily_entry')->where('id',$id)->update($data);
            
            $history = new AppointmentHistory();
            $history['appointment_id'] = $appId;
            $history['new_therapist'] = $therapistId;
            $history['item_id'] = $id;
            $history['reason'] = 'Per day visit entry for appointment';
            $history['created_by'] = Auth::User()->id;
            $history->save();

            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function getPerDayEntryDetails($id){
        $getData = DB::table('daily_entry')->where('id',$id)->first();
        return json_encode($getData);
    }

    public function updateTimeDailyEntry($id){
        try{
            $dailyEntryDetails = dailyEntryDetails($id);
            $appId = $dailyEntryDetails->appointment_id;
            $therapistId = $dailyEntryDetails->therapist_id;
            $booked_date = $dailyEntryDetails->app_booked_date;
            $booked_time = $dailyEntryDetails->app_booked_time;
            $time = date("H:i:s");
            $currentDate = date('Y-m-d');
            $firstDate = $dailyEntryDetails->app_booked_date;
            $appointmentDetails = appointmentDetails($appId);
            $appServiceType = $appointmentDetails->app_service_type;
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
            $visitCreatedDate = $dailyEntryDetails->app_booked_date;
            // $visitCreatedTime = $dailyEntryDetails->app_booked_time;
            $vCreatedTime = $dailyEntryDetails->created_at;
            $vCrDate = explode(' ', $vCreatedTime);
            $vCreatedDate = $vCrDate[0];
            $visitCreatedTime = $vCrDate[1];

            if(!empty($appServiceType) && ($appServiceType == 7)){
                // For only IPD Patient
                $updateData['visit_type'] = 'ipd';
                DB::table('daily_entry')->where('id',$id)->update($updateData);
            }elseif(!empty($appServiceType) && (($appServiceType == 9) || ($appServiceType == 1) || ($appServiceType == 8))){
                // for Home care patient only
                $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$id)->first();
                $packId = $dailyEntryPackageDetails->package_id;
                $privateBaseSalary = 70;            //only for home care patients
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
                DB::table('daily_entry')->where('id',$id)->update($updateData);

                $amount = $dailyEntryDetails->amount;
                $extAmount = $dailyEntryDetails->extra_amount;
                $amount = $amount + $extAmount;     //if extra amount add in package visit
                $therapistAmt = ($privateBaseSalary / 100) * $amount;
                $capriAmt = $amount - $therapistAmt;
                $addAmountAcc = array();
                $addAmountAcc['visit_id'] = $id;
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
                $addAmountAcc['created_by'] = Auth::user()->id;
                DB::table('account')->insert($addAmountAcc);
                // account history 
                $appHistory = array();
                $appHistory['visit_id'] = $id;
                $appHistory['appointment_id'] = $appId;
                $appHistory['therapist_id'] = $therapistId;
                $appHistory['capri_account'] = $capriAmt;
                $appHistory['therapist_account'] = $therapistAmt;
                $appHistory['total_amount'] = $amount;
                $appHistory['remark'] = 'perday entry';
                $appHistory['created_by'] = Auth::user()->id;
                $appHistory['transection_status'] = '';
                $appHistory['transection_id'] = '';
                $appHistory['payment_date'] = date("Y-m-d");
                DB::table('account_history')->insert($appHistory);
            }else{
                // For OPD Patient
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
                DB::table('daily_entry')->where('id',$id)->update($updateData);

                $amount = $dailyEntryDetails->amount;
                $baseAmount = userDetails($therapistId)->base_commision;
                // on per day entry, total amount is greater then 1500 then share % in therapist account within 4 days
                if(!empty($booked_date) && !empty($amount)){
                    $next15Day = date('Y-m-d', strtotime($appDate. ' + 4 days'));
                    $totalVisitAmount = DB::table('daily_entry')->where('appointment_id',$appId)->where('type',1)->sum('amount');
                    if((strtotime($currentDate) <= strtotime($next15Day)) && ($totalVisitAmount >= '1500')){
                        // only consultation amount send in capri account nd In therapist account of base amount % share
                        $addAmountAcc = array();
                        $addAmountAcc['visit_id'] = $id;
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
                        $addAmountAcc['transection_status'] = '';
                        $addAmountAcc['transection_id'] = '';
                        $addAmountAcc['payment_date'] = date("Y-m-d");
                        $addAmountAcc['created_by'] = Auth::user()->id;
                        DB::table('account')->insert($addAmountAcc);

                        // appointment history
                        $appHistory = array();
                        $appHistory['visit_id'] = $id;
                        $appHistory['appointment_id'] = $appId;
                        $appHistory['therapist_id'] = $therapistId;
                        $appHistory['capri_account'] = $totalVisitAmount - $amtamt;
                        $appHistory['therapist_account'] = $amtamt;
                        $appHistory['total_amount'] = $totalVisitAmount;
                        $appHistory['remark'] = 'Daily per day entry';
                        $appHistory['created_by'] = Auth::user()->id;
                        $appHistory['transection_status'] = '';
                        $appHistory['transection_id'] = '';
                        $appHistory['payment_date'] = date("Y-m-d");
                        DB::table('account_history')->insert($appHistory);
                    }elseif((strtotime($currentDate) <= strtotime($next15Day)) && ($totalVisitAmount <= '1500')){
                        // send all amount in capri account
                        $addAmountAcc = array();
                        $addAmountAcc['visit_id'] = $id;
                        $addAmountAcc['therapist_id'] = $therapistId;
                        $addAmountAcc['appointment_id'] = $appId;
                        $addAmountAcc['user_id'] = $patientId;
                        $addAmountAcc['capri_account'] = $totalVisitAmount;
                        $addAmountAcc['total_amount'] = $totalVisitAmount;
                        $addAmountAcc['flag'] = 'perday';
                        $addAmountAcc['transection_status'] = '';
                        $addAmountAcc['transection_id'] = '';
                        $addAmountAcc['payment_date'] = date("Y-m-d");
                        $addAmountAcc['created_by'] = Auth::user()->id;
                        DB::table('account')->insert($addAmountAcc);

                        // appointment history
                        $appHistory = array();
                        $appHistory['visit_id'] = $id;
                        $appHistory['appointment_id'] = $appId;
                        $appHistory['therapist_id'] = $therapistId;
                        $appHistory['capri_account'] = $totalVisitAmount;
                        $appHistory['therapist_account'] = '';
                        $appHistory['total_amount'] = $totalVisitAmount;
                        $appHistory['remark'] = 'Daily per day entry';
                        $appHistory['created_by'] = Auth::user()->id;
                        $appHistory['transection_status'] = '';
                        $appHistory['transection_id'] = '';
                        $appHistory['payment_date'] = date("Y-m-d");
                        DB::table('account_history')->insert($appHistory);
                    }else{
                        // send all amount in capri account
                        $addAmountAcc = array();
                        $addAmountAcc['visit_id'] = $id;
                        $addAmountAcc['therapist_id'] = $therapistId;
                        $addAmountAcc['appointment_id'] = $appId;
                        $addAmountAcc['user_id'] = $patientId;
                        $addAmountAcc['capri_account'] = $amount;
                        $addAmountAcc['total_amount'] = $amount;
                        $addAmountAcc['flag'] = 'perday';
                        $addAmountAcc['transection_status'] = '';
                        $addAmountAcc['transection_id'] = '';
                        $addAmountAcc['payment_date'] = date("Y-m-d");
                        $addAmountAcc['created_by'] = Auth::user()->id;
                        DB::table('account')->insert($addAmountAcc);

                        // appointment history
                        $appHistory = array();
                        $appHistory['visit_id'] = $id;
                        $appHistory['appointment_id'] = $appId;
                        $appHistory['therapist_id'] = $therapistId;
                        $appHistory['capri_account'] = $amount;
                        $appHistory['therapist_account'] = '';
                        $appHistory['total_amount'] = $amount;
                        $appHistory['remark'] = 'Daily per day entry';
                        $appHistory['created_by'] = Auth::user()->id;
                        $appHistory['transection_status'] = '';
                        $appHistory['transection_id'] = '';
                        $appHistory['payment_date'] = date("Y-m-d");
                        DB::table('account_history')->insert($appHistory);
                    }
                }
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function cancelPerdayVisit($id){
        try{
            $deletedata = DB::table('daily_entry')->where('id',$id)->delete();
            $amountData = DB::table('account')->where('visit_id',$id)->first();
            if($amountData){
                DB::table('account')->where('visit_id',$id)->delete();
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function cancelPackageVisit($id){
        try{
            $deletedata = DB::table('daily_entry')->where('id',$id)->delete();
            $amountData = DB::table('account')->where('visit_id',$id)->first();
            if($amountData){
                $result = DB::table('account')->where('visit_id',$id)->delete();
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function packageWiseVisitAppointment(Request $request){
        try{
            $appId = $request->Appontment_id_package;
            $appointmentDetails = appointmentDetails($appId);
            $patientId = $appointmentDetails->user_id;
            $appServiceType = $appointmentDetails->app_service_type;
            $userDetails = userDetails($patientId);
            $booked_date = $request->booked_date;
            $booked_time = $request->booked_time;
            $inTime = $request->inTime;
            $outTime = $request->outTime;
            if($request->extraAmount != ''){
                $extAmt = $request->extraAmount;
            }else{
                $extAmt = '';
            }
            if($request->therapist_id){
                $therapistId = $request->therapist_id;
            }else{
                $therapistId = userDetails($patientId)->therapist_id;
            }
            $packageId = $appointmentDetails->package_type;
            $jointName = $appointmentDetails->joints;

            //if package update then due days of package entries
            $appointmentDueDays = $appointmentDetails->due_package_days;
            if(($appointmentDueDays != 0) && ($appointmentDueDays != '')){
                $duePackages = $packageId - 1;
                //Daily visit entry
                $dailyEntry = array();
                $dailyEntry['appointment_id'] = $appId;
                $dailyEntry['therapist_id'] = $therapistId;
                $dailyEntry['package_id'] = $duePackages;
                $dailyEntry['app_booked_date'] = $booked_date;
                $dailyEntry['app_booked_time'] = $booked_time;
                $dailyEntry['extra_amount'] = $extAmt;
                $dailyEntry['type'] = 2;
                $dailyEntry['service_type'] = $appointmentDetails->app_service_type;
                $dailyEntry['created_date'] = date('Y-m-d');
                $dailyEntry['created_at'] = date('Y-m-d H:i:s');
                DB::table('daily_entry')->insert($dailyEntry);
            }else{
                //Daily visit entry
                $dailyEntry = array();
                $dailyEntry['appointment_id'] = $appId;
                $dailyEntry['therapist_id'] = $therapistId;
                $dailyEntry['package_id'] = $packageId;
                $dailyEntry['app_booked_date'] = $booked_date;
                $dailyEntry['app_booked_time'] = $booked_time;
                $dailyEntry['extra_amount'] = $extAmt;
                $dailyEntry['type'] = 2;
                $dailyEntry['service_type'] = $appointmentDetails->app_service_type;
                $dailyEntry['created_date'] = date('Y-m-d');
                $dailyEntry['created_at'] = date('Y-m-d H:i:s');
                DB::table('daily_entry')->insert($dailyEntry);
            }
            $mobileNo = $userDetails->mobile;
            $name = $userDetails->name;
            if(!empty($mobileNo)){
                if($appServiceType == 6){
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                }else if($appServiceType == 7){
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Physio Team of Sant Parmanand Hospital. Thanks, Team Physio, SPH';
                }else if(($appServiceType == 9) || ($appServiceType == 8) || ($appServiceType == 1)){
                    $message = 'Dear '.$name.', Your home physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Team Capri. Thanks, CapriSpine Team';
                }else{
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                }
                $sendsms = $this->sendSMSMessage($message,$mobileNo);
            }

            //history
            $history = new AppointmentHistory();
            $history['appointment_id'] = $appId;
            $history['reason'] = 'Package wise visit entry for appointment';
            $history['created_by'] = Auth::User()->id;
            $history->save();
            return redirect::back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateTimeDailyEntryForPackage($id){
        try{
            $dailyEntryDetails = dailyEntryDetails($id);
            $appId = $dailyEntryDetails->appointment_id;
            $therapistId = $dailyEntryDetails->therapist_id;
            $base_salary = userDetails($therapistId)->base_commision;
            $appDetails = appointmentDetails($appId);
            $appServiceType = $appDetails->app_service_type;
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
            // if visit create in same day and before acceptinting 4 hrs then it will be AW other wise it will be AV.
            // check patient AV or AW
            $userDetails = userDetails($patientId);
            $patientCreatedDate = $userDetails->created_at;
            $pCreatedDate = explode(' ', $patientCreatedDate);
            $patCreatedDate = $pCreatedDate[0];
            $visitCreatedDate = $dailyEntryDetails->app_booked_date;
            // $visitCreatedTime = $dailyEntryDetails->app_booked_time;
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
            $startingDateofDailyDetails = DailyEntry::where('appointment_id',$appId)->where('package_id',$packageId)->where('status','complete')->orderBy('id','ASC')->first();
            if($startingDateofDailyDetails){
                $startingDate = $startingDateofDailyDetails->app_booked_date;
                $packageDetails = packageDetails($packageId);
                $packageValidity = $packageDetails->validity;
                $nextValidityDate = date('Y-m-d', strtotime($startingDate. ' + '.$packageValidity.' days'));
            }else{
                $startingDate = date('Y-m-d');
                $packageDetails = packageDetails($packageId);
                $packageValidity = $packageDetails->validity;
                $nextValidityDate = date('Y-m-d', strtotime($startingDate. ' + '.$packageValidity.' days'));
            }
            
            if(strtotime($currentDate) <= strtotime($nextValidityDate)){
                // update due days in daily
                $totalDays = $days;
                $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->whereNull('secondFlag')->where('status','complete')->where('type',2)->count('id');

                if($workingDays == 0){
                    $dueDays = $totalDays - 1;
                }else{
                    $dueDays = $totalDays - ($workingDays + 1);
                }
                // use for avoid negative value in due days
                if($dueDays >= 0){
                    $dueDays = $dueDays;
                }else{
                    $dueDays = 0;
                }
                $dddd = packageSitting($appId,$packageId) + 1;
                $updateData['no_of_seats'] = $dddd;
                $updateData['due_days'] = $dueDays;
                $updateData['flag'] = '';
                DB::table('daily_entry')->where('id',$id)->update($updateData);
            }else{
                // limited days cross in package daily entry (validity cross convert it into back package)
                // update package with always 1st package
                // always convert into 1st package when expire your package validity
                $updateAppData = array();
                $changePackageId = 1;
                $updateAppData['package_type'] = $changePackageId;
                Appointment::where('id',$appId)->update($updateAppData);
                // update package id in last daily visit 
                $latestPckg = packageDetails($changePackageId);
                $ltstAmt = ($latestPckg->package_amount / $latestPckg->days);
                // $updatePVisit = array();
                $updateData['package_id'] = $changePackageId;
                $updateData['amount'] = $ltstAmt;
                // change daily entry with before 1 package details
                $newDays = packageDetails($changePackageId)->days;
                $totalDays = $newDays;
                $workingDays = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('flag','!=','')->where('status','complete')->where('amount','!=','')->where('type',2)->whereNull('secondFlag')->count('id');
                if($workingDays == 0){
                    $dueDays = $totalDays - 1;
                }else{
                    $dueDays = $totalDays - ($workingDays + 1);
                }
                // $noOfSeatsExp = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$changePackageId)->where('status','complete')->get();
                $noOfSeatsExp = 1;
                if($noOfSeatsExp > 0){
                    $updateData['no_of_seats'] = $noOfSeatsExp;
                    $updateData['due_days'] = $dueDays;
                }else{
                    $updateData['no_of_seats'] = '';
                    $updateData['due_days'] = '';
                }
                $updateData['package_id'] = $changePackageId;
                $updateData['flag'] = 'limited_days_cross';
                DB::table('daily_entry')->where('id',$id)->update($updateData);
                $noOfSeatsExp = $noOfSeatsExp + 1;
                $lastVisitId = $id - 1;
                // Call function for expiry module
                $checkData = $this->setExpirePackageStatus($appId,$lastVisitId);
            }
            
            if(($appServiceType == 9) || ($appServiceType == 1) || ($appServiceType == 8)){
                // For Home Care patient's service type
                // for package wise base % share to therapist account and due share to capri account
                $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$id)->first();
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
                $addAmountAcc['visit_id'] = $id;
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
                $addAmountAcc['created_by'] = Auth::user()->id;
                DB::table('account')->insert($addAmountAcc);
                // account history 
                $appHistory = array();
                $appHistory['visit_id'] = $id;
                $appHistory['appointment_id'] = $appId;
                $appHistory['therapist_id'] = $therapistId;
                $appHistory['capri_account'] = $capriAmt;
                $appHistory['therapist_account'] = $therapistAmt;
                $appHistory['total_amount'] = $amount;
                $appHistory['remark'] = 'package entry';
                $appHistory['created_by'] = Auth::user()->id;
                $appHistory['transection_status'] = '';
                $appHistory['transection_id'] = '';
                $appHistory['payment_date'] = date("Y-m-d");
                DB::table('account_history')->insert($appHistory);
            }else{
                // For OPD patient's
                // for package wise base % share to therapist account and due share to capri account
                $amount = $amount + $extAmount;     //if extra amount add in package visit

                // if 1st package complete after that on 2nd package incentive share to therapist
                $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$id)->first();
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
                $therapistAmt = ($base_salaries / 100) * $amount;
                $capriAmt = $amount - $therapistAmt;
                
                $addAmountAcc = array();
                $addAmountAcc['visit_id'] = $id;
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
                $addAmountAcc['created_by'] = Auth::user()->id;
                DB::table('account')->insert($addAmountAcc);
                // account history 
                $appHistory = array();
                $appHistory['visit_id'] = $id;
                $appHistory['appointment_id'] = $appId;
                $appHistory['therapist_id'] = $therapistId;
                $appHistory['capri_account'] = $capriAmt;
                $appHistory['therapist_account'] = $therapistAmt;
                $appHistory['total_amount'] = $amount;
                $appHistory['remark'] = 'package entry';
                $appHistory['created_by'] = Auth::user()->id;
                $appHistory['transection_status'] = '';
                $appHistory['transection_id'] = '';
                $appHistory['payment_date'] = date("Y-m-d");
                DB::table('account_history')->insert($appHistory);
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function setExpirePackageStatus($appId,$lastVisitId){
        try{
            $allData = DailyEntry::where('appointment_id',$appId)->where('type',2)->where('id','<=',$lastVisitId)->get();
            if(count($allData) > 0){
                foreach ($allData as $allVal) {
                    $dataUp = array();
                    $dataUp['secondFlag'] = 'expireVisit';
                    DailyEntry::where('id',$allVal->id)->update($dataUp);
                }
                return redirect()->back();
            }else{
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updatePackageWiseEntry(Request $request){
        try{
            // dd($request)->full();
            $id = $request->packageDayId;
            $packageDetails = DB::table('daily_entry')->where('id',$id)->first();
            $appId = $packageDetails->appointment_id;
            $appointmentDetails = appointmentDetails($appId);
            $patientId = $appointmentDetails->user_id;
            $booked_date = $request->booked_date;
            $booked_time = $request->booked_time;
            $inTime = $request->inTime;
            $outTime = $request->outTime;
            if($request->therapist_id){
                $therapist_id = $request->therapist_id;    
            }else{
                $therapist_id = userDetails($patientId)->therapist_id;
            }
            $amount = $request->extraAmount;
            $packageId = $appointmentDetails->package_type;
            $jointName = $appointmentDetails->joints;
            $checkVisitCount = DailyEntry::where('appointment_id',$appId)->where('status','complete')->count('id');
            
            if(!empty($inTime) && !empty($outTime)){
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
                    $ntTime = strtotime("+100 minutes", strtotime($inTime));     //10+30 minutes extra of 60 min
                    }else if($jointName == 'two_joint'){
                        $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10+30 minutes extra of 90 min
                    }else if($jointName == 'three_joint'){
                        $ntTime = strtotime("+160 minutes", strtotime($inTime));    //10+30 minutes extra of 120 min
                    }else if($jointName == 'neuro'){
                        $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10+30 minutes extra of 60 min
                    }
                }
                
                $nextTime = date('h:i:s', $ntTime);
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
                        $penalty = ($percentage / 100) * $extAmt;
                        $visitType = 'AW';
                    }else{
                        $visitType = 'AV';
                        $penalty = '';
                    }
                }
                $paymentMethod = $appointmentDetails->payment_method;
                $package_type = $appointmentDetails->package_type;
                if($paymentMethod === 'package_wise'){
                    $pAmt = packageDetails($package_type)->package_amount;
                    $days = packageDetails($package_type)->days;
                    $packageAmount = $pAmt / $days;
                }else{
                    $packageAmount = '';
                }
                $lastDailyEntry = DB::table('daily_entry')->where('appointment_id',$appId)->orderBy('id','DESC')->first();
                if($lastDailyEntry){
                    $dueDay = $lastDailyEntry->due_days - 1;
                }else{
                    $dueDay = 9;
                }
            }else{
                $packageAmount = '';
                $dueDay = '';
                $penalty = '';
                $visitType = '';
            }

            $data = array();
            $data['app_booked_date'] = $booked_date;
            $data['app_booked_time'] = $booked_time;
            $data['in_time'] = $inTime;
            $data['out_time'] = $outTime;
            $data['therapist_id'] = $therapist_id;
            if(!empty($amount)){
                $data['extra_amount'] = $amount;
            }
            $data['penalty'] = $penalty;
            $data['due_days'] = $dueDay;
            // $data['visit_type'] = $visitType;
            DB::table('daily_entry')->where('id',$id)->update($data);
            if($request->flag){
                $flag = $request->flag;
                if($flag == 'allVisit'){
                    return Redirect::to('all-daily-visits');
                }else{
                    return redirect()->back();
                }
            }else{
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function assignTreatmentRating(Request $request){
        try{
            date_default_timezone_set('Asia/Kolkata');
            $dailyEntryDetails = dailyEntryDetails($request->dailyEtnryId);
            $appId = $dailyEntryDetails->appointment_id;
            $therapistId = $dailyEntryDetails->therapist_id;
            $appDetails = appointmentDetails($appId);
            $userDetails = userDetails($appDetails->user_id);
            $patientReferBy = $userDetails->refer_by;
            $therapistDetails = userDetails($therapistId);
            $therapistReferCode = $therapistDetails->refer_code;
            $packageId = $appDetails->package_type;
            $packageDetails = packageDetails($packageId);
            $jointName = $appDetails->joints;
            $serviceType = $appDetails->app_service_type;
            $id = $request->dailyEtnryId;
            $rating = $request->treatmentRating;
            $amount = $dailyEntryDetails->amount;
            $inTime = $dailyEntryDetails->in_time;
            $outTime = date('H:i:s');
            $checkVisitCount = DailyEntry::where('appointment_id',$appId)->where('status','complete')->count('id');
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
                return redirect()->back();
            }else{
                $dailyEntryPackDetails = DailyEntry::where('appointment_id',$appId)->where('status','complete')->where('type',2)->whereNull('secondFlag')->orderBy('id','ASC')->first();
                if($dailyEntryPackDetails){
                    $checkpVisitCount = DailyEntry::where('appointment_id',$appId)->where('package_id',$dailyEntryPackDetails->package_id)->whereNull('secondFlag')->where('status','complete')->where('type',2)->count('id');
                    if(($checkpVisitCount > 0) && ($checkpVisitCount == $packageDetails->days) && !empty($therapistReferCode) && !empty($patientReferBy) && ($therapistReferCode == $patientReferBy)){
                        // add refer points
                        $cpPoint = DB::table('cpoint')->where('name','Package Complete')->first();
                        $cAmt = $cpPoint->amount;
                        $cPoint = $cpPoint->point;
                        $cPointId = $cpPoint->id;
                        $cpUserId = $userDetails->therapist_id;
                        $cpData = array();
                        $cpData['user_id'] = $cpUserId;
                        $cpData['other_user_id'] = $appDetails->user_id;
                        $cpData['cpoint_id'] = $cPointId;
                        $cpData['cp_point'] = $cPoint;
                        $cpData['cp_amount'] = $cAmt;
                        $cpData['type'] = 'credit';
                        $cpData['remark'] = 'Package Complete Therapist';
                        $cpData['appId'] = $appId;
                        $cpData['created_at'] = date('Y-m-d H:i:s');
                        DB::table('capri_point')->insert($cpData);
                        return redirect()->back();
                    }else{
                        return redirect()->back();
                    }
                }else{
                    return redirect()->back();
                }
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function assignPerDayTreatmentRating(Request $request){
        try{
            $id = $request->packageId;
            $rating = $request->treatmentRating;
            date_default_timezone_set('Asia/Kolkata');
            $data = array();
            $data['rating'] = $rating;
            $data['status'] = 'complete';
            $data['out_time'] = date('H:i:s');
            DB::table('daily_entry')->where('id',$id)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function complimentaryEntry($id){
        try{
            $data = array();
            $data['title'] = 'Complimentary daily Entry';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            $data['appointmentId'] = $id;
            $data['entryType'] = 'complimentary';

            $appDetails = appointmentDetails($id);
            $appServiceType = $appDetails->app_service_type;
            $userId = $appDetails->user_id;
            $userDetails = userDetails($userId);
            $userTherapist = $userDetails->therapist_id;
            $data['appServiceType'] = $appServiceType;
            $data['userTherapist'] = $userTherapist;
            $allData = DB::table('daily_entry')->where('appointment_id',$id)->where('type','3')->orderBy('app_booked_date','DESC')->get();
            $data['allData'] = $allData;
            $allAmt = DB::table('amount')->orderBy('amount','ASC')->get();
            $data['allAmt'] = $allAmt;
            $allTime = DB::table('time_slot')->orderBy('time','ASC')->get();
            $data['allTime'] = $allTime;
            $allPackages = DB::table('package')->where('joints',$appDetails->joints)->get();
            $data['allPackage'] = $allPackages;
            // dd($data);
            return view('appointment.complimentaryEntry',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function complimentaryVisitAppointment(Request $request){
        try{
            $appId = $request->Appontment_id_day;
            $appointmentDetails = appointmentDetails($appId);
            $appDate = $appointmentDetails->appointment_date;
            $patientId = $appointmentDetails->user_id;
            $userDetails = userDetails($patientId);
            $booked_date = $request->booked_date;
            $booked_time = $request->booked_time;
            $appServiceType = $appointmentDetails->app_service_type;
            if($request->therapist_id){
                $therapistId = $request->therapist_id;
            }else{
                $therapistId = userDetails($patientId)->therapist_id;
            }
            //$baseAmount = userDetails($therapistId)->base_commision;
            $packageId = $appointmentDetails->package_type;
            //$amount = $request->amount;
            // $extraAmt = $request->extraAmt;
            // $penalty = '';

            //Daily visit entry 
            $dailyEntry = new DailyEntry();
            $dailyEntry['appointment_id'] = $appId;
            $dailyEntry['therapist_id'] = $therapistId;
            $dailyEntry['package_id'] = $packageId;
            $dailyEntry['app_booked_date'] = $booked_date;
            $dailyEntry['app_booked_time'] = $booked_time;
            if(!empty($appServiceType) && ($appServiceType == '7')){
                $dailyEntry['amount'] = 0;
                $dailyEntry['extra_amount'] = 0;
            }else{
                $dailyEntry['amount'] = 0;
                $dailyEntry['extra_amount'] = 0;
            }
            $dailyEntry['created_date'] = date('Y-m-d');
            // $dailyEntry['penalty'] = $penalty;
            $dailyEntry['type'] = 3;        //3 for Complimentary
            $dailyEntry['service_type'] = $appointmentDetails->app_service_type;
            $dailyEntry['created_at'] = date('Y-m-d H:i:s');
            $dailyEntry->save();
            $dailyEntryId = $dailyEntry->id;

            $mobileNo = $userDetails->mobile;
            $name = $userDetails->name;
            if(!empty($mobileNo)){
                if($appServiceType == 6){
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                }else if($appServiceType == 7){
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Physio Team of Sant Parmanand Hospital. Thanks, Team Physio, SPH';
                }else if(($appServiceType == 9) || ($appServiceType == 1) || ($appServiceType == 8)){
                    $message = 'Dear '.$name.', Your home physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Team Capri. Thanks, CapriSpine Team';
                }else{
                    $message = 'Dear '.$name.', Your physio session dated '.$booked_date.', time '.$booked_time.' has been booked by Capri Spine. Thanks, CapriSpine Team';
                }
                
                $sendsms = $this->sendSMSMessage($message,$mobileNo);
            }

            //history
            $history = new AppointmentHistory();
            $history['appointment_id'] = $appId;
            $history['new_therapist'] = $therapistId;
            $history['item_id'] = $dailyEntryId;
            $history['reason'] = 'Complimentary visit entry for appointment';
            $history['amount'] = '0';
            $history['created_by'] = Auth::User()->id;
            $history->save();
            
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateTimeDailyForComplimentary($id){
        try{
            $dailyEntryDetails = dailyEntryDetails($id);
            $appId = $dailyEntryDetails->appointment_id;
            $therapistId = $dailyEntryDetails->therapist_id;
            $booked_date = $dailyEntryDetails->app_booked_date;
            $booked_time = $dailyEntryDetails->app_booked_time;
            $time = date("H:i:s");
            $currentDate = date('Y-m-d');
            $firstDate = $dailyEntryDetails->app_booked_date;
            $appointmentDetails = appointmentDetails($appId);
            $appServiceType = $appointmentDetails->app_service_type;
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
            $visitCreatedDate = $dailyEntryDetails->app_booked_date;
            // $visitCreatedTime = $dailyEntryDetails->app_booked_time;
            $vCreatedTime = $dailyEntryDetails->created_at;
            $vCrDate = explode(' ', $vCreatedTime);
            $vCreatedDate = $vCrDate[0];
            $visitCreatedTime = $vCrDate[1];

            if(!empty($appServiceType) && ($appServiceType == 7)){
                // For only IPD Patient
                $updateData['visit_type'] = 'ipd';
                DB::table('daily_entry')->where('id',$id)->update($updateData);
            }elseif(!empty($appServiceType) && (($appServiceType == 9) || ($appServiceType == 1) || ($appServiceType == 8))){
                // for Home care patient only
                $dailyEntryPackageDetails = DB::table('daily_entry')->where('id',$id)->first();
                $packId = $dailyEntryPackageDetails->package_id;
                $privateBaseSalary = 70;            //only for home care patients
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
                DB::table('daily_entry')->where('id',$id)->update($updateData);
            }else{
                // For OPD Patient
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
                DB::table('daily_entry')->where('id',$id)->update($updateData);
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function getPackageWiseEntry($id){
        try{
            $currentDate = date('Y-m-d');
            $data = array();
            $data['title'] = 'Package wise Entry';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            $data['appointmentId'] = $id;
            $appDetails = appointmentDetails($id);
            $appServiceType = $appDetails->app_service_type;
            $packageId = $appDetails->package_type;
            $userId = $appDetails->user_id;
            $userDetails = userDetails($userId);
            $userTherapist = $userDetails->therapist_id;
            $data['userTherapist'] = $userTherapist;
            $allData = DB::table('daily_entry')->where('appointment_id',$id)->where('type','2')->orderBy('app_booked_date','DESC')->get();
            $data['allData'] = $allData;
            $allTime = DB::table('time_slot')->orderBy('time','ASC')->get();
            $data['allTime'] = $allTime;
            $amount = DB::table('amount')->orderBy('amount','ASC')->get();
            $data['amount'] = $amount;
            // check package finish or not
            $checkLastDate = DB::table('daily_entry')->where('appointment_id',$id)->where('package_id',$packageId)->where('status','complete')->count('id');
            $packageDays = packageDetails($packageId)->days;
            if($packageDays <= $checkLastDate){
                $lastDateFlag = 'true';
            }else{
                $lastDateFlag = 'false';
            }
            // today appoint only 3 visits
            $twoMoreCondition = DB::table('daily_entry')->where('appointment_id',$id)->where(DB::raw("(DATE_FORMAT(created_date,'%Y-%m-%d'))"),$currentDate)->get();
            if(count($twoMoreCondition) < 3){
                $condFlag = 'false';
            }else{
                $condFlag = 'true';
            }
            $data['condFlag'] = $condFlag;

            $data['lastDateFlag'] = $lastDateFlag;
            $totalVisitDays = packageTotalVisitDays($id,$packageId);
            if($totalVisitDays >= 9){
                $flagCheck = 'true';
            }else{
                $flagCheck = '';
            }
            $data['flagCheck'] = $flagCheck;
            $invoiceData = DB::table('invoice')->where('appointment_id',$id)->where('package_id',$packageId)->orderBy('id','DESC')->first();
            if(!empty($invoiceData)){
                $data['invoiceData'] = $invoiceData;
            }else{
                $data['invoiceData'] = '';
            }
            return view('appointment.packageWiseEntry', $data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function packageWiseDetails($id){
        $getData = DB::table('daily_entry')->where('id',$id)->first();
        return json_encode($getData);
    }

    public function packageWiseJointDetails($jointName){
        $getData = DB::table('package')->where('joints',$jointName)->get();
        return json_encode($getData);
    }

    public function allDailyVisits(Request $request){
        try{
            $data = array();
            $data['title'] = 'All Entry Visits';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allvisit';
            $currentDate = date('Y-m-d');
            if(Auth::user()->user_type == 'superadmin'){
                // $allBranch = DB::table('location')->get();
                $allTherapist = User::where('user_type',5)->where('status','active')->get();
                $allPatient = User::where('user_type',3)->where('status','active')->get();
            }else{
                // $allBranch = DB::table('location')->where('location.id',Auth::User()->branch)->get();
                $allTherapist = User::where('user_type',5)->where('status','active')->where('branch',Auth::User()->branch)->get();
                $allPatient = User::where('user_type',3)->where('status','active')->where('branch',Auth::User()->branch)->get();
            }
            // $data['allBranch'] = $allBranch;
            $data['allTherapist'] = $allTherapist;
            $data['allPatient'] = $allPatient;
            $allTime = DB::table('time_slot')->orderBy('time','ASC')->get();
            $data['allTime'] = $allTime;
            $allAmt = DB::table('amount')->orderBy('amount','ASC')->get();
            $data['allAmt'] = $allAmt;
            $allServiceType = DB::table('service')->get();
            $data['allServiceType'] = $allServiceType;

            if(!empty($request->therapistName) || !empty($request->patientName) || !empty($request->branch) || !empty($request->to_date) || !empty($request->from_date) || !empty($request->appointmentType) || !empty($request->monthName) || !empty($request->yearName)  || !empty($request->serviceType)){
                if(Auth::user()->user_type == 'superadmin'){
                    $query = DB::table('daily_entry')->join('appointment','appointment.id','=','daily_entry.appointment_id');
                    if(!empty($request->therapistName)){
                    $query = $query->where('daily_entry.therapist_id',$request->therapistName);
                    }
                    if(!empty($request->patientName)){
                        $query = $query->where('appointment.user_id',$request->patientName);
                    }
                    if(!empty($request->monthName)){
                        $query = $query->whereMonth('daily_entry.app_booked_date',$request->monthName);
                    }
                    if(!empty($request->yearName)){
                        $query = $query->whereYear('daily_entry.app_booked_date',$request->yearName);
                    }
                    if(!empty($request->from_date)){
                        $query = $query->where('daily_entry.app_booked_date', '>=', $request->from_date);
                    }
                    if(!empty($request->to_date)){
                        $query = $query->where('daily_entry.app_booked_date', '<=', $request->to_date);
                    }
                    if(!empty($request->from_date) && !empty($request->to_date)){
                        $query = $query->where('daily_entry.app_booked_date', '>=', $request->from_date)->where('daily_entry.app_booked_date', '<=', $request->to_date);
                    }
                    if(!empty($request->appointmentType)){
                        $query = $query->where('type',$request->appointmentType);
                    }
                    if(!empty($request->serviceType)){
                        $query = $query->where('appointment.app_service_type',$request->serviceType);
                    }
                    $results = $query->select('daily_entry.id as id','daily_entry.appointment_id as appointment_id','daily_entry.package_id as package_id','daily_entry.therapist_id as therapistId','daily_entry.app_booked_date as app_booked_date','daily_entry.app_booked_time as app_booked_time','daily_entry.in_time as in_time','daily_entry.out_time as out_time','daily_entry.visit_type as visit_type','daily_entry.amount as amount','daily_entry.extra_amount as extra_amount','daily_entry.penalty as penalty','daily_entry.type as type','daily_entry.rating as rating','daily_entry.no_of_seats as no_of_seats','daily_entry.total_seats as total_seats','daily_entry.due_days as due_days','daily_entry.status as status')->get();
                    $allData = $results;
                }else{
                    $query = DB::table('daily_entry')->join('users','users.id','=','daily_entry.therapist_id')->join('appointment','appointment.id','=','daily_entry.appointment_id')->where('users.branch',Auth::User()->branch);
                    if(!empty($request->therapistName)){
                    $query = $query->where('daily_entry.therapist_id',$request->therapistName);
                    }
                    if(!empty($request->patientName)){
                        $query = $query->join('appointment','appointment.id','=','daily_entry.appointment_id')->where('appointment.user_id',$request->patientName);
                    }
                    if(!empty($request->monthName)){
                        $query = $query->whereMonth('daily_entry.app_booked_date',$request->monthName);
                    }
                    if(!empty($request->yearName)){
                        $query = $query->whereYear('daily_entry.app_booked_date',$request->yearName);
                    }
                    if(!empty($request->from_date)){
                        $query = $query->where('daily_entry.app_booked_date', '>=', $request->from_date);
                    }
                    if(!empty($request->to_date)){
                        $query = $query->where('daily_entry.app_booked_date', '<=', $request->to_date);
                    }
                    if(!empty($request->serviceType)){
                        $query = $query->where('appointment.app_service_type',$request->serviceType);
                    }
                    $results = $query->select('daily_entry.id as id','daily_entry.appointment_id as appointment_id','daily_entry.package_id as package_id','daily_entry.therapist_id as therapistId','daily_entry.app_booked_date as app_booked_date','daily_entry.app_booked_time as app_booked_time','daily_entry.in_time as in_time','daily_entry.out_time as out_time','daily_entry.visit_type as visit_type','daily_entry.amount as amount','daily_entry.extra_amount as extra_amount','daily_entry.penalty as penalty','daily_entry.type as type','daily_entry.rating as rating','daily_entry.no_of_seats as no_of_seats','daily_entry.total_seats as total_seats','daily_entry.due_days as due_days','daily_entry.status as status')->get();
                    $allData = $results;
                }
            }else{
                if(Auth::user()->user_type == 'superadmin'){
                    $allData = DailyEntry::where('app_booked_date',$currentDate)->select('id','appointment_id','package_id','therapist_id as therapistId','app_booked_date','app_booked_time','in_time','out_time','visit_type','amount','extra_amount','penalty','type','rating','no_of_seats','total_seats','due_days','status')->get();
                }else{
                    $allData = DB::table('daily_entry')->join('users','users.id','=','daily_entry.therapist_id')->where('users.branch',Auth::User()->branch)->where('daily_entry.app_booked_date',$currentDate)->select('daily_entry.id as id','daily_entry.appointment_id as appointment_id','daily_entry.package_id as package_id','users.id as therapistId','daily_entry.app_booked_date as app_booked_date','daily_entry.app_booked_time as app_booked_time','daily_entry.in_time as in_time','daily_entry.out_time as out_time','daily_entry.visit_type as visit_type','daily_entry.amount as amount','daily_entry.extra_amount as extra_amount','daily_entry.penalty as penalty','daily_entry.type as type','daily_entry.rating as rating','daily_entry.no_of_seats as no_of_seats','daily_entry.total_seats as total_seats','daily_entry.due_days as due_days','daily_entry.status as status')->get();
                }
            }
            $data['allData'] = $allData;
            return view('appointment.allVisits',$data)->with('no',1);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function checkTherapistAttendance($appId){
        $currentDate = date('Y-m-d');
        $appDetails = appointmentDetails($appId);
        $userId = $appDetails->user_id;
        $userDetails = userDetails($userId);
        $therapistId = $userDetails->therapist_id;
        // dd($therapistId);
        if($therapistId){
            $checkData = DB::table('attendance')->where('therapist_id',$therapistId)->where('date',$currentDate)->first();
            if($checkData){
                $response = 'true';
            }else{
                $response = 'false';
            }
        }else{
            $response = 'notavailable';
        }
        return json_encode($response);
    }

    public function appointmentPaymentStatus($appId){
        $checkData = Appointment::where('id',$appId)->first();
        if($checkData){
            $payment_status = $checkData->payment_status;
            if($payment_status == 'pending'){
                $response = 'false';
            }else{
                $response = 'true';
            }
        }
        return json_encode($response);
    }

    public function checkBookAppointment($appId){
        $currDate = date('Y-m-d');
        $checkDailyEntry = DB::table('daily_entry')->where('appointment_id',$appId)->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $currDate)->get();
        if(count($checkDailyEntry) > 2){
            $result = 'false';
        }else{
            $result = 'true';
        }
        return json_encode($result);
    }

    public function convertDailyVisit(Request $request){
        try{
            $appType = $request->appType;
            $packageType = $request->packageType;
            $visitId = $request->visitId;
            if(($appType == 'PackageType') && (!empty($visitId))){
                $visitDetails = dailyEntryDetails($visitId);
                $appId = $visitDetails->appointment_id;
                $therapisId = $visitDetails->therapist_id;
                $appDetails = appointmentDetails($appId);
                $userId = $appDetails->user_id;
                $type = $visitDetails->type;
                $packageDetails = packageDetails($packageType);
                $accountDetails = DB::table('account')->where('appointment_id',$appId)->where('user_id',$userId)->orderBy('id','DESC')->first();
                if($accountDetails){
                    $result = DB::table('account')->where('appointment_id',$appId)->where('user_id',$userId)->orderBy('id','DESC')->delete();
                    $visitUpdate = array();
                    $visitUpdate['type'] = 2;
                    $packAmt = $packageDetails->package_amount;
                    $packDay = $packageDetails->days;
                    $finalAmt = $packAmt / $packDay;
                    $visitUpdate['amount'] = $finalAmt;
                    $visitUpdate['package_id'] = $packageType;
                    $visitUpdate['total_seats'] = totalPackageDueDays($appId);
                    $visitUpdate['no_of_seats'] = packageSitting($appId,$packageType) + 1;
                    $visitUpdate['due_days'] = $packDay - 1;
                    DailyEntry::where('id',$visitId)->update($visitUpdate);

                    $appUpdate = array();
                    $appUpdate['payment_method'] = 'package_wise';
                    $appUpdate['package_type'] = $packageType;
                    Appointment::where('id',$appId)->update($appUpdate);

                    $accountUpdate = array();
                    $accountUpdate['therapist_id'] = $therapisId;
                    $accountUpdate['appointment_id'] = $appId;
                    $accountUpdate['user_id'] = $userId;
                    $accountUpdate['capri_account'] = $finalAmt;
                    $accountUpdate['therapist_account'] = '';
                    $accountUpdate['total_amount'] = $finalAmt;
                    $accountUpdate['flag'] = 'package';
                    $accountUpdate['transection_status'] = '';
                    $accountUpdate['transection_id'] = '';
                    $accountUpdate['payment_date'] = date('Y-m-d');
                    $accountUpdate['created_by'] = Auth::User()->id;
                    DB::table('account')->insert($accountUpdate);

                    $accountHist = array();
                    $accountHist['therapist_id'] = $therapisId;
                    $accountHist['appointment_id'] = $appId;
                    $accountHist['remark'] = 'Update visit perday into package';
                    $accountHist['capri_account'] = $finalAmt;
                    $accountHist['therapist_account'] = '';
                    $accountHist['total_amount'] = $finalAmt;
                    $accountHist['transection_status'] = '';
                    $accountHist['transection_id'] = '';
                    $accountHist['payment_date'] = date('Y-m-d');
                    DB::table('account_history')->insert($accountHist);
                }else{
                    $visitUpdate = array();
                    $visitUpdate['type'] = 2;
                    $packAmt = $packageDetails->package_amount;
                    $packDay = $packageDetails->days;
                    $finalAmt = $packAmt / $packDay;
                    $visitUpdate['amount'] = $finalAmt;
                    $visitUpdate['package_id'] = $packageType;
                    $visitUpdate['total_seats'] = totalPackageDueDays($appId);
                    $visitUpdate['no_of_seats'] = packageSitting($appId,$packageType) + 1;
                    $visitUpdate['due_days'] = $packDay - 1;
                    DailyEntry::where('id',$visitId)->update($visitUpdate);

                    $appUpdate = array();
                    $appUpdate['payment_method'] = 'package_wise';
                    $appUpdate['package_type'] = $packageType;
                    Appointment::where('id',$appId)->update($appUpdate);

                    $accountUpdate = array();
                    $accountUpdate['therapist_id'] = $therapisId;
                    $accountUpdate['appointment_id'] = $appId;
                    $accountUpdate['user_id'] = $userId;
                    $accountUpdate['capri_account'] = $finalAmt;
                    $accountUpdate['therapist_account'] = '';
                    $accountUpdate['total_amount'] = $finalAmt;
                    $accountUpdate['flag'] = 'package';
                    $accountUpdate['transection_status'] = '';
                    $accountUpdate['transection_id'] = '';
                    $accountUpdate['payment_date'] = date('Y-m-d');
                    $accountUpdate['created_by'] = Auth::User()->id;
                    DB::table('account')->insert($accountUpdate);

                    $accountHist = array();
                    $accountHist['therapist_id'] = $therapisId;
                    $accountHist['appointment_id'] = $appId;
                    $accountHist['remark'] = 'Update visit perday into package';
                    $accountHist['capri_account'] = $finalAmt;
                    $accountHist['therapist_account'] = '';
                    $accountHist['total_amount'] = $finalAmt;
                    $accountHist['transection_status'] = '';
                    $accountHist['transection_id'] = '';
                    $accountHist['payment_date'] = date('Y-m-d');
                    DB::table('account_history')->insert($accountHist);
                }
                return redirect('all-appointment');
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function approvedPendingPatientVisit($id){
        try{
            $checkVisit = DailyEntry::where('id',$id)->where('status','approval_pending')->first();
            if($checkVisit){
                $data = array();
                $data['status'] = '';
                DailyEntry::where('id',$id)->update($data);
                $appDetails = appointmentDetails($checkVisit->appointment_id);
                $serviceType = $appDetails->app_service_type;
                $userDetails = userDetails($appDetails->user_id);
                $name = $userDetails->name;
                $mobileNo = $userDetails->mobile;
                $visitDate = $checkVisit->app_booked_date;
                $visitTime = $checkVisit->app_booked_time;
                if(!empty($mobileNo)){
                    if($serviceType == 6){
                        $message = 'Dear '.$name.', Your physio session dated '.$visitDate.', time '.$visitTime.' has been approved by Team Capri. Thanks, CapriSpine Team';
                    }else if($serviceType == 7){
                        $message = 'Dear '.$name.', Your physio session dated '.$visitDate.', time '.$visitTime.' has been approved by Physio Team of Sant Parmanand Hospital. Thanks, Team Physio, SPH';
                    }else if($serviceType == 9){
                        $message = 'Dear '.$name.', Your home physio session dated '.$visitDate.', time '.$visitTime.' has been approved by Team Capri. Thanks, CapriSpine Team';
                    }else{
                        $message = 'Dear '.$name.', Your physio session dated '.$visitDate.', time '.$visitTime.' has been approved by Team Capri. Thanks, CapriSpine Team';
                    }
                    
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }
                return Redirect()->back();
            }else{
                return Redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function dailyVisitListExport(){
        Excel::create('Excel_Report', function($excel){
            $excel->sheet('All', function($sheet){
            $sheet->row(1, array('Id','Patient Name','Therapist Name','Visit Type','Booked Date','Booked Time','In Time','Out Time','Difference','Amount','Visit Type','Extra Amount','Total Seats','No of Seats','Remaining Seats'));
            $currentDate = date('Y-m-d');
            if(Auth::user()->user_type == 'superadmin'){
                $data = DailyEntry::where('app_booked_date',$currentDate)->select('id','appointment_id','package_id','therapist_id as therapistId','app_booked_date','app_booked_time','in_time','out_time','visit_type','amount','extra_amount','penalty','type','rating','no_of_seats','total_seats','due_days')->get();
            }else{
                $data = DB::table('daily_entry')->join('users','users.id','=','daily_entry.therapist_id')->where('users.branch',Auth::User()->branch)->where('daily_entry.app_booked_date',$currentDate)->select('daily_entry.id as id','daily_entry.appointment_id as appointment_id','daily_entry.package_id as package_id','users.id as therapistId','daily_entry.app_booked_date as app_booked_date','daily_entry.app_booked_time as app_booked_time','daily_entry.in_time as in_time','daily_entry.out_time as out_time','daily_entry.visit_type as visit_type','daily_entry.amount as amount','daily_entry.extra_amount as extra_amount','daily_entry.penalty as penalty','daily_entry.type as type','daily_entry.rating as rating','daily_entry.no_of_seats as no_of_seats','daily_entry.total_seats as total_seats','daily_entry.due_days as due_days')->get();
            }
            $count = 2;
            foreach ($data as $data_item) {
              $patientName = userDetails(appointmentDetails($data_item->appointment_id)->user_id)->name;
              $therapistName = userDetails($data_item->therapistId)->name;
              if($data_item->type == 1){
                $packageType = 'Perday Visit';
              }else{
                $packageType = 'Package wise visit';
              } 
              if(!empty($data_item->in_time) && !empty($data_item->out_time)){
                $startTime = new DateTime($data_item->in_time);
                $endTime = new DateTime($data_item->out_time);
                $getDiff = $startTime->diff($endTime);
                $difference = $getDiff->format("%H:%I:%S");
              }else{
                $difference = '-';
              }
              $sheet->appendRow($count++, array($data_item->id,$patientName,$therapistName,$packageType,$data_item->app_booked_date,$data_item->app_booked_time,$data_item->in_time,$data_item->out_time,$difference,$data_item->amount,$data_item->visit_type,$data_item->extra_amount,$data_item->total_seats,$data_item->no_of_seats,$data_item->due_days));
            }
        });
      })->download('xls');
      return TRUE;
    }

    public function dailyVisitPerdayListExport($id){
        Excel::create('Excel_Report', function($excel) use($id){
            $excel->sheet('All', function($sheet) use($id){
            $sheet->row(1, array('Id','Patient Name','Therapist Name','Booked Date','Booked Time','In Time','Out Time','Difference','Amount','Visit Type','Extra Amount','Total Seats','No of Seats','Remaining Seats','Rating'));
            $currentDate = date('Y-m-d');
            $data = DB::table('daily_entry')->where('appointment_id',$id)->where('type','1')->orderBy('app_booked_date','DESC')->get();
            $count = 2;
            foreach ($data as $data_item) {
              $patientName = userDetails(appointmentDetails($data_item->appointment_id)->user_id)->name;
              $therapistName = userDetails($data_item->therapist_id)->name; 
              if(!empty($data_item->in_time) && !empty($data_item->out_time)){
                $startTime = new DateTime($data_item->in_time);
                $endTime = new DateTime($data_item->out_time);
                $getDiff = $startTime->diff($endTime);
                $difference = $getDiff->format("%H:%I:%S");
              }else{
                $difference = '-';
              }
              $sheet->appendRow($count++, array($data_item->id,$patientName,$therapistName,$data_item->app_booked_date,$data_item->app_booked_time,$data_item->in_time,$data_item->out_time,$difference,$data_item->amount,$data_item->visit_type,$data_item->extra_amount,$data_item->total_seats,$data_item->no_of_seats,$data_item->due_days,$data_item->rating));
            }
        });
      })->download('xls');
      return TRUE;
    }

    public function dailyVisitPackageListExport($id){
        Excel::create('Excel_Report', function($excel) use($id){
            $excel->sheet('All', function($sheet) use($id){
            $sheet->row(1, array('Id','Patient Name','Therapist Name','Package Name','Booked Date','Booked Time','In Time','Out Time','Difference','Amount','Visit Type','Extra Amount','Total Seats','No of Seats','Remaining Seats','Rating'));
            $currentDate = date('Y-m-d');
            $data = DB::table('daily_entry')->where('appointment_id',$id)->where('type','2')->orderBy('app_booked_date','DESC')->get();
            $count = 2;
            foreach ($data as $data_item) {
              $patientName = userDetails(appointmentDetails($data_item->appointment_id)->user_id)->name;
              $therapistName = userDetails($data_item->therapist_id)->name; 
              if(!empty($data_item->in_time) && !empty($data_item->out_time)){
                $startTime = new DateTime($data_item->in_time);
                $endTime = new DateTime($data_item->out_time);
                $getDiff = $startTime->diff($endTime);
                $difference = $getDiff->format("%H:%I:%S");
              }else{
                $difference = '-';
              }
              if($data_item->type == '1'){
                $packageName = 'Per day visit'; 
              }else{
                $packageName = 'Package wise visit'.(packageDetails($data_item->package_id)->name);
              }
              $sheet->appendRow($count++, array($data_item->id,$patientName,$therapistName,$packageName,$data_item->app_booked_date,$data_item->app_booked_time,$data_item->in_time,$data_item->out_time,$difference,$data_item->amount,$data_item->visit_type,$data_item->extra_amount,$data_item->total_seats,$data_item->no_of_seats,$data_item->due_days,$data_item->rating));
            }
        });
      })->download('xls');
      return TRUE;
    }

    public function googleRankingNotification($id){
        try{
            $appDetails = appointmentDetails($id);
            $patientId = $appDetails->user_id;
            $userDetails = userDetails($patientId);
            $mobileNo = $userDetails->mobile;
            $name = $userDetails->name;
            $branch = $userDetails->branch;
            // send sms for Google Ranking
            if(!empty($mobileNo)){
                if($branch == 1){
                    // Gurgaon
                    $link = 'https://bit.ly/2YECUp8';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else if(($branch == 2)){
                    // Jagrati Enclave (Karkardooma)
                    $link = 'https://bit.ly/2KdCnm6';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else if($branch == 4){
                    // Sant Parmanand (Civil Line)
                    $link = 'https://bit.ly/2GIzUh5';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else if($branch == 3){
                    // Greated Kailash
                    $link = 'https://bit.ly/2T1Uo9r';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else if($branch == 5){
                    // Pitampura, Delhi
                    $link = 'https://bit.ly/2KakyEn';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else if($branch == 6){
                    // Karkardooma
                    $link = 'https://bit.ly/2MAMKlo';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else if($branch == 9){
                    // Noida
                    $link = 'https://bit.ly/2ZqHfcE';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }else{
                    // Jagrati Enclave
                    $link = 'https://bit.ly/2MAMKlo';
                    $message = 'Dear '.$name.', We would love to hear your feedback and we would be highly grateful if you could take a couple of minutes to write a quick Google review for us. To submit your review, simply click the link below: '.$link.'  Regards, Team Capri';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }
            }
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function generateReceipt($id){
        try{
            $data = array();
            $data['title'] = 'Invoice';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            $dailyDetails = dailyEntryDetails($id);
            $appId = $dailyDetails->appointment_id;
            $appDetails = appointmentDetails($appId);
            $userId = $appDetails->user_id;
            $userDetails = userDetails($userId);
            $data['name'] = $userDetails->name;
            $data['registration_no'] = $userDetails->registration_no;
            $data['id'] = $dailyDetails->id;
            $data['amount'] = $dailyDetails->amount;
            $invoiceData = DB::table('invoice')->where('visit_id',$id)->first();
            $data['reference_no'] = $invoiceData->check_or_ref_no;
            $data['payment_type'] = $invoiceData->payment_type;
            $data['bankName'] = $invoiceData->bank;
            if($dailyDetails->package_id){
                $packageDetails = packageDetails($dailyDetails->package_id);
                $data['treatment_days'] = $packageDetails->days;
            }else{
                $data['treatment_days'] = '';
            }
            return view('receipt.invoiceReceipt',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveInvoiceDetailsForPerday(Request $request){
        try{
            $paymentType = $request->paymentType;
            $reference_no = $request->reference_no;
            $bank = $request->bank;
            $amount = $request->amount;
            $visitId = $request->visitId;
            $dailyDetails = dailyEntryDetails($visitId);
            $appDetails = appointmentDetails($dailyDetails->appointment_id);
            $userDetails = userDetails($appDetails->user_id);
            $data = array();
            $data['visit_id'] = $visitId;
            $data['appointment_id'] = $dailyDetails->appointment_id;
            if($dailyDetails->package_id){
                $data['package_id'] = $dailyDetails->package_id;
            }else{
                $data['package_id'] = '';
            }
            $data['user_id'] = $appDetails->user_id;
            $data['registration_no'] = $userDetails->registration_no;
            $data['name'] = $userDetails->name;
            $data['branch_id'] = $userDetails->branch;
            $data['amount'] = $amount;
            $data['check_or_ref_no'] = $reference_no;
            $data['payment_type'] = $paymentType;
            if($dailyDetails->type == 1){
                $data['treatment_days'] = 1;
            }else{
                $data['treatment_days'] = packageDetails($dailyDetails->package_id)->days;
            }
            $data['joint'] = $appDetails->joints;
            if($dailyDetails->type == 1){
                $data['amountType'] = 'perday';
            }else{
                $data['amountType'] = 'package';
            }
            $data['invoice_type'] = 'package';
            $data['bank'] = $bank;
            $data['type'] = '';
            $data['date'] = date('Y-m-d');
            $data['created_by'] = Auth::User()->id;
            DB::table('invoice')->insert($data);
            // invoice generated
            $updateVisit = array();
            $updateVisit['invoice'] = 'generated';
            DB::table('daily_entry')->where('id',$visitId)->update($updateVisit);

            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveInvoiceDetailsForPackage(Request $request){
        try{
            $appId = $request->appId;
            $paymentType = $request->paymentType;
            $packages = $request->packages;
            $reference_no = $request->reference_no;
            $bank = $request->bank;
            $appDetails = appointmentDetails($appId);
            $userDetails = userDetails($appDetails->user_id);
            $packageDetails = packageDetails($packages);
            $invoiceData = array();
            $invoiceData['package_id'] = $packages;
            $invoiceData['user_id'] = $appDetails->user_id;
            $invoiceData['appointment_id'] = $appId;
            $invoiceData['registration_no'] = $userDetails->registration_no;
            $invoiceData['name'] = $userDetails->name;
            $invoiceData['branch_id'] = $userDetails->branch;
            $invoiceData['amount'] = $packageDetails->package_amount;
            $invoiceData['check_or_ref_no'] = $reference_no;
            $invoiceData['payment_type'] = $paymentType;
            $invoiceData['treatment_days'] = $packageDetails->days;
            $invoiceData['joint'] = $appDetails->joints;
            $invoiceData['amountType'] = 'package';
            $invoiceData['invoice_type'] = 'package';
            $invoiceData['bank'] = $bank;
            $invoiceData['date'] = date('Y-m-d');
            DB::table('invoice')->insert($invoiceData);

            // update in appointment table
            $appData = array();
            $appData['payment_method'] = 'package_wise';
            $appData['package_type'] = $packages;
            $appData['payment_status'] = 'approved';
            Appointment::where('id',$appId)->update($appData);

            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function generateReceiptPackage($id){
        try{
            $data = array();
            $data['title'] = 'Invoice';
            $data['masterclass'] = 'appointment';
            $data['class'] = 'allapp';
            $appDetails = appointmentDetails($id);
            $packageDetails = packageDetails($appDetails->package_type);
            $userId = $appDetails->user_id;
            $userDetails = userDetails($userId);
            $data['name'] = $userDetails->name;
            $data['registration_no'] = $userDetails->registration_no;
            $data['amount'] = $packageDetails->package_amount;
            $invoiceData = DB::table('invoice')->where('appointment_id',$id)->where('package_id',$appDetails->package_type)->orderBy('id','DESC')->first();
            if($invoiceData){
                $data['reference_no'] = $invoiceData->check_or_ref_no;
                $data['payment_type'] = $invoiceData->payment_type;
                $data['bankName'] = $invoiceData->bank;
                $data['id'] = $invoiceData->id;
            }else{
                $data['reference_no'] = '';
                $data['payment_type'] = '';
                $data['bankName'] = '';
                $data['id'] = '';
            }
            
            $data['treatment_days'] = $packageDetails->days;
            return view('receipt.invoiceReceipt',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    // public function approvedReportForPatient($appId){
    //     // try{
    //         $appDetails = appointmentDetails($appId);
    //         $patientId = $appDetails->user_id;
    //         if($patientId){
    //             $addData = DB::table('')->first();
                
    //         }else{
                
    //         }
    //     // }catch(\Exception $e){
    //     //     return view('common.503');
    //     // }
    // }
}

