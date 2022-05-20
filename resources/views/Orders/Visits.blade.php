@include('Includes.Header')
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="order-list">
    <div class="container-fluid cont-margin">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        @include('Orders.Tabs.OrdersTabs')
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <div class="head text-center">
          </div>
        </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 float-left"><h4 class="mt-3">Today's Visits : {{date('d/m/Y')}}</h4></div>
                <div class="col-md-6 text-right">
                  @if(count($editbatch) > 0)
                     <button type="submit" form="ordertobatch" class="btn btn-outline-primary waves-effect" id="batchthis"><i class="icon-item fas fa-layer-group" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""></i> Edit Them</button>
                  @else
                    <button type="submit" form="ordertobatch" class="btn btn-outline-primary waves-effect" id="batchthis" style="visibility: hidden;"><i class="icon-item fas fa-layer-group" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""></i> Batch Them</button>
                  @endif
                  @if(Session::get('usertype') == "Admin" || Session::get('usertype') == "Pharmacist")
                    <a href="{{route('AutoBatch')}}" id="autobatch"><button type="button" class="btn btn-outline-primary waves-effect">AutoBatch Visits</button></a>
                  @endif
                </div>
              </div>
              <hr>
              <form id="ordertobatch" action="@if(count($editbatch) > 0) {{route('EditBatch')}} @else {{route('VisitsToBatch')}} @endif" method="post">
              @csrf
              <input type="hidden" name="url" value="{{URL::previous()}}">
              <table id="orders_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <!-- <th>Visit ID</th> -->
                    <th>BatchID</th>
                    <th>Pharmacy</th>
                    <th>Name</th>
                    <th>Primary Phone</th>
                    <th>Arrival Time</th>
                    <th>Finish Time</th>
                    <th>Goods</th>
                    <th>Status</th>
                    <th>
                      <fieldset class="form-check">
                        <input class="form-check-input filled-in" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">All</label>
                      </fieldset>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php $x = 0;?>
                  @foreach($visitdata as $visit)
                  <?php
                  $x++;
                  $starttime = date('h:i a', strtotime($visit['ArrivalLogTime']));
                  $endtime = date('h:i a', strtotime($visit['FinishLogTime']));
                  $startdate = date('d/m/Y', strtotime($visit['VisitDate']));
                  $vs = $visit['VisitStatusID'];
                  $status = null; $color='';
                  if($vs == 1) {$status = 'New Order'; $color = 'background:#ff776b';} 
                  elseif($vs == 2) { $status ='Delivered'; $color = 'background:#00ff00';}
                  elseif($vs == 3) { $status = 'Skipped'; $color = 'background:#0099ff'; }
                  elseif($vs == 4) { $status = 'Cancelled'; $color = 'background:#CCCCCC'; }
                  elseif($vs == 5) { $status = 'Postponed'; $color = 'background:#ff33cc'; }
                  elseif($vs == 6) { $status = 'Returned'; $color = 'background:#f9f900'; } 
                  else { $status = 'Undelivered'; $color = 'background:#fff;color:black!important;'; }
                  $checked = '';
                  $BatchId = '';
                  $DriverName = 'Not Assigned';

                  for($b = 0; $b < count($batch); $b++)
                  {
                    if($batch[$b]['CustomerVisitID'] == $visit['CustomerVisitID'])
                    {
                      $checked = ' disabled';
                      $BatchId = $batch[$b]['batch']['TrackingID'];
                      if($batch[$b]['batch']['assigned'] != '')
                        $DriverName = ucfirst($batch[$b]['batch']['assigned']['driver']['FirstName']." ".$batch[$b]['batch']['assigned']['driver']['LastName']);
                      else
                        $DriverName = 'Not Assigned';
                      break;
                    }
                  }

                  for($e = 0; $e < count($editbatch); $e++)
                  {
                    if($editbatch[$e] == $visit['CustomerVisitID'])
                    {
                      $checked = ' checked';
                      break;
                    }
                  }
                  ?>
                  <tr>
                    <!-- <td># {{$visit['CustomerVisitID']}}</td> -->
                    <input type="hidden" name="BatchID" value="@if($editbatch!=null) {{$editbatch[0]}} @endif">
                    <td><div data-title="{{'Driver: '.$DriverName}}">{{$BatchId}}</div></td>
                    <td>{{$visit['customer']['pharmacy_customer']['pharmacy']['PharmacyName'] ?? 'Not Assigned'}}</td>
                    @if(Session::get('usertype') == "Admin" || Session::get('usertype') == "Pharmacist")
                    <td style="display:flex">
                          <a href="/orders/edit_schedule/{{encval($visit['SchedulerID'])}}"><span class="orderdetail_tooltip mr-1" id="{{$visit['SchedulerID']}}" style="cursor:pointer;"><i class="fas fa-info-circle"></i></span></a>
                          <a href="/customers/edit_customer/{{encval($visit['CustomerID'])}}" style="color:#007bff;"  >{{$visit['customer']['FirstName']??''}} {{$visit['customer']['LastName']??''}}
                          </a>
                    </td>
                    @else
                      <td>{{$visit['customer']['FirstName']}} {{$visit['customer']['LastName']}}</td>
                    @endif
                    <td>{{isset($visit['customer']['PhoneNumbers'][0])?$visit['customer']['PhoneNumbers'][0]->PhoneNumber:''}}</td>
                    <td>{{$starttime}}</td>
                    <td>{{$endtime}}</td>
                    <td>@for($i = 0; $i < count($visit['status']); $i++){{$visit['status'][$i]['goods']['GoodsName']}}<br>@endfor</td>
                    <td><span class="badge badge-pill status-pill" style="{{$color}}">{{$status}}</span></td>
                    <td>
                      <fieldset class="form-check">
                      <input class="form-check-input filled-in forcount" type="checkbox" id="checkbox{{$x}}"  value="{{$visit['CustomerVisitID']}}" name="SelectedOrder[]" {{$checked}} >
                      <label class="form-check-label" for="checkbox{{$x}}"></label>
                      <?php $visitid = $visit['CustomerVisitID'];?>

                      <a href="/orders/edit_schedule/{{encval($visit['SchedulerID'])}}"><i class="icon-item far fa-edit mr-n1" role="button" data-prefix="far" data-id="edit" data-unicode="f044" data-mdb-original-title="" title="Edit Order"></i></a>
                      <a href="{{route('PdfInvoice', ['id'=>$visit['CustomerVisitID'],'code'=>'download'])}}"><i class="icon-item fas fa-download text-color" role="button" title="Download Invoice"></i></a>
                      <a href="{{route('PdfInvoice', ['id'=>$visit['CustomerVisitID'],'code'=>'print'])}}" target="_blank"><i class="icon-item fas fa-print text-color" role="button" title="Print Invoice"></i></a>
                      </fieldset>

                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              </form>
            </div>
          </div>
        </div>
      </section>
      <!-- Section: Basic examples -->
    </div>
  </main>
<!-- Main layout -->
@include('Includes.Footer')
<script>
  
</script>

<script src="{{asset('js/Orders/Orders.js')}}"></script>
