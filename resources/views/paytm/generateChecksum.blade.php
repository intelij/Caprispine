<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

require_once(base_path()."/lib/config_paytm.php");
require_once(base_path()."/lib/encdec_paytm.php");

$checkSum = "";
$findme   = 'REFUND';
$findmepipe = '|';
$paramList = array();
$paramList["MID"] = '';
$paramList["ORDER_ID"] = '';
$paramList["CUST_ID"] = '';
$paramList["INDUSTRY_TYPE_ID"] = '';
$paramList["CHANNEL_ID"] = '';
$paramList["TXN_AMOUNT"] = '';
$paramList["WEBSITE"] = '';

foreach($_POST as $key=>$value)
{  
  $pos = strpos($value, $findme);
  $pospipe = strpos($value, $findmepipe);
  if ($pos === false || $pospipe === false) 
    {
        $paramList[$key] = $value;
    }
}

$checkSum = getChecksumFromArray($paramList,'_9yCj7905FL_kCb0');

 echo json_encode(array("CHECKSUMHASH" => $checkSum,"ORDER_ID" =>$orderid,"payt_STATUS" => "1"));
 
?>
