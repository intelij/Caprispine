<?php

/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (){
    if(Auth::check()){
		return redirect('dashboard');
    }else{
    	return view('auth.login');
    }
});

// // Middleware
// Route::group(array('middleware' => ['admin', 'reception']),function ()
// {
//     Route::get('dashboard', 'DashboardController@index');
// });


Auth::routes();
//Dashboard
Route::get('dashboard', 'DashboardController@index');
//State City
Route::get('myform/ajax/{id}',array('as'=>'myform.ajax','uses'=>'DashboardController@myformAjax'));


//Add Patient
Route::get('sign-up-patient', 'WelcomeController@signUpPatient');
Route::post('add-patient', 'WelcomeController@savePatient');
Route::get('patient-otp-verification/{id}', 'WelcomeController@getOtp');
Route::post('verify-otp','WelcomeController@patientOtpVerification');
Route::get('resend-otp/{id}', 'WelcomeController@resendOTP');
//Location
Route::get('location', 'SettingController@location');
Route::post('add-location', 'SettingController@addLocation');
Route::get('edit-location/{id}', 'SettingController@editLocation');
Route::post('update-location/{id}', 'SettingController@updateLocation');
Route::get('delete-location/{id}', 'SettingController@deleteLocation');
//Service
Route::get('service', 'SettingController@service');
Route::post('add-service', 'SettingController@saveService');
Route::get('edit-service/{id}', 'SettingController@editService');
Route::post('update-service/{id}', 'SettingController@updateService');
Route::get('delete-service/{id}', 'SettingController@deleteService');
//Reference Type
Route::get('reference-type', 'SettingController@referenceType');
Route::post('add-reference', 'SettingController@addReference');
Route::get('edit-reference/{id}', 'SettingController@editReference');
Route::post('update-reference/{id}', 'SettingController@updateReference');
Route::get('delete-reference/{id}', 'SettingController@deleteReference');
// Capri Point
Route::get('capri-point', 'SettingController@capriPoint');
Route::post('save-cpoint', 'SettingController@saveCPoint');
Route::get('edit-cpoint/{id}', 'SettingController@editCPoint');
Route::post('update-cpoint/{id}', 'SettingController@updateCpoint');
//Appointment
Route::get('add-appointment', 'AppointmentController@addAppointment');
Route::post('save-appointment', 'AppointmentController@saveAppointment');
Route::get('all-appointment', 'AppointmentController@allAppointment');
Route::post('search-all-appointment', 'AppointmentController@allAppointment');
Route::get('view-appointment/{id}', 'AppointmentController@myAllAppointmentDetails');
Route::get('approve-appointment/{id}', 'AppointmentController@approveAppointment');
Route::get('cancel-appointment/{id}', 'AppointmentController@cancelAppointment');
Route::post('complete-appointment', 'AppointmentController@completeAppointment');
Route::get('edit-appointment/{id}', 'AppointmentController@editAppointment');
Route::post('update-appointment/{id}', 'AppointmentController@updateAppointment');
Route::get('my-appointment', 'AppointmentController@myAppointment');
Route::get('reminder-appointment/{id}', 'AppointmentController@reminderAppointment');
Route::get('check-booked-appointment-datetime/{therapistId}/{bookedDate}/{bookedTime}','AppointmentController@checkAppointmentAvailability');
Route::get('therapist-patient-wise/{patientId}', 'AppointmentController@therapistPatientWise');
Route::post('per-day-visit-appointment', 'AppointmentController@perDayVisitAppointment');
Route::post('package-wise-visit-appointment', 'AppointmentController@packageWiseVisitAppointment');
Route::get('packages-list/{serviceId}', 'AppointmentController@allPackageList');
Route::get('package-wise-joint/{jointName}', 'AppointmentController@packageWiseJointDetails');
Route::get('per-day-daily-entry/{id}','AppointmentController@perDayDailyEntry');
Route::get('getPerDayEntryDetails/{id}', 'AppointmentController@getPerDayEntryDetails');
Route::post('edit-per-day-entry', 'AppointmentController@editPerDayDailyEntry');
Route::get('package-wise-entry/{id}', 'AppointmentController@getPackageWiseEntry');
Route::post('package-visit-appointment', 'AppointmentController@packageWiseVisitAppointment');
Route::get('getPackageWiseEntryDetails/{id}', 'AppointmentController@packageWiseDetails');
Route::post('edit-package-wise-entry', 'AppointmentController@updatePackageWiseEntry');
Route::post('package-rating', 'AppointmentController@assignTreatmentRating');
Route::post('per-day-rating', 'AppointmentController@assignPerDayTreatmentRating');
Route::get('update-time-daily-entry/{id}', 'AppointmentController@updateTimeDailyEntry');
Route::get('cancel-perday-visit/{id}', 'AppointmentController@cancelPerdayVisit');
Route::get('cancel-package-visit/{id}', 'AppointmentController@cancelPackageVisit');
Route::get('update-time-daily-entry-for-package/{id}', 'AppointmentController@updateTimeDailyEntryForPackage');
Route::get('visit-history/{id}', 'AppointmentController@allVisitHistory');
Route::get('all-daily-visits', 'AppointmentController@allDailyVisits');
Route::post('search-all-visits', 'AppointmentController@allDailyVisits');
Route::get('check-therapist-attendance/{appId}', 'AppointmentController@checkTherapistAttendance');
Route::get('check-payment-status/{appId}', 'AppointmentController@appointmentPaymentStatus');
Route::get('check-validation-daily-entry-comming/{visitId}', 'AppointmentController@checkValidationDailyEntry');
Route::get('check-book-appointment/{appId}', 'AppointmentController@checkBookAppointment');
Route::post('convert-daily-visit', 'AppointmentController@convertDailyVisit');
Route::get('approved-patient-visit/{id}', 'AppointmentController@approvedPendingPatientVisit');
Route::get('daily-visit-list-export', 'AppointmentController@dailyVisitListExport');
Route::get('daily-visit-perday-list-export/{id}', 'AppointmentController@dailyVisitPerdayListExport');
Route::get('daily-visit-package-list-export/{id}', 'AppointmentController@dailyVisitPackageListExport');
Route::get('google-ranking-notification/{id}','AppointmentController@googleRankingNotification');
Route::get('generate-receipt/{id}', 'AppointmentController@generateReceipt');
Route::post('save-invoice-details-for-perday', 'AppointmentController@saveInvoiceDetailsForPerday');
Route::post('save-invoice-details-for-package', 'AppointmentController@saveInvoiceDetailsForPackage');
Route::get('generate-receipt-package/{id}', 'AppointmentController@generateReceiptPackage');
Route::get('get-consent-record/{id}', 'AppointmentController@getConsentRecord');
Route::get('approved-report-for-patient/{id}', 'AppointmentController@approvedReportForPatient');
Route::get('complimentary-entry/{id}', 'AppointmentController@complimentaryEntry');
Route::post('complimentary-visit-appointment','AppointmentController@complimentaryVisitAppointment');
Route::get('update-time-daily-entry-for-complimentary/{id}','AppointmentController@updateTimeDailyForComplimentary');

//UserType
Route::get('user-type', 'SettingController@userType');
Route::post('add-usertype', 'SettingController@addUserType');
Route::get('edit-usertype/{id}', 'SettingController@editUserType');
Route::post('update-usertype/{id}', 'SettingController@updateUserType');
Route::get('delete-usertype/{id}', 'SettingController@deleteUserType');
//Modules
Route::get('module', 'SettingController@modules');
Route::post('add-module', 'SettingController@addModule');
Route::get('edit-module/{id}', 'SettingController@editModule');
Route::post('update-module/{id}', 'SettingController@updateModule');
Route::get('delete-module/{id}', 'SettingController@deleteModule');
//Submodule
Route::get('submodule', 'SettingController@submodule');
Route::post('add-submodule', 'SettingController@saveSubModule');
Route::get('edit-sub-module/{id}', 'SettingController@editSubModule');
Route::post('update-submodule/{id}', 'SettingController@updateSubModule');
Route::get('delete-sub-module/{id}', 'SettingController@deleteSubModule');
//Amount
Route::get('amount', 'SettingController@amount');
Route::post('add-amount', 'SettingController@saveAmount');
Route::get('edit-amount/{id}', 'SettingController@editAmount');
Route::post('update-amount/{id}', 'SettingController@updateAmount');
Route::get('delete-amount/{id}', 'SettingController@deleteAmount');
//Packages
Route::get('package', 'SettingController@package');
Route::post('add-package', 'SettingController@savePackage');
Route::get('edit-package/{id}', 'SettingController@editPackage');
Route::post('update-package/{id}', 'SettingController@updatePackage');
Route::get('delete-package/{id}', 'SettingController@deletePackage');
//Appointment Time Slot
Route::get('appointment-time-slot', 'SettingController@appointmentTimeSlot');
Route::post('add-appointment-time', 'SettingController@addAppointmentTimeSlot');
Route::get('edit-appointment-time/{id}', 'SettingController@editAppointmentTimeSlot');
Route::post('update-appointment-time/{id}', 'SettingController@updateAppointmentTimeSlot');
Route::get('delete-appointment-time/{id}', 'SettingController@deleteAppointmentTimeSlot');
//Module Assignment
Route::get('module-assignment', 'ModuleAssignmentController@moduleAssignment');
Route::get('add-permission/{id}', 'ModuleAssignmentController@addPermission');
Route::post('update-module-assignment', 'ModuleAssignmentController@updatePermission');
Route::get('edit-module-assignment/{id}', 'ModuleAssignmentController@editModulePermission');
Route::post('update-new-assignment/{id}', 'ModuleAssignmentController@updateNewAssignment');
Route::get('sub-module-data/{id}', 'ModuleAssignmentController@allSubModule');
Route::get('delete-module-assignment/{id}', 'ModuleAssignmentController@deleteModuleAssignment');
//Staff
Route::get('all-users', 'UserController@allUsers');
Route::get('add-user', 'UserController@addUser');
Route::post('save-user', 'UserController@saveUser');
Route::get('view-user/{id}', 'UserController@viewUser');
Route::get('edit-user/{id}', 'UserController@editUser');
Route::post('update-user/{id}', 'UserController@updateUser');
Route::get('mark-attandance-staff/{id}', 'UserController@markAttandance');
//Patient
Route::get('all-patient', 'PatientController@allPatient');
Route::get('add-patient', 'PatientController@addPatient');
Route::post('save-patient', 'PatientController@savePatient');
Route::get('therapist-assignment/{id}', 'PatientController@therapistAssignment');
Route::post('assign-therapist', 'PatientController@assignTherapist');
Route::get('view-patient/{id}', 'PatientController@patientDetails');
Route::get('edit-patient/{id}', 'PatientController@editPatient');
Route::post('update-patient/{id}', 'PatientController@updatePatient');
Route::get('check-duplicateNo/{contactNo}', 'PatientController@checkDuplicateContactNo');
Route::get('visit-details-report/{id}', 'PatientController@visitDetailsReport');
Route::post('search-patients', 'PatientController@allPatient');

//CMS
Route::get('cms', 'SettingController@cmsManagement');
Route::post('add-cms', 'SettingController@addCMS');
Route::post('update-cms', 'SettingController@updateCMS');
//Profile
Route::get('my-profile', 'ProfileController@myProfile');
Route::post('update-profile', 'ProfileController@updateProfile');
Route::get('change-password', 'ProfileController@changePassword');
Route::post('reset-password', 'ProfileController@resetPassword');
//Contact Us
Route::get('contact-us', 'SettingController@contactUs');
Route::post('add-contact-us', 'SettingController@saveContactUs');
//Therapist
Route::get('all-therapist', 'TherapistController@allTherapist');
Route::get('add-therapist', 'TherapistController@addTherapist');
Route::post('save-therapist', 'TherapistController@saveTherapist');
Route::get('view-therapist/{id}', 'TherapistController@viewTherapist');
Route::get('edit-therapist/{id}', 'TherapistController@editTherapist');
Route::post('update-therapist/{id}', 'TherapistController@updateTherapist');
Route::get('penalty-amount/{penaltyId}', 'TherapistController@penaltyAmount');
Route::post('therapist-penalty', 'TherapistController@therapistPenalty');
Route::get('all-penalty/{id}', 'TherapistController@allPenaltyHistory');
Route::post('search-penalty/{id}', 'TherapistController@allPenaltyHistory');
Route::get('mark-attandance/{id}', 'TherapistController@markAttandance');
Route::get('check-attendance-of-therapist/{therapistId}', 'TherapistController@checkTherapistAttendance');
//Penalty
Route::get('penalty', 'SettingController@penalty');
Route::post('add-penalty', 'SettingController@addPenalty');
Route::get('edit-penalty/{id}', 'SettingController@editPenalty');
Route::post('update-penalty/{id}', 'SettingController@updatePenalty');
Route::get('delete-penalty/{id}', 'SettingController@deletePenalty');
//IPD Calender
Route::get('ipd', 'SettingController@IPDCalender');
Route::post('add-ipd-remark', 'SettingController@addIPDCalender');
Route::get('edit-ipd-remark/{id}', 'SettingController@editIPDCalender');
Route::post('update-ipd-remark/{id}', 'SettingController@updateIPDCalender');
Route::get('delete-ipd-remark/{id}', 'SettingController@deleteIPDCalender');
//All Attendance
Route::get('all-attendance','SettingController@allAttendance');
Route::post('search-attendance', 'SettingController@allAttendance');
// All Penalty
Route::get('all-therapist-penalty', 'SettingController@allTherapistPenalty');
Route::post('search-therapist-penalty', 'SettingController@allTherapistPenalty');
Route::get('all-attendance-report/{id}', 'SettingController@allAttendanceReport');
Route::get('all-appointment-penalty/{id}', 'SettingController@allAppointmentPenalty');
// Change Password for all staff
Route::get('select-user-change-password', 'SettingController@displayAllUser');
Route::get('select-user-basedon-usertype/{id}','SettingController@selectUserAccordingToUserType');
Route::post('select-user-for-change-password', 'SettingController@selectUserForChangePassword');
Route::post('reset-password-for-user', 'SettingController@resetPasswordForUser');
// Machine
Route::get('machine', 'SettingController@machine');
Route::post('add-machine', 'SettingController@saveMachine');
Route::get('edit-machine/{id}', 'SettingController@editMachine');
Route::post('update-machine/{id}', 'SettingController@updateMachine');
//Report
// Route::get('therapist-report', 'ReportController@therapistReport');
// Route::post('search-report', 'ReportController@therapistReport');
Route::get('therapist-report-export', 'ReportController@exportTherapistReport');
Route::get('all-report', 'ReportController@allReport');
Route::post('search-all-report', 'ReportController@allReport');
Route::get('patient-report', 'ReportController@patientReport');
Route::post('search-patient-report', 'ReportController@searchPatientReport');
Route::get('all-staff-collection/{id}/{to_date}/{from_date}', 'ReportController@allStaffCollection');
Route::get('therapist-report', 'ReportController@privateHomeCareTherapistReport');
Route::post('search-therapist-report', 'ReportController@privateHomeCareTherapistReport');

// Patient Exam
Route::get('select-patient', 'PatientExamController@selectPatientForExam');
Route::post('search-patient-exam', 'PatientExamController@searchPatientExam');
Route::get('all-patient-exam', 'PatientExamController@allPatientExam');
Route::get('exam-details/{key}/{patientId}', 'PatientExamController@examDetailsForPatient');

//Receipt
Route::get('general-receipt', 'ReceiptController@generapReceipt');
Route::post('save-general-invoice', 'ReceiptController@saveGeneralInvoice');
Route::get('invoice-view/{id}', 'ReceiptController@invoiceViewDetails');
Route::get('invoice-normal-view/{id}', 'ReceiptController@normalInvoiceDetails');
Route::get('download-pdf','ReceiptController@pdf');
Route::get('package-receipt','ReceiptController@packageReceipt');
Route::get('reference-duplicate/{refNo}', 'ReceiptController@referenceDuplicancy');
Route::post('save-package-invoice', 'ReceiptController@savePackageInvoice');
Route::get('refund-amount-details/{invoiceId}/{joint}', 'ReceiptController@refundPolicy');
Route::post('save-refund-amount', 'ReceiptController@saveRefundAmount');
Route::get('refund-view/{id}', 'ReceiptController@RefundViewDetails');
Route::get('cancel-invoice/{id}', 'ReceiptController@cancelInvoice');
Route::get('user-details-traetment-wise/{flag}', 'ReceiptController@getUserDetailsTreatementWise');
// Banner
Route::get('banner', 'SettingController@banner');
Route::post('save-banner', 'SettingController@saveBanner');
Route::get('update-banner-status/{id}', 'SettingController@updateBannerStatus');
// Exercise
Route::get('all-exercise','SettingController@allExercise');
Route::post('save-exercise', 'SettingController@saveExercise');
Route::get('update-exercise-status/{id}', 'SettingController@updateExerciseStatus');
Route::get('all-exercise-video/{id}', 'SettingController@allExerciseVideo');
Route::post('add-exercise-video', 'SettingController@addExerciseVideo');
Route::get('update-exercise-video-status/{id}', 'SettingController@updateExerciseVideoStatus');
Route::get('editExercise/{id}', 'SettingController@editExercise');
Route::post('update-exercise', 'SettingController@updateExercise');
Route::get('editExerciseVideos/{id}','SettingController@editExerciseVideos');
Route::post('update-exercise-video','SettingController@updateExerciseVideo');

// Consultation Amount
Route::get('consultation-amount', 'SettingController@consultationAmount');
// Notification
Route::get('notification', 'SettingController@notification');
Route::post('save-send-notification', 'SettingController@saveSendNotification');
Route::get('get-notification-details/{id}', 'SettingController@getNotificationDetails');
Route::post('update-notifications', 'SettingController@updateNotifications');

// Capri Priviledge Point
Route::get('all-capri-points', 'CapriPointController@allCapriPoint');
Route::get('accept-pending-wallet-request/{id}', 'CapriPointController@acceptPendingWalletRequest');
Route::post('approve-pending-wallet-request', 'CapriPointController@approvePendingWalletRequest');

// Self Exercise
Route::get('all-self-exercise', 'SettingController@allSelfExerciseList');
Route::post('search-self-exercise', 'SettingController@allSelfExerciseList');

// Send Mail
Route::get('mail/send', 'MailController@sendMailMessage');

// APIs
Route::group(['prefix'=>'api'], function(){
	// general controller
	Route::post('allBranch', 'API\GeneralController@allBranch');
	Route::post('allService', 'API\GeneralController@allService');
	Route::post('allReference', 'API\GeneralController@allReference');
	Route::post('state', 'API\GeneralController@allState');
	Route::post('city', 'API\GeneralController@allCities');
	Route::post('ruleOfCapri', 'API\GeneralController@ruleOfCapri');
	Route::post('allTimeSlot', 'API\GeneralController@allTimeSlot');
	Route::post('allBanners', 'API\GeneralController@allBanners');
	Route::post('cms', 'API\GeneralController@cms');
	Route::post('allPackage', 'API\GeneralController@allPackage');
	Route::post('allExercise','API\GeneralController@allExercise');
	Route::post('exerciseVideo', 'API\GeneralController@exerciseVideo');

	// // Hit Cron
	// Route::post('birthdayWishesCron', 'API\GeneralController@birthdayWishesCron');
	// Route::post('PendingVisitCompleteCron', 'API\GeneralController@PendingVisitCompleteCron');
	// Route::post('visitReminderCron', 'API\GeneralController@visitReminderCron');
	// Route::post('exerciseNotificationCron', 'API\GeneralController@exerciseNotificationCron');
	
	// therapist controller
	Route::post('login', 'API\TherapistController@login');
	Route::post('forgetPasswordForTherapist', 'API\TherapistController@forgetPasswordForTherapist');
	Route::post('therapistProfile', 'API\TherapistController@therapistProfile');
	Route::post('allPendingVisits', 'API\TherapistController@allPendingVisits');
	Route::post('allNextPendingVisits', 'API\TherapistController@allNextPendingVisits');
	Route::post('allCompleteVisits', 'API\TherapistController@allCompleteVisits');
	Route::post('markAttendance', 'API\TherapistController@markAttendance');
	Route::post('monthlyTherapistReport', 'API\TherapistController@monthlyTherapistReport');
	Route::post('allTherapistReport', 'API\TherapistController@allTherapistReport');
	Route::post('dailyEntryDetails', 'API\TherapistController@dailyEntryDetails');
	Route::post('editDailyEntryDetails', 'API\TherapistController@editDailyEntryDetails');
	Route::post('allNotification', 'API\TherapistController@allNotification');
	Route::post('monthlyCalendar', 'API\TherapistController@monthlyCalendar');
	Route::post('branchFaculty', 'API\TherapistController@branchFaculty');
	Route::post('attendVisit', 'API\TherapistController@attendVisit');
	Route::post('addChiefComplaint', 'API\TherapistController@saveChiefComplaint');
	Route::post('chiefComplaints', 'API\TherapistController@allChiefComplaint');
	Route::post('addHistoryExam', 'API\TherapistController@addHistoryExam');
	Route::post('HistoryExams', 'API\TherapistController@HistoryExams');
	Route::post('addPainExams', 'API\TherapistController@addPainExams');
	Route::post('painExam', 'API\TherapistController@painExam');
	Route::post('addPhysicalExam', 'API\TherapistController@addPhysicalExam');
	Route::post('physicalExam', 'API\TherapistController@physicalExam');
	Route::post('addBodyChart', 'API\TherapistController@addBodyChart');
	Route::post('allBodyChart', 'API\TherapistController@allBodyChart');
	Route::post('addDiagnosis', 'API\TherapistController@addDiagnosis');
	Route::post('allDiagnosis', 'API\TherapistController@allDiagnosis');
	Route::post('addNotes', 'API\TherapistController@addNotes');
	Route::post('allNotes', 'API\TherapistController@allNotes');
	Route::post('addSensoryExam', 'API\TherapistController@addSensoryExam');
	Route::post('allSensoryExam', 'API\TherapistController@allSensoryExam');
	Route::post('addSpecialExam', 'API\TherapistController@addSpecialExam');
	Route::post('allSpecialExam', 'API\TherapistController@allSpecialExam');
	Route::post('addNDTNDPExam', 'API\TherapistController@addNDTNDPExam');
	Route::post('allNDTNDPExam', 'API\TherapistController@allNDTNDPExam');
	Route::post('addNeurologicalExam', 'API\TherapistController@addNeurologicalExam');
	Route::post('allNeurologicalExam', 'API\TherapistController@allNeurologicalExam');
	Route::post('addInvestigationExam', 'API\TherapistController@addInvestigationExam');
	Route::post('allInvestigationExam', 'API\TherapistController@allInvestigationExam');
	Route::post('adlNeck', 'API\TherapistController@adlNeck');
	Route::post('allAdlNeck', 'API\TherapistController@allAdlNeck');
	Route::post('adlHip', 'API\TherapistController@adlHip');
	Route::post('allAdlHip', 'API\TherapistController@allAdlHip');
	Route::post('adlKnee', 'API\TherapistController@adlKnee');
	Route::post('allAdlKnee', 'API\TherapistController@allAdlKnee');
	Route::post('adlElbow', 'API\TherapistController@adlElbow');
	Route::post('allAdlElbow', 'API\TherapistController@allAdlElbow');
	Route::post('adlShoulder', 'API\TherapistController@adlShoulder');
	Route::post('allAdlShoulder', 'API\TherapistController@allAdlShoulder');
	Route::post('adlWristAndHand', 'API\TherapistController@adlWristAndHand');
	Route::post('allAdlWristAndHand', 'API\TherapistController@allAdlWristAndHand');
	Route::post('adlAnkeAndFoot', 'API\TherapistController@adlAnkeAndFoot');
	Route::post('allAdlAnkeAndFoot', 'API\TherapistController@allAdlAnkeAndFoot');
	Route::post('adlBack', 'API\TherapistController@adlBack');
	Route::post('allAdlBack', 'API\TherapistController@allAdlBack');
	Route::post('addMotorExam', 'API\TherapistController@addMotorExam');
	Route::post('allMotorExam', 'API\TherapistController@allMotorExam');
	Route::post('allMachineNames', 'API\TherapistController@allMachineNames');
	Route::post('doseAutoSuggestion', 'API\TherapistController@doseAutoSuggestion');
	Route::post('goalAutoSuggestion', 'API\TherapistController@goalAutoSuggestion');
	Route::post('treatmentGoal', 'API\TherapistController@treatmentGoal');
	Route::post('allTreatmentGoal', 'API\TherapistController@allTreatmentGoal');
	Route::post('addTreatmentGiven', 'API\TherapistController@addTreatmentGiven');
	Route::post('allTreatmentGiven', 'API\TherapistController@allTreatmentGiven');
	Route::post('addNewVisit', 'API\TherapistController@addNewVisit');
	Route::post('adlGraph', 'API\TherapistController@adlGraph');
	Route::post('painGraph', 'API\TherapistController@painGraph');
	Route::post('allAppointmentForTherapist', 'API\TherapistController@allAppointmentForTherapist');
	Route::post('withdrawalCapriPointRequest', 'API\TherapistController@withdrawalCapriPointRequest');
	Route::post('referralWithdrawl', 'API\TherapistController@referralWithdrawl');
	Route::post('userDetails','API\TherapistController@userDetails');
	Route::post('addConsentSignature', 'API\TherapistController@addConsentSignature');
	Route::post('motorGraph', 'API\TherapistController@motorGraph');
	Route::post('addExerciseCalender', 'API\TherapistController@addExerciseCalender');
	Route::post('allExerciseCalender', 'API\TherapistController@allExerciseCalender');
	Route::post('searchPatientDetails', 'API\TherapistController@searchPatientDetails');
	Route::post('addExtraTreatmentGiven', 'API\TherapistController@addExtraTreatmentGiven');
	Route::post('allExtraTreatmentGiven', 'API\TherapistController@allExtraTreatmentGiven');

	// IPD / Home Visit
	Route::post('addOrthoCase', 'API\TherapistController@addOrthoCase');
	Route::post('allOrthoCase', 'API\TherapistController@allOrthoCase');
	Route::post('addNeuroCase', 'API\TherapistController@addNeuroCase');
	Route::post('allNeuroCase', 'API\TherapistController@allNeuroCase');
	Route::post('ipdGraph', 'API\PatientController@ipdGraph');
	
	// report controller
	Route::post('patientCaseReport', 'API\ReportController@patientCaseReport');

	// Paytm gateway
	Route::post('orders', 'API\PaytmController@paytmchecksumgenerate');
	Route::post('savePaytmResponse', 'API\PaytmController@savePaytmResponse');
	Route::post('paymentHistory', 'API\PaytmController@allPaymentHistory');
	Route::post('walletPay', 'API\PaytmController@walletPay');

	// patient controller
	Route::post('register', 'API\PatientController@register');
	Route::post('verifyOtp', 'API\PatientController@verifyOtp');
	Route::post('loginPatient', 'API\PatientController@loginPatient');
	Route::post('forgetPassword', 'API\PatientController@forgetPassword');
	Route::post('resetPassword', 'API\PatientController@resetPassword');
	Route::post('patientProfile', 'API\PatientController@patientProfile');
	Route::post('resendOtp', 'API\PatientController@resendOtp');
	Route::post('patientPendingVisit', 'API\PatientController@patientPendingVisit');
	Route::post('patientCompleteVisit', 'API\PatientController@patientCompleteVisit');
	Route::post('allAppointmentForPatient', 'API\PatientController@allAppointmentForPatient');
	Route::post('bookAppointment', 'API\PatientController@bookAppointment');
	Route::post('bookAppThroughCallback', 'API\PatientController@bookAppThroughCallback');
	Route::post('allReferByList', 'API\PatientController@allReferByList');
	Route::post('patientChiefComplaint', 'API\PatientController@patientChiefComplaint');
	Route::post('patientAddChiefComplaint', 'API\PatientController@patientAddChiefComplaint');
	Route::post('patientAddHistoryExam', 'API\PatientController@patientAddHistoryExam');
	Route::post('patientAddNewVisit', 'API\PatientController@patientAddNewVisit');
	Route::post('patientComplaint', 'API\PatientController@patientComplaint');
	Route::post('totalWalletAmount', 'API\PatientController@totalWalletAmount');
	Route::post('invoiceHistory', 'API\PatientController@invoiceHistory');
	Route::post('allNotifications', 'API\PatientController@allNotifications');
	Route::post('makePackagePayment', 'API\PatientController@makePackagePayment');
	Route::post('allPackageHistory', 'API\PatientController@allPackageHistory');
	Route::post('dailyExerciseActivity','API\PatientController@dailyExerciseActivity');
	Route::post('allExerciseActivity','API\PatientController@allExerciseActivity');
});