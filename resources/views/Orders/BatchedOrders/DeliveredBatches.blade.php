<style type="text/css">
  .btn-group, .btn-group-vertical
  {
    width: 16rem;
  }
</style>
@include('Includes.Header')
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="delivered-batches">
    <div class="container-fluid mt-n5">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        @include('Orders.Tabs.OrdersTabs')
        <div class="col-md-12">
          <h5 class="dark-grey-text font-weight-bold"></h5>
    		  <div class="text-center mb-3">
    		  </div>
        </div>
              <div class="card">
                <div class="card-body">
                  <form method="post" action="{{route('DeliveredBatches')}}">
                  @csrf
            				<div class="row">
            					<div class="col-md-5 float-left"><h4>Batches Assigned To Drivers</h4>
                      </div>
                      <div class="col-md-2 text-right mt-3 mr-n4">
                        <h5>Select Drivers</h5>
                      </div>
                      <div class="col-md-3 text-center mt-2">
                        <div class="form-group ">
                          <div class="iflex">
                            <select class="form-control multiselectopt" multiple="multiple" name="SelectedDrivers[]"  required>
                              @foreach($drivers as $driver)
                              <option value="{{$driver['Id']}}">{{$driver['FirstName']}} {{$driver['LastName']}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2 text-left">
                        <input type="submit" name="Find" class="btn btn-primary waves-effect waves-light" value="Find Result" >
                      </div>
                    </div>
                  </form>
    			       <hr>
              @if($batchassigned != null)
              @foreach($batchassigned as $data) 
               <div class="card">
                <div class="card-header white-text primary-color">
                  <div class="row">
                    <div class="col-md-6">
                      {{ucfirst($data['FirstName'])}} {{ucfirst($data['LastName'])}}
                    </div>
                    <div class="col-md-6 text-right">
                      @if($data['batch_assigned'] != null) 
                       <a href="/orders/batched_orders/{{encval($data['Id'])}}"  target=”_blank”><i class="icon-item far fa-edit white-text" role="button" data-prefix="far" data-id="edit" data-unicode="f044" data-mdb-original-title="" title=""></i></a>
                        <!-- <a href="javascript:void(0);" data-id="{{$data['Id']}}" id="unassigndriver" data-name="{{ucfirst($data['FirstName'])}} {{ucfirst($data['LastName'])}}"><i class="icon-item fas fa-trash-alt white-text" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a> -->
                      @endif
                    </div>
                  </div>
                </div>
                <div class="card-body mb-3 bg-white" id="">
                  @if($data['batch_assigned'] == null) 
                    <p>No Batch Assigned</p>
                  @else
                  <table id="delivery_table" class="table table-striped" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th>Visit ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Goods</th>
                        <th>Phone</th>
                        <th>Delivery</th>
                        <th></th>
                      </tr>
                    </thead>
                    @foreach($data['batch_assigned'] as $batch) 
                    @if($batch['batch']['visit_batch'][0]['customer_visit']['customer']['pharmacy_customer']['pharmacy'] != null)
                    <tbody>                                   
                      <tr class="mainrow">
                        <td class="batched_row" colspan="6"><i class="icon-item fas fa-layer-group text-color float-left" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""> # {{$batch['batch']['TrackingID']}}</i></td>       
                        <td class="batched_row">
                          <a href="/orders/{{encval($batch['batch']['VisitBatchID'])}}"  target=”_blank”><i class="icon-item far fa-edit" role="button" data-prefix="far" data-id="edit" data-unicode="f044" data-mdb-original-title="" title=""></i></a>
                          <a href="javascript:void(0);" data-id="{{encval($batch['batch']['BatchID'])}}" id="unassignbatch" data-name="{{$batch['batch']['TrackingID']}}"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a>
                        </td>  
                      </tr>
                      @foreach($batch['batch']['visit_batch'] as $visit) 
                      <?php  
                          $visit['customer_visit']['customer']['PhoneNumbers']=json_decode($visit['customer_visit']['customer']['PhoneNumbers']); 
                          $starttime = date('h:i a', strtotime($visit['customer_visit']['ArrivalLogTime']));
                        ?>
                         <tr>
                          <td>{{$visit['customer_visit']['CustomerVisitID']}}</td>
                          <td>{{$visit['customer_visit']['VisitDate']}}</td>
                          <td>
                            @if(Session::get('usertype') == "Admin" || Session::get('usertype') == "Pharmacist")
                             

                              <a href="/orders/edit_schedule/{{encval($visit['customer_visit']['SchedulerID'])}}"><span class="orderdetail_tooltip mr-1" id="{{$visit['customer_visit']['SchedulerID']}}" style="cursor:pointer;"><i class="fas fa-info-circle"></i></span></a>
                              <a href="/customers/edit_customer/{{encval($visit['customer_visit']['CustomerID'])}}" style="color:#007bff;"  >{{$visit['customer_visit']['customer']['FirstName']}} {{$visit['customer_visit']['customer']['LastName']}} </a>
                            @else
                              {{$visit['customer_visit']['customer']['FirstName']}} {{$visit['customer_visit']['customer']['LastName']}}
                            @endif 
                          </td>                  
                         <td>
                          @foreach($visit['customer_visit']['status'] as $status)
                            {{$status['goods']['GoodsName']}}</br>
                          @endforeach
                         </td>
                          <td>{{$visit['customer_visit']['customer']['PhoneNumbers'][0]->PhoneNumber}}</td>
                          <td>{{$starttime}}</td>
                          <td></td>
                        </tr> 
                      @endforeach
                      @endif
                    @endforeach
                    </tbody>                
                  </table>
                  @endif
                </div>
              </div>
              @endforeach
              @endif
            </div>
          </div>

        </div>
        <!-- Gird column -->

        <!-- Gird column -->
        <!-- Gird column -->

      </section>
      <!-- Section: Basic examples -->

    </div>

  </main>
  <!-- Main layout -->

@include('Includes.Footer')
<script type="text/javascript" src="{{ asset('js/multiselect/bootstrap-multiselect.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/multiselect/bootstrap-multiselect.min.css') }}" type="text/css"/>
<script src="{{asset('js/Orders/AssignToDriver.js')}}" ></script>
<script>
  $('.multiselectopt').multiselect({
        includeSelectAllOption: true,
        selectAllValue: 'select-all-value',
        maxHeight: 400,
  });
    // Material Select Initialization
    $(document).ready(function () {
      $('.mdb-select').materialSelect();
    $('#delivery_table').DataTable();
    });

</script>
