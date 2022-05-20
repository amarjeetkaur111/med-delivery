@include('Includes.Header')
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="order-list">
    <div class="container-fluid mb-5 cont-margin">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        <div class="col-md-12 pb-3">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <div class="head text-center">

            <!-- <a href="/orders/send_to_delivery"><button type="button" class="btn btn-outline-primary waves-effect"><i class="icon-item fas fa-boxes" role="button" data-prefix="fas" data-id="boxes" data-unicode="f468" data-mdb-original-title="" title=""></i>  Send to Delivery</button></a>
            <button type="button" class="btn btn-outline-primary waves-effect"><i class="icon-item far fa-window-close" role="button" data-prefix="far" data-id="window-close" data-unicode="f410" data-mdb-original-title="" title=""></i>  Cancel</button> -->
            
          </div>
        </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 float-left"><h4>Orders ready for delivery - 0 selected order(s)</h4></div> 
                <div class="col-md-6 text-right">
                  <button type="submit" form="ordertobatch" class="btn btn-outline-primary waves-effect" id="batchthis" style="visibility: hidden;"><i class="icon-item fas fa-layer-group" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""></i> Batch This</button>
                  <a href="/orders/batched_orders"><button type="button" class="btn btn-outline-primary waves-effect"><i class="icon-item fas fa-layer-group" role="button" data-prefix="fas" data-id="layer-group" data-unicode="f5fd" data-mdb-original-title="" title=""></i> Batched Orders</button></a>
                </div>
              </div>
              <hr>
              <form id="ordertobatch" action="{{route('OrdersToBatch')}}" method="post">
              @csrf
              <table id="orders_table" class="table table-striped" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sch Number</th>
                    <th>Customer</th>
                    <th>Tags</th>
                    <th>Address</th>
                    <th>Delivery</th>
                    <th>Amount</th>
                    <th>Actions</th>
                    <th>
                      <fieldset class="form-check">
                        <input class="form-check-input filled-in" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">All</label>
                      </fieldset>         
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @for($i = 0; $i < count($data); $i++)
                  <tr>
                    <td># {{$data[$i]['SchedulerID']}}</td>
                    <td>{{$data[$i]['customer']['FirstName']}} {{$data[$i]['customer']['LastName']}}</td>
                    <td>@foreach($data[$i]['scheduler']['Tags'] as $schtags)
                          @for($j = 0; $j < count($tags); $j++)
                            @if($tags[$j]['TagId'] == $schtags)
                              <span class="badge" title="{{$tags[$j]['TagDescription']}}" style="padding:5px;background-color:{{$tags[$j]['TagColor']}}">{{$tags[$j]['TagName']}}</span>
                            @endif
                          @endfor
                        @endforeach
                    </td>
                    <td>{{$data[$i]['customer']['customers_address'][0]['City']}}</td>
                    <td>{{$data[$i]['scheduler']['StartDate']}}</td>
                    <td>${{$data[$i]['scheduler']['Amount']}} </td>

                    <td>
                      <a href="/orders/edit_schedule/{{encval($data[$i]['SchedulerID'])}}"><i class="icon-item far fa-eye" role="button" data-prefix="far" data-id="eye" data-unicode="f06e" data-mdb-original-title="" title=""></i></a> 

                      <a href="/orders/edit_schedule/{{encval($data[$i]['SchedulerID'])}}"><i class="icon-item far fa-edit" role="button" data-prefix="far" data-id="edit" data-unicode="f044" data-mdb-original-title="" title=""></i></a>

                      <a href="javascript:void(0);" data-name="{{$data[$i]['customer']['FirstName']}} {{$data[$i]['customer']['LastName']}}" data-id="{{encval($data[$i]['SchedulerID'])}}" class="deleteOrder" id={{$data[$i]['SchedulerID']}}><i class="icon-item fas fa-trash-alt" role="button" data-prefix="far" data-id="trash-alt" data-unicode="f2ed" data-mdb-original-title="" title=""></i></a>
                    </td>
                    <td>
                      <fieldset class="form-check">
                      <input class="form-check-input filled-in" type="checkbox" id="checkbox{{$i}}"  value="{{$data[$i]['SchedulerID']}}" name="SelectedOrder[]">
                      <label class="form-check-label" for="checkbox{{$i}}"></label>
                      </fieldset>
                    </td>
                  </tr> 
                  @endfor
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
<script src="{{asset('js/Orders/Orders.js')}}"></script>

<script type="text/javascript">
  $(".form-check-input").click(function() {
    if($(".form-check-input:checked").length > 0 )
       $('#batchthis').css('visibility','visible');
     else
       $('#batchthis').css('visibility','hidden');
    // $('#batchthis').toggle( $(".form-check-input:checked").length > 0 );
  });

$(document).ready(function () {
  $('body').on('click', '#selectAll', function () {
    if ($(this).hasClass('allChecked')) {
        $('input[type="checkbox"]', '#orders_table').prop('checked', false);
    } else {
        $('input[type="checkbox"]', '#orders_table').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
    // $('#batchthis').toggle( $(".form-check-input:checked").length > 0 );
    if($(".form-check-input:checked").length > 0 )
       $('#batchthis').css('visibility','visible');
     else
       $('#batchthis').css('visibility','hidden');
  })
});

</script>