<?php
namespace App\Helper;

trait SendSMS{
    // public function sendSMSMessage($message, $numbers){
    //     /*sms getway start*/  
    //     $sms = urlencode($message);
    //     $phones = $numbers;
    //     $url = "https://smsleads.in/pushsms.php?";

    //     $ch = curl_init($url);
    //     curl_setopt($ch, CURLOPT_HEADER, 0);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, "username=deepakcapri&password=deepak@123&sender=PHYSIO&numbers=$phones&message=$sms");
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    //     $response = curl_exec($ch);
    //     /*sms getway ends*/
    //     return $response;
    // }

    public function sendSMSMessage($message, $numbers){
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

        // if ($err) {
        //   echo "cURL Error #:" . $err;
        // } else {
        //   echo $response;
        // }
        return $response;
    }
}
?>