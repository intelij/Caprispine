<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Auth;
use Redirect;
use App\User;
use App\Appointment;
use Carbon\Carbon;

class DashboardController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function myformAjax($id)
    {
        $cities = DB::table("cities")
                    ->select("name","id")
                    ->where("state_id",$id)
                    ->get();
        return json_encode($cities);
    }

    public function index(){
        try{
            $data =array();
            $data['title'] = 'Dashboard';
            if(Auth::User()->user_type == 'superadmin'){
                $totalUser = User::where('status','active')->where('user_type',3)->count('id');
                $totalApprovedAppointment = Appointment::where('status','approved')->count('id');
                $totalCancelAppointment = Appointment::where('status','cancel')->count('id');
                $totalPendingAppointment = Appointment::where('status','pending')->count('id');
                $totalCompleteAppointment = Appointment::where('status','complete')->count('id');
                $totalAppointment = Appointment::count('id');
                $totalTherapist = User::where('user_type',5)->count('id');
                $totalStaff = User::where('user_type','!=',3)->where('user_type','!=',5)->where('user_type','!=','superadmin')->count('id');
            }else{
                $totalUser = User::where('status','active')->where('user_type',3)->where('branch',Auth::User()->branch)->count('id');
                $totalApprovedAppointment = DB::table('appointment')->join('users','appointment.user_id','=','users.id')->where('appointment.status','approved')->where('users.branch',Auth::User()->branch)->count('appointment.id');
                $totalCancelAppointment = DB::table('appointment')->join('users','appointment.user_id','=','users.id')->where('appointment.status','cancel')->where('users.branch',Auth::User()->branch)->count('appointment.id');
                $totalPendingAppointment = DB::table('appointment')->join('users','appointment.user_id','=','users.id')->where('appointment.status','pending')->where('users.branch',Auth::User()->branch)->count('appointment.id');
                $totalCompleteAppointment = DB::table('appointment')->join('users','appointment.user_id','=','users.id')->where('appointment.status','complete')->where('users.branch',Auth::User()->branch)->count('appointment.id');
                $totalAppointment = DB::table('appointment')->join('users','appointment.user_id','=','users.id')->where('users.branch',Auth::User()->branch)->count('appointment.id');
                $totalTherapist = User::where('user_type',5)->where('users.branch',Auth::User()->branch)->count('id');
                $totalStaff = User::where('user_type','!=',3)->where('user_type','!=',5)->where('user_type','!=','superadmin')->where('users.branch',Auth::User()->branch)->count('id');
            }
            $allAppointment = Appointment::groupBy('appointment_date')->orderBy('appointment_date','DESC')->select('appointment_date', DB::raw('count(id) as count'))->take(7)->get();
            $currentYear = date("Y");
            $allMonthlyAppointment = Appointment::selectRaw('COUNT(id) as count, MONTHNAME(appointment_date) as month')
                                                        ->groupBy(DB::raw("MONTH(appointment_date)"))
                                                        ->whereYear('appointment_date',$currentYear)
                                                        ->get()->toArray();

            $data['totalUser'] = $totalUser;
            $data['totalApprovedAppointment'] = $totalApprovedAppointment;
            $data['totalCancelAppointment'] = $totalCancelAppointment;
            $data['totalPendingAppointment'] = $totalPendingAppointment;
            $data['totalCompleteAppointment'] = $totalCompleteAppointment;
            $data['allAppointment'] = $allAppointment;
            $data['allMonthlyAppointment'] = $allMonthlyAppointment;
            $data['totalAppointment'] = $totalAppointment;
            $data['totalTherapist'] = $totalTherapist;
            $data['totalStaff'] = $totalStaff;
            $data['masterclass'] = '';
            $data['class'] = 'dashboard';
            return view('dashboard.index',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }
}
