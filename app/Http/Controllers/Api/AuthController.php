<?php

namespace App\Http\Controllers\Api;

use App\Models\BatchAssign;
use App\Models\CustomerVisit;
use App\Models\PharmacyCustomer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;


class AuthController extends Controller
{
    public function index(Request $request){

        $request->validate([
            'Email' => 'required|email',
            'Password' => 'required',
            'Appid' => 'required',
        ]);


            $email = $request['Email'];
            $pass = $request['Password'];

            $user = UserModel::where('email',$email)->first();

            if (!$user || !Hash::check($pass, $user->Password)) {
                return response([
                    'Status' => 'Failed',
                    'Message' => 'These credentials do not match our records.'
                ], 403);
            }

            $user = UserModel::where('email',$email)->first();

            if (!$user || !Hash::check($pass, $user->Password)) {
                return response([
                    'Status' => 'Failed',
                    'Message' => 'These credentials do not match our records.'
                ], 403);
            }

            $Newtoken = strtotime(now()).$email;

            $token = $user->createToken($Newtoken)->plainTextToken;
            $DriverId = $user->Id;

            /*The Logic Written below is to get all pharmacies who's Listing is available in today's visit */
            $start = now();
            $FindPharmaciesAssigned = collect(BatchAssign::with('Batch')
                ->where('DriverID', $DriverId)->whereDate('AssignedDate' , $start)->get()
                ->pluck('batch')->pluck('VisitBatchID'));

            $VisitIds = [];

            foreach($FindPharmaciesAssigned as $k => $SortVids){

                $VisitIds[$k] = explode('_',$SortVids);
                unset($VisitIds[$k][0]);
            }
            $FinalAr =  Arr::collapse($VisitIds);

            $FindPharmacies = CustomerVisit::with('PharmacyUser')->whereIn('CustomerVisitID',$FinalAr)
                ->get()->keyBy('PharmacyUser.PharmacyId')->pluck('PharmacyUser.PharmacyId')->toArray();
               // ->pluck('PharmacyId')->toArray();
            //dd($FindCustomers);
           /* $FindPharmacies = PharmacyCustomer::select('PharmacyId')->whereIn('CustomerID',$FindCustomers)
                ->get()->pluck('PharmacyId')->toArray();*/

            return response([
               // 'Customers'=>$FindCustomers,
                'Id' => $DriverId,
                'FirstName' => $user->FirstName,
                'LastName' => $user->LastName,
                'Email' => $user->Email,
                'Pharmacies'=>$FindPharmacies,
                'Token' => $token,
                'Status' => 'Success',
                'Message' => 'Successfully Logged In!'
            ], 200);


     }

    public function logout(Request $request){

        //$user = new UserModel();

        // change status time but not deleting from DB
        // $user->tokens()->delete();

        // delete current token which will send from user // Delete from DB as well
        $user = $request->user()->currentAccessToken()->delete();

        if($user){
            return response([
                'Status' => 'Success',
                'Message' => 'You are successfully Logged out.',
            ], 200);
        }

        return response([
            'message' => ['Unauth']
        ], 200);



    }

    function testtoken(){

        echo 1;

    }



}
