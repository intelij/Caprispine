<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use file;
use Redirect;
use Auth;
use Hash;
use App\User;
use App\Helper\FileUpload;

class ProfileController extends Controller
{
	use FileUpload;
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function myProfile(){
        try{
        	$data = array();
        	$data['title'] = 'My Profile';
        	$state = DB::table('states')->get();
            $data['states'] = $state;
            $cities = DB::table('cities')->get();
            $data['cities'] = $cities;
            $data['masterclass'] = '';
            $data['class'] = '';
        	return view('profile.view',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateProfile(Request $request){
        try{
        	$data = array();
        	$data['state'] = $request->state;
        	$data['city'] = $request->city;
        	$data['mobile'] = $request->mobile;
        	if($request->hasfile('profile_pic')){
        		$folder = "upload/profile_pic/";
        		$file = $this->upload_file($request->profile_pic, $folder);
                if($file)
                {
                	$data['profile_pic'] = $file;
                }
        	}

        	User::where('id',Auth::user()->id)->update($data);
        	return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function changePassword(Request $request){
        try{
            $data = array();
            $data['title'] = 'Change Password';
            $data['masterclass'] = '';
            $data['class'] = '';
            return view('profile.changePassword',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function resetPassword(Request $request){
        try{
            $oldPassword = $request->old_password;
            $newPassword = $request->new_password;
            $confirmPassword = $request->confirm_password;
            if(Auth::User()->confirmpassword == $oldPassword){
                if($newPassword == $confirmPassword){
                    $data =array();
                    $data['password'] = bcrypt($newPassword);
                    $data['confirmpassword'] = $newPassword;
                    User::where('id', Auth::User()->id)->update($data);
                    return Redirect::to('dashboard')->with('Success','Password Successfully Changed!!');
                }else{
                    return Redirect::to('change-password')->with('Danger','New Password and Confirm Password does not match!!');
                }
            }else{
                return Redirect::to('change-password')->with('Danger','Old Password is wrong!!');
            }
        }catch(\Exception $e){
            return view('common.503');
        }
    }
}
