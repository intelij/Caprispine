<?php
 $base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
 $base .= '://'.@$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
 // $base =  '/';

define('PREFIX', $base);
define('CSS', PREFIX.'css/');
define('JS', PREFIX.'js/');
define('UPLOAD', PREFIX.'public/upload/');
define('APP_IMG', UPLOAD.'appointment_images/');
define('APP_VIDEO', UPLOAD.'appointment_video/');
define('PROFILE_PIC', UPLOAD.'profile_pic/');
define('DEFAULT_PROFILE', UPLOAD.'profile_pic/default.gif');
define('THERAPIST_DOC', UPLOAD.'therapist_doc/');
define('ALERT_IMAGES', UPLOAD.'images/alert.gif');
define('API_PROFILE_PIC', PREFIX.'upload/profile_pic/');
define('API_THERAPIS_DOC', PREFIX.'upload/therapist_doc/');
define('NOTIFICATION_IMG', UPLOAD.'notification_img/');
define('API_NOTIFICATION_IMG', PREFIX.'upload/notification_img/');
define('API_BODY_CHART_IMG', PREFIX.'upload/body_chart/');
define('BODY_CHART', UPLOAD.'body_chart/');
define('API_SIGNATURE_IMG', PREFIX.'upload/signature/');
define('SIGNATURE_IMG', UPLOAD.'signature/');
define('API_INVESTIGATION_DOC', PREFIX.'upload/investigation_doc/');
define('INVESTIGATION_DOC', UPLOAD.'investigation_doc/');
define('API_CASE_REPORT_DOC', PREFIX.'upload/patient_case_report/');
define('CASE_REPORT_DOC', UPLOAD.'patient_case_report/');
define('API_FOR_DEFAULT_IMG', PREFIX.'upload/profile_pic/default.gif');
define('BANNER_IMG', UPLOAD.'banner/');
define('API_BANNER_IMG', PREFIX.'upload/banner/');
define('API_DEFAULT_DOC', PREFIX.'upload/images/doc.png');
define('VISIT_DETAILS_DOC', UPLOAD.'patient_daily_visit_report/');
define('API_EXERCISE_VIDEOS', PREFIX.'upload/exercise_video/');
define('EXERCISE_VIDEO', UPLOAD.'exercise_video/');
define('API', UPLOAD.'api/');
// define('THERAPIST_PROFILE', UPLOAD.'therapist_profile_pic/');

?>