<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use Redirect;
use Auth;
use App\Appointment;

class ModuleAssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function moduleAssignment(){
        try{
        	$data = array();
        	$data['title'] = 'Module Assignment';
        	$allData = DB::table('user_type')->get();
        	$data['allData'] = $allData;
            $data['masterclass'] = '';
            $data['class'] = 'assignment';
        	return view('moduleAssign.list',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function addPermission($id){
    	try{
            $data = array();
        	$data['title'] = 'Module Permission';
        	$userTypeData = DB::table('user_type')->where('id',$id)->get();
        	$data['userTypeData'] = $userTypeData;
    		$moduleData = DB::table('modules')->get();
        	$data['moduleData'] = $moduleData;
        	$premissionData = DB::table('master_assign')->where('user_type',$id)->get();
        	$data['permissionData'] = $premissionData;
            $data['editData'] = '';
            $data['allSubModule'] = '';
            $data['masterclass'] = '';
            $data['class'] = 'assignment';
        	return view('moduleAssign.permission',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updatePermission(Request $request){
        try{
        	$addData = array();
        	$getData = DB::table('master_assign')->where('user_type',$request->userType)->where('assign_module',$request->module_name)->first();
        	if($getData){
                echo "<script>alert('These module already added');</script>";
        	}else{
        		$addData['user_type'] = $request->userType;
    	    	$addData['assign_module'] = $request->module_name;
                if($request->sub_module_name){
                    $submoduleId = implode(',',$request->sub_module_name);
                }else{
                    $submoduleId = '';
                }
                $addData['assign_sub_modules'] = $submoduleId;
    	    	$addData['status'] = 'active';
    	    	DB::table('master_assign')->insert($addData);
        	}
        	return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function editModulePermission($id){
        try{
            $data = array();
            $data['title'] = 'Module Permission';
            $userTypeData = DB::table('user_type')->where('id',$id)->get();
            $data['userTypeData'] = $userTypeData;
            $moduleData = DB::table('modules')->get();
            $data['moduleData'] = $moduleData;
            $allSubModule = DB::table('sub_module')->get();
            $data['allSubModule'] = $allSubModule;
            $premissionData = DB::table('master_assign')->where('user_type',$id)->get();
            $data['permissionData'] = $premissionData;
            $editData = DB::table('master_assign')->where('id',$id)->first();
            $data['editData'] = $editData;
            $data['masterclass'] = '';
            $data['class'] = 'assignment';
            return view('moduleAssign.permission',$data);
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function updateNewAssignment($id, Request $request){
        try{
            $submoduleId = implode(',',$request->sub_module_name);
            $data['assign_sub_modules'] = $submoduleId;
            DB::table('master_assign')->where('id',$id)->update($data);
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }

    public function allSubModule($id){
        $subModules = DB::table("sub_module")
                    ->select("name","id")
                    ->where("module_id",$id)
                    ->get();
        return json_encode($subModules);
    }

    public function deleteModuleAssignment($id){
        try{
            $deletedata = DB::table('master_assign')->where('id',$id)->delete();
            return redirect()->back();
        }catch(\Exception $e){
            return view('common.503');
        }
    }
}
