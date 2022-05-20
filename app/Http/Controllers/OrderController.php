<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomersAddress;
use App\Models\Goods;
use App\Models\Tag;
use App\Models\Scheduler;
use App\Models\SchedulerCustomer;
use App\Models\SchedulerRecurrence;
use App\Models\CustomerVisit;
use App\Models\VisitStatus;
use App\Models\VisitBatch;
use App\Models\PharmacyUser;
use App\Models\Batch;
use Sk\Geohash\Geohash;

use Illuminate\Support\Facades\Validator;

use Redirect;
use Auth;
use DB;
use PDF;
use Session;

use Haruncpi\LaravelIdGenerator\IdGenerator;

class OrderController extends Controller
{
    public function GeohashUpdate()
    {
        $allrecords = CustomersAddress::get()->toArray();
        $g = new Geohash();

        foreach ($allrecords as $key => $value) {
            $customeraddress = CustomersAddress::where('CustomerAddressId', $value['CustomerAddressId'])
                ->update(['GeoCode' => $g->encode($value['Latitude'], $value['Longitude'], 9)]);
        }
        return "All Records Updated";
    }

    public function Visits(Request $req, $id = null)
    {
        $editbatchvisits = [];
        $todaydate = date('Y-m-d');
        if (!is_null($id)) {
            $id = decval($id);
            $editbatchvisits = explode("_", $id);
            $editbatchvisits[0] = $id;
        }


        if (isset($req->CustomerId) || isset($req->status)) {
            $cid = $req->CustomerId;
            if (isset($req->CustomerId) && isset($req->status)) {
                $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                    ->where('VisitDate', $todaydate)
                    ->whereHas('Customer', function ($q) use ($cid) {
                        $q->where('CustomerId', $cid);
                    })
                    ->whereIn('VisitStatusID', $req->status)
                    ->get()
                    ->toArray();
            } elseif (isset($req->status)) {
                $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                    ->where('VisitDate', $todaydate)
                    ->whereIn('VisitStatusID', $req->status)
                    ->get()
                    ->toArray();
            } elseif (isset($req->CustomerId)) {
                $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                    ->where('VisitDate', $todaydate)
                    ->whereHas('Customer', function ($q) use ($cid) {
                        $q->where('CustomerId', $cid);
                    })
                    ->get()
                    ->toArray();
            }
        } else {
            if (Session::get('usertype') == "Admin") {
                $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                    ->where('VisitDate', $todaydate)
                    ->get()
                    ->toArray();
            } elseif (Session::get('usertype') == "Pharmacist") {

                $pharmacyid = GetPharmacyId(Session::get('user'));
                $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                    ->whereHas('Customer.PharmacyCustomer.Pharmacy', function ($query) use ($pharmacyid) {
                        $query->where('PharmacyId', $pharmacyid);
                    })
                    ->where('VisitDate', $todaydate)
                    ->get()
                    ->toArray();
                // get_d($pharmacyid);
            } else {
                $driverid = GetDriverId(Session::get('user'));
                $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                    ->whereHas('VisitBatch.Batch.Assigned', function ($query) use ($driverid) {
                        $query->where('DriverID', $driverid);
                    })
                    ->where('VisitDate', $todaydate)
                    ->get()
                    ->toArray();
            }
        }
        // get_d($visits);
        for ($i = 0; $i < count($visits); $i++) {
            try {
                $visits[$i]['customer']['PhoneNumbers'] = json_decode($visits[$i]['customer']['PhoneNumbers']);
            } catch (\Throwable $th) {
                $visits[$i]['customer']['PhoneNumbers'] = array();
            }
        }
        $matchdate = date('y-m-d');
        $batch = VisitBatch::with('Batch.Assigned.Driver')
            ->whereHas('Batch', function ($query) use ($matchdate) {
                $query->where('VisitBatchID', 'LIKE', $matchdate . '%');
            })
            ->get()->toArray();
        // get_d($batch);

        return view('Orders.Visits', ['visitdata' => $visits, 'batch' => $batch, 'editbatch' => $editbatchvisits]);
    }
    public function new_visits(Request $req, $id = null)
    {
        $editbatchvisits = [];
        $todaydate = date('Y-m-d');
        if (!is_null($id)) {
            $id = decval($id);
            $editbatchvisits = explode("_", $id);
            $editbatchvisits[0] = $id;
        }
        return view('Orders.NewVisits', ['id' => $id, 'editbatch' => $editbatchvisits]);
    }
    public function orders_list(Request $request)
    {
        $id = $request->id;
        $todaydate = date('Y-m-d');
        $order_column = "batch.TrackingID";
        $order_dir = "asc";
        $result = array();
        $limit = 10;
        $search = '';
        $is_goods_name = 0;
        if ($request->has('order')) {
            if (is_array($request->order)) {
                try {
                    if ($request->order[0]["column"] == 1)
                        $order_column = "pharmacies.PharmacyName";
                    else if ($request->order[0]["column"] == 2)
                        $order_column = "customers.FirstName";
                    else if ($request->order[0]["column"] == 3)
                        $order_column = "customers.PhoneNumbers";
                    else if ($request->order[0]["column"] == 4)
                        $order_column = "ArrivalLogTime";
                    else if ($request->order[0]["column"] == 5)
                        $order_column = "FinishLogTime";
                    else if ($request->order[0]["column"] == 7)
                        $order_column = "VisitStatusID";
                    else if ($request->order[0]["column"] == 6)
                        // $is_goods_name = 1;
                        $order_column = 'GoodsName';
                    $order_dir = $request->order[0]["dir"];
                } catch (\Throwable $th) {
                }
            }
        }
        $request["page"] = 1;
        if ($request->has('length')) {
            $limit = $request->length;
            $request["page"] = ($request->start / $request->length) + 1;
        }
        if (isset($request->search['value'])) {
            $search = $request->search['value'];
        }
        $editbatch = [];
        $todaydate = date('Y-m-d');
        if (!is_null($id)) {
            $id = decval($id);
            $editbatch = explode("_", $id);
            $editbatch[0] = $id;
        }
        $data = CustomerVisit::with('Status')
            ->with(['Status.Goods'])
            ->leftJoin('visitbatch', 'visitbatch.CustomerVisitID', '=', 'customervisit.CustomerVisitID')
            ->leftJoin('batch', 'batch.BatchID', '=', 'visitbatch.BatchID')
            ->leftJoin('batchassign', 'batchassign.BatchID', '=', 'visitbatch.BatchID')
            ->leftJoin('users', 'users.Id', '=', 'batchassign.DriverID')
            ->leftJoin('pharmacycustomer', 'pharmacycustomer.CustomerId', '=', 'customervisit.CustomerID')
            ->leftJoin('pharmacies', 'pharmacies.PharmacyId', '=', 'pharmacycustomer.PharmacyId')
            ->leftJoin('customers', 'customers.CustomerId', '=', 'pharmacycustomer.CustomerID')
            ->leftJoin('visitstatus', 'visitstatus.CustomerVisitID', '=', 'customervisit.CustomerVisitID')
            ->leftJoin('goods', function ($join) use ($search) {
                $join->on('goods.GoodsId', '=', 'visitstatus.GoodsID');
                $join->Where('GoodsName', 'like', '%' . $search . '%');
            });
        // if($is_goods_name){
        //     $data = $data->
        // }
        if (isset($request->CustomerId) || isset($request->status)) {
            $cid = $request->CustomerId;
            if (isset($request->CustomerId) && isset($request->status)) {
                $data = $data->where('VisitDate', $todaydate)
                    ->whereHas('Customer', function ($q) use ($cid) {
                        $q->where('CustomerId', $cid);
                    })
                    ->whereIn('VisitStatusID', $request->status);
            } elseif (isset($request->status)) {
                $data = $data->where('VisitDate', $todaydate)
                    ->whereIn('VisitStatusID', $request->status);
            } elseif (isset($request->CustomerId)) {
                $data = $data->where('VisitDate', $todaydate)
                    ->whereHas('Customer', function ($q) use ($cid) {
                        $q->where('CustomerId', $cid);
                    });
            }
        } else {
            if (Session::get('usertype') == "Admin") {
                $data = $data->where('VisitDate', $todaydate);
            } elseif (Session::get('usertype') == "Pharmacist") {

                $pharmacyid = GetPharmacyId(Session::get('user'));
                $data = $data->whereHas('Customer.PharmacyCustomer.Pharmacy', function ($query) use ($pharmacyid) {
                    $query->where('PharmacyId', $pharmacyid);
                })
                    ->where('VisitDate', $todaydate);
            } else {
                $driverid = GetDriverId(Session::get('user'));
                $data = $data->whereHas('VisitBatch.Batch.Assigned', function ($query) use ($driverid) {
                    $query->where('DriverID', $driverid);
                })
                    ->where('VisitDate', $todaydate);
            }
        }
        $data = $data->Where(function ($query) use ($search) {
            $query->Where('customers.PhoneNumbers', 'like', '%' . $search . '%')
                ->orWhere('batch.TrackingID', 'like', '%' . $search . '%')
                ->orWhere('pharmacies.PharmacyName', 'like', '%' . $search . '%')
                // ->orWhere('customers.FirstName', 'like', '%' . $search . '%')
                // ->orWhere('customers.LastName', 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(customers.FirstName,' ',customers.LastName)"), 'like', '%' . $search . '%')
                ->orWhere('customervisit.ArrivalLogTime', 'like', '%' . $search . '%')
                ->orWhere('customervisit.FinishLogTime', 'like', '%' . $search . '%')
                ->orWhere('GoodsName', 'like', '%' . $search . '%')
                ->orWhere('customervisit.VisitStatusID', 'like', '%' . $search . '%');
        });
        $data = $data->select(
            'customervisit.*',
            'customers.PhoneNumbers',
            'batch.TrackingID',
            'users.FirstName as driver_first_name',
            'users.LastName as driver_last_name',
            DB::raw("CONCAT(customers.FirstName,' ',customers.LastName) as full_name"),
            // DB::raw("GROUP_CONCAT(SELECT (goods.GoodsName) FROM visitstatus
            // LEFT JOIN goods on goods.GoodsId = visitstatus.GoodsID
            // WHERE visitstatus.CustomerVisitID=customervisit.CustomerVisitID and goods.GoodsName like '%".$search."%') as product_stock")
        )->orderBy($order_column, $order_dir)->groupBy('customervisit.CustomerVisitID')->paginate($limit);
        $data->appends(request()->input())->links();
        // return json_encode($data);
        if ($data !== null) {
            $data = json_decode(json_encode($data), true);
            if (isset($data['total'])) {
                $result['data'] = array();
                for ($i = 0; $i < count($data['data']); $i++) {
                    $row_data = $data['data'][$i];
                    $phone_number = json_decode($row_data['PhoneNumbers'], true);
                    $starttime = date('h:i a', strtotime($row_data['ArrivalLogTime']));
                    $endtime = date('h:i a', strtotime($row_data['FinishLogTime']));
                    $startdate = date('d/m/Y', strtotime($row_data['VisitDate']));
                    $vs = $row_data['VisitStatusID'];
                    $status = null;
                    $color = '';
                    if ($vs == 1) {
                        $status = 'New Order';
                        $color = 'background:#ff776b';
                    } elseif ($vs == 2) {
                        $status = 'Delivered';
                        $color = 'background:#00ff00';
                    } elseif ($vs == 3) {
                        $status = 'Skipped';
                        $color = 'background:#0099ff';
                    } elseif ($vs == 4) {
                        $status = 'Cancelled';
                        $color = 'background:#CCCCCC';
                    } elseif ($vs == 5) {
                        $status = 'Postponed';
                        $color = 'background:#ff33cc';
                    } elseif ($vs == 6) {
                        $status = 'Returned';
                        $color = 'background:#f9f900';
                    } else {
                        $status = 'Undelivered';
                        $color = 'background:#fff;color:black!important;';
                    }
                    $checked = '';
                    $BatchId = $row_data['TrackingID'];
                    $DriverName = ucfirst($row_data['driver_first_name'] . " " . $row_data['driver_last_name']);

                    for ($e = 0; $e < count($editbatch); $e++) {
                        if ($editbatch[$e] == $row_data['CustomerVisitID']) {
                            $checked = ' checked';
                            break;
                        }
                    }
                    $result['data'][$i][] = '<input type="hidden" name="BatchID" value="' . (($editbatch != null) ? $editbatch[0] : '') . '"><div data-title="Driver: ' . $DriverName . '">' . $BatchId . '</div>';
                    $result['data'][$i][] = $row_data['customer']['pharmacy_customer']['pharmacy']['PharmacyName'] ?? 'Not Assigned';
                    if (Session::get('usertype') == "Admin" || Session::get('usertype') == "Pharmacist") {
                        $result['data'][$i][] = '<span class="orderdetail_tooltip mr-1" id="' . $row_data['SchedulerID'] . '" style="cursor:pointer;"><i class="fas fa-info-circle"></i></span>
                          <a href="/customers/edit_customer/' . encval($row_data['CustomerID']) . '" style="color:#007bff;"  >' . $row_data['full_name'] . '</a>';
                    } else {
                        $result['data'][$i][] = $row_data['customer']['FirstName'] . ' ' . $row_data['customer']['LastName'];
                    }
                    $result['data'][$i][] = $phone_number[0]["PhoneNumber"];
                    $result['data'][$i][] = $starttime;
                    $result['data'][$i][] = $endtime;
                    $GoodsName = "";
                    for ($j = 0; $j < count($row_data['status']); $j++) {
                        $GoodsName .= $row_data['status'][$j]['goods']['GoodsName'] . '<br>';
                    }
                    $result['data'][$i][] = $GoodsName;
                    $result['data'][$i][] = '<span class="badge badge-pill" style="' . $color . '">' . $status . '</span>';
                    $visitid = $row_data['CustomerVisitID'];
                    $result['data'][$i][] = '
                      <fieldset class="form-check">
                      <input class="form-check-input filled-in forcount" type="checkbox" id="checkbox' . $i . '"  value="' . $row_data['CustomerVisitID'] . '" name="SelectedOrder[]" ' . $checked . ' >
                      <label class="form-check-label" for="checkbox' . $i . '"></label>
                      <a href="/orders/edit_schedule/' . encval($row_data['SchedulerID']) . '"><i class="icon-item far fa-edit mr-n1" role="button" data-prefix="far" data-id="edit" data-unicode="f044" data-mdb-original-title="" title="Edit Order"></i></a>
                      <a href="' . route('PdfInvoice', ['id' => $row_data['CustomerVisitID'], 'code' => 'download']) . '"><i class="icon-item fas fa-download text-color" role="button" title="Download Invoice"></i></a>
                      <a href="' . route('PdfInvoice', ['id' => $row_data['CustomerVisitID'], 'code' => 'print']) . '" target="_blank"><i class="icon-item fas fa-print text-color" role="button" title="Print Invoice"></i></a>
                      </fieldset>';
                }
                $result['recordsTotal'] = $data['total'];
                $result['recordsFiltered'] = $data['total'];
                $result['draw'] = $request->draw;
            } else {
                $result['data'] = array();
            }
        } else {
            $result['data'] = array();
        }
        return json_encode($result);
    }
    public function UnBatchedVisits()
    {
        $visit = '';
        $todaydate = date('Y-m-d');
        if (Session::get('usertype') == "Admin") {
            $visits = CustomerVisit::with('VisitBatch', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                ->where('VisitDate', $todaydate)
                ->get()
                ->toArray();
        } elseif (Session::get('usertype') == "Pharmacist") {

            $pharmacyid = GetPharmacyId(Session::get('user'));
            $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                ->whereHas('Customer.PharmacyCustomer.Pharmacy', function ($query) use ($pharmacyid) {
                    $query->where('PharmacyId', $pharmacyid);
                })
                ->where('VisitDate', $todaydate)
                ->get()
                ->toArray();
            // get_d($pharmacyid);
        } else {
            $driverid = GetDriverId(Session::get('user'));
            $visits = CustomerVisit::with('Status', 'Customer.PharmacyCustomer.Pharmacy', 'Status.Goods')
                ->whereHas('VisitBatch.Batch.Assigned', function ($query) use ($driverid) {
                    $query->where('DriverID', $driverid);
                })
                ->where('VisitDate', $todaydate)
                ->get()
                ->toArray();
        }
        for ($i = 0; $i < count($visits); $i++) {
            $visits[$i]['customer']['PhoneNumbers'] = json_decode($visits[$i]['customer']['PhoneNumbers']);
        }
        // get_d($visits);
        return view('Orders.UnBatchedVisits', ['visitdata' => $visits]);
    }

    public function AutoBatch(Request $req)
    {
        $todaydate = date('Y-m-d');
        if (Session::get('usertype') == "Admin") {
            //AutoBatch Location Wise
            // $visits = CustomerVisit::join('customersaddress','customervisit.CustomerID','=','customersaddress.CustomerId')
            //                     ->leftJoin('visitbatch', function($join) {
            //                       $join->on('customervisit.CustomerVisitID', '=', 'visitbatch.CustomerVisitID');
            //                     })
            //                     ->whereNull('visitbatch.CustomerVisitID')
            //                     ->where('VisitDate', $todaydate)
            //                     ->where('customersaddress.SetAsPrimary',1)
            //                     ->selectRaw('SUBSTRING(customersaddress.GeoCode, 1, 4) as Code,group_concat(customervisit.CustomerVisitID) as VisitID')
            //                     ->groupBy('Code')
            //                     ->get()->toArray();

            //AutoBatch Pharmacy Wise
            $visits = CustomerVisit::join('customers', 'customers.CustomerId', '=', 'customervisit.CustomerID')
                ->join('pharmacycustomer', 'pharmacycustomer.CustomerId', '=', 'customers.CustomerId')
                ->leftJoin('visitbatch', function ($join) {
                    $join->on('customervisit.CustomerVisitID', '=', 'visitbatch.CustomerVisitID');
                })
                ->whereNull('visitbatch.CustomerVisitID')
                ->where('VisitDate', $todaydate)
                ->selectRaw('PharmacyId as PharmacyId,group_concat(customervisit.CustomerVisitID) as VisitID')
                ->groupBy('PharmacyId')
                ->get()->toArray();
        } elseif (Session::get('usertype') == "Pharmacist") {

            $pharmacyid = GetPharmacyId(Session::get('user'));
            // $visits = CustomerVisit::join('customersaddress','customervisit.CustomerID','=','customersaddress.CustomerId')
            //                     ->join('customers','customers.CustomerId','=','customervisit.CustomerID')
            //                     ->join('pharmacycustomer','pharmacycustomer.CustomerId','=','customers.CustomerId')
            //                     ->leftJoin('visitbatch', function($join) {
            //                       $join->on('customervisit.CustomerVisitID', '=', 'visitbatch.CustomerVisitID');
            //                     })
            //                     ->whereNull('visitbatch.CustomerVisitID')
            //                     ->where('VisitDate', $todaydate)
            //                     ->where('pharmacycustomer.PharmacyId', $pharmacyid)
            //                     ->where('customersaddress.SetAsPrimary',1)
            //                     ->selectRaw('SUBSTRING(customersaddress.GeoCode, 1, 4) as Code,group_concat(customervisit.CustomerVisitID) as VisitID')
            //                     ->groupBy('Code')
            //                     ->get()->toArray();
            // get_d($pharmacyid);

            $visits = CustomerVisit::join('customers', 'customers.CustomerId', '=', 'customervisit.CustomerID')
                ->join('pharmacycustomer', 'pharmacycustomer.CustomerId', '=', 'customers.CustomerId')
                ->leftJoin('visitbatch', function ($join) {
                    $join->on('customervisit.CustomerVisitID', '=', 'visitbatch.CustomerVisitID');
                })
                ->whereNull('visitbatch.CustomerVisitID')
                ->where('VisitDate', $todaydate)
                ->where('pharmacycustomer.PharmacyId', $pharmacyid)
                ->selectRaw('PharmacyId as PharmacyId,group_concat(customervisit.CustomerVisitID) as VisitID')
                ->groupBy('PharmacyId')
                ->get()->toArray();
        }

        // get_d($visits);
        foreach ($visits as $visits) {
            $batchid = date('y-m-d');
            $visits['VisitID'] = explode(",", $visits['VisitID']);
            $batchid .= "_" . implode("_", $visits['VisitID']);
            // $visits['VisitID'] =  $batchid;
            // get($visits);
            $id = IdGenerator::generate(['table' => 'batch', 'field' => 'TrackingID', 'length' => 11, 'prefix' => 'DL-' . date('y'), 'reset_on_prefix_change' => true]);

            $batch = new Batch();
            $batch->VisitBatchID = $batchid;
            $batch->TrackingID = $id;
            $batch->save();

            for ($j = 0; $j < count($visits['VisitID']); $j++) {
                $visitbatch = new VisitBatch();
                $visitbatch->CustomerVisitID = $visits['VisitID'][$j];
                if ($batch->VisitBatch()->save($visitbatch))
                    $flag = 1;
                else
                    $flag = 0;
            }
        }
        if ($flag == 1) {
            $req->session()->flash('msg', 'Batch(s) Created Successfully');
            return redirect('/orders/batched_orders');
        }
    }

    public function VisitsToBatch(Request $req)
    {
        $flag = 0;
        // $batch = VisitBatch::with('CustomerVisit')->get()->toArray();
        $batchid = date('y-m-d');
        $selectedorders = $req->input('SelectedOrder');
        sort($selectedorders);

        for ($i = 0; $i < count($selectedorders); $i++) {
            $batchid .= "_" . $selectedorders[$i];
        }


        $id = IdGenerator::generate(['table' => 'batch', 'field' => 'TrackingID', 'length' => 11, 'prefix' => 'DL-' . date('y'), 'reset_on_prefix_change' => true]);

        $batch = new Batch();
        $batch->VisitBatchID = $batchid;
        $batch->TrackingID = $id;
        $batch->save();

        for ($j = 0; $j < count($selectedorders); $j++) {
            $visitbatch = new VisitBatch();
            $visitbatch->CustomerVisitID = $selectedorders[$j];
            if ($batch->VisitBatch()->save($visitbatch))
                $flag = 1;
            else
                $flag = 0;
        }
        if ($flag == 1) {
            $req->session()->flash('msg', 'Batch Created Successfully');
            return redirect('/orders');
        }
        // get($batchid);
    }

    public function Schedules()
    {
        $schedulercustomer = SchedulerCustomer::with(['Scheduler', 'Customer', 'Customer.CustomersAddress' => function ($query) {
            $query->where('SetAsPrimary', 1);
        }])
            ->get()
            ->toArray();

        $tags = Tag::all()->toArray();


        for ($i = 0; $i < count($schedulercustomer); $i++) {
            $schedulercustomer[$i]['scheduler']['Tags'] = explode(" ", $schedulercustomer[$i]['scheduler']['Tags']);
        }
        return view('Orders.Orders', ['data' => $schedulercustomer, 'tags' => $tags]);
    }

    public function ScheduleDetails(Request $req)
    {
        $id = $req->input('id');
        $recurrenceids = SchedulerRecurrence::with('Goods')->where('SchedulerID', $id)->get()->toArray();
        $html = "";
        $html .= '<div class="row outerschedulediv">';
        $html .= '<div class="col-md-12 text-center schedulesdiv">Schedules</div>';
        $html .= '<div class="col-md-12">';
        foreach ($recurrenceids as $index => $item) {
            if ($index > 0 && ($recurrenceids[$index]['RecurrenceTypeID'] == $recurrenceids[$index - 1]['RecurrenceTypeID'])) {
                $n++;
                $html .= '<div class="row"><div class="col-md-6"></div>';
                $html .= '<div class="col-md-6">' . $n . ') ' . $recurrenceids[$index]['goods']['GoodsName'] . '</div></div>';
            } else {
                $n = 1;
                if ($recurrenceids[$index]['RecurrenceTypeID'] == 1) $days = "One Time";
                elseif ($recurrenceids[$index]['RecurrenceTypeID'] == 2) $days = "Daily";
                elseif ($recurrenceids[$index]['RecurrenceTypeID'] == 3) $days = "Weekly";
                elseif ($recurrenceids[$index]['RecurrenceTypeID'] == 4) $days = "1st Week";
                elseif ($recurrenceids[$index]['RecurrenceTypeID'] == 5) $days = "2nd Week";
                elseif ($recurrenceids[$index]['RecurrenceTypeID'] == 6) $days = "3rd Week";
                elseif ($recurrenceids[$index]['RecurrenceTypeID'] == 7) $days = "4th Week";
                else $days = "Custom Dates";
                $html .= '<div class="row schedulerow"><div class="col-md-6"> Schedules : ' . $days . '</div>';
                $html .= '<div class="col-md-6">' . $n . ')  ' . $recurrenceids[$index]['goods']['GoodsName'] . '</div></div>';
            }
        }
        $html .= '</div>';
        echo $html;
    }

    public function GoodsAmt(Request $req)
    {
        $goods = $req->input('goods');
        $goodsamt = [];
        foreach ($goods as $key => $value) {
            $goodsamt[] = Goods::select('Cost')
                ->where('GoodsId', $value)->get();
        }
        $amount = 0;
        for ($i = 0; $i < count($goodsamt); $i++) {
            $amount = $amount + $goodsamt[$i][0]['Cost'];
        }

        // $return_array = json_encode($goods['id0']);
        // $return_array = json_encode($goodsamt[1][0]['Cost']);
        $return_array = json_encode($amount);
        echo $return_array;
    }

    public function EditSchedule($id)
    {
        $id = decval($id);
        $schedulercustomer = SchedulerCustomer::with([
            'Scheduler' => function ($query) use ($id) {
                $query->where('SchedulerID', $id);
            },
            'Scheduler.SchedulerRecurrence' => function ($query) use ($id) {
                $query->where('SchedulerID', $id);
            }, 'Customer'
        ])
            ->where('SchedulerID', $id)
            ->get()
            ->toArray();

        $tags = Tag::all()->toArray();
        $meds = Goods::all()->toArray();
        for ($i = 0; $i < count($schedulercustomer); $i++) {
            $schedulercustomer[$i]['scheduler']['Tags'] = explode(",", $schedulercustomer[$i]['scheduler']['Tags']);
        }

        for ($i = 0; $i < count($schedulercustomer[0]['scheduler']['scheduler_recurrence']); $i++) {
            $schedulercustomer[0]['scheduler']['scheduler_recurrence'][$i]['RecurrenceSelectedDays'] = explode(",", $schedulercustomer[0]['scheduler']['scheduler_recurrence'][$i]['RecurrenceSelectedDays']);
        }

        // get_d($schedulercustomer);

        return view('Orders.EditOrder', ['data' => $schedulercustomer, 'tags' => $tags, 'meds' => $meds]);
    }

    public function DeleteSchedule($id)
    {
        $id = decval($id);
        $scheduler = new Scheduler();
        $scheduler = Scheduler::with('SchedulerRecurrence', 'SchedulerCustomer')->find($id);
        $scheduler->delete();

        echo "Order Record Deleted";
    }

    public function AddCustomer()
    {
        $customersId = Customer::all('CustomerId', 'FirstName', 'LastName')->toArray();
        // get_d($customersId);
        return view('Orders.CustomerSummary', ['ids' => $customersId]);
    }
    public function SelectCustomer($id)
    {
        $id = decval($id);
        $CustomerData = Customer::join('customersaddress', 'customersaddress.CustomerId', '=', 'customers.CustomerId')
            ->where('customers.CustomerId', '=', $id)
            ->get()
            ->toArray();

        for ($i = 0; $i < count($CustomerData); $i++) {
            $CustomerData[$i]['PhoneNumbers'] = json_decode($CustomerData[$i]['PhoneNumbers']);
            // print_r($CustomerData[$i]);
            // print_r($CustomerData[0]['PhoneNumbers'][0]->PhoneTypeId);
        }
        if ($CustomerData[0]['SetAsPrimary'] == 0) {
            for ($i = 1; $i < count($CustomerData); $i++) {
                if ($CustomerData[$i]['SetAsPrimary'] == 1) {
                    $temp = $CustomerData[0];
                    $CustomerData[0] = $CustomerData[$i];
                    $CustomerData[$i] = $temp;
                    break;
                }
            }
        }

        $CustomerData[0]['CustomerId'] = encval($CustomerData[0]['CustomerId']);
        return response()->json($CustomerData, 200);
    }

    public function Schedule($id)
    {
        $id = decval($id);
        $customer = Customer::find($id)->toArray();
        $tags = Tag::all();
        return view('Orders.AddSchedule', ['tags' => $tags, 'customer' => $customer]);
    }

    public function GetGoods()
    {
        $goods = Goods::all('GoodsId', 'GoodsName');
        return  response()->json($goods, 200);
    }

    public function AddSchedule(Request $req)
    {

        Validator::make(
            $req->all(),
            [
                'Amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'Notes' => 'required',
                'EmployeeCode' => 'required',
                'Goods' => 'required|min:1',
                'Goods.*.SelectedGoods' => 'required | min:1',
                'Recurrence' => 'required | min:1',
                'Recurrence.*.RecurrenceTypeID' => 'required',
                'Recurrence.*.RDay' => 'required_if:Recurrence.*.RecurrenceTypeID,3,4,5,6,7',
                'Recurrence.*.CustomDates' => 'required_if:Recurrence.*.RecurrenceTypeID,8',
            ]
        )->validateWithBag('AddSchedule');

        $scheduler = new Scheduler();
        $scheduler->SchedulerTypeID = 1;
        $scheduler->StartDate = $req->input('StartDate');
        $scheduler->EndDate = ($req->input('EndDate') != null) ? $req->input('EndDate') : NULL;
        $scheduler->StartTime = date('H:i:s', strtotime($req->input('StartTime')));
        $scheduler->EndTime = date('H:i:s', strtotime($req->input('EndTime')));
        $scheduler->Amount = $req->input('Amount');
        $scheduler->OrderNote = $req->input('Notes');
        $tags = $req->input('SelectedTags') ? implode(', ', $req->input('SelectedTags')) : null;
        $scheduler->Tags = $tags;
        $scheduler->EmployeeNumber = $req->input('EmployeeCode');
        $scheduler->UpdatedDate = date("Y-m-d h:i:s");
        $scheduler->AddedDate = date("Y-m-d h:i:s");
        $scheduler->save();
        $lastInsertedId = $scheduler->SchedulerID;

        $meds = $req->input('Goods');
        $rec = $req->input('Recurrence');
        $weekdays = [];
        $recId = [];
        $x = 0;

        foreach ($rec as $rec) {
            $x++;
            $recId[$x] = $rec['RecurrenceTypeID'];
            if ($rec['RecurrenceTypeID'] == 1 || $rec['RecurrenceTypeID'] == 2)
                $weekdays[$x] = " ";
            else if ($rec['RecurrenceTypeID'] == 3 || $rec['RecurrenceTypeID'] == 4 || $rec['RecurrenceTypeID'] == 5 || $rec['RecurrenceTypeID'] == 6 || $rec['RecurrenceTypeID'] == 7)
                $weekdays[$x] = implode(', ', $rec['RDay']);
            else
                $weekdays[$x] = $rec['CustomDates'];
        }

        $j = 0;
        foreach ($meds as $meds) {
            $j++;
            for ($i = 0; $i < count($meds['SelectedGoods']); $i++) {
                $schedulerrecurrence = new SchedulerRecurrence();
                $schedulerrecurrence->GoodsID = $meds['SelectedGoods'][$i];
                $schedulerrecurrence->RecurrenceTypeID = $recId[$j];
                $schedulerrecurrence->RecurrenceSelectedDays = $weekdays[$j];
                $scheduler->SchedulerRecurrence()->save($schedulerrecurrence);
            }
        }

        $schedulercustomer = new SchedulerCustomer();
        $schedulercustomer->CustomerID = $req->input('CustomerId');
        $schedulercustomer->Status = 1;


        if ($scheduler->SchedulerCustomer()->save($schedulercustomer)) {
            daily_visit_insert($lastInsertedId);
            $req->session()->flash('msg', 'New Scheduler Added Successfully');
        } else
            $req->session()->flash('errormsg', 'Something went Wrong! Scheduler Not Added');
        return redirect('/orders');
    }

    public function UpdateSchedule(Request $req)
    {
        Validator::make(
            $req->all(),
            [
                'Amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'Notes' => 'required',
                'EmployeeCode' => 'required',
                'Goods' => 'required|min:1',
                'Goods.*.SelectedGoods' => 'required | min:1',
                'Recurrence' => 'required | min:1',
                'Recurrence.*.RecurrenceTypeID' => 'required',
                'Recurrence.*.RDay' => 'required_if:Recurrence.*.RecurrenceTypeID,3,4,5,6,7',
                'Recurrence.*.CustomDates' => 'required_if:Recurrence.*.RecurrenceTypeID,8',
            ]
        )->validateWithBag('AddSchedule');

        $scheduler = new Scheduler();
        $scheduler = Scheduler::find($req->input('ScheduleId'));
        $scheduler->SchedulerTypeID = 1;
        $scheduler->StartDate = $req->input('StartDate');
        $scheduler->EndDate = ($req->input('EndDate') != null) ? $req->input('EndDate') : NULL;
        $scheduler->StartTime = date('H:i:s', strtotime($req->input('StartTime')));
        $scheduler->EndTime = date('H:i:s', strtotime($req->input('EndTime')));
        $scheduler->Amount = $req->input('Amount');
        $scheduler->OrderNote = $req->input('Notes');
        $tags = $req->input('SelectedTags') ? implode(', ', $req->input('SelectedTags')) : null;
        $scheduler->Tags = $tags;
        $scheduler->EmployeeNumber = $req->input('EmployeeCode');
        $scheduler->UpdatedDate = date("Y-m-d h:i:s");
        $scheduler->push();

        $schedulerrecurrence = SchedulerRecurrence::where('SchedulerID', $req->input('ScheduleId'))->delete();

        $meds = $req->input('Goods');
        $rec = $req->input('Recurrence');
        $weekdays = [];
        $recId = [];
        $x = 0;
        $flag = 0;

        foreach ($rec as $rec) {
            $x++;
            $recId[$x] = $rec['RecurrenceTypeID'];
            if ($rec['RecurrenceTypeID'] == 1 || $rec['RecurrenceTypeID'] == 2)
                $weekdays[$x] = " ";
            else if ($rec['RecurrenceTypeID'] == 3 || $rec['RecurrenceTypeID'] == 4 || $rec['RecurrenceTypeID'] == 5 || $rec['RecurrenceTypeID'] == 6 || $rec['RecurrenceTypeID'] == 7)
                $weekdays[$x] = implode(', ', $rec['RDay']);
            else
                $weekdays[$x] = $rec['CustomDates'];
        }

        $j = 0;
        foreach ($meds as $meds) {
            $j++;
            for ($i = 0; $i < count($meds['SelectedGoods']); $i++) {
                $schedulerrecurrence = new SchedulerRecurrence();
                $schedulerrecurrence->SchedulerID = $req->input('ScheduleId');
                $schedulerrecurrence->GoodsID = $meds['SelectedGoods'][$i];
                $schedulerrecurrence->RecurrenceTypeID = $recId[$j];
                $schedulerrecurrence->RecurrenceSelectedDays = $weekdays[$j];
                if ($schedulerrecurrence->save())
                    $flag = 1;
            }
        }

        $todaydate = date('Y-m-d');
        $previousvisit = CustomerVisit::with('Status')
            ->where([
                'SchedulerID' => $req->input('ScheduleId'),
                'CustomerID' => $req->input('CustomerId'),
                'VisitDate' => $todaydate
            ])
            ->delete();

        // if($previousvisit > 0)
        daily_visit_insert($req->input('ScheduleId'));

        if ($flag == 1)
            $req->session()->flash('msg', 'Scheduler Edited Successfully');
        else
            $req->session()->flash('errormsg', 'Something went Wrong! Scheduler Not Edited');

        $url = $req->input('url');
        return redirect($url);
        // return redirect('/orders');
        // return back();
    }

    public function CreatePdfInvoice($id, $code)
    {
        $visit = CustomerVisit::with([
            'Status.Goods', 'Customer.PharmacyCustomer.Pharmacy',
            'Customer.CustomersAddress' => function ($query) {
                $query->where('SetAsPrimary', 1);
            }
        ])
            ->where('CustomerVisitID', $id)
            ->get()
            ->toArray();
        // get_d($visit);

        view()->share('visit', $visit);
        $pdfdl = PDF::loadview('Orders.InvoicePdf', $visit);
        $pdf = view('Orders.InvoicePdf', $visit)->render();
        if ($code == 'view') {
            $mobilepdf = view('Orders.InvoicePdfMobile', $visit)->render();
            return $mobilepdf;
        } elseif ($code == 'print')
            return @\PDF::loadHTML($pdf, 'utf-8')->stream();
        else
            return $pdfdl->download('Invoice.pdf');
    }

    public function OrdersToBatch(Request $req)
    {
        get_d($req->all());
    }

    function getDistance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}
