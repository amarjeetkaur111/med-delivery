<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BatchResource;
use App\Http\Resources\CustomerVisitResource;
use App\Http\Resources\DailyBatchesResource;
use App\Models\Batch;
use App\Models\CustomerVisit;
use Illuminate\Http\Request;

class DailyBatchesController extends Controller
{
    public function index(Request $request){

        $todaydate = date('Y-m-d').' 00:00:00';

            $dailyVisit = CustomerVisit::select(['CustomerVisitID','CustomerID','VisitStatusID','SchedulerID'])
                ->where('VisitDate' , $todaydate)
                ->with('PharmacyUser')
                ->has('VisitBatch')
                ->whereHas('PharmacyUser',function($q) use ($request) {
                    $q->where('PharmacyId', $request->PharmacyID);
                })->get();

           if(isset($dailyVisit[0])){

                return DailyBatchesResource::collection($dailyVisit)->additional([
                    'Status'=>'Success',
                    'Message'=>'Data Fetch Successfully'
                ])->response()->setStatusCode(200);
           }else{
               return [
                   "Status" => 'Failed',
                   "Message" => 'No Visits Found Today :('
               ];
           }
    }

}
