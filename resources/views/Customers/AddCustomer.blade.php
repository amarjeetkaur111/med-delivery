@include('Includes.Header')	
  <!-- Main Navigation -->
  <!-- Main layout -->
  <main>
    <div class="add-customer container-fluid mt-n3">
      <!-- Section: Cascading panels -->
      <section class="mb-5">
        <!-- Grid row -->
        <div class="row">
          <!-- Grid column -->
          <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
            <!-- Panel -->
            <div class="card">
              <div class="card-header white-text primary-color">
                <i class="fas fa-user">  Add Customer</i>
              </div>
          	  <div class="card-body mb-3">
          	  	<form method="post" action="{{route('AddCustomer')}}">
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
											<option value="{{$pharmacy['PharmacyId']}}">{{$pharmacy['PharmacyName']}}</option>	
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
								<div class="md-form md-outline">
									<i class="fas fa-user prefix"></i>
									<input type="text" id="f1" class="form-control" name="FirstName" autofocus value="{{old('FirstName')}}">
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
									<input type="text" id="f2" class="form-control" name="LastName" autofocus value="{{old('LastName')}}">
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
									<i class="fas fa-phone-alt prefix"></i>
									<select class="phonetype-select form-control" name="Phone[0][PhoneTypeId]">
										<option value="">Select Phone Type</option>
										<option value="1" {{old('Phone.0.PhoneTypeId') == 1 ? "selected" :' '}}>Home</option>
										<option value="2" {{old('Phone.0.PhoneTypeId') == 2 ? "selected" :' '}}>Mobile</option>
										<option value="3" {{old('Phone.0.PhoneTypeId') == 3 ? "selected" :' '}}>Work</option>
										<option value="4" {{old('Phone.0.PhoneTypeId') == 4 ? "selected" :' '}}>Fax</option>
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
									<input type="tel" id="f3" class="form-control" name="Phone[0][PhoneNumber]" maxlength="12" autofocus value="{{old('Phone.0.PhoneNumber')}}" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
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
							<!-----------------Multiple Phone Number Div Start----------------------->
													
							<div id="multiple_phone_numbers_section">
								<div class="row" id="multiple_phone_title">
									<div class="col-md-12">													
									</div>
								</div>
							</div>
							<!-----------------Multiple Phone Number Div End----------------------->
						
						<!-- <div class="row">
							Grid column
							<div class="col-md-10">
								<div class="md-form md-outline">
									<fieldset class="form-check mt-n3">
										<input class="form-check-input filled-in" type="checkbox" id="checkbox2" name="CourierNotification" value="{{old('CourierNotification')}}">
										<label class="form-check-label" for="checkbox2">Send notification to the customer when a courier takes ownership</label>
									</fieldset>
								</div>
							</div>
							Grid column
						</div> -->
						<div class="row">
							<h5 class=""><br><i class="icon-item fas fa-address-card"> Delivery Address</i></h5>										
						</div>
						<div class="row">
							<!-- Grid column -->
							<div class="col-md-5">
								<div class="md-form md-outline">	
								<div id="locationField">
										<label for="locationField" class=''>Street Address</label>
										<input id="autocomplete" name="AddressLine" class="form-control" value="{{old('AddressLine')}}" type="text" autocomplete="off" />	
								<input type="hidden" name="" id="street_number" value="">
								<input type="hidden" name="" id="route" value="" >
								<input type="hidden" name="Latitude" id="Latitude" value="{{old('Latitude')}}" maxlength="12">
								<input type="hidden" name="Longitude" id="Longitude" value="{{old('Longitude')}}" maxlength="12">
							</div> 
						</div>
							@error('AddressLine')
								<p class="text-danger error-p">{{$message}}</p>
							@enderror
							</div>
							
							<!-- Grid column -->
				
							<!-- Grid column -->
							<div class="col-md-5">
									<div class="md-form md-outline">
									<input type="text" id="f5" class="form-control" name="UnitNumber" autofocus value="{{old('UnitNumber')}}">
									<label for="f5" class="">Unit Number</label>
								</div>
							</div>
						</div>
						<div class="row">
							<!-- Grid column -->
							<div class="col-md-5">
								<div class="md-form md-outline">
									<input type="text" id="locality" class="form-control" name="City" autofocus value="{{old('City')}}" onkeypress="return /[a-z ]/i.test(event.key)">
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
									<input type="text" class="form-control" name="Province" autofocus value="{{old('Province')}}" id="administrative_area_level_1"  onkeypress="return /[a-z ]/i.test(event.key)">
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
									<input type="text" id="postal_code" class="form-control" name="PostalCode" autofocus value="{{old('PostalCode')}}" >
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
									<input type="text" id="country" class="form-control" name="Country" autofocus value="Canada" onkeypress="return /[a-z ]/i.test(event.key)">
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
									<select class="form-control form-control-sm" name="PhoneTypeId" value="{{old('PhoneTypeId')}}" >
											<option value="">Select Phone Type</option>
											<option value="1" {{old('PhoneTypeId') == 1 ? "selected" :' '}} >Home</option>
											<option value="2" {{old('PhoneTypeId') == 2 ? "selected" :' '}} >Mobile</option>
											<option value="3" {{old('PhoneTypeId') == 3 ? "selected" :' '}} >Work</option>
											<option value="4" {{old('PhoneTypeId') == 4 ? "selected" :' '}}>Fax</option>
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
									<input type="number" id="f11" class="form-control" name="Extension" autofocus value="{{old('Extension')}}" >
									<label for="f11" class="">Extension</label>
								</div>
							</div>
						</div>
						<div class="row">
							<!-- Grid column -->
							<div class="col-md-5">
								<div class="md-form md-outline">
									<input type="tel" id="f10" class="form-control" name="PhoneNumber" autofocus value="{{old('PhoneNumber')}}" maxlength="12" >
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
									<input type="text" id="f12" class="form-control" name="AdditionalInfo" autofocus value="{{old('AdditionalInfo')}}" >
									<label for="f12" class="">Additional Information</label>
								</div>
							</div>
						</div>
						<div class="row">
							<!-- Grid column -->
							<div class="col-md-10">
								<div class="md-form md-outline">
									<textarea type="text" id="form10" class="md-textarea form-control" name="DoorSecurityCode" rows="3" placeholder="Notes" >{{old('DoorSecurityCode')}}</textarea>
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
									<?php $u = str_replace(url('/'), '', url()->previous());
									if($u == '/orders/add_customer') 
									{
										echo '<input type="hidden" name="from" value="order">';
										echo '<input type="submit" class="btn btn-primary waves-effect waves-light" value="Create Customer & Next">';
									}
									else
									{
										echo '<input type="hidden" name="from" value="customer">';
										echo '<input type="submit" class="btn btn-primary waves-effect waves-light" value="Create Customer">';
									}
									?>	
									
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
	</main>

<script>


</script>	

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEggTYQKQ_7Qg0CJQvuPwb0lmOXI05wjE&libraries=places" async defer></script>
<script src="{{asset('js/Customer/Customer.js')}}"></script>

@include('Includes.Footer')
