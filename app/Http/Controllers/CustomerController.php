<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Scheduler;
use App\Models\CustomersAddress;
use App\Models\SchedulerCustomer;
use App\Models\SchedulerRecurrence;
use App\Models\Goods;
use App\Models\Pharmacy;
use App\Models\PharmacyCustomer;
use Sk\Geohash\Geohash;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;
use Redirect;
use Session;


class CustomerController extends Controller
{
    public function index()
    {
        if (Session::get('usertype') == "Admin") {
            $data = Customer::join('customersaddress', 'customersaddress.CustomerId', '=', 'customers.CustomerId')
                ->where('customersaddress.SetAsPrimary', '=', 1)
                ->get();
        } else {
            $pharmacyid = GetPharmacyId(Session::get('user'));
            $data = Customer::join('customersaddress', 'customersaddress.CustomerId', '=', 'customers.CustomerId')
                ->whereHas('PharmacyCustomer.Pharmacy', function ($query) use ($pharmacyid) {
                    $query->where('PharmacyId', $pharmacyid);
                })
                ->where('customersaddress.SetAsPrimary', '=', 1)
                ->get();
        }

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->PhoneNumbers = json_decode($data[$i]->PhoneNumbers);
        }
        // get_d($data);

        return view('Customers.Customers', ['data' => $data]);
    }
    public function new_index()
    {
        return view('Customers.NewCustomers');
    }
    public function customer_list(Request $request)
    {
        $order_column = "customers.CustomerId";
        $order_dir = "asc";
        $result = array();
        $limit = 10;
        $search = '';
        if ($request->has('order')) {
            if (is_array($request->order)) {
                try {
                    if ($request->order[0]["column"] == 1)
                        $order_column = "FirstName";
                    else if ($request->order[0]["column"] == 2)
                        $order_column = "PhoneNumbers";
                    else if ($request->order[0]["column"] == 3)
                        $order_column = "AddressLine";
                    $order_dir = $request->order[0]["dir"];
                } catch (\Throwable $th) {
                }
            }
        }
        if ($request->has('length')) {
            $limit = $request->length;
        }
        if (isset($request->search['value'])) {
            $search = $request->search['value'];
        }
        $request["page"] = ($request->start / $request->length) + 1;
        if (Session::get('usertype') == "Admin") {
            $data = Customer::join('customersaddress', 'customersaddress.CustomerId', '=', 'customers.CustomerId')
                ->where('customersaddress.SetAsPrimary', '=', 1)
                ->Where(function ($query) use ($search) {
                    $query->where('PhoneNumbers', 'like', '%' . $search . '%')
                        ->orWhere('customers.CustomerId', 'like', '%' . $search . '%')
                        ->orWhere('FirstName', 'like', '%' . $search . '%')
                        ->orWhere('LastName', 'like', '%' . $search . '%')
                        ->orWhere('AddressLine', 'like', '%' . $search . '%')
                        ->orWhere('City', 'like', '%' . $search . '%')
                        ->orWhere('Province', 'like', '%' . $search . '%');
                })
                ->orderBy($order_column, $order_dir)
                ->paginate($limit);
            $data->appends(request()->input())->links();
        } else {
            $pharmacyid = GetPharmacyId(Session::get('user'));
            $data = Customer::join('customersaddress', 'customersaddress.CustomerId', '=', 'customers.CustomerId')
                ->whereHas('PharmacyCustomer.Pharmacy', function ($query) use ($pharmacyid) {
                    $query->where('PharmacyId', $pharmacyid);
                })
                ->Where(function ($query) use ($search) {
                    $query->where('PhoneNumbers', 'like', '%' . $search . '%')
                        ->orWhere('customers.CustomerId', 'like', '%' . $search . '%')
                        ->orWhere('FirstName', 'like', '%' . $search . '%')
                        ->orWhere('LastName', 'like', '%' . $search . '%')
                        ->orWhere('AddressLine', 'like', '%' . $search . '%')
                        ->orWhere('City', 'like', '%' . $search . '%')
                        ->orWhere('Province', 'like', '%' . $search . '%');
                })
                ->where('customersaddress.SetAsPrimary', '=', 1)
                ->orderBy($order_column, $order_dir)
                ->paginate($limit);
            $data->appends(request()->input())->links();
        }
        // get_d($data);
        if ($data !== null) {
            $data = json_decode(json_encode($data), true);
            if (isset($data['total'])) {
                $result['data'] = array();
                for ($i = 0; $i < count($data['data']); $i++) {
                    $row_data = $data['data'][$i];
                    $phone_number = json_decode($row_data["PhoneNumbers"], true);
                    $result['data'][$i][] = $row_data["CustomerId"];
                    $result['data'][$i][] = $row_data["FirstName"] . " " . $row_data["LastName"];
                    $result['data'][$i][] = implode("<br>", array_column($phone_number, "PhoneNumber"));
                    $result['data'][$i][] = $row_data["AddressLine"] . " " . $row_data["City"] . " " . $row_data["Province"];
                    $result['data'][$i][] = '<a href="/customers/edit_customer/' . $row_data["CustomerId"] . '" ><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="user-edit" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a>';
                    if ($row_data["Status"] == 1) {
                        $result['data'][$i][] = '<a href="javascript:void(0);" data-name="' . $row_data["FirstName"] . ' ' . $row_data["LastName"] . '" data-id="' . encval($row_data["CustomerId"]) . '" id="deleteCustomer">
                <i class="icon-item fas fa-toggle-on activate" role="button" data-prefix="fas" data-id="" title="Activate/Deactivate" style="font-size: 25px;"></i>
              </a>';
                    } else {
                        $result['data'][$i][] = '<a href="javascript:void(0);" data-name="' . $row_data["FirstName"] . ' ' . $row_data["LastName"] . '" data-id="' . encval($row_data["CustomerId"]) . '" id="deleteCustomer">
              <i class="icon-item fas fa-toggle-off deactivate" role="button" data-prefix="fas" data-id="" title="Activate/Deactivate" style="font-size: 25px;"></i>
            </a>';
                    }
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
    public function AddCustomerForm()
    {
        $pharmacies = Pharmacy::all()->toArray();
        return view('Customers.AddCustomer', ['pharmacies' => $pharmacies]);
    }

    public function AddCustomer(Request $req)
    {
        $fname = $req->input('FirstName');
        $lname = $req->input('LastName');
        $uniqueRule =  Rule::unique('customers')->where(function ($query) use ($fname, $lname) {
            return $query->where('FirstName', $fname ?? '')
                ->where('LastName', $lname ?? '');
        });

        $validator = $req->validate(
            [
                'Pharmacy' => 'required',
                'FirstName' => ['required', 'alpha', $uniqueRule],
                'LastName' => ['required', 'alpha', $uniqueRule],
                'Phone.*.PhoneTypeId' => 'required',
                'Phone.*.PhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'AddressLine' => 'required',
                'City' => 'required',
                'Province' => 'required',
                'Country' => 'required',
                'PostalCode' => 'required|regex:/^[A-Za-z][0-9][A-Za-z][ ][0-9][A-Za-z][0-9]$/',
                'PhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'PhoneTypeId' => 'required',
            ],
            [
                'Phone.*.PhoneNumber.required' => 'Phone Number is required eg: 555-555-5555',
                'Phone.*.PhoneNumber.regex' => 'Phone Number Should be of pattern : 555-555-5555',
                'Phone.*.PhoneTypeId.required' => 'Select Phone Type',
                'PhoneNumber.required' => 'Phone Number is required eg: 555-555-5555',
                'PhoneNumber.regex' => 'Phone Number Should be of pattern : 555-555-5555',
            ]
        );

        //Adding Customer Into DB
        $customeraddress = new CustomersAddress();
        $customer = new Customer();
        $pharmacycustomer = new PharmacyCustomer();

        $existingphone = Customer::whereJsonContains('PhoneNumbers', [['PhoneNumber' => $req->input('Phone.0.PhoneNumber')]])->get();

        foreach ($existingphone as $existingphone) {
            $existingphone->PhoneNumbers = json_decode($existingphone->PhoneNumbers);
            if ($existingphone->PhoneNumbers[0]->PhoneNumber == $req->input('Phone.0.PhoneNumber')) {
                return back()->with('existing_phone', 'Primary Phone Number Already Registered')
                    ->withInput();
            } else {
            }
        }

        // $AddressJson=json_encode($req->input('Address'));
        $PhoneJson = json_encode($req->input('Phone'));

        $customer->FirstName = $req->input('FirstName');
        $customer->LastName = $req->input('LastName');
        $customer->PhoneNumbers = $PhoneJson;
        $customer->save();
        $lastInsertedId = $customer->CustomerId;

        $lat = round($req->input('Latitude'), 8);
        $lon = round($req->input('Longitude'), 8);
        $g = new Geohash();

        $customeraddress->SetAsPrimary = 1;
        $customeraddress->AddressLine = $req->input('AddressLine');
        $customeraddress->UnitNumber = $req->input('UnitNumber') ?? null;
        $customeraddress->Longitude = $lon;
        $customeraddress->Latitude = $lat;
        $customeraddress->GeoCode = $g->encode($lat, $lon, 9);
        $customeraddress->City = $req->input('City');
        $customeraddress->Province = $req->input('Province');
        $customeraddress->PostalCode = $req->input('PostalCode');
        $customeraddress->Country = $req->input('Country');
        $customeraddress->PhoneTypeId = $req->input('PhoneTypeId');
        $customeraddress->PhoneNumber = $req->input('PhoneNumber');
        $customeraddress->Extension = $req->input('Extension') ?? null;
        $customeraddress->AdditionalInfo = $req->input('AdditionalInfo') ?? null;
        $customeraddress->DoorSecurityCode = $req->input('DoorSecurityCode');
        $customer->CustomersAddress()->save($customeraddress);

        $pharmacycustomer->PharmacyId = $req->input('Pharmacy');
        $customer->PharmacyCustomer()->save($pharmacycustomer);

        if ($req->input('from') == 'order') {
            $CustomerData = Customer::join('customersaddress', 'customersaddress.CustomerId', '=', 'customers.CustomerId')
                ->where('customers.CustomerId', '=', $lastInsertedId)
                ->get();
            $CustomerData[0]->AddressInfo = json_decode($CustomerData[0]->AddressInfo);
            $CustomerData[0]->PhoneNumbers = json_decode($CustomerData[0]->PhoneNumbers);
            return view('Orders.CustomerSummary', ['data' => $CustomerData]);
            // return Redirect::route('Order.AddCustomer')->with( ['data' => $CustomerData] );
        } else if ($req->input('from') == 'customer') {
            $req->session()->flash('msg', 'Customer Inserted Successfully');
            return redirect('/orders/add_schedule/' . encval($lastInsertedId));
        }
    }

    public function EditCustomer($id)
    {
        $id = decval($id);
        $scheduleData = SchedulerCustomer::with('Scheduler', 'Scheduler.SchedulerRecurrence', 'Scheduler.SchedulerRecurrence.Goods')
            ->where('CustomerID', $id)
            ->get()
            ->toArray();


        $CustomerData = Customer::with(['CustomersAddress' => function ($query) {
            $query->orderBy('SetAsPrimary', 'DESC');
        }, 'PharmacyCustomer.Pharmacy'])
            ->where('CustomerId', $id)
            ->get()
            ->toArray();

        $pharmacies = Pharmacy::all()->toArray();

        $CustomerData[0]['PhoneNumbers'] = json_decode($CustomerData[0]['PhoneNumbers']);


        for ($j = 0; $j < count($scheduleData); $j++) {
            $scheduleData[$j]['scheduler']['Tags'] = explode(",", $scheduleData[$j]['scheduler']['Tags']);

            for ($i = 0; $i < count($scheduleData[$j]['scheduler']['scheduler_recurrence']); $i++) {
                $scheduleData[$j]['scheduler']['scheduler_recurrence'][$i]['RecurrenceSelectedDays'] = explode(",", $scheduleData[$j]['scheduler']['scheduler_recurrence'][$i]['RecurrenceSelectedDays']);
            }
        }

        // get_d($scheduleData);

        return view('Customers.EditCustomer', ['data' => $CustomerData, 'scheduledata' => $scheduleData, 'pharmacies' => $pharmacies]);
    }

    public function UpdateCustomer(Request $req)
    {
        // get_d($req->all());
        $customer = new Customer();

        $fname = $req->input('FirstName');
        $lname = $req->input('LastName');
        $existingid = $req->input('CustomerId');
        $uniqueRule =  Rule::unique('customers')->where(function ($query) use ($fname, $lname, $existingid) {
            return $query->where('FirstName', $fname ?? '')
                ->where('LastName', $lname ?? '')
                ->where('CustomerId', '!=', $existingid);
        });

        $validator = $req->validate(
            [
                'Pharmacy' => 'required',
                'FirstName' => ['required', 'alpha', $uniqueRule],
                'LastName' => ['required', 'alpha', $uniqueRule],
                'Phone.*.PhoneTypeId' => 'required',
                'Phone.*.PhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'AddressLine' => 'required',
                'City' => 'required',
                'Province' => 'required',
                'Country' => 'required',
                'PostalCode' => 'required|regex:/^[A-Za-z][0-9][A-Za-z][ ][0-9][A-Za-z][0-9]$/',
                'PhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
                'PhoneTypeId' => 'required',
            ],
            [
                'Phone.*.PhoneNumber.required' => 'Phone Number is required eg: 555-555-5555',
                'Phone.*.PhoneNumber.regex' => 'Phone Number Should be of pattern : 555-555-5555',
                'Phone.*.PhoneTypeId.required' => 'Select Phone Type',
                'PhoneNumber.required' => 'Phone Number is required eg: 555-555-5555',
                'PhoneNumber.regex' => 'Phone Number Should be of pattern : 555-555-5555',
            ]
        );

        $customeraddress = new CustomersAddress();
        $existingphone = Customer::whereJsonContains('PhoneNumbers', [['PhoneNumber' => $req->input('Phone.0.PhoneNumber')]])->get();

        foreach ($existingphone as $existingphone) {
            $existingphone->PhoneNumbers = json_decode($existingphone->PhoneNumbers);
            if ($existingphone->PhoneNumbers[0]->PhoneNumber == $req->input('Phone.0.PhoneNumber')) {
                return back()->with('existing_phone', 'Primary Phone Number Already Registered')
                    ->withInput();
            } else {
            }
        }

        $PhoneJson = json_encode(array_values($req->input('Phone')));
        $customer = CustomersAddress::whereHas('Customer', function ($query) use ($req) {
            $query->where('CustomerId', '=', $req->input('CustomerId'));
        })->find($req->input('AddressId'));

        $g = new Geohash();
        $customer->AddressLine = $req->input('AddressLine');
        $customer->City = $req->input('City');
        $customer->UnitNumber = $req->input('UnitNumber') ?? null;
        $customer->Province = $req->input('Province');
        $customer->PostalCode = $req->input('PostalCode');
        $customer->Country = $req->input('Country');
        $customer->Longitude = $req->input('Longitude');
        $customer->Latitude = $req->input('Latitude');
        $customer->GeoCode = $g->encode($req->input('Latitude'), $req->input('Longitude'), 9);
        $customer->PhoneTypeId = $req->input('PhoneTypeId');
        $customer->PhoneNumber = $req->input('PhoneNumber');
        $customer->Extension = $req->input('Extension') ?? null;
        $customer->AdditionalInfo = $req->input('AdditionalInfo') ?? null;
        $customer->DoorSecurityCode = $req->input('DoorSecurityCode');

        if ($customer->push())
            $req->session()->flash('successmsg', 'Customer Record Updated Successfully');
        else
            $req->session()->flash('errormsg', 'Customer Record Not Updated!');

        $customer->Customer()->update([
            'FirstName' => $req->input('FirstName'),
            'LastName' => $req->input('LastName'),
            'PhoneNumbers' => $PhoneJson,
        ]);

        $pharmacycustomer = PharmacyCustomer::updateOrCreate(
            ['CustomerId' => $req->input('CustomerId')],
            ['PharmacyId' => $req->input('Pharmacy')]
        );

        return  redirect('/customers');
    }

    public function DeleteCustomer($id)
    {
        $id = decval($id);
        $customer = new Customer();
        $customer = Customer::find($id);

        if ($customer['Status'] == 1) {
            $customer->Status = 0;
            $customer->save();
        } else {
            $customer->Status = 1;
            $customer->save();
        }

        echo "Customer Record Deleted";
    }

    public function UpdateAddress(Request $req)
    {
        Validator::make($req->all(), [
            'EditAddressLine' => 'required',
            'EditCity' => 'required',
            'EditProvince' => 'required',
            'EditCountry' => 'required',
            'EditPostalCode' => 'required|regex:/^[A-Za-z][0-9][A-Za-z][ ][0-9][A-Za-z][0-9]$/',
            'EditPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'EditPhoneTypeId' => 'required',
        ])->validateWithBag('EditAddress');

        $lat = round($req->input('EditLatitude'), 8);
        $lon = round($req->input('EditLongitude'), 8);
        $g = new Geohash();

        $customeraddress = new CustomersAddress();
        $customeraddress = CustomersAddress::findOrFail($req->input('AddressId'));

        if ($req->input('set_primary_address') == 1) {
            $primaryaddress = CustomersAddress::where('CustomerId', '=', $req->input('CustomerId'))->where('SetAsPrimary', '=', 1)->first();
            $primaryaddress->SetAsPrimary = 0;
            $primaryaddress->save();
            $customeraddress->SetAsPrimary = 1;
        }

        $customeraddress->AddressLine = $req->input('EditAddressLine');
        $customeraddress->UnitNumber = $req->input('EditUnitNumber') ?? null;
        $customeraddress->Longitude = $lon;
        $customeraddress->Latitude = $lat;
        $customeraddress->GeoCode = $g->encode($lat, $lon, 9);
        $customeraddress->City = $req->input('EditCity');
        $customeraddress->Province = $req->input('EditProvince');
        $customeraddress->PostalCode = $req->input('EditPostalCode');
        $customeraddress->Country = $req->input('EditCountry');
        $customeraddress->PhoneTypeId = $req->input('EditPhoneTypeId');
        $customeraddress->PhoneNumber = $req->input('EditPhoneNumber');
        $customeraddress->Extension = $req->input('EditExtension') ?? null;
        $customeraddress->AdditionalInfo = $req->input('EditAdditionalInfo') ?? null;

        if ($customeraddress->save()) {
            $req->session()->flash('successmsg', 'Customer Record Updated Successfully');
            $req->session()->flash('editcustomer', '1');
        } else
            $req->session()->flash('errormsg', 'Customer Record Not Updated!');
        return redirect()->back();
    }

    public function AddAddress(Request $req)
    {
        Validator::make($req->all(), [
            'AddAddressLine' => 'required',
            'AddCity' => 'required',
            'AddProvince' => 'required',
            'AddCountry' => 'required',
            'AddPostalCode' => 'required|regex:/^[A-Za-z][0-9][A-Za-z][ ][0-9][A-Za-z][0-9]$/',
            'AddPhoneNumber' => 'required|regex:/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/',
            'AddPhoneTypeId' => 'required',
        ])->validateWithBag('AddAddress');


        $lat = round($req->input('AddLatitude'), 8);
        $lon = round($req->input('AddLongitude'), 8);
        $g = new Geohash();

        $customeraddress = new CustomersAddress();
        $customeraddress->CustomerId = $req->input('Id');


        if ($req->input('set_primary_address') == 1) {
            $primaryaddress = CustomersAddress::where('CustomerId', '=', $req->input('Id'))->where('SetAsPrimary', '=', 1)->first();
            $primaryaddress->SetAsPrimary = 0;
            $primaryaddress->save();
            $customeraddress->SetAsPrimary = 1;
        } else {
            $customeraddress->SetAsPrimary = 0;
        }
        // $customeraddress->SetAsPrimary = 0;
        $customeraddress->AddressLine = $req->input('AddAddressLine');
        $customeraddress->UnitNumber = $req->input('AddUnitNumber') ?? null;
        $customeraddress->Longitude = $lon;
        $customeraddress->Latitude = $lat;
        $customeraddress->GeoCode = $g->encode($lat, $lon, 9);
        $customeraddress->City = $req->input('AddCity');
        $customeraddress->Province = $req->input('AddProvince');
        $customeraddress->PostalCode = $req->input('AddPostalCode');
        $customeraddress->Country = $req->input('AddCountry');
        $customeraddress->PhoneTypeId = $req->input('AddPhoneTypeId');
        $customeraddress->PhoneNumber = $req->input('AddPhoneNumber');
        $customeraddress->Extension = $req->input('AddExtension') ?? null;
        $customeraddress->AdditionalInfo = $req->input('AddAdditionalInfo') ?? null;
        $customeraddress->DoorSecurityCode = '';
        if ($customeraddress->save()) {
            $req->session()->flash('successmsg', 'Customer Address Added Successfully');
        } else
            $req->session()->flash('errormsg', 'Customer Record Not Updated!');
        return redirect()->back();
    }

    public function DeleteAddress($id)
    {
        $id = decval($id);
        $customeraddress = new CustomersAddress();
        $customeraddress = CustomersAddress::find($id);
        $customeraddress->delete();

        echo "Secondary Address Deleted";
    }
}
