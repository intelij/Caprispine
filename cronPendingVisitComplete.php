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
    
  // cron set for taday's pending visit make be completed
  date_default_timezone_set('Asia/Kolkata');
  $currentDate = date('Y-m-d');
  $outTimes = date('H:i:s');
  $status = 'complete';
  $rating = 5;
//   $query1 = $conn->prepare("SELECT * FROM daily_entry WHERE app_booked_date = '2019-10-07' AND status = 'pending' AND appointment_id ='1369'");
  $query1 = $conn->prepare("SELECT * FROM daily_entry WHERE app_booked_date = '$currentDate' AND status = 'pending'");
//   $query1 = $conn->prepare("SELECT * FROM daily_entry WHERE app_booked_date = '$currentDate' AND status = 'pending' AND therapist_id = '5' ");
  $query1->execute();
  $getTodaysVisit = $query1->fetchAll();
  if(count($getTodaysVisit) > 0){
    foreach($getTodaysVisit as $allVisit){
        $visitId = $allVisit['id'];
        $visitType = $allVisit['type'];
        $amount = $allVisit['amount'];
        if($visitType == 1){
          // for per day visit
          $query2 = "UPDATE daily_entry SET rating = '$rating', status = '$status', out_time = '$outTimes' WHERE id = '$visitId' AND app_booked_date = '$currentDate'";
          $stmt3 = $conn->prepare($query2);
          $stmt3->execute();
        }else{
            $query3 = $conn->prepare("SELECT * FROM daily_entry WHERE id = '$visitId'");
            $query3->execute();
            $visitDetails = $query3->fetch();
            $inTime = $visitDetails['in_time'];
            $outTime = date('H:i:s');
            $appId = $allVisit['appointment_id'];
            $query4 = $conn->prepare("SELECT * FROM appointment WHERE id = '$appId'");
            $query4->execute();
            $appDetails = $query4->fetch();
            $serviceType = $appDetails['app_service_type'];
            // for package wise visit
            $query5 = $conn->prepare("SELECT count(id) FROM daily_entry WHERE appointment_id = '$appId' AND app_booked_date = '$currentDate' AND status = 'complete'");
            $query5->execute();
            $checkVisitCount = $query5->fetch();
            if(!empty($inTime) && !empty($outTime) && ($serviceType != '7') && ($serviceType != '1') && ($serviceType != '8') && ($serviceType != '9')){
                $jointName = $appDetails['joints'];
                if($checkVisitCount > 1){
                    if($jointName == 'one_joint'){
                        $ntTime = strtotime("+70 minutes", strtotime($inTime));     //10 minutes extra of 60 min
                    }else if($jointName == 'two_joint'){
                        $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 90 min
                    }else if($jointName == 'three_joint'){
                        $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 120 min
                    }else if($jointName == 'neuro'){
                        $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                    }
                }else{
                    // add 30 min extra for 1st visit patients for filling capri file
                    if($jointName == 'one_joint'){
                        $ntTime = strtotime("+100 minutes", strtotime($inTime));     //10 minutes extra of 60 min
                    }else if($jointName == 'two_joint'){
                        $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 90 min
                    }else if($jointName == 'three_joint'){
                        $ntTime = strtotime("+160 minutes", strtotime($inTime));    //10 minutes extra of 120 min
                    }else if($jointName == 'neuro'){
                        $ntTime = strtotime("+130 minutes", strtotime($inTime));    //10 minutes extra of 60 min
                    }
                }
                $nextTime = date('H:i:s', $ntTime);
                $time1 = new DateTime($outTime);
                $time2 = new DateTime($nextTime);
                $interval = $time2->diff($time1);
                $diff = $interval->format('%h:%i:%s');

                if($time1 < $time2){
                    $penalty = '';
                    $visitType = 'AV';
                }else{
                    if(strtotime($diff) <= strtotime('0:10:0')){
                        $penalty = '25';
                        $visitType = 'AW';
                    }else if(strtotime($diff) <= strtotime('0:20:0')){
                        $penalty = '50';
                        $visitType = 'AW';
                    }else if(strtotime($diff) <= strtotime('0:30:0')){
                        $penalty = '75';
                        $visitType = 'AW';
                    }else if(strtotime($diff) > strtotime('0:30:0')){
                        //get 50% of extra amount for penalty
                        $percentage = 50;
                        $penalty = ($percentage / 100) * $amount;
                        $visitType = 'AW';
                    }else{
                        $penalty = '';
                        $visitType = 'AV';
                    }
                }
            }else{
                $penalty = '';
                $visitType = '';
            }
            $appointmentDueDays = $appDetails['due_package_days'];
            if(($appointmentDueDays != 0) && ($appointmentDueDays != '') && ($dailyEntryDetails->type == 2)){
                //if package update then due days of package entries
                $dueUpdateVal = $appointmentDueDays - 1;
                $query6 = "UPDATE appointment SET due_package_days = '$dueUpdateVal' WHERE id = '$appId'";
                $stmt1 = $conn->prepare($query6);
                $stmt1->execute();
            }
            // daily entry update
            $query7 = "UPDATE daily_entry SET rating = '$rating', status = '$status', out_time = '$outTime', penalty = '$penalty' WHERE id = '$visitId' AND app_booked_date = '$currentDate'";
            $stmt2 = $conn->prepare($query7);
            $stmt2->execute();
        }
        return true;
    }
  }
?>