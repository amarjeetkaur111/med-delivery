<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerVisit;
use App\Models\Pharmacy;
use App\Models\UserModel;

class MapController extends Controller
{
    public function MapCordinates(Request $req)
    {
        $todaydate = date('Y-m-d');
        $Patients_geoinfo = [];
        $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
            $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
        }, 'PharmacyUser.Pharmacy'])
            ->where('VisitDate', $todaydate)
            ->get()
            ->toArray();

        $inputs = [];

        if ($req->Pharmacy || $req->Driver) {
            // get_d($req->all());
            if (is_numeric($req->Pharmacy)) {
                $inputs['pid'] = $pid = $req->Pharmacy;
                $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
                    $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
                }, 'PharmacyUser.Pharmacy'])
                    ->where('VisitDate', $todaydate)
                    ->whereHas('PharmacyUser.Pharmacy', function ($query) use ($pid) {
                        $query->where('PharmacyId', $pid);
                    })
                    ->get()
                    ->toArray();
            }
            if (is_numeric($req->Driver)) {
                $inputs['did'] = $did = $req->Driver;
                $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
                    $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
                }, 'PharmacyUser.Pharmacy'])
                    ->where('VisitDate', $todaydate)
                    ->whereHas('VisitBatch.Batch.Assigned.Driver', function ($query) use ($did) {
                        $query->where('Id', $did);
                    })
                    ->get()
                    ->toArray();
            }
            if (is_numeric($req->Pharmacy) && is_numeric($req->Driver)) {
                $inputs['pid'] = $pid = $req->Pharmacy;
                $inputs['did'] = $did = $req->Driver;
                $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
                    $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
                }, 'PharmacyUser.Pharmacy'])
                    ->where('VisitDate', $todaydate)
                    ->whereHas('PharmacyUser.Pharmacy', function ($query) use ($pid) {
                        $query->where('PharmacyId', '=', $pid);
                    })
                    ->whereHas('VisitBatch.Batch.Assigned.Driver', function ($query) use ($did) {
                        $query->where('Id', $did);
                    })
                    ->get()
                    ->toArray();
            }
        }
        if ($customerinfo) {
            $map_status = true;
            foreach ($customerinfo as $info) {
                $visitId = $info['CustomerVisitID'];
                $visitstatus = $info['VisitStatusID'];
                $time = date('h:i a', strtotime($info['ArrivalLogTime']));
                $customerId = $info['CustomerID'];
                $name = $info['customer']['FirstName'] . ' ' . $info['customer']['LastName'];
                $latitude = $info['customer']['customers_address'][0]['Latitude'];
                $longitude = $info['customer']['customers_address'][0]['Longitude'];
                $latlngfinal = "{'Geometry':{'Latitude':$latitude,'Longitude':$longitude,'Name':'$name','VisitID':$visitId, 'CustomerID':$customerId,'VisitStatusID':$visitstatus,'Time':'$time'}}";
                $Patients_geoinfo[] = $latlngfinal;
            }
        } else {
            $map_status = false;
        }
        $pharmacylist = Pharmacy::select('PharmacyId', 'PharmacyName')->get()->toArray();
        $driverlist = UserModel::select('Id', 'FirstName', 'LastName')->where('UserType', 'Driver')->get()->toArray();


        // get_d($customerinfo);
        return view('Map', ['Patients_geoinfo' => $Patients_geoinfo, 'map_status' => $map_status, 'pharmacy' => $pharmacylist, 'driver' => $driverlist, 'InputData' => $inputs]);
    }
    public function MapNewCordinates(Request $req)
    {
        $map_status = false;
        $todaydate = date('Y-m-d');
        $Patients_geoinfo = [];
        $driver_ids = [];
        $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
            $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
        }, 'PharmacyUser.Pharmacy', 'VisitBatch.Batch.Assigned.Driver'])
            ->where('VisitDate', $todaydate)
            ->get()
            ->toArray();

        $inputs = [];
        if ($req->Pharmacy || $req->Driver) {
            // get_d($req->all());
            if (is_numeric($req->Pharmacy)) {
                $inputs['pid'] = $pid = $req->Pharmacy;
                $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
                    $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
                }, 'PharmacyUser.Pharmacy', 'VisitBatch.Batch.Assigned.Driver'])
                    ->where('VisitDate', $todaydate)
                    ->whereHas('PharmacyUser.Pharmacy', function ($query) use ($pid) {
                        $query->where('PharmacyId', $pid);
                    })
                    ->get()
                    ->toArray();
            }
            if (is_numeric($req->Driver)) {
                $inputs['did'] = $did = $req->Driver;
                $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
                    $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
                }, 'PharmacyUser.Pharmacy', 'VisitBatch.Batch.Assigned.Driver'])
                    ->where('VisitDate', $todaydate)
                    ->whereHas('VisitBatch.Batch.Assigned.Driver', function ($query) use ($did) {
                        $query->where('Id', $did);
                    })
                    ->get()
                    ->toArray();
            }
            if (is_numeric($req->Pharmacy) && is_numeric($req->Driver)) {
                $inputs['pid'] = $pid = $req->Pharmacy;
                $inputs['did'] = $did = $req->Driver;
                $customerinfo = CustomerVisit::with(['Customer.CustomersAddress' => function ($query) {
                    $query->select('CustomerId', 'Longitude', 'Latitude')->where('SetAsPrimary', 1);
                }, 'PharmacyUser.Pharmacy', 'VisitBatch.Batch.Assigned.Driver'])
                    ->where('VisitDate', $todaydate)
                    ->whereHas('PharmacyUser.Pharmacy', function ($query) use ($pid) {
                        $query->where('PharmacyId', '=', $pid);
                    })
                    ->whereHas('VisitBatch.Batch.Assigned.Driver', function ($query) use ($did) {
                        $query->where('Id', $did);
                    })
                    ->get()
                    ->toArray();
            }
        }
        // return json_encode($customerinfo);
        if ($customerinfo) {
            foreach ($customerinfo as $info) {
                $visitId = $info['CustomerVisitID'];
                $visitstatus = $info['VisitStatusID'];
                $time = date('h:i a', strtotime($info['ArrivalLogTime']));
                $customerId = $info['CustomerID'];
                $name = $info['customer']['FirstName'] . ' ' . $info['customer']['LastName'];
                $latitude = $info['customer']['customers_address'][0]['Latitude'];
                $longitude = $info['customer']['customers_address'][0]['Longitude'];
                $DriverID = 0;
                try {
                    $DriverID = $info['visit_batch']['batch']['assigned']['DriverID'];
                    $latlngfinal = array(
                        'lat' => $latitude,
                        'lng' => $longitude,
                        'Name' => $name,
                        'VisitID' => $visitId,
                        'CustomerID' => $customerId,
                        'VisitStatusID' => $visitstatus,
                        'Time' => $time,
                        'DriverID' => $DriverID
                    );
                    if (!isset($Patients_geoinfo['Driver_' . $DriverID]))
                        $driver_ids[] = $DriverID;
                    $Patients_geoinfo['Driver_' . $DriverID][] = $latlngfinal;
                    $map_status = true;
                } catch (\Throwable $th) {
                }
            }
        }
        // get_d($customerinfo);
        $pharmacylist = Pharmacy::select('PharmacyId', 'PharmacyName')->get()->toArray();
        $driverlist = UserModel::select('Id', 'FirstName', 'LastName')->where('UserType', 'Driver')->get()->toArray();

        // get_d($Patients_geoinfo);
        return view('Map_new', ['Patients_geoinfo' => $Patients_geoinfo, 'map_status' => $map_status, 'InputData' => $inputs,'driver_ids'=>$driver_ids,'driver' => $driverlist,'pharmacy' => $pharmacylist]);
    }
    public function MarkerInfo(Request $req)
    {
        $todaydate = date('Y-m-d');
        $customerinfo = CustomerVisit::with(['Customer', 'Customer.CustomersAddress' => function ($q) {
            $q->where('SetAsPrimary', 1);
        }, 'Status.Goods', 'VisitBatch.Batch.Assigned.Driver'])
            ->where('CustomerID', $req->input('CustomerID'))
            ->where('VisitDate', $todaydate)
            ->get()
            ->toArray();
        $x = 0;
        foreach ($customerinfo as $customerinfo) {
            $customerinfo['customer']['PhoneNumbers'] = json_decode($customerinfo['customer']['PhoneNumbers']);
            $data['CustomerID'] = $customerinfo['CustomerID'];
            $data['VisitStatus'] = $customerinfo['VisitStatusID'];
            $data['Name'] = ucwords($customerinfo['customer']['FirstName'] . ' ' . $customerinfo['customer']['LastName']);
            $data['Address'] = $customerinfo['customer']['customers_address'][0]['AddressLine'];
            $data['Phone'] = $customerinfo['customer']['PhoneNumbers'][0]->PhoneNumber;
            foreach ($customerinfo['status'] as $visit) {
                $data['visit'][$x]['Goods'] = $visit['goods']['GoodsName'];
                $data['visit'][$x]['GoodsQty'] = $visit['GoodsQty'];
                $data['visit'][$x]['GoodsAmt'] = $visit['GoodsAmt'];
                $x++;
            }
            if (isset($customerinfo['visit_batch']['batch']['assigned'])) {
                $data['Driver'] = ucwords($customerinfo['visit_batch']['batch']['assigned']['driver']['FirstName'] . ' ' . $customerinfo['visit_batch']['batch']['assigned']['driver']['LastName']);
                $data['DriverPhone'] = $customerinfo['visit_batch']['batch']['assigned']['driver']['PhoneNumber'];
            } else {
                $data['Driver'] = $data['DriverPhone'] = 'Unassigned';
            }
        }
        return response($data, 200);
        // return $customerinfo;  
    }

    public function GetMarkerInfo()
    {
        $todaydate = date('Y-m-d');
        $customerinfo = CustomerVisit::with(['Customer', 'Customer.CustomersAddress' => function ($q) {
            $q->where('SetAsPrimary', 1);
        }, 'Status.Goods', 'VisitBatch.Batch.Assigned.Driver'])
            ->where('CustomerID', 59)
            ->where('VisitDate', $todaydate)
            ->get()
            ->toArray();

        $x = 0;
        foreach ($customerinfo as $customerinfo) {
            $customerinfo['customer']['PhoneNumbers'] = json_decode($customerinfo['customer']['PhoneNumbers']);
            $data['CustomerID'] = $customerinfo['CustomerID'];
            $data['Name'] = $customerinfo['customer']['FirstName'] . ' ' . $customerinfo['customer']['LastName'];
            $data['Address'] = $customerinfo['customer']['customers_address'][0]['AddressLine'];
            $data['Phone'] = $customerinfo['customer']['PhoneNumbers'][0]->PhoneNumber;
            foreach ($customerinfo['status'] as $visit) {
                $data['visit'][$x]['Goods'] = $visit['GoodsID'];
                $data['visit'][$x]['GoodsAmt'] = $visit['GoodsAmt'];
                $x++;
            }
        }
        get_d($customerinfo);
        // get_d($data);
    }
}
