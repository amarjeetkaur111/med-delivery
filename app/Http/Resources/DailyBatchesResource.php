<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyBatchesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $PharmacyID = data_get($this->Customer->PharmacyCustomer,'PharmacyId');

        return [
            'PharmacyId' => $PharmacyID,
            'PharmacyName' => GetPharmacyName($PharmacyID),
            'CustomerVisitID' => $this->CustomerVisitID,
            'CustomerID' => $this->CustomerID,
            'VisitStatusID' => $this->VisitStatusID,
            'FirstName' => data_get($this->Customer,'FirstName'),
            'LastName' => data_get($this->Customer,'LastName'),
           // 'PhoneNumbers' => json_decode(data_get($this->Customer,'PhoneNumbers')),
            'Address' => $this->Customer->CustomersAddressPrimary(),
            'Tags' => new TagsResource($this->Scheduler),
            'BatchID' => data_get($this->VisitBatch, 'BatchID'),
            'BatchStatusID' => data_get($this->VisitBatch, 'BatchStatusID'),
            'ManifestURL' => url('/')."/orders/visit_invoice/$this->CustomerVisitID/view",
            'ServiceStartTime' => date('H:i A', strtotime(data_get($this->Scheduler,'StartTime'))),
            'Amount' => data_get($this->Scheduler,'Amount'),
            'OrderNote' => data_get($this->Scheduler,'OrderNote'),
            'InvoiceNo' => date('Ymd').$this->CustomerVisitID,
        ];


        //return parent::toArray($request);
    }
}
