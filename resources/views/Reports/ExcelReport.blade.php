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