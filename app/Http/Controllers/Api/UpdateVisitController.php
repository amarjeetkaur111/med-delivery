<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerVisit;
use App\Models\VisitBatch;
use App\Models\VisitStatus;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UpdateVisitController extends Controller
{
   public function index(Request $request){

       $validity = auth('sanctum')->check();

       if($validity == ''){
           return response([
               'Status' => 'Failed',
               'Message' => 'Token Expired. Plz login.',
               'StatusCode' => 0,
           ]);
       }else {
           //$posts = $request->all();
           foreach ($request->all() as $item) {

               $CustomerVisit = CustomerVisit::where('CustomerVisitID', $item['CustomerVisitID'])->first();
               $CustomerVisit->VisitStatusID = $item['VisitStatusID'];
               $CustomerVisit->EmployeeID = $item['EmployeeID'];
               $CustomerVisit->PackageScanStatus = $item['PackageScanStatus'];
               $CustomerVisit->DeliveryNotes = $item['DeliveryNotes'];
               $CustomerVisit->DeliveryComment = $item['DeliveryComment'];
               $CustomerVisit->RecipientName = $item['RecipientName'];
               $CustomerVisit->ReceivedBy = $item['ReceivedBy'];
               $CustomerVisit->PaymentMode = $item['PaymentMode'];
               $CustomerVisit->ReceivedByRelation = $item['ReceivedByRelation'];
               $CustomerVisit->CollectedAmount = $item['CollectedAmount'];

               $CustomerVisit->save();

               $VisitStatus = VisitStatus::where('CustomerVisitID', $item['CustomerVisitID'])->update(["GoodsStatusID" => 1]);

               $VisitBatch = VisitBatch::where('CustomerVisitID', $item['CustomerVisitID'])->update(["BatchStatusID" => 2]);

           }

           if ($CustomerVisit) {
               return response([
                   'Status' => 'Success',
                   'Message' => 'Visit Updated Successfully!'
               ], 200);
           }
       }
    }

    public function UploadVisitSign(Request $request){

        $validity = auth('sanctum')->check();

        if($validity == ''){
            return response([
                'Status' => 'Failed',
                'Message' => 'Token Expired. Plz login.',
                'StatusCode' => 0,
            ]);
        }else {
            $VisitID = $request->CustomerVisitID;
            $base64image = $request->SignFile;

            $validator = Validator::make($request->all(), [
                'CustomerVisitID' => 'required',
                'SignFile' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'Errors' => $validator->errors(),
                ], 403);
            }
            $ImageTypesSupport = ['png', 'jpeg', 'jpg'];

            @list($type, $file_data) = explode(';', $base64image);
            @list(, $file_data) = explode(',', $file_data);
            $type = explode(";", explode("/", $base64image)[1])[0];

            if (!in_array($type, $ImageTypesSupport)) {
                return response([
                    'Status' => 'Failed',
                    'Message' => 'Type Not Supported!'
                ], 403);
            }

            $path = 'VSign/' . Crypt::encryptString($VisitID) . '.' . $type;
            $Stored = Storage::disk('local')->put($path, base64_decode($file_data));

            //Update Sign Path
            $CustomerVisit = CustomerVisit::where('CustomerVisitID', $VisitID)->first();
            $CustomerVisit->SignPath = $path;
            $CustomerVisit->save();

            if ($Stored && $CustomerVisit) {
                return response([
                    'Status' => 'Success',
                    'Message' => 'Signature Images Updated Successfully!'
                ], 200);
            }
        }

    }

}
