<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Appointment;
use Redirect;
use Auth;
use PDF;
use DB;

class ReceiptController extends Controller
{
    public function __construct()
      {
          $this->middleware('auth');
      }
      
    public function generapReceipt(){
      try{
        $data = array();
        $data['title'] = 'General Invoice';
          $data['masterclass'] = 'receipt';
          $data['class'] = 'greceipt';
          if(Auth::user()->user_type == 'superadmin'){
            $allRegistrationId = User::where('user_type','3')->get();
            $allInvoice = DB::table('invoice')->where('invoice_type','general')->get();
          }else{
            $allRegistrationId = User::where('user_type','3')->where('branch',Auth::user()->branch)->get();
            $allInvoice = DB::table('invoice')->where('invoice_type','general')->where('branch_id',Auth::User()->branch)->get();
          }
          $data['allReg'] = $allRegistrationId;
          // $allBranch = DB::table('location')->orderBy('name','ASC')->get();
          // $data['allBranch'] = $allBranch;
          
          $data['allInvoice'] = $allInvoice;
          return view('receipt.general',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function saveGeneralInvoice(Request $request){
      try{
          $userId = $request->userId;
          $date = date("Y-m-d");
          $registration_no = $request->registration_no;
          $userDetails = User::where('registration_no',$registration_no)->first();
          $userId = $userDetails->id;
          $branch_id = userDetails($userId)->branch;
          $payment_type = $request->paymentType;
          $amount = $request->amount;
          $invoice_type = 'general';
          $days = $request->days;
          $name = userDetails($userId)->name;
          $bankName = $request->bank;
          $refNo = $request->reference_no;
          $data = array();
          $data['registration_no'] = $registration_no;
          $data['name'] = $name;
          $data['branch_id'] = $branch_id;
          $data['payment_type'] = $payment_type;
          $data['amount'] = $amount;
          $data['invoice_type'] = $invoice_type;
          $data['check_or_ref_no'] = $refNo;
          $data['bank'] = $bankName;
          $data['treatment_days'] = $days;
          $data['date'] = $date;
          $data['created_by'] = Auth::user()->id;
          DB::table('invoice')->insert($data);
          return redirect()->back();
        }catch(\Exception $e){
          return view('common.503');
        }
    }

    public function invoiceViewDetails($id){
      try{
        $data = array();
        $getData = DB::table('invoice')->where('id',$id)->first();
        $data['getData'] = $getData;
        return view('receipt.invoice',$data);
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function normalInvoiceDetails($id){
      try{
        $data = array();
        $getData = DB::table('invoice')->where('id',$id)->first();
        $data['getData'] = $getData;
        return view('receipt.normalInvoice',$data);
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function downloadPDF(){
      try{
        $data = '';
        $pdf = PDF::loadView('pdfView',$data);
        return $pdf->download('sumanInvoice.pdf');
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function get_customer_data()
    {
      try{
        $customer_data = DB::table('invoice')
             ->limit(10)
             ->get();
        return $customer_data;
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function pdf()
    {
         $pdf = \App::make('dompdf.wrapper');
         $pdf->loadHTML($this->convert_customer_data_to_html());
         return $pdf->stream();
    }

    public function convert_customer_data_to_html()
    {
     $customer_data = $this->get_customer_data();
     $output = '
     <h3 align="center">Customer Data</h3>
     <table width="100%" style="border-collapse: collapse; border: 0px;">
      <tr>
    <th style="border: 1px solid; padding:12px;" width="30%">Registration No</th>
    <th style="border: 1px solid; padding:12px;" width="20%">Name</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Amount</th>
    <th style="border: 1px solid; padding:12px;" width="15%">Payment Type</th>
    <th style="border: 1px solid; padding:12px;" width="20%">Invoice Type</th>
    </tr>
     ';  
     foreach($customer_data as $customer)
     {
      $output .= '
      <tr>
       <td style="border: 1px solid; padding:12px;">'.$customer->registration_no.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->name.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->amount.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->payment_type.'</td>
       <td style="border: 1px solid; padding:12px;">'.$customer->invoice_type.'</td>
      </tr>
      ';
     }
     $output .= '</table>';
     return $output;
    }

    public function packageReceipt(){
      try{
        $data = array();
        $data['title'] = 'Package Invoice';
        $data['masterclass'] = 'receipt';
        $data['class'] = 'preceipt';
        if(Auth::user()->user_type == 'superadmin'){
          $allRegistrationId = User::where('user_type','3')->get();
        $allInvoice = DB::table('invoice')->where('invoice_type','package')->get();
        }else{
          $allRegistrationId = User::where('user_type','3')->where('branch',Auth::user()->branch)->get();
        $allInvoice = DB::table('invoice')->where('invoice_type','package')->where('branch_id',Auth::User()->branch)->get();
        }
        $data['allReg'] = $allRegistrationId;
        $data['allInvoice'] = $allInvoice;
        return view('receipt.package',$data);
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function savePackageInvoice(Request $request){
      try{
        $userId = $request->userId;
        $date = date("Y-m-d");
        $appId = $request->registration_no;
        // $appId = $request->registration_no;
        $appointmentDetails = appointmentDetails($appId);
        $userId = $appointmentDetails->user_id;
        $userDetails = userDetails($userId);
        $registration_no = $userDetails->registration_no;
        $registration_no = $registration_no;
        $userDetails = User::where('registration_no',$registration_no)->first();
        $userId = $userDetails->id;
        $branch_id = userDetails($userId)->branch;
        $payment_type = $request->paymentType;
        $type = $request->type;
        $joint = $request->joints;
        $bankName = $request->bank;
        $refNo = $request->reference_no;
        $amount = $request->amount;
        $invoice_type = 'package';
        $branch_id = userDetails($userId)->branch;
        $treatment_days = $request->treatment_days;

        $data = array();
        $data['registration_no'] = $registration_no;
        $data['name'] = $userDetails->name;
        $data['appointment_id'] = $appId;
        $data['amount'] = $amount;
        $data['branch_id'] = $branch_id;
        $data['payment_type'] = $payment_type;
        $data['treatment_days'] = $treatment_days;
        $data['amountType'] = $type;
        $data['joint'] = $joint;
        $data['invoice_type'] = $invoice_type;
        $data['check_or_ref_no'] = $refNo;
        $data['bank'] = $bankName;
        $data['date'] = $date;
        $data['created_by'] = Auth::user()->id;
        DB::table('invoice')->insert($data);

        // update payment statusin appointment
        $updateApp = array();
        $updateApp['payment_status'] = 'approved';
        Appointment::where('id',$appId)->update($updateApp);
        return redirect()->back();
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function saveRefundAmount(Request $request){
      try{
        $data = array();
        $invoiceId = $request->invoiceId;
        // $joint = $request->joint;
        $amount = $request->refund_amount;
        $remark = $request->remark;
        $invoiceDetails = DB::table('invoice')->where('id',$invoiceId)->first();
        $regNo = $invoiceDetails->registration_no;
        $userDetails = User::where('registration_no',$regNo)->first();
        $userId = $userDetails->id;
        $appointmentDetails = Appointment::where('user_id',$userId)->where('payment_method','package_wise')->orderBy('appointment_date','DESC')->first();
        $appId = $appointmentDetails->id;

        $data['invoice_id'] = $invoiceId;
        $data['appointment_id'] = $appId;
        $data['amount'] = $request->refund_amount;
        $data['remark'] = $remark;
        $data['created_by'] = Auth::user()->id;
        DB::table('refund')->insert($data);
        return redirect()->back();
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function refundPolicy($invoiceId, $joint){
      try{
        $getData = array();
        $invoiceDetails = DB::table('invoice')->where('id',$invoiceId)->first();
        $regNo = $invoiceDetails->registration_no;
        $userDetails = User::where('registration_no',$regNo)->first();
        $userId = $userDetails->id;
        $appointmentDetails = Appointment::where('user_id',$userId)->where('payment_method','package_wise')->orderBy('appointment_date','DESC')->first();
        $appId = $appointmentDetails->id;
        $PaidAmount = DB::table('daily_entry')->where('appointment_id',$appId)->where('type',2)->sum('amount');
        $packageId = $appointmentDetails->package_type;
        if(!empty($packageId)){
          $getData['packageAmount'] = packageDetails($packageId)->package_amount;
        }else{
          $getData['packageAmount'] = '';
        }
        $getData['PaidAmount'] = $PaidAmount;
        return json_encode($getData);
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function RefundViewDetails($id){
        $data = array();
        $getData = DB::table('invoice')->join('refund','refund.invoice_id','=','invoice.id')->where('invoice.id',$id)->select('refund.*','invoice.id as invoiceId','invoice.registration_no','invoice.name','invoice.joint','invoice.bank','invoice.check_or_ref_no','invoice.treatment_days')->first();
        $data['getData'] = $getData;
        return view('receipt.refund',$data);
    }

    public function referenceDuplicancy($refNo){
      $getData = DB::table('invoice')->where('check_or_ref_no',$refNo)->first();
      return json_encode($getData);
    }

    public function cancelInvoice($id){
      try{
        $getData = DB::table('invoice')->where('id',$id)->first();
        if($getData){
          $deletedata = DB::table('invoice')->where('id',$id)->delete();
        }
        return redirect()->back();
      }catch(\Exception $e){
        return view('common.503');
      }
    }

    public function getUserDetailsTreatementWise($flag){
      try{
        if($flag == 'perday'){
          if(Auth::user()->user_type == 'superadmin'){
            $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.user_type','3')->where('appointment.payment_method','per_day_visit')->where('appointment.status','approved')->select('appointment.id as id','users.name as name','users.registration_no as registration_no','appointment.payment_method','appointment.appointment_date as appointment_date')->get();
          }else{
            $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.user_type','3')->where('appointment.payment_method','per_day_visit')->where('appointment.status','approved')->where('users.branch',Auth::user()->branch)->select('appointment.id as id','users.name as name','users.registration_no as registration_no','appointment.payment_method','appointment.appointment_date as appointment_date')->get();
          }
        }else{
          if(Auth::user()->user_type == 'superadmin'){
            $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.user_type','3')->where('appointment.payment_status','pending')->where('appointment.status','approved')->where('appointment.payment_method','package_wise')->select('appointment.id as id','users.name as name','users.registration_no as registration_no','appointment.payment_method','appointment.appointment_date as appointment_date')->get();
          }else{
            $getData = DB::table('appointment')->join('users','users.id','=','appointment.user_id')->where('users.user_type','3')->where('appointment.payment_status','pending')->where('appointment.status','approved')->where('appointment.payment_method','package_wise')->where('users.branch',Auth::user()->branch)->select('appointment.id as id','users.name as name','users.registration_no as registration_no','appointment.payment_method','appointment.appointment_date as appointment_date')->get();
          }
        }
        return json_encode($getData);
      }catch(\Exception $e){
        return view('common.503');
      }
    }

}