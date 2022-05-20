<table id="reports_table" class="table table-striped" cellspacing="0" width="100%">
  	@if(isset($result['visits']))
      @foreach($result['visits'] as $key => $customerinfo)
        <thead style="color:#ffffff;background:#4285f4;">
          <tr>
			<th>Customer Info</th>
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
          <td>{{$customerinfo['FirstName']}} {{$customerinfo['LastName']}}</td>
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
											{{'----------------------------'}}<br>
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
										 	Sign:@if(isset($signpath[1]) && (false !== ($signpath = @file_get_contents("{{ URL::asset('/reports/signimage/'.$signpath[1]) }}")))) <img src="{{ URL::asset('/reports/signimage/'.$signpath[1]) }}" alt="Sign Image" style="height:8rem; width: 8rem;"> @else {{' '}} @endif<br>
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