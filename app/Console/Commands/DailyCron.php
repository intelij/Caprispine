<?php

namespace App\Console\Commands;
use DB;
use Illuminate\Console\Command;

class DailyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:completePendingVisit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete All Pending Visit on Today Date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $currentDate = date('Y-m-d');
        $outTime = date('H:i:s');
        $status = 'complete';
        $rating = 1;
        $getTodaysVisit = DB::table('daily_entry')->where('app_booked_date',$currentDate)->where('status','pending')->get();
        if(count($getTodaysVisit) > 0){
            foreach($getTodaysVisit as $allVisit) {
                $visitId = $allVisit->id;
                $visitType = $allVisit->type;
                if($visitType == 1){
                    // for per day visit
                    $data = array();
                    $data['rating'] = $rating;
                    $data['status'] = $status;
                    $data['out_time'] = $outTime;
                    DB::table('daily_entry')->where('id',$visitId)->update($data);
                }else{
                    // for package wise visit
                    $dailyEntryDetails = DB::table('daily_entry')->where('id',$visitId)->first();
                    $appId = $dailyEntryDetails->appointment_id;
                    $therapistId = $dailyEntryDetails->therapist_id;
                    $appDetails = DB::table('appointment')->where('id',$appId)->first();
                    $packageId = $appDetails->package_type;
                    $jointName = $appDetails->joints;
                    
                    $id = $visitId;
                    $amount = $dailyEntryDetails->amount;
                    $inTime = $dailyEntryDetails->in_time;
                    $outTime = date('H:i:s');
                    $checkVisitCount = DB::table('daily_entry')->where('appointment_id',$appId)->where('status','complete')->count('id');

                    if(!empty($inTime) && !empty($outTime)){
                        $jointName = $appDetails->joints;
                        if($checkVisitCount > 1){
                            if($jointName == 'one_joint'){
                                $ntTime = strtotime("+70 minutes", strtotime($inTime));    //10 minutes extra of 60 min
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
                                $ntTime = strtotime("+100 minutes", strtotime($inTime));    //10 minutes extra of 60 min
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

                    $appointmentDueDays = $appDetails->due_package_days;
                    if(($appointmentDueDays != 0) && ($appointmentDueDays != '') && ($dailyEntryDetails->type == 2)){
                        //if package update then due days of package entries
                        $updateAppData = array();
                        $updateAppData['due_package_days'] = $appointmentDueDays - 1;
                        DB::table('appointment')->where('id',$appId)->update($updateAppData);
                    }

                    // daily entry update
                    $data = array();
                    $data['rating'] = $rating;
                    $data['status'] = $status;
                    $data['out_time'] = $outTime;
                    $data['penalty'] = $penalty;
                    DB::table('daily_entry')->where('id',$id)->update($data);
                }
            }
        }
        $this->info('Pending Visit Complete Successfully');
    }
}
