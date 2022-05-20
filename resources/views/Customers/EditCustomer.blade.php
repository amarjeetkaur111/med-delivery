
@include('Includes.Header')

  <!-- Main Navigation -->
  <!-- Main layout -->
  <main>
  	<div class="edit-customer">
	<!-- Nav tabs -->
	<div class="tag-row row">
		<div class="col-lg-12 col-md-12 mb-lg-0 mt-1">
			<ul class="nav nav-tabs md-tabs nav-justified primary-color" id="myTab" role="tablist">
	      <li class="nav-item">
	        <a class="nav-link active" id="customerinfo-tab" data-toggle="tab" href="#customerinfo" role="tab" aria-controls="customerinfo" aria-selected="true">Customer Info</a>
	      </li>
	      <li class="nav-item">
	        <a class="nav-link" id="address-tab" data-toggle="tab" href="#address" role="tab" aria-controls="address" aria-selected="false">Address</a>
	      </li> 
	      <li class="nav-item">
	        <a class="nav-link" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="schedule" aria-selected="false">Schedules</a>
	      </li>     
	    </ul>
		</div>
	</div>    
   <!-- Nav tabs Ends -->
   <!----Customer Name ----->
   <div class="row">
   	<div class="col-lg-12 col-md-12 mb-lg-0 mt-4 mb-2 ">	
   	  <div id="sticky-anchor"></div>
   		<div id="sticky">
   			<div class="chip btn btn-primary lighten-4 waves-effect waves-effect mb-0 mt-n1">
   				<i class="fas fa-male"><span>{{old('FirstName',$data[0]['FirstName'])}} {{old('LastName', $data[0]['LastName'])}}</span></i>
   			</div>
   		</div>
   	</div>
  </div>

   <!------Customer Name Ends --------->
   <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="customerinfo" role="tabpanel" aria-labelledby="customerinfo-tab">
		    <div class="container-fluid">
		      <!-- Section: Cascading panels -->
		      <section class="mb-5">
		        <!-- Grid row -->
		        <div class="row">
		          <!-- Grid column -->
		          <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
		            <!-- Panel -->
		            <div class="card">
		              <div class="card-header white-text primary-color">
		                <i class="fas fa-user">  Edit Customer</i>
		              </div>
		          	  <div class="card-body edit-cardbody mb-3">
		          	  	<form method="post" action="{{route('UpdateCustomer')}}">
		          	  		@csrf	
		          	  		<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form ">
											<i class="fas fa-clinic-medical prefix" style="font-size:20px;margin-top: 10px;"></i>
											<input class="form-control" value="Select Pharmacy" disabled style="border: none;">
										</div>
									</div>												
									<div class="col-md-5">
										<div class="md-form md-outline form-control-sm">
											<select class="phonetype-select form-control" name="Pharmacy" style="margin-left: -8px;width: 104%;">
												<option value="">Select Pharmacy</option>
												@foreach($pharmacies as $pharmacy)
													<option value="{{$pharmacy['PharmacyId']}}" 
													@if($data[0]['pharmacy_customer'] != null)
													{{old('Pharmacy',$data[0]['pharmacy_customer']['pharmacy']['PharmacyId']) == $pharmacy['PharmacyId']?"selected":' '}}
													@endif
													>{{$pharmacy['PharmacyName']}}</option>	
												@endforeach														
											</select>
											@error('Pharmacy')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div> 
									</div>										
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<input type="hidden" id="f" class="form-control" name="CustomerId" value="{{$data[0]['CustomerId']}}">
										<div class="md-form md-outline">
											<i class="fas fa-user prefix"></i>
											<input type="text" id="f1" class="form-control" name="FirstName" autofocus value="{{old('FirstName',$data[0]['FirstName'])}}">
											<label for="f1" class="">First Name</label>
											@error('FirstName')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									
									<!-- Grid column -->
						
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="f2" class="form-control" name="LastName" autofocus value="{{old('LastName', $data[0]['LastName'])}}">
											<label for="f2" class="">Last Name</label>
											@error('LastName')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">

										<div class="md-form md-outline form-control-sm">
											<i class="fas fa-phone-alt prefix" style=""></i>
											<select class="phonetype-select form-control" name="Phone[0][PhoneTypeId]">
												<option value="">Select Phone Type</option>
												<option value="1" {{old('Phone.0.PhoneTypeId',$data[0]['PhoneNumbers'][0]->PhoneTypeId)=='1'?"selected":' '}}>Home</option>
												<option value="2" {{old('Phone.0.PhoneTypeId',$data[0]['PhoneNumbers'][0]->PhoneTypeId)=='2'?"selected":' '}}>Mobile</option>
												<option value="3" {{old('Phone.0.PhoneTypeId',$data[0]['PhoneNumbers'][0]->PhoneTypeId)=='3'?"selected":' '}}>Work</option>
												<option value="4" {{old('Phone.0.PhoneTypeId',$data[0]['PhoneNumbers'][0]->PhoneTypeId)=='4'?"selected":' '}}>Fax</option>
											</select>
											@error('Phone.0.PhoneTypeId')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									<!-- Grid column -->
						
									<!-- Grid column -->
									<div class="col-md-5">											
										<div class="md-form md-outline">												
											<input type="tel" id="f3" class="form-control" name="Phone[0][PhoneNumber]" autofocus value="{{old('Phone.0.PhoneNumber', $data[0]['PhoneNumbers'][0]->PhoneNumber)}}" maxlength="12" >
											<label for="f3" class="">Mobile No</label>
											@error('Phone.0.PhoneNumber')
												<span class="text-danger">{{$message}}</span>
											@enderror
											@if(Session::has('existing_phone'))
												<span class="text-danger">{{Session::get('existing_phone')}}</span>
											@endif	
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-10">
										<div class="new-phone md-form md-outline">
											<button type="button" class="btn btn-outline-primary waves-effect" name="AddNewNumber" onclick="add_multiple_phone();"><i class="icon-item fas fa-plus"> Add a new phone number</i></button>
										</div>
									</div>
								</div>
								<input type="hidden" id="phonecount" value="{{count($data[0]['PhoneNumbers'])}}"/>
								<!-----------------Edit Multiple Phone Number Div End----------------------->
									@php ($j = 0)
									@if(count($data[0]['PhoneNumbers'])>1)
									@for($i = 1; $i < count($data[0]['PhoneNumbers']);$i++)
									@php ($j++)
									<div id="multiple_phone_numbers_edit_section">
										<div class="alternative-phone-div" id="multiple_phone_<?= $i?>">

										<!-- <div id="multiple_phone_title"> -->
										<div class="row">
											<div class="col-md-5 col-sm-10">
												<div class="md-form md-outline form-control-sm">
													<i class="fas fa-phone-alt prefix"></i>
													<select class="phonetype-select form-control" name="Phone[<?= $i;?>][PhoneTypeId]">
														<option value="">Select Phone Type</option
														><option value="1" {{old('Phone.'.$i.'.PhoneTypeId', $data[0]['PhoneNumbers'][$i]->PhoneTypeId)=='1'?"selected":' '}}>Home</option>
														<option value="2" {{old('Phone.'.$i.'.PhoneTypeId', $data[0]['PhoneNumbers'][$i]->PhoneTypeId)=='2'?"selected":' '}}>Mobile</option>
														<option value="3" {{old('Phone.'.$i.'.PhoneTypeId', $data[0]['PhoneNumbers'][$i]->PhoneTypeId)=='3'?"selected":' '}}>Work</option>
														<option value="4" {{old('Phone.'.$i.'.PhoneTypeId', $data[0]['PhoneNumbers'][$i]->PhoneTypeId)=='4'?"selected":' '}}>Fax</option>
													</select>
													@error('Phone.'.$i.'.PhoneTypeId')
														<span class="text-danger">{{$message}}</span>
													@enderror
												</div>
											</div>
											<div class="col-md-5 col-sm-9">
												<div class="md-form md-outline">
													<input id="altnum1" type="tel" class="form-control" name="Phone[<?= $i;?>][PhoneNumber]" maxlength="12" autofocus="" value="{{old('Phone.'.$i.'.PhoneNumber',$data[0]['PhoneNumbers'][$i]->PhoneNumber)}}">
													<label for="altnum1" class="">Alternative Number <?= $i;?></label>
													@error('Phone.'.$i.'.PhoneNumber')
														<span class="text-danger">{{$message}}</span>
													@enderror
												</div>
											</div>
											<div class="col-md-1 col-sm-1">
												<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="delete_multiple_phone(<?= $i;?>);"><i class="fas fa-times"></i></button>
											</div>
										</div>		
										</div>
										</div>								
									@endfor
									@endif
									<!-----------------Edit Multiple Phone Number Div End----------------------->

									<!-----------------Multiple Phone Number Div Start----------------------->
															
									<div id="multiple_phone_numbers_section">
										<!-- <div class="row" id="multiple_phone_title" style="display:none;"> -->
											
										<!-- </div> -->
									</div>

									<!-----------------Multiple Phone Number Div End----------------------->
								
								<!-- <div class="row">
									Grid column
									<div class="col-md-10">
										<div class="md-form md-outline">
											<fieldset class="form-check mt-n3">
												<input class="form-check-input filled-in" type="checkbox" id="checkbox2" name="CourierNotification" value="">
												<label class="form-check-label" for="checkbox2">Send notification to the customer when a courier takes ownership</label>
											</fieldset>
										</div>
									</div>
									Grid column
								</div> -->
								<div class="row">
									<h5 class="pb-1"><br><i class="icon-item fas fa-address-card"> Delivery Address</i></h5>
									<input type="hidden" class="form-control" name="AddressId" autofocus value="{{$data[0]['customers_address'][0]['CustomerAddressId']}}">

								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<div id="locationField">
												<label for="locationField" class=''>Street Address</label>
												<input id="autocomplete1" name="AddressLine" class="form-control" value="{{old('AddressLine', $data[0]['customers_address'][0]['AddressLine'])}}" type="text" autocomplete="off" onfocus="initAutocomplete(1);" />	
										<input type="hidden" name="" id="street_number1" value="">
										<input type="hidden" name="" id="route1" value="" >
										<input type="hidden" name="Latitude" id="Latitude1" value="{{old('Latitude', $data[0]['customers_address'][0]['Latitude'])}}" >
										<input type="hidden" name="Longitude" id="Longitude1" value="{{old('Longitude', $data[0]['customers_address'][0]['Longitude'])}}">
									</div> 												
											@error('AddressLine')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									<!-- Grid column -->
						
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="f5" class="form-control" name="UnitNumber" autofocus value="{{old('UnitNumber', $data[0]['customers_address'][0]['UnitNumber'])}}">
											<label for="f5" class="">Unit Number</label>
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="locality1" class="form-control" name="City" autofocus value="{{old('City', $data[0]['customers_address'][0]['City'])}}">
											<label for="locality" class="">City</label>
											@error('City')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									<!-- Grid column -->					
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="administrative_area_level_11" class="form-control" name="Province" autofocus value="{{old('Province', $data[0]['customers_address'][0]['Province'])}}">
											<label for="administrative_area_level_1" class="">Province</label>
											@error('Province')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="postal_code1" class="form-control" name="PostalCode" autofocus value="{{old('PostalCode', $data[0]['customers_address'][0]['PostalCode'])}}">
											<label for="postal_code" class="">Postal Code</label>
												@error('PostalCode')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									<!-- Grid column -->	
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="country1" class="form-control" name="Country" autofocus value="{{old('Country', $data[0]['customers_address'][0]['Country'])}}">
											<label for="country" class="">Country</label>
											@error('Country')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="address-phone md-form md-outline">
											<select class="form-control form-control-sm" name="PhoneTypeId">
													<option value="">Select Phone Type</option>
													<option value="1" {{old('PhoneTypeId', $data[0]['customers_address'][0]['PhoneTypeId']) == 1 ? "selected" :' '}} >Home</option>
													<option value="2" {{old('PhoneTypeId', $data[0]['customers_address'][0]['PhoneTypeId']) == 2 ? "selected" :' '}} >Mobile</option>
													<option value="3" {{old('PhoneTypeId', $data[0]['customers_address'][0]['PhoneTypeId']) == 3 ? "selected" :' '}} >Work</option>
													<option value="4" {{old('PhoneTypeId', $data[0]['customers_address'][0]['PhoneTypeId']) == 4 ? "selected" :' '}} >Fax</option>
											</select>
											@error('PhoneTypeId')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									<!-- Grid column -->
						
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="number" id="f11" class="form-control" name="Extension" autofocus value="{{old('Extension',$data[0]['customers_address'][0]['Extension'])}}">
											<label for="f11" class="">Extension</label>
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="tel" id="f10" class="form-control" name="PhoneNumber" autofocus value="{{old('PhoneNumber', $data[0]['customers_address'][0]['PhoneNumber'])}}" maxlength="12">
											<label for="f10" class="">Phone Number</label>
											@error('PhoneNumber')
												<span class="text-danger">{{$message}}</span>
											@enderror												
										</div>
									</div>
									<!-- Grid column -->
						
									<!-- Grid column -->
									<div class="col-md-5">
										<div class="md-form md-outline">
											<input type="text" id="f12" class="form-control" name="AdditionalInfo" autofocus value="{{old('AdditionalInfo', $data[0]['customers_address'][0]['AdditionalInfo'])}}">
											<label for="f12" class="">Additional Information</label>
										</div>
									</div>
								</div>
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-10">
										<div class="md-form md-outline">
											<textarea type="text" id="form10" class="md-textarea form-control" name="DoorSecurityCode" rows="3" placeholder="Notes">{{old('DoorSecurityCode', $data[0]['customers_address'][0]['DoorSecurityCode'])}}</textarea>
											@error('DoorSecurityCode')
												<span class="text-danger">{{$message}}</span>
											@enderror
										</div>
									</div>
									<!-- Grid column -->					
									<!-- Grid column -->
								</div>
								
								<div class="row">
									<!-- Grid column -->
									<div class="col-md-10">
										<div class="text-center">
											<a href="/customers"  class="btn btn-primary waves-effect waves-light">Cancel</a>
											<input type="submit" class="btn btn-primary waves-effect waves-light" value="Update">
										</div>
									</div>
								</div>
							</form>
			            </div>
			          </div>
			          <!-- Panel -->
			        </div>
			          <!-- Grid column -->
			      </div>
			        <!-- Grid row -->
			    </section>
			      <!--Section: Cascading panels-->
			 	</div>
			</div>
      <div class="tab-pane" id="address" role="tabpanel" aria-labelledby="address-tab">
      	@include('Customers.Tabs.Address')      	
      </div>
      <div class="tab-pane" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
      	@include('Customers.Tabs.Schedule') 
      </div>
		</div>
    <!-- Tab Panes Ends -->
 		</div>
	</main>
	
@include('Includes.Footer')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEggTYQKQ_7Qg0CJQvuPwb0lmOXI05wjE&libraries=places" async defer></script>
<script src="{{asset('js/Customer/EditCustomer.js')}}"></script>

 @if(session()->has('editcustomer'))  
 	<script>
 		$.noConflict();
 	 $(function(){
 		 $('#mytabs a[href="#address"]').tab('show'); 		
  	// $("#edit-customer .tab-content #address").addClass("active");
 		 // $('#address-tab').addClass('active');
 		});
 	</script>    
 @endif
