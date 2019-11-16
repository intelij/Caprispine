<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use \App;
use DB;
use App\User;
use App\Appointment;

class PaytmController extends Controller
{
	public function index()
    {
        //
    }

	public function paytmchecksumgenerate(Request $request){
		$orderid=$request->ORDER_ID;
		return view('paytm.generateChecksum',compact('orderid','MID','CUST_ID','INDUSTRY_TYPE_ID','CHANNEL_ID','TXN_AMOUNT','WEBSITE'));
	}
	
	public function savePaytmResponse(Request $request){
		try{
			if(Input::has('appId') && Input::has('user_id') && Input::has('transection_status') && Input::has('amount') && Input::has('transection_id')){
				$appId = $request->appId;
				$patientId = $request->user_id;
				$transectionStatus = $request->transection_status;
				$amount = $request->amount;
				$transectionId = $request->transection_id;
				if(!empty($appId) && !empty($patientId) && !empty($transectionStatus) && !empty($amount) && !empty($transectionId)){
					$paymentDate = date('Y-m-d');
					$checkPatient = User::where('id',$patientId)->first();
					if($checkPatient){
						$checkAppointment = Appointment::where('id',$appId)->first();
						if($checkAppointment){
							$addData = array();
							$addData['therapist_id'] = '';
							$addData['appointment_id'] = $appId;
							$addData['user_id'] = $patientId;
							$addData['capri_account'] = $amount;
							$addData['total_amount'] = $amount;
							$addData['flag'] = 'consultation';
							$addData['transection_status'] = $transectionStatus;
							$addData['transection_id'] = $transectionId;
							$addData['payment_date'] = $paymentDate;
							$addData['created_by'] = 'paytm';
							DB::table('account')->insert($addData);

							// Account History
							$addHist = array();
							$addHist['appointment_id'] = $appId;
							$addHist['therapist_id'] = '';
							$addHist['capri_account'] = $amount;
							$addHist['therapist_account'] = '';
							$addHist['total_amount'] = $amount;
							$addHist['remark'] = 'consultation amount';
							$addHist['created_by'] = 'paytm';
							$addHist['transection_status'] = $transectionStatus;
							$addHist['transection_id'] = $transectionId;
							$addHist['payment_date'] = date("Y-m-d");
							DB::table('account_history')->insert($addHist);

							$response['message'] = 'Successfully Payment!';
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
				$response['message'] = 'All fields are mandatory!';
	            $response['status'] = '0';
			}
		}catch(\Exception $e){
            $response['message'] = 'Something went wrong!';
            $response['status'] = '0';
        }
    	return response()->json($response);
	}

	public function allPaymentHistory(Request $request){
		try{
			if(Input::has('user_id')){
				$userId = $request->user_id;
				if(!empty($userId)){
					$userCheck = User::where('id',$userId)->where('status','active')->first();
					if($userCheck){
						$allData = DB::table('account')->where('user_id',$userId)->orderBy('id','DESC')->select('id','therapist_id','appointment_id','user_id','total_amount','flag','transection_status','transection_id','created_at')->get();
						if(count($allData) > 0){
							foreach ($allData as $allVal) {
								if(!empty($allVal->user_id)){
									$userName = User::where('id',$allVal->user_id)->first();
                                	$allVal->user_id = $userName->name;
								}
								if(!empty($allVal->therapist_id)){
									$thName = User::where('id',$allVal->therapist_id)->first();
                                	$allVal->therapist_id = $thName->name;
								}
								$allVal->created_at = date("d-M-Y", strtotime($allVal->created_at));
								array_walk_recursive($allVal, function (&$item, $key) {
                                    $item = null === $item ? '' : $item;
                                });
							}

							$response['message'] = 'Successfully Login!';
                            $response['status'] = '1';
                            $response['allData'] = $allData;
						}else{
							$response['message'] = 'Data not found!';
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

	public function walletPay(Request $request){
		try{
			if(Input::has('patientId') && Input::has('Appoint_Id') && Input::has('amount')){
				$patientId = $request->patientId;
				$appId = $request->Appoint_Id;
				$amount = $request->amount;
				if(!empty($patientId) && !empty($appId) && !empty($amount)){
					$userCheck = User::where('id',$patientId)->where('status','active')->where('user_type',3)->first();
					if($userCheck){
						$checkAppointment = DB::table('appointment')->where('id',$appId)->first();
						if($checkAppointment){
							$walletCRAmount = DB::table('capri_point')->where('user_id',$patientId)->where('type','credit')->sum('cp_amount');
							$walletDRAmount = DB::table('capri_point')->where('user_id',$patientId)->where('type','debit')->sum('cp_amount');
							$totalAmount = $walletCRAmount - $walletDRAmount;
							if($totalAmount > 500){
								$walletUpdate = array();
								$walletUpdate['user_id'] = $patientId;
								$walletUpdate['cp_amount'] = $amount;
								$walletUpdate['cpoint_id'] = '';
								$walletUpdate['cp_point'] = '';
								$walletUpdate['type'] = 'debit';
								$walletUpdate['remark'] = 'Use wallet point';
								DB::table('capri_point')->insert($walletUpdate);
								// add in amount
								$accountDetails = array();
								$accountDetails['appointment_id'] = $appId;
								$accountDetails['user_id'] = $patientId;
								$accountDetails['capri_account'] = $amount;
								$accountDetails['therapist_account'] = 0;
								$accountDetails['total_amount'] = $amount;
								$accountDetails['flag'] = 'consultation';
								$accountDetails['payment_date'] = date('Y-m-d');
								$accountDetails['created_by'] = $patientId;
								DB::table('account')->insert($accountDetails);
								// update consultation in appointment 
								$appUpdate = array();
								$appUpdate['consultation_fees'] = $amount;
								Appointment::where('id',$appId)->update($appUpdate);

								$response['message'] = 'Successfully use WalletPay!';
	                        	$response['status'] = '0';
							}else{
								$response['message'] = 'Amount not exist in your wallet!';
	                        	$response['status'] = '0';
							}
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

}
