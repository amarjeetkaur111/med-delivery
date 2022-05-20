<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitBatch;
use App\Models\Goods;
use App\Models\Batch;
use App\Models\BatchAssign;
use App\Models\UserModel;
use App\Http\Resources\TagsResource;
use App\Models\CustomerVisit;

use Session;


class BatchedOrderController extends Controller
{
    public function BatchedOrders($id = '')
    {
        $editassigedbatch = '';
        if($id != null)
        {
            // get_d($id);
            $id = decval($id);
            $matchdate = date('Y-m-d');
            $editassigedbatch = Batch::with('Assigned')
                                ->whereHas('Assigned', function($query) use($id, $matchdate) {
                                    $query->where('DriverID',$id);
                                    $query->whereDate('AssignedDate',$matchdate);
                                })
                                ->get()
                                ->toArray();
        }

        $matchdate = date('y-m-d');

        if(Session::get('usertype') == "Admin")
        {
            $batchdetails = Batch::with('VisitBatch.CustomerVisit.Status.Goods', 'VisitBatch.CustomerVisit.Customer.PharmacyCustomer.Pharmacy','VisitBatch.CustomerVisit.Scheduler','Assigned.Driver')
                                    ->where('VisitBatchID','LIKE', $matchdate.'%')
                                    ->get()
                                    ->toArray();

        }else{
            $pharmacyid = GetPharmacyId(Session::get('user'));
            $batchdetails = Batch::with('VisitBatch.CustomerVisit.Status.Goods', 'VisitBatch.CustomerVisit.Customer.PharmacyCustomer.Pharmacy','VisitBatch.CustomerVisit.Scheduler','Assigned.Driver')
                                    ->whereHas('VisitBatch.CustomerVisit.Customer.PharmacyCustomer.Pharmacy', function($query) use($pharmacyid){
                                        $query->where('PharmacyId', $pharmacyid);
                                        })
                                    ->where('VisitBatchID','LIKE', $matchdate.'%')
                                    ->get()
                                    ->toArray();

        }
        for($i = 0; $i < count($batchdetails); $i++)
        {
            for($j = 0; $j < count($batchdetails[$i]['visit_batch']); $j++)
            {
                $tagscolor = [];
                $tags = $batchdetails[$i]['visit_batch'][$j]['customer_visit']['scheduler']['Tags'];
                $k = 0;
                if(!empty($tags))
                {
                    foreach(explode(',',$tags) as $tag){
                        $TagColor = GetTabInfo($tag);
                        $tagscolor[$k]['color'] =  $TagColor['TagColor'];
                        $tagscolor[$k]['name'] =  $TagColor['TagName'];
                        $k++;
                    }
                }
                $batchdetails[$i]['visit_batch'][$j]['customer_visit']['scheduler']['Tags'] = $tagscolor;
            }
            if ($batchdetails[$i]['assigned'] != "")
            { 
                $batchdetails[] = $batchdetails[$i];
                unset($batchdetails[$i]);
            }
        }
        // uasort($batchdetails, function($a, $b){
        //     return $a['assigned'] - $b['assigned'];
        // });
            // get_d($batchdetails);


        return view('Orders.BatchedOrders.BatchedOrders',['batchdata' => $batchdetails , 'editassigedbatch' => $editassigedbatch]);
    }

    public function EditBatch(Request $req)
    {
        // get_d($req->all());
        $id = $req->input('BatchID');
        $batchID = Batch::where('VisitBatchID',$id)->get('BatchID')->toArray();
        $batchID = $batchID[0]['BatchID'];
        // get_d($batchID);

        $flag = 0;
        $batchid = date('y-m-d');

        $selectedorders = $req->input('SelectedOrder');
        sort($selectedorders);
        for($i = 0; $i < count($selectedorders); $i++)
        {
            $batchid .= "_".$selectedorders[$i];
        }

        $visitbatchdelete = VisitBatch::where('BatchID',$batchID)->delete();
        Batch::where('BatchID', $batchID)->update(array('VisitBatchID' => $batchid));
        $batch = Batch::find($batchID);

        for($j = 0; $j < count($selectedorders); $j++)
        {
            $visitbatch = new VisitBatch();
            $visitbatch->CustomerVisitID = $selectedorders[$j];
            if($batch->VisitBatch()->save($visitbatch))
                $flag = 1;
            else
                $flag = 0;
        }
        if($flag == 1)
        {
            $req->session()->flash('msg','Batch Modified Successfully');
            // return redirect('/orders/batched_orders');
             $url = $req->input('url');
            return redirect($url);
        }
        // get($batchid);
    }

    public function DeleteBatch($id)
    {
        $id = decval($id);
        $batchdel = new Batch();
        $batchdel = Batch::with('VisitBatch')->find($id);
        if($batchdel->delete())
            echo "Batch Deleted";
    }

    public function SendToDelivery(Request $req)
    {
        $batches = [];
        $batches = implode(',',$req->input('SelectedBatch'));
        // get($req->all());
        $driver = UserModel::where('UserType','Driver')->get()->toArray();
        // get_d($batches);
        return view('Orders.BatchedOrders.AssignToDriver',['batch' => $batches, 'drivers' => $driver]);
    }

    public function SelectDriver($id)
    {
        $driver = UserModel::where('Id',$id)->get()->toArray();
        return response()->json($driver,200);
    }

    public function AssignToDriver(Request $req)
    {
        $flag = 0;
        $batches = explode(',',$req->input('Batches'));
        // get_d($batches);
        foreach($batches as $batch)
        {
            // $batchid = Batch::where('VisitBatchID',$batch)->get()->toArray();
            $assign = new BatchAssign();
            $assign->BatchID = $batch;
            $assign->DriverID = $req->input('Driver');
            if($assign->save())
                $flag = 1;
        }

        if($flag == 1)
        {
            $req->session()->flash('msg','Successfullly Assigned Batch(s) to Driver');
            return redirect('/orders/batched_orders');
        }
    }

    public function UnassignBatch($id)
    {
        $id = decval($id);
        $batchid = BatchAssign::where('BatchID',$id);
        if($batchid->delete())
            echo "Batch Deleted";
    }

    public function EditAssignedBatches(Request $req)
    {
        // get_d($req->all());
        $matchdate = date('Y-m-d');
        $driverid = $req->input('DriverId');
        $batchdel = BatchAssign::where('DriverID',$driverid)->whereDate('AssignedDate',$matchdate)->delete();
        $selectedbatch = $req->input('SelectedBatch');

        for($j = 0; $j < count($selectedbatch); $j++)
        {
            $newassignedbatch = new BatchAssign();
            $newassignedbatch->BatchID = $selectedbatch[$j];
            $newassignedbatch->DriverID = $driverid;
            if($newassignedbatch->save())
                $flag = 1;
            else
                $flag = 0;
        }
        if($flag == 1)
        {
            $req->session()->flash('msg','Batches Modified Successfully');
            return redirect('/orders/batched_orders/delivered_batches/');
        }
    }

    public function DeliveredBatches(Request $req)
    {
        $batchassigned = '';
        if($req->input('SelectedDrivers') != null)
        {
            // get_d($req->input('SelectedDrivers'));
            $driverids = $req->input('SelectedDrivers');
            $matchdate = date('y-m-d');
            $matchdate2 = date('Y-m-d');
            if(Session::get('usertype') == "Admin")
            {
                $batchassigned = UserModel::with(['BatchAssigned.Batch.VisitBatch.CustomerVisit.Customer.PharmacyCustomer.Pharmacy','BatchAssigned.Batch.VisitBatch.CustomerVisit.Status.Goods',
                                            'BatchAssigned' =>  function($query) use($matchdate2){
                                            $query->whereDate('AssignedDate',$matchdate2);}
                                        ])
                                        ->whereIn('Id', $driverids)
                                        ->get()
                                        ->toArray();
            }else{
                $pharmacyid = GetPharmacyId(Session::get('user'));
                $batchassigned = UserModel::with(['BatchAssigned.Batch.VisitBatch.CustomerVisit.Customer.PharmacyCustomer.Pharmacy' =>
                                        function($query) use($pharmacyid){
                                        $query->where('PharmacyId', $pharmacyid);
                                        },
                                        'BatchAssigned.Batch.VisitBatch.CustomerVisit.Status.Goods',
                                        'BatchAssigned' =>  function($query) use($matchdate2){
                                            $query->whereDate('AssignedDate',$matchdate2);}
                                        ])
                                        ->whereIn('Id', $driverids)
                                        ->get()
                                        ->toArray();
            }
        }

            // get_d($batchassigned);
        $drivers = UserModel::where('UserType','Driver')->get()->toArray();
        return view('Orders.BatchedOrders.DeliveredBatches',['batchassigned' => $batchassigned, 'drivers' => $drivers]);
    }

    public function VisitCount()
    {
        $todaydate = date('Y-m-d');
        $matchdate = date('y-m-d');
        $countdetails = [];
        $countdetails['batch'] = Batch::where('VisitBatchID','LIKE', $matchdate.'%')->count();

        $countdetails['pending'] = CustomerVisit::where('VisitStatusID',1)
                                        ->where('VisitDate', $todaydate)
                                        ->count();
        $countdetails['complete'] = CustomerVisit::where('VisitStatusID',2)
                                        ->where('VisitDate', $todaydate)
                                        ->count();
        $countdetails['skip'] = CustomerVisit::where('VisitStatusID',3)
                                        ->where('VisitDate', $todaydate)
                                        ->count();
        $countdetails['cancel'] = CustomerVisit::where('VisitStatusID',4)
                                        ->where('VisitDate', $todaydate)
                                        ->count();
        $countdetails['postpone'] = CustomerVisit::where('VisitStatusID',5)
                                        ->where('VisitDate', $todaydate)
                                        ->count();
        $countdetails['return'] = CustomerVisit::where('VisitStatusID',6)
                                        ->where('VisitDate', $todaydate)
                                        ->count();
        $countdetails['undelivered'] = CustomerVisit::where('VisitStatusID',7)
                                        ->where('VisitDate', $todaydate)
                                        ->count();

         return  response()->json(['data' => $countdetails]);
    }

}
