<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SchedulerRecurrence;
use App\Models\CustomerVisit;
use App\Models\VisitStatus;


class DailyVisit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:visit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retriving Visits for Todays Date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function weekOfMonth($date) 
    {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        return $this->weekOfYear($date) - $this->weekOfYear($firstOfMonth) + 1;
    }
    
    public  function weekOfYear($date) 
    {
        $weekOfYear = intval(date("W", $date));
        if (date('n', $date) == "1" && $weekOfYear > 51) {
            // It's the last week of the previos year.
            $weekOfYear = 0;    
        }
        return $weekOfYear;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $result = daily_visit_insert();         
        echo $result;        
        // foreach($result as $result)
        // {
        //     print_r($result);
        // }
        echo date("l jS \of F Y h:i:s A");
    }
}
