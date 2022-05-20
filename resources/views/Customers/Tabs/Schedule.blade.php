<div class="container-fluid">
<!-- Section: Cascading panels -->
  <section>
  <!-- Grid row -->
    <div class="row">
      <!-- Grid column -->
      <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
        <div class="card">
          <div class="card-body">
    				<div class="row">
    					<div class="col-md-2">
                <span><i class="fas fa-file-archive fa-5x" style="color: #707070"></i></span>
              </div>
              <div class="col-md-9">
                <h4 class="pt-4">Schedules</h4>
              </div>
    				</div>
            <div class="row ml-n5 mt-3">
              <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
                <table id="customer_table" class="table table-striped" cellspacing="0" width="100%">
                  <thead>
                    <tr>
                      <!--<th>Sch ID</th>-->
                      <th>Type</th>
                      <th>Description</th>
                      <th>Goods</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach($scheduledata as $data)
                   <?php
                    if($data['scheduler']['scheduler_recurrence'] !==null)
                    {
                      $rec_count =  count($data['scheduler']['scheduler_recurrence']);
                      $goods = [];

                      for($i=0; $i < count($data['scheduler']['scheduler_recurrence']); $i++)
                      {

                        $selecteddays =  $data['scheduler']['scheduler_recurrence'][$i]['RecurrenceSelectedDays'];
                        $rectypeid = $data['scheduler']['scheduler_recurrence'][$i]['RecurrenceTypeID'];
                        // get_d($selecteddays)

                        if(($i+1) != $rec_count && $rectypeid == $data['scheduler']['scheduler_recurrence'][$i+1]['RecurrenceTypeID'] && count(array_diff($selecteddays,$data['scheduler']['scheduler_recurrence'][$i+1]['RecurrenceSelectedDays'])) == 0)
                         {
                            array_push($goods,$data['scheduler']['scheduler_recurrence'][$i]['goods']['GoodsName']);
                         }
                        else
                        {
                           array_push($goods,$data['scheduler']['scheduler_recurrence'][$i]['goods']['GoodsName']);
                           $rectype='';
                           if($rectypeid == 1)
                              $rectype = 'OneTime';
                            elseif($rectypeid == 2)
                              $rectype = 'Daily';
                            elseif($rectypeid == 3)
                              $rectype = 'Weekly';
                            elseif($rectypeid == 4)
                              $rectype = '1st Week';
                            elseif($rectypeid == 5)
                              $rectype = '2nd Week';
                            elseif($rectypeid == 6)
                              $rectype = '3rd Week';
                            elseif($rectypeid == 7)
                              $rectype = '4th Week';
                            else
                              $rectype = 'Custom Dates';

                            $starttime = date('h:i a', strtotime($data['scheduler']['StartTime']));
                            $endtime = date('h:i a', strtotime($data['scheduler']['EndTime']));
                            $startdate = date('d/m/Y', strtotime($data['scheduler']['StartDate']));

                            if($data['scheduler']['EndDate'] == null)
                              $enddate = null;
                            else
                              $enddate = date('d/m/Y', strtotime($data['scheduler']['EndDate']));

                        ?>
                        <tr>
                          <!--<td>{{$data['SchedulerID']}}</td>-->
                          <td>{{$rectype}}</td>
                          <td>{{'Visit'}}
                            @if($rectypeid == '1')
                              {{' One Time'}}
                            @elseif($rectypeid == '2')
                              {{' Every Day'}}
                            @elseif($rectypeid == '3' || $rectypeid == '4' || $rectypeid == '5' || $rectypeid == '6' || $rectypeid == '7')
                              {{' Every'}}
                              @if($rectypeid == '4')
                                {{' 1st Week on'}}
                              @elseif($rectypeid == '5')
                                {{' 2nd Week on'}}
                              @elseif($rectypeid == '6')
                                {{' 3rd Week on'}}
                              @elseif($rectypeid == '7')
                                {{' 4th Week on'}}
                              @endif
                              @foreach($selecteddays as $selecteddays)
                                @if($selecteddays=='1'){{' Monday'}}
                                @elseif($selecteddays=='2'){{', Tuesday'}}
                                @elseif($selecteddays=='3'){{', Wednesday'}}
                                @elseif($selecteddays=='4'){{', Thursday'}}
                                @elseif($selecteddays=='5'){{', Friday'}}
                                @elseif($selecteddays=='6'){{', Saturday'}}
                                @else(){{', Sunday'}}
                                @endif
                              @endforeach
                            @else()
                              {{' Every'}}
                              @foreach($selecteddays as $selecteddays){{$selecteddays, }}@endforeach
                            @endif
                            <br>
                            {{' during '}}
                            {{$starttime}} - {{$endtime}}

                            {{' from '}}
                            {{$startdate}}{{($enddate == null) ? ' go on to indefinitely.' : ' - '.$enddate.'.'}}
                            <br>
                          </td>
                          <td>
                              <ol>
                                @for($m = 0; $m < count($goods); $m++)
                                  <li> {{$goods[$m]}} </li>
                                @endfor
                              </ol>
                          </td>
                            <?php if(count($data['scheduler']['scheduler_recurrence']) - 1 == $i){ ?>
                          <td><a href="{{ '/orders/edit_schedule/'. encval($data['SchedulerID']) }}" ><i class="icon-item fas fa-user-edit" role="button" data-prefix="fas" data-id="user-edit" data-unicode="f4ff" data-mdb-original-title="" title=""></i></a></td>

                          <td><a href="javascript:void(0);" data-name="" data-id="{{encval($data['SchedulerID'])}}" id="deleteCustomer"><i class="icon-item fas fa-trash-alt" role="button" data-prefix="fas" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a></td>
                             <?php   }else{   ?>
                                <td></td>
                                <td></td>
                            <?php   }  ?>
                        </tr>
                      <?php
                         $goods = [];
                      }
                    }
                  }
                  ?>
                  @endforeach
                </tbody>
                </table>
                <div class="row">
                    <!-- Grid column -->
                    <div class="col-md-12">
                      <div class="float-right">
                        @if($scheduledata)
                          <a href="{{'/orders/add_schedule/'.encval($scheduledata[0]['CustomerID'])}}"  class="btn btn-outline-primary waves-effect waves-light">Add Schedule</a>
                        @else                          
                          <a href="{{'/orders/add_schedule/'.encval($data[0]['CustomerId'])}}"  class="btn btn-outline-primary waves-effect waves-light">Add Schedule</a>
                        @endif
                      </div>                    
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
