@include('Includes.Header')
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="batched-orders">
    <div class="container-fluid mt-n5">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        @include('Orders.Tabs.OrdersTabs')
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <form id="batchtodriver" method="post" action="@if($editassigedbatch != null) {{route('EditAssignedBatches')}} @else {{route('SendToDelivery')}} @endif">
            @csrf
          <div class="text-center mb-3">            
          </div>
        </div>
          <div class="card">
            <div class="card-body">
                <div class="row">
                  <div class="col-md-6 float-left"><h4>Batch(s) ready for delivery - <span id="count-checked-checkboxes">0</span> selected batch(s)</h4></div>
                 <div class="col-md-6 text-right">
                   @if($editassigedbatch != null)
                     <button type="submit" form="batchtodriver" class="btn btn-outline-primary waves-effect" id="sendtodelivery"><i class="icon-item fas fa-layer-group" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""></i> Edit Them</button>
                     <input type="hidden" name="DriverId" value="{{$editassigedbatch[0]['assigned']['DriverID']}}">
                  @else
                    <button type="submit" class="btn btn-outline-primary waves-effect" id="sendtodelivery" style="visibility: hidden;"><i class="icon-item fas fa-boxes" role="button" data-prefix="fas" data-id="boxes" data-unicode="f468" data-mdb-original-title="" title=""></i> Send to Delivery</button>
                  @endif

                  <!-- <a href="{{route('DeliveredBatches')}}"><button type="button" class="btn btn-outline-primary waves-effect"><i class="icon-item fas fa-layer-group" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""></i> Delivered Batches</button></a> -->
                </div>
                </div>
              <hr>
              <table id="batched_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Visit ID</th>
                    <th>Pharmacy</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Goods</th>
                    <th>Phone</th>
                    <th>Delivery</th>
                    <th>Tags</th>
                    <th style="width: 12%;">
                      <fieldset class="form-check">
                        <input class="form-check-input filled-in" type="checkbox" id="SelectAll">
                        <label class="form-check-label" for="SelectAll">All</label>
                      </fieldset>         
                    </th>
                  </tr>
                </thead>
                <tbody>
              <?php 
              $x = 0;
              foreach($batchdata as $batchdetails)  { 
              $checked = '';
              $x++;
              $DriverName = '';
              if($batchdetails['assigned'] != null){
                $checked = 'disabled';
                $Drivername = ucfirst($batchdetails['assigned']['driver']['FirstName'].' '.$batchdetails['assigned']['driver']['LastName']);
              }else{
                $Drivername ="Not Assigned";
              }
              if($editassigedbatch != null)
              {

                foreach($editassigedbatch as $e)
                {
                  if($e['VisitBatchID'] == $batchdetails['VisitBatchID']) 
                  {
                    $checked = ' checked';
                    break;
                  } 
                }
              }
              ?>              
              <tr class="mainrow">
                <td class="batched_row" colspan="8"><div><i class="icon-item fas fa-layer-group text-color float-left" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title=""  data-title="{{'Driver: '.$Drivername}}" title="" > # {{$batchdetails['TrackingID']}}</i></div>
                  <?php $batchid = $batchdetails['TrackingID'];?>
                  <div class="print-section-{{$x}}" style="display: none;">
                    <img src="data:image/png;base64,{{DNS2D::getBarcodePNG(ucwords($batchid), 'QRCODE')}}" class="img-fluid" alt="" name="Barcode" style="height:8rem;">
                  </div>                 
                  <a href="data:image/png;base64,{{DNS2D::getBarcodePNG(ucwords($batchid), 'QRCODE')}}" download="{{'Batch_'.$batchdetails['TrackingID'].'_bc'}}"><i class="icon-item fas fa-download text-color ml-3" role="button" title="Download Barcode"></i></a>
                  <a href="javascript:void(0);" onclick="printbarcode({{$x}})"><i class="icon-item fas fa-print text-color" role="button" title="Print Barcode"></i></a>
                </td>       
                <td class="batched_row">
                  <fieldset class="form-check">
                    <input class="form-check-input filled-in forcount" type="checkbox" id="checkbox{{$x}}" value="{{$batchdetails['BatchID']}}" name="SelectedBatch[]" {{$checked}}>
                    <label class="form-check-label" for="checkbox{{$x}}"></label>
                    <a href="/orders/{{encval($batchdetails['VisitBatchID'])}}"><i class="icon-item far fa-edit" role="button" data-prefix="far" data-id="edit" data-unicode="f044" data-mdb-original-title="" title=""></i></a>
                    <a href="javascript:void(0);" data-id="{{encval($batchdetails['BatchID'])}}" data-name="{{$batchdetails['TrackingID']}}" id="deleteBatch"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a>
                  </fieldset>
                </td>
                <?php for($b = 0; $b < count($batchdetails['visit_batch']); $b++) { 
                  $batchdetails['visit_batch'][$b]['customer_visit']['customer']['PhoneNumbers']=json_decode($batchdetails['visit_batch'][$b]['customer_visit']['customer']['PhoneNumbers']); 
                ?>
              </tr>
                  <tr>
                    <?php
                      $starttime = date('h:i a', strtotime($batchdetails['visit_batch'][$b]['customer_visit']['ArrivalLogTime']));
                    ?>
                    <td>{{$batchdetails['visit_batch'][$b]['CustomerVisitID']}}</td>
                    <td>{{$batchdetails['visit_batch'][$b]['customer_visit']['customer']['pharmacy_customer']['pharmacy']['PharmacyName']}}</td>
                    <td>{{$batchdetails['visit_batch'][$b]['customer_visit']['VisitDate']}}</td>
                    <td>                   
                     @if(Session::get('usertype') == "Admin" || Session::get('usertype') == "Pharmacist")                      
                      <a href="/orders/edit_schedule/{{encval($batchdetails['visit_batch'][$b]['customer_visit']['SchedulerID'])}}"><span class="orderdetail_tooltip mr-1" id="{{$batchdetails['visit_batch'][$b]['customer_visit']['SchedulerID']}}" style="cursor:pointer;"><i class="fas fa-info-circle"></i></span></a>
                      <a href="/customers/edit_customer/{{encval($batchdetails['visit_batch'][$b]['customer_visit']['CustomerID'])}}" style="color:#007bff;"  >{{$batchdetails['visit_batch'][$b]['customer_visit']['customer']['FirstName']}} {{$batchdetails['visit_batch'][$b]['customer_visit']['customer']['LastName']}}
                      </a>
                    @else
                      {{$batchdetails['visit_batch'][$b]['customer_visit']['customer']['FirstName']}} {{$batchdetails['visit_batch'][$b]['customer_visit']['customer']['LastName']}}
                    @endif 
                   </td>                  
                   <td>
                     @foreach($batchdetails['visit_batch'][$b]['customer_visit']['status'] as $status)
                      {{$status['goods']['GoodsName']}}</br>
                      @endforeach
                   </td>
                    <td>{{$batchdetails['visit_batch'][$b]['customer_visit']['customer']['PhoneNumbers'][0]->PhoneNumber}}</td>
                    <td>{{$starttime}}</td>
                    <td>@foreach($batchdetails['visit_batch'][$b]['customer_visit']['scheduler']['Tags'] as $tags)<span style="padding:5px;background-color:{{$tags['color']}}" class="badge status-pill">{{$tags['name']}}</span> @endforeach</td>
                    <td></td>
                  </tr> 
                  <?php
                    }
                  }
                  ?>
              </table>
            </div>
          </div>
          </form>
        </div>
        <!-- Gird column -->

        <!-- Gird column -->
        <!-- Gird column -->

      </section>
      <!-- Section: Basic examples -->

    </div>

  </main>
@include('Includes.Footer')
<script src="{{asset('js/Orders/BatchedOrders.js')}}"></script>

 
