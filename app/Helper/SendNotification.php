<?php
    namespace App\Helper;
    trait SendNotification{

        public function SendNotification($token, $title){
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
            $result = curl_exec($ch);
            curl_close($ch);

            return true;
        }
    }
?>