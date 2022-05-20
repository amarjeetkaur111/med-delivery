    <div class="container-fluid add-schedule">
      <!-- Section: Cascading panels -->
      <section class="mb-5">
        <!-- Grid row -->
        <div class="row">
          <!-- Grid column -->
          <div class="col-lg-12 col-md-12 mb-lg-0 mb-4">
            <!-- Panel -->
            <div class="card">
              <div class="card-header white-text primary-color">
                <!-- <i class="fas fa-user float-left">  Create Order</i> -->
                <span class="nam mr-3">{{$data[0]['customer']['FirstName']}} {{$data[0]['customer']['LastName']}}</span>
              </div>
	            <div class="card-body mb-3">
	            	<form method="post" action="{{route('UpdateSchedule')}}">
	            		@csrf
									<div class="row">				
										<div class="col-md-4" >
							        <p>Schedule Start Date</p>
						          <div class="form-group">
						            <div class="iflex">
						            	<input type="hidden" name="CustomerId" value="{{$data[0]['CustomerID']}}">
						            	<input type="hidden" name="ScheduleId" value="{{$data[0]['SchedulerID']}}">
						            	<input type="hidden" name="url" value="{{URL::previous()}}">
						              <input type="date" id="FromDate" name="StartDate" class="date" value="{{old('StartDate',$data[0]['scheduler']['StartDate'])}}"  required="required"/>
										    	<p class="colorred font-weight-normal"></p> 
						  						<!--</select>-->
						            </div>
						          </div>	  
						        </div> 				
										<div class="col-md-4" >
						          <button class="btn btn-primary add_recurrence"><i class="fas fa-plus"></i>Add Recurrence Type</button>	
						        </div> 
						        <?php $recurrence = 0 ?>
									</div>
									<p id="recurrence_validation" class="error"></p>									

							    <div class="more_recurrence">	
								    @if($errors->AddSchedule->has('Goods'))
	                    <p class="text-danger">{{$errors->AddSchedule->first('Goods')}}</p>
	                  @endif							   
	                  @if($errors->AddSchedule->has('Recurrence'))
	                    <p class="text-danger">{{"Atleast One Recurrence Is required"}}</p>
	                  @endif                 

	                  <?php $rec_count =  count($data[0]['scheduler']['scheduler_recurrence']); 	                 
	                  if($data[0]['scheduler']['scheduler_recurrence'] !==null)
	                  {
		                  $rec_count =  count($data[0]['scheduler']['scheduler_recurrence']);
							        $goods = [];  
							        for($i=0; $i < count($data[0]['scheduler']['scheduler_recurrence']); $i++)
							        {		

							        	$selecteddays =  $data[0]['scheduler']['scheduler_recurrence'][$i]['RecurrenceSelectedDays'];	
	                  		$rectypeid = $data[0]['scheduler']['scheduler_recurrence'][$i]['RecurrenceTypeID'];
	                  				            
						            if(($i+1) != $rec_count && $rectypeid == $data[0]['scheduler']['scheduler_recurrence'][$i+1]['RecurrenceTypeID'] && count(array_diff($selecteddays,$data[0]['scheduler']['scheduler_recurrence'][$i+1]['RecurrenceSelectedDays'])) == 0)
						             {
						                array_push($goods,$data[0]['scheduler']['scheduler_recurrence'][$i]['GoodsID']);
						             }
						            else
						            {
						               array_push($goods,$data[0]['scheduler']['scheduler_recurrence'][$i]['GoodsID']);
						            ?>	           	
	                  	<!-- <script>   get_allGoods({{$i}}); </script> -->
	                  	<div class="row" id="{{++$recurrence}}" style="border:1px solid gray; padding:5px;border-radius:5px;margin:5px;position:relative;"> 
	                  		<div class="col-md-3" > 
	                  			<p>#{{$recurrence}} Recurrence Type</p>
	                  			<div class="form-group"><div class="iflex"> 
	                  				<select class="form-control form-control-sm RecurrenceTypeID" name="Recurrence[{{$recurrence}}][RecurrenceTypeID]" id="RecurrenceTypeSelector{{$recurrence}}" onchange="RTSShowHide();" >
	                  					<option value="">Select Recurrence Type</option>
	                  					<option value="1" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 1) ? ' selected' : '' }} >One Time</option> 
	                  					<option value="2" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 2) ? ' selected' : '' }} >Daily</option> 
	                  					<option value="3" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 3) ? ' selected' : '' }}>Weekly</option> 
	                  					<option value="4" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 4) ? ' selected' : '' }}>1st Week</option> 
	                  					<option value="5" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 5) ? ' selected' : '' }}>2nd Week</option> 
	                  					<option value="6" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 6) ? ' selected' : '' }}>3rd Week</option> 
	                  					<option value="7" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 7) ? ' selected' : '' }}>4th Week</option> 
	                  					<option value="8" {{(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 8) ? ' selected' : '' }}>Custom Dates</option> 
	                  				</select>
	                  			</div>
	                  		</div>
		                  	<p id="recurrence_type_error_{{$recurrence}}" class="error"></p>
	                  	</div>											 

	                  	<div class="col-md-3 MultipleDates{{$recurrence}}" style='<?php if(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 8 )echo"display:visible"; else echo "display:none" ?>'>
	                  		<p>Select Multiple Date</p>
	                  		<div class="form-group"> 
	                  			<div class="iflex"> 
	                  				<input type="text" name="Recurrence[{{$recurrence}}][CustomDates]" id="CustomDates_{{$recurrence}}" class="form-control customdatefield" value="{{old('Recurrence.'.$recurrence.'.CustomDates',$selecteddays[0])}}"> 
	                  			</div>
	                  		</div>
		                  	<p id="custom_dates_error_{{$recurrence}}" class="error"></p>
	                  	</div>
	                  	<div class="col-md-6" >
	                  		<p>Select Goods</p>
	                  		<div class="form-group ">
	                  			<div class="iflex">
	                  				<select class="form-control multiselectopt" mid="<?=$i?>" multiple="multiple" name="Goods[{{$recurrence}}][SelectedGoods][]" id="SelectedGoods{{$recurrence}}">
								             <?php foreach($meds as $service){ ?>
														<option value="<?php echo $service['GoodsId']; ?>" 
															@if (old('Goods.'.$recurrence.'.SelectedGoods')){{ (in_array($service['GoodsId'], old('Goods.'.$recurrence.'.SelectedGoods')) ? " selected":"") }}
															@else {{in_array($service['GoodsId'],$goods) ? ' selected': ''}}
															@endif 
															>
															<?php echo $service['GoodsName']; ?></option>
								             <?php } ?>
													</select>
	                  			</div>
	                  		</div>
		                  	<p id="goods_error_{{$recurrence}}" class="error"></p>
	                  	</div>
	                  	<button type="button" recid="{{$recurrence}}" class="close CloseRecurrence" aria-label="Close" style="position: absolute;right: 10px;">
	                  		<span aria-hidden="true">&times;</span>	                  		
	                  	</button>

	                  	<div class="col-md-12 RecurrenceTypeDAYS{{$recurrence}}" style='<?php if(old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid) == 3 || old('Recurrence.'.$recurrence.'.RecurrenceTypeID',$rectypeid)==4 || old('Recurrence.'.$recurrence.'.RecurrenceTypeID', $rectypeid)==5 || old('Recurrence.'.$recurrence.'.RecurrenceTypeID', $rectypeid)==6 || old('Recurrence.'.$recurrence.'.RecurrenceTypeID', $rectypeid)==7) echo"display:visible"; else echo "display:none" ?>'>     
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.1') == 1 )?' active' : ' '}} 
	                  			@else {{in_array(1,$selecteddays) ? ' active':'' }} 
	                  			@endif " >
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][1]" id= "RDay1_{{$recurrence}}" value="1"

	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.1') == 1 )?' checked' : ' '}} 
		                  			@else {{in_array(1,$selecteddays) ? ' checked':'' }} 
		                  			@endif >Mon 
	                  			</label>
	                  		</div>
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light 
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.2') == 2 )?' active' : ' '}} 
	                  			@else {{in_array(2,$selecteddays) ? ' active':'' }} 
	                  			@endif ">
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][2]" 
	                  				id="RDay2_{{$recurrence}}" value="2" 
	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.2') == 2 )?' checked' : ' '}} 
		                  			@else {{in_array(2,$selecteddays) ? ' checked':'' }} 
		                  			@endif >Tue 
	                  			</label>
	                  		</div>
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light 
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.3') == 3 )?' active' : ' '}} 
	                  			@else {{in_array(3,$selecteddays) ? ' active':'' }} 
	                  			@endif ">
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][3]" 
	                  				id="RDay3_{{$recurrence}}" value="3" 
	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.3') == 3 )?' checked' : ' '}} 
		                  			@else {{in_array(3,$selecteddays) ? ' checked':'' }} 
		                  			@endif>Wed 
	                  			</label>
	                  		</div>
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light 
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.4') == 4 )?' active' : ' '}} 
	                  			@else {{in_array(4,$selecteddays) ? ' active':'' }} 
	                  			@endif ">
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][4]" 
	                  				id="RDay4_{{$recurrence}}" value="4" 
	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.4') == 4 )?' checked' : ' '}} 
		                  			@else {{in_array(4,$selecteddays) ? ' checked':'' }} 
		                  			@endif>Thu 
	                  			</label>
	                  		</div>
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light 
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.5') == 5 )?' active' : ' '}} 
	                  			@else {{in_array(5,$selecteddays) ? ' active':'' }} 
	                  			@endif ">
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][5]" 
	                  				id="RDay5_{{$recurrence}}" value="5" 
	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.5') == 5 )?' checked' : ' '}} 
		                  			@else {{in_array(5,$selecteddays) ? ' checked':'' }} 
		                  			@endif>Fri 
	                  			</label>
	                  		</div>
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light 
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.6') == 6 )?' active' : ' '}} 
	                  			@else {{in_array(6,$selecteddays) ? ' active':'' }} 
	                  			@endif ">
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][6]" value="6" 
	                  				id="RDay6_{{$recurrence}}" 
	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.6') == 6 )?' checked' : ' '}} 
		                  			@else {{in_array(6,$selecteddays) ? ' checked':'' }} 
		                  			@endif>Sat 
	                  			</label>
	                  		</div>
	                  		<div class="btn-group" data-toggle="buttons">
	                  			<label class="btn btn-primary waves-effect waves-light 
	                  			@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.7') == 7 )?' active' : ' '}} 
	                  			@else {{in_array(7,$selecteddays) ? ' active':'' }} 
	                  			@endif ">
	                  				<input type="checkbox" class="R_days" name="Recurrence[{{$recurrence}}][RDay][7]" value="7" 
	                  				id="RDay7_{{$recurrence}}" 
	                  				@if (is_array(old('Recurrence.'.$recurrence.'.RDay'))){{(old('Recurrence.'.$recurrence.'.RDay.7') == 7 )?' checked' : ' '}} 
		                  			@else {{in_array(7,$selecteddays) ? ' checked':'' }} 
		                  			@endif>Sun 
	                  			</label>
	                  		</div>
	                  		<p id="weekday_validation_{{$recurrence}}" class="error"></p>
	                  	</div>
	                  </div>

			                  @if($errors->AddSchedule->has('Recurrence.'.$recurrence.'.RecurrenceTypeID'))
			                    <p class="text-danger">{{$errors->AddSchedule->first('Recurrence.'.$recurrence.'.RecurrenceTypeID')}}</p>
			                  @endif   
			                  @if($errors->AddSchedule->has('Recurrence.'.$recurrence.'.RDay'))
			                    <p class="text-danger">{{"Atleast One Weekday Is Required"}}</p>
			                  @endif  
			                  @if($errors->AddSchedule->has('Goods.'.$recurrence.'.SelectedGoods'))
			                    <p class="text-danger">{{$errors->AddSchedule->first('Goods.'.$recurrence.'.SelectedGoods')}}</p>
			                  @endif
			                  @if($errors->AddSchedule->has('Recurrence.'.$recurrence.'.CustomDates'))
			                    <p class="text-danger">{{'Select Date for Custom Dates'}}</p>
			                  @endif

	                 	 		 <?php 
						             $goods = [];  
						            }
						        	}
						        }
						      ?>	  
									<input type="hidden" id="recurrececount" value="{{$recurrence}}"/>
							    </div>
									<div class="row">
									<!-- Grid column -->
										<div class="col-md-5 mt-4">					
											<div class="btn-group" data-toggle="buttons">
												<label class="btn btn-primary waves-effect waves-light <?php if((old('EndDate') !== null) || ($data[0]['scheduler']['EndDate'] !== null)) echo ''; else echo ' active' ?> ">
													<input type="radio" name="CheckComplianceFlag" value="1" id="ongo"  onchange="ONENDShowHide(this);">Ongoing
												</label>
												<label class="btn btn-primary waves-effect waves-light <?php if((old('EndDate') !== null) || ($data[0]['scheduler']['EndDate'] !== null)) echo ' active'; else echo '' ?>">
													<input type="radio" name="CheckComplianceFlag" value="0" id="enddradio"  onchange="ONENDShowHide(this);" >End Date
												</label>
											</div>
										</div>
										<div class="col-md-3 mt-4" id="ENDD" style='<?php if((old('EndDate') !== null) || ($data[0]['scheduler']['EndDate'] !== null)) echo"display:visible"; else echo "display:none" ?>';>
											<p class="p_bold">Visit End Date</p>
											<div class="form-group">
												<div class="iflex">
											    <input type="date" id="end_date" name="EndDate" class="date" value="{{old('EndDate', $data[0]['scheduler']['EndDate'])}}" />
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
													<input placeholder="Start Time" name="StartTime" type="text" class="form-control timepicker scheduletimepicker" style="height:30px;" required="required" autocomplete="off" value="{{ date('h:iA', strtotime($data[0]['scheduler']['StartTime'])) }}">
													<span class="colorred"></span> 
												</div>
											</div>
										</div>
					
										<div class="col-md-5 mt-5">
											<p>Target End Time</p>
				   
											<div class="form-group">
												<div class="iflex">
													<input placeholder="End Time" name="EndTime" type="text" class="form-control timepicker scheduletimepicker" style="height:30px;" required="required" autocomplete="off" value="{{ date('h:iA', strtotime($data[0]['scheduler']['EndTime'])) }}">
													 <span class="colorred"></span> 
												</div>
											</div>
										</div>
									</div>
									<div class="row">
									<!-- Grid column -->										
										<div class="col-md-5">
											<div class="md-form md-outline">
												<input type="text" id="Amount" name="Amount" class="form-control" value="{{$data[0]['scheduler']['Amount']}}" required="required">
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
												<input type="text" id="Notes" name="Notes" class="form-control" value="{{$data[0]['scheduler']['OrderNote']}}" required="required">
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
												<input type="text" id="EmployeeCode" name="EmployeeCode" class="form-control" value="{{old('EmployeeCode',$data[0]['scheduler']['EmployeeNumber'])}}" required="required">
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
												<?php echo '<label class="badge btn btn-primary waves-effect waves-light '; ?> {{ (is_array($data[0]['scheduler']['Tags']) && in_array($tags[$i]['TagId'], $data[0]['scheduler']['Tags'])) ? ' active' : '' }}
													<?php echo'" style="color:#fff;background-color:'.$tags[$i]['TagColor'].'!important;">

													<input type="checkbox" class="ColorTag" name="SelectedTags[]" value="'.$tags[$i]['TagId'].'"';?> 
													{{ (is_array($data[0]['scheduler']['Tags']) && in_array($tags[$i]['TagId'],$data[0]['scheduler']['Tags'])) ? ' checked' : '' }} 
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
										<input type="submit" id="createOrder" name="submit" class="btn btn-primary waves-effect waves-light" value="Save Schedule" >
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
