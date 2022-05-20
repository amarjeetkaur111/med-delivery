@include('Includes.Header')	
  <!-- Main Navigation -->
  <!-- Main layout -->
  <main class="add_medication">
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
                <i class="fas fa-user">  Add Goods</i>
              </div>
          	  <div class="card-body mb-3">
          	  	<form method="post" action="{{route('AddGoods')}}">
          	  		@csrf									
									<div class="row">
										<!-- Grid column -->
										<div class="col-md-5">											
											<div class="md-form md-outline">											
												<select class="medicationtype-select form-control" name="GoodsTypeId" id="GoodsTypeId">
													<option value="">Select Goods Type</option>
												@foreach($ids as $id)													
													<option value={{$id->GoodsTypeId}} {{old('GoodsTypeId') == $id->GoodsTypeId ? "selected" :' '}}>{{$id->GoodsTypeName}}</option>
												@endforeach
												</select>
												@error('GoodsTypeId')
													<span class="text-danger">{{$message}}</span>
												@enderror
											</div>
										</div>
										<!-- Grid column -->
							
										<!-- Grid column -->
										<div class="col-md-5">											
											<div class="md-form md-outline">
												<input type="text" id="f2" class="form-control" name="GoodsName" autofocus value="{{old('GoodsName')}}">
												<label for="f2" class="">Goods Name</label>
												@error('GoodsName')
													<span class="text-danger">{{$message}}</span>
												@enderror
											</div>
										</div>
									</div>
									<div class="row">
										<!-- Grid column -->
										<div class="col-md-5">
											<div class="md-form md-outline">
												<input type="text" id="f2" class="form-control" name="Cost" autofocus value="{{old('Cost')}}">
												<label for="f2" class="">Goods Cost</label>
												@error('Cost')
													<span class="text-danger">{{$message}}</span>
												@enderror
											</div>
										</div>
										<!-- Grid column -->
							
										<!-- Grid column -->
										<div class="col-md-5">
											<div class="md-form md-outline">												
												<input type="text" id="f2" class="form-control" name="Quantity" autofocus value="{{old('Quantity')}}">
												<label for="f2" class="">Goods Quantity</label>
												@error('Quantity')
													<span class="text-danger">{{$message}}</span>
												@enderror							
											</div>
										</div>
									</div>
									
									<div class="row">
										<!-- Grid column -->
										<div class="col-md-10">
											<div class="text-center">
												<a href="/administration/goods"  class="btn btn-primary waves-effect waves-light">Cancel</a>	
												<input type="submit" class="btn btn-primary waves-effect waves-light" value="Submit">												
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
<script src="{{asset('js/Administration/Administration.js')}}"></script>

@include('Includes.Footer')
