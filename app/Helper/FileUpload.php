<?php
namespace App\Helper;

trait FileUpload{

        public function upload_file($file,$folder){
        	$destinationPath = public_path(). '/'.$folder;
		    $ext = strtolower($file->getClientOriginalExtension());
	        $filename = str_random(15).'.'.$ext;
	 		if($file->move($destinationPath,$filename)) return $filename;
        }
}
?>