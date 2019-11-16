<?php

	function json($data = array(), $status = 200, $headers = array(), $options = 0){
	    return \Illuminate\Routing\ResponseFactory::json($data, $status, $headers, $options);
	}

	function subModulepermission($module, $subModule, $userType){
		if(Auth::User()->user_type == 'superadmin'){
			return true;
		}else{
			$masterAssignData = DB::table('master_assign')->where('user_type',$userType)->where('assign_module',$module)->first();
			if($masterAssignData){
				$subModuleData = $masterAssignData->assign_sub_modules;
				$exData = explode(',', $subModuleData);
				if(count($exData) > 0){
					if(in_array($subModule, $exData)){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}
	}

	function checkPermission($moduleType, $userType){
		if(Auth::User()->user_type == 'superadmin'){
			return true;
		}else{
			$checkData = DB::table('master_assign')->where('user_type',$userType)->get();
			if($checkData){
				foreach ($checkData as $chValue) {
					$module_id = $chValue->assign_module;
					if(($chValue->assign_module == $moduleType) && ($chValue->user_type == $userType)){
						return true;
					}
				}
			}else{
				return false;
			}
		}
	}

	function getConsentRecord($appId){
		$getData = DB::table('consent_record')->where('appId',$appId)->orderBy('id','DESC')->first();
		if($getData){
			$result = 'true';
		}else{
			$result = 'false';
		}
		return $result;
	}
	
	function genRand() {
		return $no = rand(100000, 999999);
	}

	function quickRandom($length = 10) {
	    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
	}
 
 	function userDetails($id){
 		return $getData = App\User::where('id',$id)->first();
 	}

 	function userTypeDetails($id){
 		return $getData = DB::table('user_type')->where('id',$id)->first();
 	}

 	function getState($id){
		return $state = DB::table('states')->where('id',$id)->first();
	}

	function getCity($id){
		return $city = DB::table('cities')->where('id',$id)->first();
	}

	function exerciseDetials($id){
		return $data = DB::table('exercise')->where('id',$id)->first();
	}

	function referenceTypeDetails($id){
		return $getData = DB::table('reference')->where('id',$id)->first();;
	}

	function appointmentDetails($id){
		return $data = App\Appointment::where('id',$id)->first();
	}

	function branchDetails($id){
		return $data = DB::table('location')->where('id',$id)->first();
	}
 	
 	function moduleDetails($id){
 		return $data = DB::table('modules')->where('id',$id)->first();
 	}

 	function subModuleDetails($id){
 		return $data = DB::table('sub_module')->where('id',$id)->first();
 	}

 	function serviceDetails($id){
 		return $data = DB::table('service')->where('id',$id)->first();
 	}

 	function timeSlotDetails($id){
 		return $getData = DB::table('time_slot')->where('id',$id)->first();
 	}

 	function therapistList($userId){
 		$userDetails = DB::table('users')->where('id',$userId)->first();
 		if($userDetails->user_type == 'superadmin'){
 			$branchID = userDetails($userId)->branch;
	 		if($branchID){
		 		$therapistData = App\User::where('user_type','5')->get();
	 		}else{
	 			$therapistData = '';
	 		}
 		}else{
 			$branchID = userDetails($userId)->branch;
	 		if($branchID){
		 		$therapistData = App\User::where('user_type','5')->where('branch',$branchID)->get();
	 		}else{
	 			$therapistData = '';
	 		}
 		}
 		return $therapistData;
 	}

 	function packageDetails($id){
 		return $getData = DB::table('package')->where('id',$id)->first();
 	}

 	function dailyEntryDetails($id){
 		return $getData = DB::table('daily_entry')->where('id',$id)->first();
 	}

 	function totalPackageDueDays($appId){
 		$getData = DB::table('daily_entry')->where('appointment_id',$appId)->where('status','complete')->count('id');
 		return $getData;
 	}

 	function registrationWiseUserDetails($regId){
 		return $getData = DB::table('users')->where('registration_no',$regId)->first();
 	}

 	function packageSitting($appId,$packageId){
 		return $getData = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->where('status','complete')->where('type',2)->whereNull('secondFlag')->count('id');
 	}

 	function packageDueDaysRemine($id){
 		return $getData = DB::table('daily_entry')->where('appointment_id',$id)->orderBy('id','DESC')->first();
 	}

 	function dailyPackageReport($appId){
 		$getData = DB::table('daily_entry')->where('daily_entry.appointment_id',$appId)->join('package','daily_entry.package_id','=','package.id')->groupBy('daily_entry.package_id')->select('package.name as name')->get()->toArray();
 		if($getData){
		  foreach($getData as $packNm){
		    $allName[] = $packNm->name;
		  }
		  $packName = implode(', ',$allName);
		}else{
		  $packName = ''; 
		}
 		return $packName;
 	}

 	function checkAttendance($therapistId){
 		$date = date('Y-m-d');
 		return $getData = DB::table('attendance')->where('date',$date)->where('therapist_id',$therapistId)->first();
 	}

 	function totalDailyPenalty($id){
 		return $getData = DB::table('daily_penalty')->where('daily_penalty.therapist_id',$id)->join('penalty','daily_penalty.penalty_id','=','penalty.id')->sum('penalty.amount');
 	}

 	function totalPenalty($id){
 		return $getData = DB::table('daily_penalty')->where('therapist_id',$id)->sum('amount');
 	}

 	function totalApoointedVisitPatient($id){
 		return $getData = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AV')->count('id');
 	}

 	function totalApoointedWithoutVisitedPatient($id){
 		return $getData = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','AW')->count('id');
 	}

 	function totalApoointedTNP($id){
 		// return $getData = DB::table('daily_entry')->where('therapist_id',$id)->count('id');
 		return $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$id)->where('appointment.patient_type','new')->count('appointment.id');
 	}

 	function totalPatientVisit($id){
 		// return $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$id)->count('appointment.id');
 		return $getData = DB::table('daily_entry')->where('therapist_id',$id)->where('visit_type','!=','')->count('id');
 	}

 	function totalStaffCollection($id){
 		return $getData = DB::table('daily_entry')->where('therapist_id',$id)->sum('amount');
 	}

 	function totalPackageAmount($id){
 		// return $getData = DB::table('daily_entry')->where('therapist_id',$id)->where('type','2')->sum('amount');
 		return $getData = DB::table('daily_entry')->where('therapist_id',$id)->sum('amount');
 	}

 	function totalPlannedAppointment($id){
 		// return $getData = DB::table('appointment_history')->where('new_therapist',$id)->where('reason','Booked Appointment')->count('id');
 		return $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.therapist_id',$id)->count('appointment.id');
 	}

 	function penaltyDetails($id){
 		return $getData = DB::table('penalty')->where('id',$id)->first();
 	}

 	function accountDetails($id){
 		return $getData = DB::table('account')->where('therapist_id',$id)->first();
 	}

 	function refundAmount($invoiceId){
 		return $getData = DB::table('refund')->where('invoice_id',$invoiceId)->sum('amount');
 	}

 	function totalIPDAmount($therapistId){
 		return $getData = DB::table('attendance')->join('ipd_calendar','ipd_calendar.date','=','attendance.date')->where('attendance.therapist_id',$therapistId)->count('attendance.id');
 	}

 	function totalSharingAmount($therapistId){
 		return $getData = DB::table('account')->where('therapist_id',$therapistId)->sum('therapist_account');
 	}

 	function dailyEntryFirstDate($appId,$packageId){
 		return $getData = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packageId)->first();
 	}

 	function packageTotalVisitDays($appId,$packId){
 		return $getData = DB::table('daily_entry')->where('appointment_id',$appId)->where('package_id',$packId)->where('type',2)->count('id');
 	}

 	function convertNumberToWord($num){
        $num = str_replace(array(',', ' '), '' , trim($num));
        if(! $num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array('', 'thousand', 'million', 'billion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ( $tens < 20 ) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '' );
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_levels[$i] ) ) ? ' ' . $list3[$levels] . ' ' : '' );
        }
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        $data= implode(' ', $words);
        return $data." ".'only';
    }


 	// Report function
 	function ttotalStaffCollection($id,$to_date = '',$from_date = ''){
 		// dd($to_date,$from_date);
 		if($from_date != ''){
 			$startingDate = $from_date;
 		}else if($to_date != ''){
 			$startingDate = '2019-03-01';		//project starting date
 		}
 		if(($from_date != '') && ($to_date == '')){
 			$getData = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date','>=',$from_date)->sum('amount');
 		}elseif(($to_date != '') && ($from_date == '')){
 			$getData = DB::table('daily_entry')->where('therapist_id',$id)->where('app_booked_date','<=',$to_date)->sum('amount');
 		}elseif(($from_date != '') && ($to_date != '')) {
 			$getData = DB::table('daily_entry')->where('therapist_id',$id)->whereBetween('app_booked_date', [$from_date,$to_date])->sum('amount');
 		}else{
 			$getData = DB::table('daily_entry')->where('therapist_id',$id)->sum('amount');
 			// dd($getData);
 		}
 		// dd($getData);
 		return $getData;
 	}
	// we need to add starting date when project is start to get all leaves.
 	function totalLeaves($therapistId){
 		$startingDate = '2019-04-17';		//project starting date
 		$endDate = date('Y-m-d');				//current date
 	// 	$totalPresent = DB::table('attendance')->where('status','present')->where('date','>=',$startingDate)->count('id');
 	// 	$now = time();
		// $your_date = strtotime($startingDate);	
		// $datediff = $now - $your_date;

		// $getDays =  round($datediff / (60 * 60 * 24));
		// $leaves = $getDays - $totalPresent;
		// return $leaves;

 	// 	$timeDiff = abs(strtotime($startingDate) - strtotime($endDate));
 	// 	$numberDays = $timeDiff/86400;  // 86400 seconds in one day
		// $totaldays = intval($numberDays);

 	// 	// dd($totaldays);
 	// 	$totalPresent = DB::table('attendance')->where('status','present')->where('therapist_id',$therapistId)->where('flag','not_ipd')->count('id');
 	// 	$days = $totaldays - $totalPresent;

 		$days = DB::table('attendance')->where('status','apsent')->where('therapist_id',$therapistId)->count('id');
 		
 		return $days;
 	}

 	function totalWorkingDays($to_date = '',$from_date = ''){
 		if($from_date != ''){
 			$startingDate = $from_date;
 		}else{
 			$startingDate = '2019-04-17';		//project starting date
 		}

 		if($to_date != ''){
 			$now = strtotime($to_date);
 		}else{
 			$now = time();
 		}
		$your_date = strtotime($startingDate);	
		$datediff = $now - $your_date;
		$getDays =  round($datediff / (60 * 60 * 24));
		return $getDays;
 	}

 	function totalWorkingDaysForReport($therapistId){
 		return $getData = DB::table('attendance')->where('therapist_id',$therapistId)->where('status','present')->where('flag','not_ipd')->count('id');
 	}


?>