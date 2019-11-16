<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Redirect;
use App\Helper\SendSMS;

class CapriPointController extends Controller
{
    use SendSMS;
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function allCapriPoint(){
    	try{
    		$data = array();
            $data['title'] = 'Capri Privilege Points';
            $data['masterclass'] = 'capripoint';
            $data['class'] = 'caprilist';
            $allData = DB::table('capri_point')->get();
            $data['allData'] = $allData;
            return view('capriPoint.list',$data)->with('no',1);
    	}catch(\Exception $e){
            return view('common.503');
        }
    }

    public function acceptPendingWalletRequest($id){
        try{
            $checkData = DB::table('capri_point')->where('id',$id)->first();
            if($checkData){
                $userDetails = userDetails($checkData->user_id);
                $mobileNo = $userDetails->mobile;
                $updateData = array();
                $updateData['type'] = 'accepted';
                DB::table('capri_point')->where('id',$id)->update($updateData);
                if($mobileNo){
                    $message = 'Your Capri Privilege Point has been proceed!';
                    $sendsms = $this->sendSMSMessage($message,$mobileNo);
                }
                return redirect()->back();
            }else{
                return redirect()->back();
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function approvePendingWalletRequest(Request $request){
    	try{
            $id = $request->cpointId;
            $checkCapriData = DB::table('capri_point')->where('id',$id)->first();
            if($checkCapriData){
                $userId = $checkCapriData->user_id;
                $userDetails = userDetails($userId);
                $mobileNo = $userDetails->mobile;
                $creditAmt = DB::table('capri_point')->where('user_id',$userId)->where('type','credit')->sum('cp_amount');
                $debitAmt = DB::table('capri_point')->where('user_id',$userId)->where('type','debit')->sum('cp_amount');
                $finalAmt = $creditAmt - $debitAmt;
                if(($finalAmt > 0) && ($checkCapriData->cp_amount <= $finalAmt)){
                    $updateData = array();
                    $updateData['type'] = 'debit';
                    $updateData['transaction_id'] = $request->transactionId;
                    DB::table('capri_point')->where('id',$id)->update($updateData);
                    if($mobileNo){
                        $message = 'Your Capri Privilege Point has been approved!';
                        $sendsms = $this->sendSMSMessage($message,$mobileNo);
                    }
                    return Redirect::to('all-capri-points');
                }else{
                    return Redirect::to('all-capri-points');
                    echo "<script>alert('Amount can not debit!);</script>";
                }
            }else{
                return Redirect::to('all-capri-points');
            }
    	}catch(\Exception $e){
            return view('common.503');
        }
    }
}
