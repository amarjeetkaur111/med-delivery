
<div id="slide-out" class="side-nav sn-bg-4 fixed" style="display: none;">
    <ul class="custom-scrollbar">
        <li>
            <a class="float-right pr-5 font-weight-400" href="javascript:void(0)" class="closebtn" onclick="closeNav()" style="font-size: 3rem; color: white">&times;</a>
        </li>
        <!-- Logo  -->
        <li class="logo-sn waves-effect py-3">
            <div class="text-center">
                <a href="#" class="pl-0"></a>
            </div>
        </li>
        <li class="text-center">
            <a href="/orders/add_customer" class="newordera"><button class="btn blue-gradient btn-lg neworderbtn">New Order</button></a>
        </li>
        <li>
            <!-- <form class="search-form" role="search">
              <div class="md-form mt-0 waves-light">
                <input type="date" class="form-control">
              </div>
            </form> -->
        </li>
        <li class="flex-class ml-1">
            <a href="{{route('visits')}}"><button class="btn btn-outline-white btn-sm waves-effect waves-light" id="orders_page"><i class="fa fa-th-large fa-lg"></i></button></a>
            <a href="{{route('map')}}"><button class="btn btn-outline-white btn-sm waves-effect waves-light" id="map_page"><i class="icon-item fas fa-map-marker-alt fa-lg"></i></button></a>
            <a href="{{route('BatchedOrders')}}" ><button class="btn btn-outline-white btn-sm waves-effect waves-light" id="">7</button></a>
        </li>

        <!-- Side navigation links  -->
        <li>
            <ul class="collapsible collapsible-accordion">
                <li>
                    <a class="collapsible-header waves-effect arrow-r" href="{{route('BatchedOrders')}}">
                        <i class="icon-item fas fa-truck" role="button" data-prefix="fas" data-id="truck" data-unicode="f0d1" data-mdb-original-title="" title=""></i>Batching <span class="badge blue" id="batch-count"> </span>
                    </a>
                </li>
                <li>
                    <a class="collapsible-header waves-effect arrow-r active" href="javascript:void(0)">
                        <i class="icon-item fas fa-home" role="button" data-prefix="fas" data-id="home" data-unicode="f015" data-mdb-original-title="" title=""></i>Order Status<i class="fas fa-angle-down rotate-icon"></i>
                    </a>
                    <div class="collapsible-body" style="display:block !important">
                        <form class="search-form" role="search" method="post" action="{{route('visitpost')}}">
                            @csrf
                            <div class="row statuslist mt-2">
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="1">New <span class="countspan" id="pending-count"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="2">Deliver <span class="countspan" id="complete-count"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="3">Skip <span class="countspan" id="skip-count"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="4">Cancel <span class="countspan" id="cancel-count"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="5">Postpone <span class="countspan" id="postpone-count"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="6">Return <span class="countspan" id="return-count"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary waves-effect waves-light statusbtn" style="width:7rem">
                                            <input type="checkbox" class="R_days" name="status[]" id= "statuslist" value="7">Undelivered <span class="countspan" id="undelivered-count"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-10 offset-1">
                                    <input type="text" id="sidebar_customer_autocomplete" class="typeahead form-control pl-0" name="CustomerName" value="" style="height:35px;margin-top:-5px;" placeholder="Customer Name"/>
                                    <input type="hidden" name="CustomerId" id="sidebar_CustomerId" value="" />
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-10 offset-1 text-center">
                                    <input type="submit" name="" value="submit" class="btn blue-gradient neworderbtn">
                                </div>
                            </div>
                            <!-- </form>           -->
                    </div>
                </li>

                <!-- Search Form -->
            <!-- <form class="search-form" role="search" method="post" action="{{route('visitpost')}}">
       @csrf
                <li>
                        <div class="md-form mt-0 waves-light">
                      <input type="date" class="form-control">

                                <input type="text" id="sidebar_customer_autocomplete" class="typeahead form-control" name="CustomerName" value="" style="height:35px;margin-top:-5px;" placeholder="Search Customer Name"/>
                                <input type="hidden" name="sidebar_CustomerId" id="sidebar_CustomerId" value="" />
                        </div>
                </li> -->
                </form>
            </ul>
        </li>
        <!-- Side navigation links  -->

    </ul>
    <div class="sidenav-bg mask-strong"></div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js">
</script>
<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.min.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
<script>
    src = "{{url('/reports/customer_info')}}";
    $("#sidebar_customer_autocomplete").autocomplete({
        select: function (event, ui) {//trigger when you click on the autocomplete item
            // event.preventDefault();//you can prevent the default event
            // alert( ui.item.id);//employee id
            $('#sidebar_CustomerId').val(ui.item.id);
            $('#sidebar_customer_autocomplete').val(ui.item.value);

        },
        source: function(request, response) {
            $.ajax({
                url: src,
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);

                }
            });
        },
        minLength: 1,
    });


</script>
