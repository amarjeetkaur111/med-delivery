@include('Includes.Header')
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
 .tooltip-inner {
    max-width: 600px !important;
    text-align:left;
    font-size:12px;
  }
  .outerschedulediv{
    margin-bottom:10px;
  }
  .schedulesdiv{
    margin-top:5px;
    margin-bottom:5px;
  }
  .schedulerow{
    border-top:1px solid #FFFFFF;
    margin-top:10px;
  }
</style>
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
                      <input type="hidden" id="visit_id" value="{{$id}}">
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
   $(".orderdetail_tooltip").tooltip(
        {
            // classes: {"ui-tooltip-content": "toolTipDetails"},
            // tooltipClass: "toolTipDetails",
            placement: "bottom",
            html:true,
            //trigger:"manual"
        }).on({
                mouseenter:function(){
                var id = this.id;
                //varhtml_data="";
                var $el=$(this);
                $.ajax(
                {
                    url:"/orders/schedule_details",
                    type:'post',
                    async:false,
                    dataType:"html",
                    data:{id:id},
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(response){
                        console.log(response);
                        $el.attr("data-original-title",response);
                       // if($("#"+$el.attr("aria-describedby")).is(":visible")){
                            $el.tooltip("show");
                        //}
                    }
                });
            //returnhtml_data;
             },mouseleave:function(){
                    $(this).tooltip("hide");
                }
            });	
</script>

<script src="{{asset('js/Orders/NewOrders.js')}}"></script>
