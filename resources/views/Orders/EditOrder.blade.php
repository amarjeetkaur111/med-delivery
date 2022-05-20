
@include('Includes.Header')	
  <!-- Main Navigation -->
  <!-- Main layout -->
  <main>
  <div class="edit-customer edit-order">
	<!-- Nav tabs -->
	<div class="row">
		<div class="col-lg-12 col-md-12 mb-lg-0 mt-1">
			<ul class="nav nav-tabs md-tabs nav-justified primary-color" id="myTab" role="tablist">
	      <li class="nav-item">
	        <a class="nav-link active" id="schedule-tab" data-toggle="tab" href="#schedule" role="tab" aria-controls="schedule" aria-selected="true">Scheduling </a>
	      </li>
	    </ul>
		</div>
	</div>
    
   <!-- Nav tabs Ends -->
   <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="schedule" role="tabpanel" aria-labelledby="schedule-tab">
				@include('Orders.Tabs.EditSchedule')      	
			</div>      
		</div>
    <!-- Tab Panes Ends -->
  </div>
	</main>
	
@include('Includes.Footer')
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js')}}"></script>
<link href="{{ asset('css/datepicker/bootstrap-datepicker.min.css') }}"/>
<script type="text/javascript" src="{{ asset('js/multiselect/bootstrap-multiselect.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('css/multiselect/bootstrap-multiselect.min.css') }}" type="text/css"/>
<script src="{{asset('js/Orders/Orders.js')}}" ></script>
		
<script>
$('.multiselectopt').multiselect({
        includeSelectAllOption: true,
        selectAllValue: 'select-all-value',
				maxHeight: 400,
  });

	// Time Picker Initialization
	var timeToSend = $('.scheduletimepicker').pickatime({
    twelvehour: true,
  	minutestep: 5,
	  afterShow: function() {  
        var picker = timeToSend.data().clockpicker;
		console.log(picker)       ;
            if( picker.amOrPm == "AM") {
				$('.am-button').addClass( "active" );
       			 $('.pm-button').removeClass( "active" );
            } 
        
    	}
    
  });
    
</script>
