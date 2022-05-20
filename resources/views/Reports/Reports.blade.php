@include('Includes.Header')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<style type="text/css">
th {
border: 2px solid;
font-size:16px;
font-weight:bold;
line-height: 35px;
text-align:center;
min-width: 250px;
}
td {
border: 1px solid;
min-width: 250px;
}
.pagination{
	visibility: hidden;
}
</style>
 <!--  <link rel="stylesheet" type="text/css" href="http://localhost/medication_delivery/css/bootstrap-datetimepicker.min.css"> -->
<link href="{{ asset('css/datepicker/bootstrap-datepicker.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <!-- Main Navigation  --> 
  <!-- Main layout  -->
  <main class="reports">
    <div class="container-fluid mb-5 cont-margin">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <div class="card">		  
            <div class="card-body">
              <form method="post" action="{{route('ShowReport')}}">
              	@csrf
				      <?php
				      $vs = null;
				      $driverid = null;
				      $cn = $cid = null;
				      if(isset($result)){							 
							 $StartDate = date("m/d/Y", strtotime($result['StartDate']));
							 $EndDate = date("m/d/Y", strtotime($result['EndDate']));
							 $dates = $StartDate . " - " . $EndDate;
							 $vs = $result['VisitStatus'] ? $result['VisitStatus']:null;
							 $driverid = $result['DriverID'] ? $result['DriverID'] : null;
							 $cn = $result['CustomerName'] ? $result['CustomerName'] : null;
							 $cid = $result['CustomerId'] ? $result['CustomerId'] : null;
						 }else{							 
							 $StartDate = date("m/d/Y"); 
							 $EndDate = date("m/d/Y");
							 $dates = $StartDate . " - " . $EndDate;
						 }
						 ?>
							<div class="row mt-5">   	
								<div class="col-md-12 col-sm-12" >
									<div class="row">					
										<div class="pharmacy-select col-md-3 col-sm-3" >
											<p>Pharmacy</p>
											<select class="form-control form-control-sm form_inline10" name="PharmacyID" id="Pharmacy" >
												<option value="">Select Pharmacy</option>	
											</select>						
										</div>				
										<div class="col-md-2 col-sm-2" >
											<p>Driver</p>
											<select class="form-control form-control-sm form_inline10" id="Driver" name="DriverID" value={{$driverid}}>
												<option value="">Select Driver</option>
											</select>
					         	</div>					 	 
									<div class="customer-name col-md-3 col-sm-3" >
										<p>Customer Name</p>
										
										<input type="text" id="customer_autocomplete" class="typeahead form-control" name="CustomerName" value="{{$cn}}" style="height:35px;margin-top:-5px;" placeholder="Search Customer Name"/>
										<input type="hidden" name="CustomerId" id="CustomerId" value="{{$cid}}" />
										<div id="countryList">
    								</div>
										
									</div>
									<div class="col-md-2 col-sm-2" >
										<p>Visit Status</p>
										<select class="form-control form-control-sm form_inline10" id="fc_op1" name="VisitStatus">
											<option value="">Select Visit Status</option>	
												<option value="1" {{$vs == 1 ? "selected":" "}}>Incomplete</option>
												<option value="2" {{$vs == 2 ? "selected":" "}}>Completed</option>
												<option value="3" {{$vs == 3 ? "selected":" "}}>Skipped</option>
												<option value="4" {{$vs == 4 ? "selected":" "}}>Cancelled</option>
												<option value="5" {{$vs == 5 ? "selected":" "}}>Postponed</option>
												<option value="6" {{$vs == 6 ? "selected":" "}}>Returned</option>
										</select>
									</div>
									<div class="col-md-2 col-sm-2 pl-2">
										<p>Date Range</p>
										<div class="item">
										<input name="dates" id="dates" type="text" value="{{$dates}}" class="font-weight-500" />
											<input name="StartDate" id="startdate" type="hidden" value="{{$StartDate}}" />
											<input name="EndDate" id="enddate" type="hidden"  value="{{$EndDate}}" />  
				           	</div>
				          </div>	
				        	</div>
									<div class="row">
									<div class="col-md-6 col-sm-6">
										
									</div>
									<div class="col-md-6 col-sm-6 text-right">
							      <button type="submit" class="mt-4 btn btn-outline-primary waves-effect" id="exportresults" role="button" name="report" value="download">Download Results</button>
							      <button type="submit" class="mt-4 btn btn-outline-primary waves-effect" id="viewresults" role="button" name="report" value="view">View Results</button>
									</div>
									</div>					
								</div>
							</div>
							</form>
							<div class="row">
								<div class="col-md-8 float-left"><h4>Reports</h4></div>
							</div>
							<hr>
							<div class="table-responsive">	
				       <table id="reports_table" class="table table-striped" cellspacing="0" width="100%">
				       	@if(isset($result))
	                <thead style="color:#ffffff;background:#4285f4;">
	                  <tr>
											<th>Customer Info</th>
	                   <?php 
												$begin = new DateTime($result['StartDate']);
												$end = new DateTime($result['EndDate']);
												$end->setTime(0,0,1);    // To include last date

										
												$interval = DateInterval::createFromDateString('1 day');
												$period = new DatePeriod($begin, $interval, $end);
												foreach ($period as $dt) {
													echo "<th>".$dt->format("m-d-Y")."</th>";
												}
												// dd($totalrows);

										?>	                    
	                  </tr>
	                </thead>
	                <tbody>
	                	@foreach($result['visits'] as $customerinfo)
	                  <tr>
	                    <td>{{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}</td>
	                    @foreach($period as $dt)
	                    	@if($customerinfo['customer_visit'] == null)
	                    			<td>{{'No Visit Assigned'}}</td>
	                    	@else
		                    <td>
		                    	@php $nomatch = 0; @endphp
		                    	@php $nextrow = 0 ; @endphp

		                    	@foreach($customerinfo['customer_visit']  as $visit)
		                    		@if($visit['VisitDate'] == $dt->format("Y-m-d"))
			                    		@if($nextrow > 0)
																<hr style="background : #4285f4">
															@endif
		                    			{{'Visit Date: '.date('m-d-Y',strtotime($visit['VisitDate']))}}<br>
		                    			{{'Customer Visit ID: '.$visit['CustomerVisitID']}}<br>
		                    			{{'Visit Status: '}}
															 @if($visit['VisitStatusID'] == 1) {{'Pending'}}
															 @elseif($visit['VisitStatusID'] == 2) {{'Completed'}}
															 @elseif($visit['VisitStatusID'] == 3) {{'Skipped'}}
															 @elseif($visit['VisitStatusID'] == 4) {{'Cancelled'}}
															 @elseif($visit['VisitStatusID'] == 5) {{'Postponed'}}
															 @else {{'Returned'}}
															 @endif<br>
															 {{'Start Time: '.date('h:ma',strtotime($visit['ArrivalLogTime']))}}<br>
															 {{'End Time: '.date('h:ma',strtotime($visit['FinishLogTime']))}}<br>
															 @foreach($visit['status'] as $good)
															 <span class="btn-primary mb-1 pl-1 pr-1 mr-2" style="border-radius:10px;font-size: smaller;padding: 2px">{{$good['goods']['GoodsName']}}</span>  
															 <span class="mb-1 pl-1 pr-1" style="background-color: yellow;border-radius:10px;font-size: smaller;padding: 2px;color: black">{{$good['GoodsAmt']}}</span><br>
															 @endforeach	
															 @if($visit['VisitStatusID'] != 1 && $visit['VisitStatusID'] != 5)														 
																 	{{'PackageScanStatus: '.$visit['PackageScanStatus']}}<br>
																 	{{'DeliveryNotes: '.$visit['DeliveryNotes']}}<br>
																 	{{'Comments: '.$visit['DeliveryComment']}}<br>
																 	{{'RecipientName: '.$visit['RecipientName']}}<br>
																 	{{'RecievedBy: '.$visit['ReceivedBy']}}<br>
																 @endif		
															 @php $nextrow++; @endphp
		                    		@else
		                    			@php $nomatch++; @endphp 
		                    		@endif
		                    	@endforeach
		                    	@if($nomatch == count($customerinfo['customer_visit']))
		                    		{{'No Visit Assigned'}}
		                    	@endif
		                    </td>
	                    	@endif
	                    @endforeach
	                  </tr>
	                 @endforeach
	                </tbody> 
	              @endif               
	             </table>
	            </div>
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
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="{{asset('js/Reports/Reports.js')}}"></script>
	<!-- <script src="{{asset('js/bootstrap-typeahead.js')}}"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"> 
	</script>	
<link href="https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.min.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.10.2/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>

<script type="text/javascript">
  driverid = <?php if(isset($result['DriverID'])) {echo $result['DriverID'];} else echo 0; ?>;
  pharmacyid = <?php if(isset($result['PharmacyID'])) {echo $result['PharmacyID'];} else echo 0; ?>;
</script>
<script>
	src = "{{url('/reports/customer_info')}}";
    $("#customer_autocomplete").autocomplete({
    	select: function (event, ui) {//trigger when you click on the autocomplete item
            event.preventDefault();//you can prevent the default event
            // alert( ui.item.id);//employee id
            $('#CustomerId').val(ui.item.id);
            $('#customer_autocomplete').val(ui.item.value);

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


@if(isset($result))
	              @foreach($result['visits'] as $customerinfo)
	                <thead style="color:#ffffff;background:#4285f4;">
	                  <tr>
											<th>Customer Info</th>
	                   <?php 
												$begin = new DateTime($result['StartDate']);
												$end = new DateTime($result['EndDate']);
												$end->setTime(0,0,1);    // To include last date

										
												$interval = DateInterval::createFromDateString('1 day');
												$period = new DatePeriod($begin, $interval, $end);
												foreach ($period as $dt) 
												{
	                    		if($customerinfo['customer_visit'])
	                    		{
	                    			$nomatch = 0;
	                    			foreach($customerinfo['customer_visit']  as $visit)
	                    			{
		                    			if($visit['VisitDate'] != $dt->format("Y-m-d"))
		                    			{
		                    				$nomatch++;
															}
														}
														if($nomatch != count($customerinfo['customer_visit']))
																echo "<th>".$dt->format("m-d-Y")."</th>";
													}
												}
												// dd($totalrows);

										?>	                    
	                  </tr>
	                </thead>
	                <tbody>
	                  <tr>
	                    <td>{{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}</td>
	                    @foreach($period as $dt)
	                    	@if($customerinfo['customer_visit'])
		                    
		                    	@php $nomatch = 0; @endphp
		                    	@php $nextrow = 0 ; @endphp

		                    	@foreach($customerinfo['customer_visit']  as $visit)
		                    		@if($visit['VisitDate'] != $dt->format("Y-m-d"))
		                    			@php $nomatch++; @endphp 
		                    		@endif
		                    	@endforeach

		                    	@if($nomatch < count($customerinfo['customer_visit']))
		                    	<td>
		                    		@foreach($customerinfo['customer_visit']  as $visit)
		                    			@if($visit['VisitDate'] == $dt->format("Y-m-d"))
				                    		@if($nextrow > 0)
																	<hr style="background : #4285f4">
																@endif
			                    			{{'Visit Date: '.date('m-d-Y',strtotime($visit['VisitDate']))}}<br>
			                    			{{'Customer Visit ID: '.$visit['CustomerVisitID']}}<br>
			                    			{{'Visit Status: '}}
																 @if($visit['VisitStatusID'] == 1) {{'Pending'}}
																 @elseif($visit['VisitStatusID'] == 2) {{'Completed'}}
																 @elseif($visit['VisitStatusID'] == 3) {{'Skipped'}}
																 @elseif($visit['VisitStatusID'] == 4) {{'Cancelled'}}
																 @elseif($visit['VisitStatusID'] == 5) {{'Postponed'}}
																 @else {{'Returned'}}
																 @endif<br>
																 {{'Start Time: '.date('h:ma',strtotime($visit['ArrivalLogTime']))}}<br>
																 {{'End Time: '.date('h:ma',strtotime($visit['FinishLogTime']))}}<br>
																 @foreach($visit['status'] as $good)
																 <span class="btn-primary mb-1 pl-1 pr-1 mr-2" style="border-radius:10px;font-size: smaller;padding: 2px">{{$good['goods']['GoodsName']}}</span>  
																 <span class="mb-1 pl-1 pr-1" style="background-color: yellow;border-radius:10px;font-size: smaller;padding: 2px;color: black">{{$good['GoodsAmt']}}</span><br>
																 @endforeach	
																 @if($visit['VisitStatusID'] != 1 && $visit['VisitStatusID'] != 5)														 
																 	{{'PackageScanStatus: '.$visit['PackageScanStatus']}}<br>
																 	{{'DeliveryNotes: '.$visit['DeliveryNotes']}}<br>
																 	{{'Comments: '.$visit['DeliveryComment']}}<br>
																 	{{'RecipientName: '.$visit['RecipientName']}}<br>
																 	{{'RecievedBy: '.$visit['ReceivedBy']}}<br>
																 	@php $signpath = explode('/',$visit['SignPath']);@endphp
																 	Sign:@if(isset($signpath[1])) <img src="{{ URL::asset('/reports/signimage/'.$signpath[1]) }}" style="height:8rem; width: 8rem;"> @else {{' '}} @endif
																 @endif														 
															 	 @php $nextrow++; @endphp
		                    			@endif
		                    		@endforeach
		                    	</td>
		                    	@endif
	                    	@endif
	                    @endforeach
	                  </tr>
	                 @endforeach
	                </tbody> 
	              @endif  