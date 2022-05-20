@include('Includes.Header')
  <!-- Main Navigation  -->
  <!-- Main layout  -->
  <main class="assign-to-driver">
    <div class="container-fluid mb-5">
      <!-- Section: Basic examples -->
      <section>
        <!-- Gird column -->
        <div class="col-md-12">
          <h5 class="my-4 dark-grey-text font-weight-bold"></h5>
          <div class="card">
            <div class="card-body">
							<div class="row">
								<div class="col-md-8 float-left"><h4>Orders assigned to driver</h4></div>
							</div>
							<hr>
		          <!-- Section heading -->
		          <form method="post" action="{{route('AssignToDriver')}}">
		          	@csrf
		          	<input type="hidden" name="Batches" value="{{$batch}}">
			          <div class="text-center">
									<div class="col-md-3">
										<select class="mdb-select md-form" id="driver" name="Driver">
											<option value="">Select Driver</option>
											@foreach($drivers as $driver)
												<option value="{{$driver['Id']}}">{{$driver['FirstName']}} {{$driver['LastName']}}</option>	
											@endforeach
										</select>
									</div>
			 					</div>
			 				<div id="driver_detail" style="display: hidden;">
			          <h5 class="pb-2">Driver Details</h5>
			          <!-- Grid row -->
			          <div class="row driver-section">
				          <!-- Grid column -->
			            <div class="col-md-4 mb-4">
										<div class="md-form md-outline">
											<input type="text" id="driver_name" class="form-control" name="DriverName" autofocus readonly>
											<label id="forDriverName" for="driver_name" class="">Driver Name</label>
										</div>
			            </div>
			            <!-- Grid column -->
			            <!-- Grid column -->
			            <div class="col-md-4 mb-4">
										<div class="md-form md-outline">
											<input type="text" id="driver_mobile" class="form-control" name="DriverMobile" autofocus readonly>
											<label for="driver_mobile" class="">Mobile Number</label>
										</div>
			            </div>
			            <!-- Grid column -->
			          </div> 
	         			<div class="row driver-section">
			            <!-- Grid column -->
			            <div class="col-md-4 mb-4">
										<div class="md-form md-outline">
											<input type="text" id="driver_email" class="form-control" name="DriverEmail" autofocus readonly>
											<label for="driver_email" class="">Email</label>
										</div>
			            </div>
			            <!-- Grid column -->
			            <!-- Grid column -->
			            <!-- <div class="col-md-4 mb-4">				
										<div class="barcode-img md-form md-outline"> -->
										<!-- <p class="prefix">Barcode</p> -->
										<!-- <p class="">Barcode</p>
											<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('$batch', 'C39')}}" class="img-fluid" alt="Sample image barcode." name="DriverBarcode" style="height:4rem"> -->
											<!-- <p>{!! DNS1D::getBarcodeHTML('4445645656', 'PHARMA') !!}</p> -->
										<!-- </div>
					        </div> -->
			            <!-- Grid column -->
		          	</div>
								<div class="text-left driver-section">
									<input type="submit" name="submit" class="btn btn-primary waves-effect waves-light" value="Assign">
								</div>
							</form>
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
<script src="{{asset('js/Orders/AssignToDriver.js')}}"></script>

