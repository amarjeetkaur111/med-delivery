<style>

	.modal
{
	 z-index: 1001 !important;
}
.modal-backdrop
{
	 z-index: 1000 !important;
}
.pac-container
{
	 z-index: 1055 !important;
}
.modal-dialog
{
	margin-top: 6rem;
}
</style>
@if($errors->EditAddress->any()) 
	<script>  
    $(function(){
    	 $(".edit_address_modal").appendTo("body");
         $('.edit_address_modal').modal('show');
     });
  </script>
@enderror
@if($errors->AddAddress->any()) 
	<script>  
    $(function(){
    	 $("#add_new_address").appendTo("body");
         $('#add_new_address').modal('show');
     });
  </script>
@enderror

<div class="container-fluid">
<!-- Section: Cascading panels -->
	<section class="">
		<?php $custdata = $data[0]['customers_address']?>
		      @for($i =0 ;$i < count($custdata);$i++)		      

	<!-- Grid row -->
		<div class="row">
		  <!-- Grid column -->
		  <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
		    <!-- Panel -->		    
		    <div class="card">
		      <!-- <div class="card-header white-text primary-color">
		        Addresses & Contact Info
		      </div> -->

			  	  <div class="card-body mb-3 bg-white" id="address_<?php echo $i; ?>">
			  	  	<div class="row">
			  	  		<div class="col-md-2">										    								    
						    	<span><i class="fas fa-file-archive fa-5x" style="color: #707070"></i></span>
					  		</div>
							  <div class="col-md-9">										    								    
							    <h4 class="pt-4">@if(($custdata[$i]['SetAsPrimary']) == 1) {{'Primary Address'}} @else {{'Secondary Address '.$i}} @endif</h4>
							  </div>
			  	  	</div>
			  	  	<br>
			  	  	<div class="row">
								<div class="col-md-2">
									<label class="labels">Street Address</label>
								</div>
								<div class="col-md-5">
									<label class="data-label">{{$custdata[$i]['AddressLine']}}</label>
								</div>	
								<div class="col-md-2">
									<label class="labels">Unit Number</label>
								</div>
								<div class="col-md-2">
									<label class="data-label">{{$custdata[$i]['UnitNumber']}}</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label class="labels">City</label>
								</div>
								<div class="col-md-5">
									<label class="data-label">{{$custdata[$i]['City']}}</label>
								</div>
								<div class="col-md-2">
									<label class="labels">Phone Number</label>
								</div>
								<div class="col-md-2">
									<label class="data-label">{{$custdata[$i]['PhoneNumber']}}</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label class="labels">Province</label>
								</div>
								<div class="col-md-5">
									<label class="data-label">{{$custdata[$i]['Province']}}</label>
								</div>
								<div class="col-md-2">
									<label class="labels">Phone Type</label>
								</div>
								<div class="col-md-2">
									<?php $pid = $custdata[$i]['PhoneTypeId'] ?>
									<label class="data-label">@if($pid==1) {{'Home'}} @elseif($pid==2) {{'Mobile'}} @elseif($pid==3) {{'Work'}} @else {{'Fax'}} @endif</label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2">
									<label class="labels">Additional Info</label>
								</div>
								<div class="col-md-5">
									<label class="data-label">{{$custdata[$i]['AdditionalInfo']}}</label>
								</div>
								<div class="col-md-2">
									<label class="labels">Extension</label>
								</div>
								<div class="col-md-2">
									<label class="data-label">{{$custdata[$i]['Extension']}}</label>
								</div>
							</div>
							<div class="row mt-5">
								<!-- Grid column -->
								<div class="col-md-11">
									<div class="float-left">
										<button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#edit_address_modal_{{$i+2}}" >Edit This Address</button>
										<?php if($custdata[$i]['SetAsPrimary']==0) { ?>
											<button type="button" class="btn btn-outline-primary waves-effect"><a href="javascript:void(0);" data-name="{{$i+2}}" data-id="{{encval($custdata[$i]['CustomerAddressId'])}}" id="deleteAddress">Delete This Address</a></button>
										<?php } ?>
									</div>
								</div>
							</div>
							<?php if(($i+1) == count($custdata)){ ?>
							<hr>
							<!--------- Add Address Button ------------->
							<div class="row">
							  <!-- Grid column -->
							  <div class="col-md-11">
									<div class="float-right">
										<button type="button" class="btn btn-outline-primary waves-effect" data-toggle="modal" data-target="#add_new_address" >Add Address</button>
											<a href="/customers"  class="btn btn-outline-primary waves-effect waves-light">Save & Next</a>
									</div>										
								</div>
							</div>
							<!--------- Add Address Button Ends------------->
						<?php } ?>
	      	  </div>
      	</div>
      </div>
    </div>
  </section>

 <!-- Edit Address -->

<div class="modal fade edit_address_modal" id="edit_address_modal_{{$i+2}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="mxodal-title" id="ModalLabel">Edit Address</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="{{route('UpdateAddress')}}">
          @csrf
          <input type="hidden" class="form-control" name="SetAsPrimary" autofocus value="{{$custdata[$i]['SetAsPrimary']}}">
          <input type="hidden" class="form-control" name="CustomerId" autofocus value="{{$custdata[$i]['CustomerId']}}">
          <input type="hidden" class="form-control" name="AddressId" autofocus value="{{$custdata[$i]['CustomerAddressId']}}">
          <div class="row">
            <!-- Grid column -->
            <div class="col-md-12" style="padding-left: 70px;">
					  	<div class="row">
	              <div class="col-md-4">
							     <label class="labels">Street Address <span class="redcolor">*</span> </label>
	              </div>
	              <div class="col-md-6"> 
										<div id="locationField">
											<input id="autocomplete{{$i+2}}" name="EditAddressLine" class="form-control new-add-input" value="{{old('EditAddressLine',$custdata[$i]['AddressLine'])}}" type="text" autocomplete="off" onfocus="initAutocomplete({{$i+2}});" />	
						          <input type="hidden" name="" id="street_number{{$i+2}}" value="">
						          <input type="hidden" name="" id="route{{$i+2}}" value="" >
						          <input type="hidden" name="EditLatitude" id="Latitude{{$i+2}}" value="{{$custdata[$i]['Latitude']}}" >
						          <input type="hidden" name="EditLongitude" id="Longitude{{$i+2}}" value="{{$custdata[$i]['Longitude']}}">
					       		</div>
					       		@if($errors->EditAddress->has('EditAddressLine'))
                      <span class="text-danger">{{$errors->EditAddress->first('EditAddressLine')}}</span>
                    @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Unit Number</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditUnitNumber"  id="UnitNumber"  class="form-control new-add-input" value="{{old('EditUnitNumber', $custdata[$i]['UnitNumber'])}}" type="text"  />
								</div>
							</div>
						  <div class="row">
	              <div class="col-md-4">
							     <label class="labels"> City</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditCity" class="form-control new-add-input" id="locality{{$i+2}}" value="{{old('EditCity', $custdata[$i]['City'])}}" type="text" />
									@if($errors->EditAddress->has('EditCity'))
                    <span class="text-danger">{{$errors->EditAddress->first('EditCity')}}</span>
                  @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Province</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditProvince" id="administrative_area_level_1{{$i+2}}" class="form-control new-add-input" value="{{old('EditProvince', $custdata[$i]['Province'])}}" type="text" />
									@if($errors->EditAddress->has('EditProvince'))
                    <span class="text-danger">{{$errors->EditAddress->first('EditProvince')}}</span>
                  @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Postal Code</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditPostalCode" id="postal_code{{$i+2}}" class="form-control new-add-input" value="{{old('EditEditPostalCode',$custdata[$i]['PostalCode'])}}" type="text" />
									@if($errors->EditAddress->has('EditPostalCode'))
                    <span class="text-danger">{{$errors->EditAddress->first('EditPostalCode')}}</span>
                  @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Country</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditCountry" id="country{{$i+2}}" class="form-control new-add-input" value="{{old('EditCountry', $custdata[$i]['Country'])}}" type="text" />
									@if($errors->EditAddress->has('EditCountry'))
                    <span class="text-danger">{{$errors->EditAddress->first('EditCountry')}}</span>
                  @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Phone Number <span class="redcolor">*</span></label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditPhoneNumber" class="form-control new-add-input" value="{{old('EditPhoneNumber' ,$custdata[$i]['PhoneNumber'])}}" type="tel" maxlength="12" />
									@if($errors->EditAddress->has('EditPhoneNumber'))
                    <span class="text-danger">{{$errors->EditAddress->first('EditPhoneNumber')}}</span>
                  @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Extension</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditExtension" class="form-control new-add-input" value="{{old('EditEditExtension',$custdata[$i]['Extension'])}}" type="text" />
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Phone Type <span class="redcolor">*</span></label>
	              </div>
	              <div class="col-md-6"> 
									<select class="form-control new-add-input" name="EditPhoneTypeId">
										<option value="">Select Phone Type</option>
										<option value="1" {{old('EditPhoneTypeId', $custdata[$i]['PhoneTypeId']) == 1 ? "selected" :' '}}>Home</option>
										<option value="2" {{old('EditPhoneTypeId' ,$custdata[$i]['PhoneTypeId']) == 2 ? "selected" :' '}}>Mobile</option>
										<option value="3" {{old('EditPhoneTypeId' ,$custdata[$i]['PhoneTypeId']) == 3 ? "selected" :' '}}>Work</option>
										<option value="4" {{old('EditPhoneTypeId' ,$custdata[$i]['PhoneTypeId']) == 4 ? "selected" :' '}}>Fax</option>
									</select>
									@if($errors->EditAddress->has('EditPhoneTypeId'))
                    <span class="text-danger">{{$errors->EditAddress->first('EditPhoneTypeId')}}</span>
                  @endif 
								</div>
							</div>
							<div class="row">
	              <div class="col-md-4">
							     <label class="labels"> Additional Info</label>
	              </div>
	              <div class="col-md-6"> 
									<input name="EditAdditionalInfo" class="form-control new-add-input" value="{{old('EditAdditionalInfo', $custdata[$i]['AdditionalInfo'])}}" type="text" />
								</div>
							</div>
							<?php 
							if($custdata[$i]['SetAsPrimary']==0) {?> 
							<div class="row">
	              <div class="col-md-4">
	              </div>
	              <div class="col-md-6"> 
	              	<input type="checkbox" id="checkbox2" name="set_primary_address" value="1" class="custom-checkbox">
									<label for="set_primary_address"> Set this as primary address</label>
								</div>
							</div>	
							<?php  }?>
							<div class="row">
								<div class="col-md-10 offset-1 text-center">
              		<button type="submit" class="btn btn-primary waves-effect waves-light">Update</button>									
								</div>
							</div>									
            </div>
          </div>
        </form> 
      </div>   
    </div>              
  </div>
</div>      
<!--------- Edit Address Ends ------------->
@endfor
 <!-- Add New Address -->

  <div class="modal fade" id="add_new_address" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" >
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ModalLabel">Add New Address</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{route('AddAddress')}}">
            @csrf
            <input type="hidden" class="form-control" name="Id" autofocus value="{{$custdata[0]['CustomerId']}}">
            <div class="row">
              <!-- Grid column -->
              <div class="col-md-12" style="padding-left: 70px;">
						  	<div class="row">
		              <div class="col-md-4">
								     <label class="labels">Street Address <span class="redcolor">*</span> </label>
		              </div>
		              <div class="col-md-6"> 
											<div id="locationField">
												<input id="autocomplete0" name="AddAddressLine" class="form-control new-add-input" value="{{old('AddAddressLine')}}" type="text" autocomplete="off" value="Hello" onfocus="initAutocomplete(0);" />	
							          <input type="hidden" name="" id="street_number0" value="">
							          <input type="hidden" name="" id="route0" value="" >
							          <input type="hidden" name="AddLatitude" id="Latitude0" value="" >
							          <input type="hidden" name="AddLongitude" id="Longitude0" value="">
						       		</div> 												
											@if($errors->AddAddress->has('AddAddressLine'))
                      <span class="text-danger">{{'Address Line is required'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Unit Number</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddUnitNumber"  id="UnitNumber"  class="form-control new-add-input" value="{{old('AddUnitNumber')}}" type="text"  />
									</div>
								</div>
							  <div class="row">
		              <div class="col-md-4">
								     <label class="labels"> City</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddCity" class="form-control new-add-input" id="locality0" value="{{old('AddCity')}}" type="text" />
										@if($errors->AddAddress->has('AddCity'))
                      <span class="text-danger">{{'City is required'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Province</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddProvince" id="administrative_area_level_10" class="form-control new-add-input" value="{{old('AddProvince')}}" type="text" />
										@if($errors->AddAddress->has('AddProvince'))
                      <span class="text-danger">{{'Province is required'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Postal Code</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddPostalCode" id="postal_code0" class="form-control new-add-input" value="{{old('AddPostalCode')}}" type="text" />
										@if($errors->AddAddress->has('AddPostalCode'))
                      <span class="text-danger">{{'Postal Code is required with pattern Eg - K5V 8F9'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Country</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddCountry" id="country0" class="form-control new-add-input" value="{{old('AddCountry')}}" type="text" />
										@if($errors->AddAddress->has('AddCountry'))
                      <span class="text-danger">{{'Country is required'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Phone Number <span class="redcolor">*</span></label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddPhoneNumber" class="form-control new-add-input" value="{{old('AddPhoneNumber')}}" type="tel" maxlength="12" />
										@if($errors->AddAddress->has('AddPhoneNumber'))
                      <span class="text-danger">{{'Phone is required with pattern - XXX-XXX-XXXX'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Extension</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddExtension" class="form-control new-add-input" value="{{old('AddExtension')}}" type="text" />
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Phone Type <span class="redcolor">*</span></label>
		              </div>
		              <div class="col-md-6"> 
										<select class="form-control new-add-input" name="AddPhoneTypeId">
											<option value="">Select Phone Type</option>
											<option value="1" {{old('AddPhoneTypeId') == 1 ? "selected" :' '}}>Home</option>
											<option value="2" {{old('AddPhoneTypeId') == 2 ? "selected" :' '}}>Mobile</option>
											<option value="3" {{old('AddPhoneTypeId') == 3 ? "selected" :' '}}>Work</option>
											<option value="4" {{old('AddPhoneTypeId') == 4 ? "selected" :' '}}>Fax</option>
										</select>
										@if($errors->AddAddress->has('AddPhoneTypeId'))
                      <span class="text-danger">{{'Select Phone Type'}}</span>
                    @endif 
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
								     <label class="labels"> Additional Info</label>
		              </div>
		              <div class="col-md-6"> 
										<input name="AddAdditionalInfo" class="form-control new-add-input" value="{{old('AddAdditionalInfo')}}" type="text" />
									</div>
								</div>
								<div class="row">
		              <div class="col-md-4">
		              </div>
		              <div class="col-md-6"> 
		              	<input type="checkbox" id="checkbox2" name="set_primary_address" value="1" class="custom-checkbox">
  									<label for="set_primary_address"> Set this as primary address</label>
									</div>
								</div>	
								<div class="row">
									<div class="col-md-10 offset-1 text-center">
                		<button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>									
									</div>
								</div>									
              </div>
            </div>
          </form> 
        </div>   
      </div>              
    </div>
  </div>  

  </div>  
  <!--------- Add New Address Ends ------------->
  	
