<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Exports\OrderExport;
use App\Models\SchedulerCustomer;
use App\Models\Customer;
use App\Models\UserModel;
use App\Models\Pharmacy;
use App\Models\ActivityTable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;


class ReportsController extends Controller
{

    public function index()
    {
        return view('Reports.NewReports');
    }

    public function GetFilterationData()
    {
        $data['Driver'] = UserModel::where('UserType','Driver')->get()->toArray();
        $data['Pharmacy'] = Pharmacy::select('PharmacyId','PharmacyName')->get()->toArray();       
        return response($data, 200);
    }

    public function CustomerInfo(Request $req)
    {                                                       
        $search = $req->get('term',''); 
        $output = [];

        $data = Customer::select('CustomerId','FirstName','LastName')->orWhere('FirstName','LIKE','%'.$search.'%')
                                                                    ->orWhere('LastName','LIKE','%'.$search.'%')
                                                                    ->get()->toArray();
        if($data)
        {
            foreach($data as $data)
            {
                $output[] = array('value' => $data['FirstName'].' '.$data['LastName'],'id'=>$data['CustomerId']);
            }
        }
        if(count($output))
        return $output;
        else
        return ['value'=>'No Result Found','id'=>''];

       
    }

    public function SignImage($filename)
    {
        return Storage::disk('local')->get('VSign/'.$filename);
    }

    public function ShowReport(Request $req)
    {
        // get_d($req->all());
        $pharmacyid = $req->input('PharmacyID');
        $driverid = $req->input('DriverID');
        $vs = $req->input('VisitStatus');
        $customerid = $req->input('CustomerId');
        $customername = $req->input('CustomerName');
        $startdate = date("Y-m-d", strtotime($req->input('StartDate')));
        $enddate = date("Y-m-d", strtotime($req->input('EndDate')));
        if($startdate == ""){
           $startdate = date("Y-m-d");
           $enddate = date("Y-m-d"); 
        }
        
        $result['CustomerName'] = $req->input('CustomerName'); 
        $result['CustomerId'] = $req->input('CustomerId'); 
        $result['PharmacyID'] = $req->input('PharmacyID'); 
        $result['DriverID'] = $req->input('DriverID'); 
        $result['VisitStatus'] = $req->input('VisitStatus'); 
        $result['CustomerId'] = $req->input('CustomerId'); 
        $result['StartDate'] = $req->input('StartDate'); 
        $result['EndDate']= $EndDate = $req->input('EndDate');
        $result['ItemNumber'] = $req->report_table_length;
        
       $q = Customer::with(['CustomerVisit.Status.Goods', 
                                            'CustomerVisit' => function($query) use($startdate, $enddate){
                                                $query->whereBetween('VisitDate', [$startdate, $enddate]);
                                            }]);
        // get_d($q);
        if($vs != null)
        {
            $q = $q->with(['CustomerVisit' => function($query) use($vs, $startdate, $enddate){
                                                $query->where('VisitStatusID', $vs)->whereBetween('VisitDate', [$startdate, $enddate]);
                                            }]);
        }
        if($driverid != null)
        {
            $q = $q->with(['CustomerVisit' => function($query) use($driverid, $startdate, $enddate){
                        $query->whereHas('VisitBatch.Batch.Assigned.Driver', function($query) use($driverid) {
                            $query->where('DriverID', $driverid);
                        })->whereBetween('VisitDate', [$startdate, $enddate]);
                    }]);
        }
        if($vs != null && $driverid != null)
        {
            $q = $q->with(['CustomerVisit' => function($query) use($vs, $driverid, $startdate, $enddate){
                        $query->whereHas('VisitBatch.Batch.Assigned.Driver', function($query) use($driverid) {
                            $query->where('DriverID', $driverid);
                        })->where('VisitStatusID', $vs)->whereBetween('VisitDate', [$startdate, $enddate]);
                    }]);   
        }
        if($pharmacyid != null)
        {
            $q = $q->whereHas('PharmacyCustomer',function($query) use($pharmacyid){
                $query->where('PharmacyId',$pharmacyid);
            });
        }
        if($customerid != null && $customername != null)
        {
            $q = $q->where('CustomerId',$customerid);
        }
        $result['visits'] =  $q->get()->toArray();
        foreach($result['visits'] as $key => $value)
        {
            if(empty($value['customer_visit']))
                unset($result['visits'][$key]);
        }
        $result['visits'] = array_values($result['visits']);
        $pageStart = $req->has('page') ? $req->get('page') : 1;
        $item_numbers = $req->report_table_length ? $req->report_table_length : 10;
        $paginator = $this->paginate($result['visits'],$pageStart, $item_numbers);

            // get_d($result['visits']);

        if($req->input('download') == 'download')
        {
            $selectedPatients = $req->input('Patients');
            if(isset($selectedPatients))
                $result['visits'] = array_filter($result['visits'], function($v) use ($selectedPatients) {
                    return in_array($v['CustomerId'], $selectedPatients);
                });

            // get_d($result['visits']);

            if(!empty($result['visits']))             
                 return Excel::download(new OrderExport($result),'report.xlsx');
            else{
                $req->session()->flash('errormsg', 'No Record For Export!');
                return view('Reports.NewReports',['result' => $result, 'paginator' => $paginator]);    
            }
        }
        if ($req->input('report') == 'view') 
        {
            if(empty($result['visits'])) 
            $req->session()->flash('errormsg', 'No Records Found!');
            return view('Reports.NewReports',['result' => $result, 'paginator' => $paginator]);
        }
    }

    public function paginate($items,$pageStart,$perPage,)
    {
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage; 

        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    
    }
    
    public function TrackDownload(Request $req)
    {
        $Employee = $req->input('EmployeeName');
        $ExportComment = $req->input('ExportComment');
		if ($Employee) {
			$status = 0;
            $activity = new ActivityTable();
            $activity->ActivityGenerateBy = 1;
            $activity->UserID = 0;
            $activity->UserType = 1;
            $activity->EntityID = 5;
            $activity->EntityType = 'Download Report';
            $activity->UpdatedByUser = $Employee;
            $activity->EntityComment = $ExportComment;
			if ($activity->save()) {
				$status = 1;
			}
			$response = array('status' => $status);
			echo json_encode($response);
		} 
    }
}
