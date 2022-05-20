  
    <!-- Navbar  -->
  <div class="row">
    <div class="col-lg-12 col-md-12" style="margin-top: 2rem;">        
     
    	<ul class="nav nav-tabs md-tabs nav-justified primary-color" style="margin:auto">
          <li class="nav-item " >
            <a class="nav-link nav-bar {{ Request::routeIs('visits') ? 'active' : '' }}" href="{{route('visits')}}" style="padding:0.7rem"><i class="icon-item fas fa-box fa-lg" role="button" data-prefix="fas" data-id="box" data-unicode="f466" data-mdb-original-title="" title=""></i> Today's Visits</a>
          </li>
          @if(Session::get('usertype') != "Driver")
          <li class="nav-item ">
            <a class="nav-link nav-bar {{ Request::routeIs('UnBatchedVisits') ? 'active' : '' }}" href="{{route('UnBatchedVisits')}}" style="padding:0.7rem"><i class="icon-item fas fa-layer-group fa-lg" role="button" data-prefix="fas" data-id="box" data-unicode="f466" data-mdb-original-title="" title=""></i> UnBatched Visits</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link nav-bar {{ Request::routeIs('BatchedOrders') ? 'active' : '' }}" href="{{route('BatchedOrders')}}" style="padding:0.7rem"><i class="icon-item fas fa-layer-group fa-lg" role="button" data-prefix="fas" data-id="box" data-unicode="f466" data-mdb-original-title="" title=""></i> Batched Orders</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-bar {{ Request::routeIs('DeliveredBatches') ? 'active' : '' }}" href="{{route('DeliveredBatches')}}" style="padding:0.7rem"><i class="icon-item fas fa-users fa-lg" role="button" data-prefix="fas" data-id="users" data-unicode="f0c0" data-mdb-original-title="" title=""></i> Assigned / Ready To Deliver</a>
          </li> 
          @endif 
      </ul>
      <!-- Navbar links  -->     
   </div>
  </div>


