<style>
.pagination{
	visibility: visible !important;
}
#reports_table .custom-control-label::before{top:8px;}
#reports_table .custom-control-label::after{top:0.5rem;}
</style>
@include('Includes.Header')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<style type="text/css">
th {
border: 2px solid;
font-size:16px;
font-weight:bold;
line-height: 35px;
text-align:center;
/* min-width: 250px; */
padding-top:0.5rem !important; padding-bottom: 0.5rem !important;
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
              <form method="post" action="{{route('ShowReport')}}" id="report">
              	@csrf
				<?php
				$vs = null;
				$driverid = null;
				$cn = $cid = null;
				$noi = null;
				if(isset($result)){							 
					$StartDate = date("m/d/Y", strtotime($result['StartDate']));
					$EndDate = date("m/d/Y", strtotime($result['EndDate']));
					$dates = $StartDate . " - " . $EndDate;
					$vs = $result['VisitStatus'] ? $result['VisitStatus']:null;
					$driverid = $result['DriverID'] ? $result['DriverID'] : null;
					$cn = $result['CustomerName'] ? $result['CustomerName'] : null;
					$cid = $result['CustomerId'] ? $result['CustomerId'] : null;
					$noi = $result['ItemNumber'];
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
								<input type="hidden" name="download" id="download" value="" />						
							</div>
							<div class="col-md-2 col-sm-2" >
								<p>Visit Status</p>
								<select class="form-control form-control-sm form_inline10" id="fc_op1" name="VisitStatus">
									<option value="">Select Visit Status</option>	
										<option value="1" {{$vs == 1 ? "selected":" "}}>New Order</option>
										<option value="2" {{$vs == 2 ? "selected":" "}}>Delivered</option>
										<option value="3" {{$vs == 3 ? "selected":" "}}>Skipped</option>
										<option value="4" {{$vs == 4 ? "selected":" "}}>Cancelled</option>
										<option value="5" {{$vs == 5 ? "selected":" "}}>Postponed</option>
										<option value="6" {{$vs == 6 ? "selected":" "}}>Returned</option>
										<option value="7" {{$vs == 7 ? "selected":" "}}>Undelivered</option>
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
								<button type="submit" class="mt-4 btn btn-outline-primary waves-effect" id="exportresults" role="button" name="report" value="download" onClick="ChangeTarget('new')">Download Results</button>
								<button type="submit" class="mt-4 btn btn-outline-primary waves-effect" id="viewresults" role="button" name="report" value="view" onClick="ChangeTarget('same')">View Results</button>
							</div>
						</div>					
					</div>
				</div>
				<div class="row">
					<div class="col-md-8 float-left"><h4>Reports</h4></div>
				</div>
				<hr>
				<div class="table-responsive">	
				    <table id="reports_table" class="table table-striped" cellspacing="0" width="100%">
				       	<div class="row">
							<div class="col-sm-12 col-md-12">
								<div class="dataTables_length" id="report_table_length">
									<label >Show 
				  						<select name="report_table_length" aria-controls="orders_table" class="custom-select custom-select-sm form-control form-control-sm" style="width:auto">
											<option value="5" {{$noi == 5 ? "selected":" "}}>5</option>
											<option value="10" {{$noi == 10 ? "selected":" "}}>10</option>
											<option value="25" {{$noi == 25 ? "selected":" "}}>25</option>
											<option value="50" {{$noi == 50 ? "selected":" "}}>50</option>
											<option value="100" {{$noi == 100 ? "selected":" "}}>100</option>
										</select> entries
									</label>
								</div>
							</div>
						</div>					
				       	@if(isset($paginator))
						   <!-----------------Patient checkboxes select and deselect- Amarjeet------------->
							<fieldset class="form-check">
								<input class="form-check-input filled-in allChecked" type="checkbox" id="selectAll" checked>
								<label class="form-check-label" for="selectAll">All</label>
							</fieldset>
							<!-----------------Patient checkboxes select and deselect- End------------->
	                   @foreach($paginator as $key => $customerinfo)
	                <thead style="color:#ffffff;background:#4285f4;">
	                  <tr>
						<th></th>
						<th style="min-width:200px;">Customer Info</th>
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
						<th>
						<input class="form-check-input filled-in PatientIds" type="checkbox" id="{{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}" name="Patients[]" checked="checked" value="{{$customerinfo['CustomerId']}}">
						<label class="form-check-label" for="{{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}" style="padding-left: 20px;margin-top: 5px;"></label>		
						</th>
					  	<th scope="row">
						  {{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}
						</th>
	                    <!-- <td>{{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}</td> -->
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
											@if($visit['VisitStatusID'] == 1) {{'New Order'}}
											@elseif($visit['VisitStatusID'] == 2) {{'Delivered'}}
											@elseif($visit['VisitStatusID'] == 3) {{'Skipped'}}
											@elseif($visit['VisitStatusID'] == 4) {{'Cancelled'}}
											@elseif($visit['VisitStatusID'] == 5) {{'Postponed'}}
											@elseif($visit['VisitStatusID'] == 6) {{'Returned'}}
											@else {{'Undelivered'}}
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
											Sign:@if(isset($signpath[1])) <img src="{{ URL::asset('/reports/signimage/'.$signpath[1]) }}" alt="Sign Image" style="height:8rem; width: 8rem;"> @else {{' '}} @endif
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
	             </table>
	              {{-- Pagination --}}
	              @if(isset($paginator))
					<div class="d-flex justify-content-center">
						{!! $paginator->appends(request()->input())->links('Pagination.Pagination') !!}
					</div>
					@endif
	            </div>
            </div>
          </div>
        </div>
		</form>

        <!-- Gird column -->
        <!-- Gird column -->
        <!-- Gird column -->
      </section>
      <!-- Section: Basic examples -->
    </div>
  </main>
  <!-- Main layout -->
  <!-----------------------Prompt modal for user to download PDF Report------------------------->
  <div class="modal fade" id="ActivityModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document" style="z-index: 1041;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalLabel">User Verification</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="UserVerifyReport">
              		@csrf
						<div class="row">
							<!-- Grid column -->
							<div class="col-md-10 offset-1">
								<div class="row">
									<div class="col-md-10 offset-1">
										<input type="text" class="form-control" placeholder="Employee Initials" id="EmployeeName" name="EmployeeName" required>
										<p id="employeename_error" style="color:red;display:none;">This field is required</p>
									</div>
								</div>
								<div class="row" style="margin-top:15px;">
									<div class="col-md-10 offset-1">
										<input type="text" class="form-control" placeholder="Comment" id="Notes" name="Notes">
									</div>
								</div>
								<div class="row mt-3">
									<div class="col-md-12 text-center">
										<button type="button" class="btn btn-primary waves-effect waves-light" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-primary waves-effect waves-light" id="AccessDownload">Save</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-----------------------Prompt modal for user to download PDF Report End--------------------->							
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

	$('body').on('click', '#selectAll', function () {
		var all = $('#reports_table').find('input[type="checkbox"]');
		$(this).toggleClass('allChecked');
		if ($(this).hasClass('allChecked')) {
		all.prop('checked', true);
		} else {        
		all.prop('checked', false);
		}
	});

	/*-----------------Prompt for downloading the Report---------------*/
	$("#exportresults").click(function(event) {
		event.preventDefault();
		if ($('.pagination').length) {   
			var all = document.querySelectorAll('#reports_table input:checked');
			if (all.length === 0) {
				toastr.warning("Select Atleast One Patient");
				return false;
			} 
		}
		$("#ActivityModal").modal('show');
	});
	/*---------------Insert Download PDF Activity-------------------------*/
	$("#AccessDownload").click(function(event) {
		event.preventDefault();
		var EmployeeName = $("#UserVerifyReport #EmployeeName").val();
		var ExportComment = $("#UserVerifyReport #Notes").val();
		src = "{{route('TrackDownload')}}";
		var token =  $('#UserVerifyReport input[name="_token"]').attr('value'); 
		if (EmployeeName != '') {
			$("#employeename_error").hide();
			$.ajax({
				url: src,
				type: "POST",
				data: {
					EmployeeName: EmployeeName,
					ExportComment: ExportComment
				},
				dataType: "JSON",
				headers: {
					'X-CSRF-TOKEN': token
				},
				success: function(data) {
					// alert(data.status);
					if (data.status == 1) {
						$("#ActivityModal").modal('hide');
						$("#report #download").val('download');
						$("#report").submit();
					}
				},
				complete: function(data) {
					$("#UserVerifyReport #EmployeeName").val('');
					$("#UserVerifyReport #Notes").val('');
					$("#report #download").val('');
				},
				error: function(xhr, ajaxOptions, thrownError) {
					swal("", "Please try again", "error");
				}
			});
		} else {
			$("#employeename_error").show();
		}
	});

	function ChangeTarget(loc) {
		if(loc=="new") {
		document.getElementById('report').target="_blank";
		} else {
		document.getElementById('report').target="";
		}
	}
</script>