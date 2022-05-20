@include('Includes.Header')	
  <!-- Main Navigation -->
  <!-- Main layout -->
  <main>
    <div class="add-customer container-fluid">
      <!-- Section: Cascading panels -->
      <section class="mb-5">
        <!-- Grid row -->
        <div class="row">
          <!-- Grid column -->
          <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
            <!-- Panel -->
            <div class="card">
              <div class="card-header white-text primary-color">
                <i class="fas fa-user">  Create Order - Select Customer</i>
              </div>
          	  <div class="card-body mb-3">          	  	
									<div class="row">
											<fieldset class="form-check mb-4">
												<input class="form-check-input" name="group1" type="radio" id="radio1" value="1" checked="checked">
												<label class="form-check-label" for="radio1">New Customer</label>
											</fieldset>
											<fieldset class="form-check mb-4">
												<input class="form-check-input" name="group1" type="radio" id="radio2" value="2">
												<label class="form-check-label" for="radio2">Existing Customer</label>
											</fieldset>				
									</div>
									<div class="row" id="new_customer_div">
										<div class="col-md-5">
												<a href="/customers/add_customer" class="btn btn-primary waves-effect waves-light">Create Customer</a>
										</div>
									</div>
									<div class="row" id="existing_customer_div">
										<div class="col-md-5 ml-4">
												<select class="form-control mdb-select md-form" searchable="Search here.." name="existing_customer" id="existing_customer">
												<option value="" disabled selected>Choose Customer</option>
												@foreach($ids as $id)													
													<option value="{{encval($id['CustomerId'])}}">{{$id['FirstName']}} {{$id['LastName']}}</option>		
												@endforeach
												</select>
										</div>
									</div>
									<hr>
									<div class="customer-details" id="customer-details" style="display:none;">										
										<form name="customerForm" method="post" action="{{route('UpdateCustomer')}}">
		          	  		@csrf		          	  		
										<input type="hidden" id="f" class="form-control" name="CustomerId" value="{{isset($data)?$data->CustomerId:''}}">
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">														
														<div class="md-form md-outline">
															<i class="fas fa-user prefix"></i>
															<input type="text" id="f1" class="form-control" name="FirstName" autofocus value="{{isset($data)?$data->FirstName:''}}" readonly>
															<label id="forF1" for="f1" class="">First Name</label>
														</div>
													</div>
													
													<!-- Grid column -->
										
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f2" class="form-control" name="LastName" autofocus value="{{isset($data)?$data->LastName:''}}" readonly>
															<label for="f2" class="">Last Name</label>
														</div>
													</div>
												</div>
													
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">

														<div class="md-form md-outline form-control-sm">
															<i class="fas fa-phone-alt prefix" style=""></i>
															<select class="phonetype-select form-control" id="PrimaryPhone" name="Phone[0][PhoneTypeId]" disabled>
																<option value="">Select Phone Type</option>
																<option value="1" {{isset($data)?($data->PhoneNumbers[0]->PhoneTypeId)=='1'?"selected":' ':' '}}>Home</option>
																<option value="2" {{isset($data)?($data->PhoneNumbers[0]->PhoneTypeId)=='2'?"selected":' ':' '}}>Mobile</option>
																<option value="3" {{isset($data)?($data->PhoneNumbers[0]->PhoneTypeId)=='3'?"selected":' ':' '}}>Work</option>
																<option value="4" {{isset($data)?($data->PhoneNumbers[0]->PhoneTypeId)=='4'?"selected":' ':' '}}>Fax</option>
															</select>
														</div>
													</div>
													<!-- Grid column -->
										
													<!-- Grid column -->
													<div class="col-md-5">											
														<div class="md-form md-outline">												
															<input type="tel" id="f3" class="form-control" name="Phone[0][PhoneNumber]" autofocus value="{{isset($data)?$data->PhoneNumbers[0]->PhoneNumber:''}}" maxlength="12" readonly>
															<label for="f3" class="">Mobile No</label>
														</div>
													</div>
												</div>												
												

												<!-----------------Edit Multiple Phone Number Div End----------------------->
													@if(isset($data))
													@php ($j = 0)
													@if(count($data->PhoneNumbers)>1)
													@for($i = 1; $i < count($data->PhoneNumbers);$i++)
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
																		><option value="1" {{isset($data)?($data->PhoneNumbers[$i]->PhoneTypeId)=='1'?"selected":' ':' '}}>Home</option>
																		<option value="2" {{isset($data)?($data->PhoneNumbers[$i]->PhoneTypeId)=='2'?"selected":' ':' '}}>Mobile</option>
																		<option value="3" {{isset($data)?($data->PhoneNumbers[$i]->PhoneTypeId)=='3'?"selected":' ':' '}}>Work</option>
																		<option value="4" {{isset($data)?($data->PhoneNumbers[$i]->PhoneTypeId)=='4'?"selected":' ':' '}}>Fax</option>
																	</select>																	
																</div>
															</div>
															<div class="col-md-5 col-sm-9">
																<div class="md-form md-outline">
																	<input id="altnum1" type="tel" class="form-control" name="Phone[<?= $i;?>][PhoneNumber]" maxlength="12" autofocus="" value="{{isset($data)?$data->PhoneNumbers[$i]->PhoneNumber:''}}">
																	<label for="altnum1" class="">Alternative Number <?= $i;?></label>
																</div>
															</div>															
														</div>		
														</div>
														</div>								
													@endfor
													@endif
													@endif
													<!-----------------Edit Multiple Phone Number Div End----------------------->

													<!-----------------Multiple Phone Number Div Start----------------------->
																			
													<div id="multiple_phone_numbers_section">
														<!-- <div class="row" id="multiple_phone_title" style="display:none;"> -->
															
														<!-- </div> -->
													</div>

													<!-----------------Multiple Phone Number Div End----------------------->
											

												<div class="row">
													<!-- Grid column -->
													<div class="col-md-10">
														<div class="md-form md-outline">
															<fieldset class="form-check mt-4">
																<input class="form-check-input filled-in" type="checkbox" id="checkbox2" name="CourierNotification" value="{{isset($data)?$data->CourierNotification:' '}}" disabled>
																<label class="form-check-label" for="checkbox1">Send notification to the customer when a courier takes ownership</label>
															</fieldset>
														</div>
													</div>
													<!-- Grid column -->
												</div>
												<div class="row">
													<h5 class="pb-5"><br><i class="icon-item fas fa-address-card"> Delivery Address</i></h5>										
												</div>
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f4" class="form-control" name="AddressLine" autofocus value="{{isset($data)?$data->AddressInfo->Street:' '}}" readonly>
															<label for="f4" class="">Street Address</label>
														</div>
													</div>
													<!-- Grid column -->
										
													<!-- Grid column -->
													<div class="col-md-5">
														
															<div class="md-form md-outline">
															<input type="text" id="f5" class="form-control" name="UnitNumber" autofocus value="{{isset($data)?$data->AddressInfo->UnitNumber:' '}}" readonly>
															<label for="f5" class="">Unit Number</label>
														</div>
													</div>
												</div>
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f6" class="form-control" name="City" autofocus value="{{isset($data)?$data->AddressInfo->City:' '}}" readonly>
															<label for="f6" class="">City</label>
														</div>
													</div>
													<!-- Grid column -->					
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f7" class="form-control" name="Province" autofocus value="{{isset($data)?$data->AddressInfo->Province:' '}}" readonly>
															<label for="f7" class="">Province</label>
														</div>
													</div>
												</div>
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f8" class="form-control" name="PostalCode" autofocus value="{{isset($data)?$data->AddressInfo->PostalCode:' '}}" readonly>
															<label for="f8" class="">Postal Code</label>
														</div>
													</div>
													<!-- Grid column -->	
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f9" class="form-control" name="Country" autofocus value="{{isset($data)?$data->AddressInfo->Country:' '}}" readonly>
															<label for="f9" class="">Country</label>
														</div>
													</div>
												</div>
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="address-phone md-form md-outline">
															<select class="form-control form-control-sm" id="AddressPhone" name="PhoneTypeId" disabled>
																	<option value="">Select Phone Type</option>
																	<option value="1" {{isset($data)?($data->AddressInfo->PhoneTypeId) == 1 ? "selected" :' ':' '}} >Home</option>
																	<option value="2" {{isset($data)?($data->AddressInfo->PhoneTypeId) == 2 ? "selected" :' ':' '}} >Mobile</option>
																	<option value="3" {{isset($data)?($data->AddressInfo->PhoneTypeId) == 3 ? "selected" :' ':' '}} >Work</option>
																	<option value="4" {{isset($data)?($data->AddressInfo->PhoneTypeId) == 4 ? "selected" :' ':' '}} >Fax</option>
															</select>
														</div>
													</div>
													<!-- Grid column -->
										
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="number" id="f10" class="form-control" name="Extension" autofocus value="" readonly>
															<label for="f10" class="">Extension</label>
														</div>
													</div>
												</div>
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="tel" id="f11" class="form-control" name="PhoneNumber" autofocus value="{{isset($data)?$data->AddressInfo->PhoneNumber:' '}}" maxlength="12" readonly>
															<label for="f11" class="">Phone Number</label>
														</div>
													</div>
													<!-- Grid column -->
										
													<!-- Grid column -->
													<div class="col-md-5">
														<div class="md-form md-outline">
															<input type="text" id="f12" class="form-control" name="AdditionalInfo" autofocus value="{{isset($data)?$data->AddressInfo->AdditionalInfo:' '}}" readonly>
															<label for="f12" class="">Additional Information</label>	
														</div>
													</div>
												</div>
												<div class="row">
													<!-- Grid column -->
													<div class="col-md-10">
														<div class="md-form md-outline">
															<textarea type="text" id="f13" class="md-textarea form-control" name="DoorSecurityCode" rows="3" placeholder="Side door, Security code 1234" readonly>{{isset($data)?$data->AddressInfo->DoorSecurityCode:' '}}</textarea>															
														</div>
													</div>
													<!-- Grid column -->					
													<!-- Grid column -->
												</div>											
											<div class="row">
												<!-- Grid column -->
												<div class="col-md-10">
													<div class="float-left">
														<a href="/orders"  class="btn btn-primary waves-effect waves-light">Back</a>
													</div>
														<div class="float-right" id="submitbutton">															
																<?php													
																if(isset($data))
																	echo '<a href="/orders/add_schedule/'.encval($data->CustomerId).'" class="btn btn-primary waves-effect waves-light">Next</a>';
																else
																{}
																?>															
														</div>														
													
												</div>
											</div>
											
										</form>
									</div>
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
	</main>

<script>

 
</script>	

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEggTYQKQ_7Qg0CJQvuPwb0lmOXI05wjE&libraries=places" async defer></script>
<script type="text/javascript" src="{{ asset('js/multiselect/bootstrap-multiselect.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/multiselect/bootstrap-multiselect.min.css') }}" type="text/css"/>
<script src="{{asset('js/Orders/Orders.js')}}"></script>

@include('Includes.Footer')
<script type="text/javascript">
	$(document).ready(function() {
	$('.mdb-select').materialSelect();
	});
</script>