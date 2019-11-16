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
	$todayDate = date('Y-m-d');
    // $userData = DB::table('users')->where('status','active')->select('id','name','dob','mobile','token_id')->get();
	$getUsers = $conn->prepare("SELECT id,name,dob,mobile,token_id FROM users WHERE status = 'active'");
    $getUsers->execute();
    $userData = $getUsers->fetchAll();
    if(count($userData) > 0){
        foreach($userData as $users) {
            $dob = $users['dob'];
            $token = $users['token_id'];
            if(!empty($dob) && !empty($token)){
                if(date("m-d", strtotime($dob)) == date("m-d", strtotime($todayDate))){
                    $title = "Dear ".$users['name'].", Wish you a very Happy Birthday";
                    // send birthday notification
                    $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
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

                    // send birthday message alert
                     $message = "Dear ".$users['name'].", Capri Spine Clinic wishes you a very happy birthday! May God bless you with all the happiness, good health and a healthy, mobile spine. Regards, Team Capri";
                     $numbers = $users['mobile'];
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
                        return $response;
                }
            }
        }
    }
?>