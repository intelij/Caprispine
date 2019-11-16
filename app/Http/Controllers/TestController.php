<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;


class TestController extends Controller
{
    public function uploadImage(Request $request){
    	$image = $request->image;
    	if(!empty($image)){
	        $image = str_replace('data:image/png;base64,', '', $image);
	        $image = str_replace(' ', '+', $image);
	        $imageName = str_random(10).'.'.'png';
	        $dd = File::put(storage_path(). '/' . $imageName, base64_decode($image));
	        if($dd){
	        	$response['message'] = 'send';
      			$response['status'] = '1';
	        }else{
	        	$response['message'] = 'not upload image';
      			$response['status'] = '0';
	        }
    	}else{
    		$response['message'] = 'not send';
      		$response['status'] = '0';
    	}
    	return response()->json($response);
    }
}
