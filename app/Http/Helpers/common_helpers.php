<?php

use App\Models\Pharmacy;
use App\Models\PharmacyUser;
use App\Models\SchedulerRecurrence;
use App\Models\CustomerVisit;
use App\Models\VisitStatus;
use App\Models\Scheduler;
use App\Models\Tag;
use App\Models\UserModel;


function get_d($data)
{
    echo "<pre>";
    print_r($data);
    die();
}

function get($data)
{
    echo "<pre>";
    print_r($data);
}


function encval($id)
{
     $Requri = $_SERVER['HTTP_HOST'];
     if(is_numeric(strpos($Requri, '127.0.0.1')))
     {
         return $id;
     }else
     {
        $enc = encrypt($id);
        return $enc;
     }
}

function decval($id)
{
     $Requri = $_SERVER['HTTP_HOST'];
     if(is_numeric(strpos($Requri, '127.0.0.1')))
     {
         return $id;
     }else
     {
        $dec = decrypt($id);
        return $dec;
     }
}


function weekOfMonth($date) {
    //Get the first day of the month.
    $firstOfMonth = strtotime(date("Y-m-01", $date));
    //Apply above formula.
    //$weekOfYear=weekOfYear($date);
    if(date('D',$firstOfMonth)=="Mon")
    {
        return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
    }
    else
    {
        return weekOfYear($date) - weekOfYear($firstOfMonth);
    }
}

function weekOfYear($date) {
    $weekOfYear = intval(date("W", $date));
    if (date('n', $date) == "1" && $weekOfYear > 51) {
        // It's the last week of the previos year.
        $weekOfYear = 0;
    }
    return $weekOfYear;
}

function GetTabInfo($TagId){

    $tag = Tag::where('TagId',$TagId)->first();

    return $tag;
}

function GetPhoteType($TypeId){

    $Type = '';
    if($TypeId == 1){ $Type = 'Home';   }
    if($TypeId == 2){ $Type = 'Mobile';   }
    if($TypeId == 3){ $Type = 'Work';   }
    if($TypeId == 4){ $Type = 'Fax';   }

    return $Type;
}

function GetPharmacyName($PharmacyId){

    $Pharmacy = Pharmacy::select('PharmacyName')->where('PharmacyID',$PharmacyId)->value('PharmacyName');
    return $Pharmacy;

}

function GetPharmacyId($email){
    $pharmacyid = PharmacyUser::select('PharmacyId')->whereHas('User', function($query) use($email){
                                $query->where('Email', $email);
                                })
                                ->get()->toArray();

    return $pharmacyid[0]['PharmacyId'];
}

function GetDriverId($email){
    $driverid = UserModel::select('Id')->where('Email', $email)
                                ->get()->toArray();

    return $driverid[0]['Id'];
}

function daily_visit_insert($scheduleId='')
{
    $result="";
    $insertedVisits = 0;
    if($scheduleId == '')
    {
        // $schedulercustomer = Scheduler::with('SchedulerRecurrence','SchedulerCustomer')
        //                     ->get()
        //                     ->toArray();
        $schedulercustomer = Scheduler::with('SchedulerRecurrence.Goods','SchedulerCustomer')
                            ->whereHas('SchedulerCustomer.Customer', function($query){
                                    $query->where('Status','1');
                            })
                            ->get()
                            ->toArray();
    }
    else
    {
        $schedulercustomer = Scheduler::with('SchedulerRecurrence.Goods','SchedulerCustomer')
                            ->whereHas('SchedulerCustomer.Customer', function($query){
                                    $query->where('Status','1');
                            })
                            ->where('SchedulerID',$scheduleId)
                            ->get()
                            ->toArray();

    }
        // return $schedulercustomer;
        // die();

    $todaydate = date('Y-m-d');
    foreach($schedulercustomer as $schedule_info)
    {
        if($schedule_info['EndDate'] =='' ||($schedule_info['EndDate']) >= $todaydate)
        {
            $medications=[];
            $x=0;
            foreach($schedule_info['scheduler_recurrence'] as $rec)
            {
               $enableentry = 1;
               if($rec['RecurrenceTypeID'] == 1 )
               {
                    $current_date = strtotime(date('Y-m-d'));

                    if(strtotime($schedule_info['StartDate'] )  == $current_date ){
                        $enableentry = 1;
                    }else{
                        $enableentry = 0;
                    }
                }

                if($rec['RecurrenceTypeID'] == 2)
                {
                    $enableentry = 1;
                }

                if($rec['RecurrenceTypeID'] == 3)
                {

                    $current_date = date('d-m-Y');
                    $start_date = strtotime($current_date);
                    $startday = idate('w', $start_date);
                    $explodedays = explode(',', $rec['RecurrenceSelectedDays']);
                    if(in_array($startday,$explodedays)){
                        $enableentry = 1;
                    }else{
                        $enableentry = 0;
                    }
                }

                if($rec['RecurrenceTypeID'] == 8)
                {
                    $current_date = date('d');
                    $explodedays = explode(',', $rec['RecurrenceSelectedDays']);
                    $dates = array();
                    foreach($explodedays as $savedates){
                        $dates[] = $savedates;
                    }
                    if(in_array($current_date,$dates)){
                        $enableentry = 1;
                    }else{
                        $enableentry = 0;
                    }
                }
                // 5 for 2 weeks & 6 for
                if($rec['RecurrenceTypeID'] == 4 || $rec['RecurrenceTypeID'] == 5 || $rec['RecurrenceTypeID'] == 6 || $rec['RecurrenceTypeID'] == 7)
                {

                    $date_from = strtotime($schedule_info['StartDate']);
                    if($schedule_info['EndDate'] == ''){
                        $date_to = strtotime(date('d-m-Y', strtotime('+1 years')));
                    }else{
                        $date_to = strtotime($schedule_info['EndDate']);
                    }

                    if($rec['RecurrenceTypeID'] == 4){
                        $whichweek = array(1);
                    }elseif($rec['RecurrenceTypeID'] == 5){
                        $whichweek = array(2);
                    }elseif($rec['RecurrenceTypeID'] == 6){
                        $whichweek = array(3);
                    }elseif($rec['RecurrenceTypeID'] == 7){
                        $whichweek = array(4);
                    }

                    $days = explode(',', $rec['RecurrenceSelectedDays']);

                    for($i=$date_from; $i<=$date_to; $i+=86400) {

                        $new_start_date = strtotime(date("Y-m-d", $i));
                        $noofweek = weekOfMonth($new_start_date);
                        $day = date('N', $new_start_date);
                        if(in_array($noofweek,$whichweek) && in_array($day,$days) && strtotime(date('Y-m-d')) == $new_start_date){
                            $enableentry = 1;
                            break;
                        }else{
                            $enableentry = 0;
                        }
                    }
                }

                if($enableentry == 1)
                {
                    $x++;
                    $medications[$x]['GoodsId']=$rec['goods']['GoodsId'];
                    $medications[$x]['GoodsAmt']=$rec['goods']['Cost'];
                }
            }

            if(count($medications) > 0)
            {
                echo $schedule_info['scheduler_customer']['CustomerID'];
                echo " ";
                echo $schedule_info['SchedulerID'];
                echo " ";

                $visit = new CustomerVisit();
                $visit->CustomerID = $schedule_info['scheduler_customer']['CustomerID'];
                $visit->SchedulerID = $schedule_info['SchedulerID'];
                $visit->VisitDate = $todaydate;
                $visit->EmployeeID =($schedule_info['EmployeeNumber'] != '') ? $schedule_info['EmployeeNumber'] : null;
                $visit->ArrivalLogTime =$schedule_info['StartTime'];
                $visit->FinishLogTime = $schedule_info['EndTime'];
                $visit->save();

                foreach($medications as $meds)
                {
                    $status = new VisitStatus();
                    $status->GoodsID = $meds['GoodsId'];
                    $status->GoodsAmt = $meds['GoodsAmt'];
                    $status->GoodsQty = 1;
                    $status->GoodsSubID = 0;
                    $status->GoodsStatusID = 0;
                    $status->GoodsTypeID = null;

                    $visit->Status()->save($status);

                    echo $meds['GoodsId'];
                    echo " ";
                }
                echo "\n";
            }
        }
    }
}



