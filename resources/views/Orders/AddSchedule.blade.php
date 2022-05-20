@include('Includes.Header')
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<script>
	 function get_allGoods(addC){
  
  $.ajax({
      url:'/orders/get_goods',
      type:"GET",
      data:'',
      dataType:"JSON",
      success: function(response){
        for(var i in response) {
          $('.multiselectopt_'+addC).append( '<option value="'+ response[i].GoodsId +'">'+ response[i].GoodsName +'</option>' );
        }        
        $('.multiselectopt_'+addC).multiselect('rebuild');     
      },   
    
    });  
}
</script>
  <!-- Main Navigation -->
  <!-- Main layout -->
  <main>
    <div class="add-schedule container-fluid">
      <!-- Section: Cascading panels -->
      <section class="mb-5">
        <!-- Grid row -->
        <div class="row">
          <!-- Grid column -->
          <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
            <!-- Panel -->
            <div class="card">
              <div class="card-header white-text primary-color">
                <i class="fas fa-user">  Create Order</i>
                <span id="name"class="float-right nam mr-3">{{$customer['FirstName']}} {{$customer['LastName']}}</span>
              </div>
	            <div class="card-body mb-3">
	            	<form method="post" action="{{route('AddSchedule')}}">
	            		@csrf
									<div class="row">				
										<div class="col-md-4" >
							        <p>Schedule Start Date</p>
						          <div class="form-group">
						            <div class="iflex">
						            	<input type="hidden" name="CustomerId" value="{{decval(request()->route('id'))}}">
						              <input type="date" id="FromDate" name="StartDate" class="date" value="<?php echo date('Y-m-d'); ?>"  required="required"/>
										    	<p class="colorred font-weight-normal"></p> 
						  						<!--</select>-->
						            </div>
						          </div>	  
						        </div> 				
										<div class="col-md-4" >
						          <button class="btn btn-primary add_recurrence"><i class="fas fa-plus"></i>Add Recurrence Type</button>	
						        </div> 
										<input type="hidden" id="recurrececount" value="{{(old('Recurrence')!==null)?count(old('Recurrence')): 0 }}"/>
									</div>
									<p id="recurrence_validation" class="error"></p>									

							    <div class="more_recurrence">	
							    @if($errors->AddSchedule->has('Goods'))
                    <p class="text-danger">{{$errors->AddSchedule->first('Goods')}}</p>
                  @endif							   
                  @if($errors->AddSchedule->has('Recurrence'))
                    <p class="text-danger">{{"Atleast One Recurrence Is required"}}</p>
                  @endif                 

                  @if(old('Recurrence')!==null)
                  	@for($i=1; $i <= count(old('Recurrence')); $i++)
                  	<script>   get_allGoods({{$i}}); </script>
                  	<div class="row" id="{{$i}}" style="border:1px solid gray; padding:5px;border-radius:5px;margin:5px;position:relative;"> 
                  		<div class="col-md-3" > 
                  			<p>#{{$i}} Recurrence Type</p>
                  			<div class="form-group"><div class="iflex"> 
                  				<select class="form-control form-control-sm RecurrenceTypeID" name="Recurrence[{{$i}}][RecurrenceTypeID]" id="RecurrenceTypeSelector" onchange="RTSShowHide();" >
                  					<option value="">Select Recurrence Type</option>
                  					<option value="1" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 1) ? ' selected' : '' }} >One Time</option> 
                  					<option value="2" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 2) ? ' selected' : '' }} >Daily</option> 
                  					<option value="3" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 3) ? ' selected' : '' }}>Weekly</option> 
                  					<option value="4" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 4) ? ' selected' : '' }}>1st Week</option> 
                  					<option value="5" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 5) ? ' selected' : '' }}>2nd Week</option> 
                  					<option value="6" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 6) ? ' selected' : '' }}>3rd Week</option> 
                  					<option value="7" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 7) ? ' selected' : '' }}>4th Week</option> 
                  					<option value="8" {{(old('Recurrence.'.$i.'.RecurrenceTypeID') == 8) ? ' selected' : '' }}>Custom Dates</option> 
                  				</select>
                  			</div>
                  		</div>
                  	</div>
                  	<div class="col-md-3 MultipleDates{{$i}}" style='<?php if(old('Recurrence.'.$i.'.RecurrenceTypeID') == 4 )echo"display:visible"; else echo "display:none" ?>'>
                  		<p>Select Multiple Date</p>
                  		<div class="form-group"> 
                  			<div class="iflex"> 
                  				<input type="text" name="Recurrence[{{$i}}][CustomDates]" class="form-control customdatefield" value="{{old('Recurrence.'.$i.'.CustomDates')}}"> 
                  			</div>
                  		</div>
                  	</div>
                  	<div class="col-md-6" >
                  		<p>Select Goods</p>
                  		<div class="form-group ">
                  			<div class="iflex">
                  				<select class="form-control multiselectopt_{{$i}}" multiple="multiple" name="Goods[{{$i}}][SelectedGoods][]"  ></select>
                  			</div>
                  		</div>
                  	</div>
                  	<button type="button" recid="{{$i}}" class="close CloseRecurrence" aria-label="Close" style="position: absolute;right: 10px;">
                  		<span aria-hidden="true">&times;</span>
                  	</button>
                  	<div class="col-md-12 RecurrenceTypeDAYS{{$i}}" style='<?php if(old('Recurrence.'.$i.'.RecurrenceTypeID') == 3 || old('Recurrence.'.$i.'.RecurrenceTypeID')==5 || old('Recurrence.'.$i.'.RecurrenceTypeID')==6) echo"display:visible"; else echo "display:none" ?>'>     
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(1,old('Recurrence.'.$i.'.RDay')))? ' active':''}}" >
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][1]" id="RDay1_{{$i}}" value="1" {{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(1,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Mon 
                  			</label>
                  		</div>
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(2,old('Recurrence.'.$i.'.RDay')))? ' active':''}}">
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][2]" id="RDay2_{{$i}}" value="2" {{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(2,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Tue 
                  			</label>
                  		</div>
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(3,old('Recurrence.'.$i.'.RDay')))? ' active':''}}">
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][3]" id="RDay3_{{$i}}" value="3"{{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(3,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Wed 
                  			</label>
                  		</div>
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(4,old('Recurrence.'.$i.'.RDay')))? ' active':''}}">
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][4]" id="RDay4_{{$i}}" value="4" {{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(4,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Thu 
                  			</label>
                  		</div>
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(5,old('Recurrence.'.$i.'.RDay')))? ' active':''}}">
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][5]" id="RDay5_{{$i}}" value="5" {{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(5,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Fri 
                  			</label>
                  		</div>
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(6,old('Recurrence.'.$i.'.RDay')))? ' active':''}}">
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][6]" value="6" id="RDay6_{{$i}}" {{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(6,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Sat 
                  			</label>
                  		</div>
                  		<div class="btn-group" data-toggle="buttons">
                  			<label class="btn btn-primary waves-effect waves-light {{(is_array(old('Recurrence.'.$i.'.RDay')) && in_array(7,old('Recurrence.'.$i.'.RDay')))? ' active':''}}">
                  				<input type="checkbox" class="R_days" name="Recurrence[{{$i}}][RDay][7]" value="7" id="RDay7_{{$i}}" {{ (is_array(old('Recurrence.'.$i.'.RDay')) && in_array(7,old('Recurrence.'.$i.'.RDay'))) ? ' checked':'' }}>Sun 
                  			</label>
                  		</div>
                  		<p id="weekday_validation" class="error"></p>
                  	</div>
                  </div>
                  @if($errors->AddSchedule->has('Recurrence.'.$i.'.RecurrenceTypeID'))
                    <p class="text-danger">{{$errors->AddSchedule->first('Recurrence.'.$i.'.RecurrenceTypeID')}}</p>
                  @endif   
                  @if($errors->AddSchedule->has('Recurrence.'.$i.'.RDay'))
                    <p class="text-danger">{{"Atleast One Weekday Is Required"}}</p>
                  @endif  
                  @if($errors->AddSchedule->has('Goods.'.$i.'.SelectedGoods'))
                    <p class="text-danger">{{$errors->AddSchedule->first('Goods.'.$i.'.SelectedGoods')}}</p>
                  @endif
                  @if($errors->AddSchedule->has('Recurrence.'.$i.'.CustomDates'))
                    <p class="text-danger">{{'Select Multiple Date Is Required'}}</p>
                  @endif
                  	@endfor                  	
                  @endif

							    </div>
									<div class="row">
									<!-- Grid column -->
										<div class="col-md-5 mt-4">					
											<div class="btn-group" data-toggle="buttons">
												<label class="btn btn-primary waves-effect waves-light active">
													<input type="radio" name="CheckComplianceFlag" value="1" id="ongo"  onchange="ONENDShowHide(this);">Ongoing
												</label>
												<label class="btn btn-primary waves-effect waves-light ">
													<input type="radio" name="CheckComplianceFlag" value="0" id="enddradio"  onchange="ONENDShowHide(this);" >End Date
												</label>
											</div>
										</div>
										<div class="col-md-3 mt-4" id="ENDD" style='<?php if(old('EndDate')!==null) echo"display:visible"; else echo "display:none" ?>';>
											<p class="p_bold">Visit End Date</p>
											<div class="form-group">
												<div class="iflex">
											    <input type="date" id="end_date" name="EndDate" class="date" value="{{old('EndDate')}}" />
													<p class="colorred" style="font-weight:400;"></p>
											  </div>
											</div>
	                    <p id="ENDDate_validation" class="error"></p>
										</div>
									<!-- Grid column -->
									</div>									
									<div class="row">
										<div class="col-md-5 mt-5" >
											<p>Target Start Time</p>
											<div class="form-group">
												<div class="iflex">
													<input placeholder="Start Time" name="StartTime" type="text" class="form-control timepicker scheduletimepicker" style="height:30px;" required="required" autocomplete="off" value="<?php echo date('h:iA', strtotime(old('StartTime'))); ?>">
													<span class="colorred"></span> 
												</div>
											</div>
										</div>
					
										<div class="col-md-5 mt-5">
											<p>Target End Time</p>
				   
											<div class="form-group">
												<div class="iflex">
													<input placeholder="End Time" name="EndTime" type="text" class="form-control timepicker scheduletimepicker" style="height:30px;" required="required" autocomplete="off" value="<?php echo date('h:iA', strtotime(old('EndTime'))); ?>">
													 <span class="colorred"></span> 
												</div>
											</div>
										</div>
									</div>
									<div class="row">
									<!-- Grid column -->
										<div class="col-md-5 mt-1">
											<div class="md-form md-outline">
												<input type="text" id="Amount" name="Amount" class="form-control" value="{{old('Amount')}}" required="required">
												<label for="Amount" class="">Amount</label>
											</div>
											@if($errors->AddSchedule->has('Amount'))
                        <span class="text-danger">{{$errors->AddSchedule->first('Amount')}}</span>
                      @endif
											<p id="amount_error" class="error"></p>
										</div>
									</div>
									<div class="row">
										<!-- Grid column -->
										<div class="col-md-5">
											<div class="md-form md-outline">
												<input type="text" id="Notes" name="Notes" class="form-control" value="{{old('Notes')}}" required="required">
												<label for="Notes" class="">Order Notes</label>
											</div>
											@if($errors->AddSchedule->has('Notes'))
                        <span class="text-danger">{{$errors->AddSchedule->first('Notes')}}</span>
                      @endif
											<p id="notes_error" class="error"></p>
										</div>
										<!-- Grid column -->
									
										<!-- Grid column -->
										<div class="col-md-5">
											<div class="md-form md-outline">
												<input type="text" id="EmployeeCode" name="EmployeeCode" class="form-control" value="{{old('EmployeeCode')}}" required="required">
												<label for="EmployeeCode" class="">Employee Code</label>
											</div>
											@if($errors->AddSchedule->has('EmployeeCode'))
                        <span class="text-danger">{{$errors->AddSchedule->first('EmployeeCode')}}</span>
                      @endif
											<p id="empcode_error" class="error"></p>
										</div>
										<!-- Grid column -->
									</div>
									<div class="row mt-4">
										<!-- Grid column -->
										<div class="col-md-10 ">
										<p class="p_bold">Select Tags</p>
										@for($i=0;$i < count($tags);$i++)
											<div class="tags btn-group " data-toggle="buttons">
												<?php echo '<label class="badge btn btn-primary waves-effect waves-light '; ?> {{ (is_array(old('SelectedTags')) && in_array($tags[$i]['TagId'], old('SelectedTags'))) ? ' active' : '' }}
													<?php echo'" style="color:#fff;background-color:'.$tags[$i]['TagColor'].'!important;">

													<input type="checkbox" class="ColorTag" name="SelectedTags[]" value="'.$tags[$i]['TagId'].'"';?> 
													{{ (is_array(old('SelectedTags')) && in_array($tags[$i]['TagId'],old('SelectedTags'))) ? ' checked' : '' }} 
													<?php echo ' >'.$tags[$i]['TagName'].'</label>'; ?>	
											</div>
										@endfor		
										</div>
										<!-- Grid column -->				
									</div>
									<br>
									<br>
									<div class="row">
									<div class="col-md-10 text-center">
										<input type="submit" id="createOrder" name="submit" class="btn btn-primary waves-effect waves-light" value="Create Order" >
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

@include('Includes.Footer')
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js')}}"></script>
<link href="{{ asset('css/datepicker/bootstrap-datepicker.min.css') }}"/>
<script type="text/javascript" src="{{ asset('js/multiselect/bootstrap-multiselect.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/multiselect/bootstrap-multiselect.min.css') }}" type="text/css"/>

<script src="{{asset('js/Orders/Orders.js')}}"></script>


<script type="text/javascript">
	// Time Picker Initialization
	var timeToSend = $('.scheduletimepicker').pickatime({
    twelvehour: true,
  	minutestep: 5,
	  afterShow: function() {
        $('.am-button').addClass( "active" );
        $('.pm-button').removeClass( "active" );
    }
  });
</script>