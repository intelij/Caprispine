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
    $currentTime = date('H:i:s');
    $query1 = $conn->prepare("SELECT * FROM exercise_calender WHERE date = '$currentDate'");
    $query1->execute();
    $getData = $query1->fetchAll();
    if(count($getData) > 0){
        foreach($getData as $allData){
            $patientId = $allData['patient_id'];
            $exerciseTime = $allData['time'];
            $exerciseDate = $allData['date'];
            $before5MinTime = date('H:i:s',strtotime('-5 minutes',strtotime($exerciseTime)));
            $query2 = $conn->prepare("SELECT * FROM users WHERE id = '$patientId'");
            $query2->execute();
            $userDetials = $query2->fetch();
            $patientName = $userDetials['name'];
            $mobile = $userDetials['mobile'];
            $token = $userDetials['token_id'];
            $title = 'Self exercise session reminder!';
            if(($currentTime == $before5MinTime) && ($currentDate == $exerciseDate)){
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
                    $message = "Dear ".$patientName.", Your self exercise session is about to start in 5 minutes. Gear up to live a healthy and happy life. Regards, Team Capri.";
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
    
?>