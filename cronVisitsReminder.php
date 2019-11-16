<?php
    // start connection
    
    $servername = "localhost";
    $username = "capri_user";
    $password = "1qa2WS3ed";
    
    // try {
        $conn = new PDO("mysql:host=$servername;dbname=capri_crm_db", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
    //     }
    // catch(PDOException $e){
    //     echo "Connection failed: " . $e->getMessage();
    // }
    
    // end connection
    
    date_default_timezone_set('Asia/Kolkata');
    $currentDate = date('Y-m-d');
    $outTimes = date('H:i:s');
    // $visitDetails = DB::table('daily_entry')->where('app_booked_date',$currentDate)->where('status','!=','approval_pending')->where('status','!=','pending')->where('status','!=','complete')->get();
    $query = $conn->prepare("SELECT id,appointment_id,status,app_booked_date,app_booked_time FROM daily_entry WHERE app_booked_date = '$currentDate' AND status != 'approval_pending' AND status != 'pending' AND status != 'complete'");
    $query->execute();
    $visitDetails = $query->fetchAll();
    if(count($visitDetails) > 0){
        foreach($visitDetails as $visitVal) {
            if(($visitVal->status == '') || ($visitVal->status == null) || empty($visitVal->status == null)){
                $appId = $visitVal['appointment_id'];
                $appDate = $visitVal['app_booked_date'];
                $appTime = $visitVal['app_booked_time'];
                // $appDetails = DB::table('appointment')->where('id',$visitVal->appointment_id)->first();
                $query2 = $conn->prepare("SELECT * FROM appointment WHERE id = '$appId' "); 
                $query2->execute(); 
                $appDetails = $query2->fetch();
                $userId = $appDetails['user_id'];
                // $userDetials = DB::table('users')->where('id',$appDetails->user_id)->first();
                $query3 = $conn->prepare("SELECT * FROM users WHERE id = '$userId' "); 
                $query3->execute(); 
                $userDetials = $query3->fetch();
                
                $patientName = $userDetials['name'];
                $mobile = $userDetials['mobile'];
                $token = $userDetials['token_id'];
                $hourbeforeTime = date('H:i:s',strtotime('-1 hour',strtotime($appTime)));
                if(($hourbeforeTime == $outTimes) && ($currentDate == $appDate)){
                    $title = "Dear ".$patientName.", Gentle reminder: Your physio session is scheduled at Today.";
                    if(!empty($token) && !empty($title)){
                        // send notification
                        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
                        $token=$token;
    
                        $notification = [
                            'title' => $title,
                            'sound' => true,
                        ];
                        
                        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];
    
                        $fcmNotification = [
                            //'registration_ids' => $tokenList, //multple token array
                            'to'        => $token, //single token
                            'notification' => $notification,
                            'data' => $extraNotificationData
                        ];
    
                        $headers = [
                            'Authorization: key=AIzaSyCmo-dbPyBmkqEVotMmcRsvuRcQWo3iXXY',
                            'Content-Type: application/json'
                        ];
    
    
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
                        $response = curl_exec($ch);
                        curl_close($ch);
    
                        // return true;
                    }
                    if(!empty($mobile) && !empty($patientName)){
                        // send SMS Message 
                        $numbers = $mobile;
                        $message = "Dear ".$patientName.", Gentle reminder: Your physio session dated ".$appDate.", time ".$appTime."is scheduled at Capri Spine. Regards, Team Capri.";
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?route=4&sender=CAPRIS&mobiles=$numbers&authkey=275077ABU34gWQkd9v5ccc8f67&message=$message&country=91",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "GET",
                          CURLOPT_SSL_VERIFYHOST => 0,
                          CURLOPT_SSL_VERIFYPEER => 0,
                        ));
    
                        $response = curl_exec($curl);
                        $err = curl_error($curl);
    
                        curl_close($curl);
                    }
                    return $response;
                }
            }
        }
    }
?>