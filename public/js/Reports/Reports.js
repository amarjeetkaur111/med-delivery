

$(document).ready(function() {
	$('input[name="dates"]').daterangepicker({
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
		"alwaysShowCalendars": true,
	}, function(start, end, label) {
	  console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
	});

	});

	$('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {

	 $('#date_r').html(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	 $('#startdate').val(picker.startDate.format('YYYY-MM-DD'));
	 $('#enddate').val(picker.endDate.format('YYYY-MM-DD'));
	 // alert($(this).val(picker.startDate.format('MM/DD/YYYY')));
});

// $(document).ready(function () {
//   $('.mdb-select').materialSelect();
//   $('#reports_table').DataTable();
// });	

$(document).ready(function() {
	$.ajax({
      url:'/reports/get_filteration_data',
      type:"GET",
      data:'',
      success: function(response){
         for(var i in response['Pharmacy']) {
            if(response['Pharmacy'][i]['PharmacyId'] == pharmacyid)
            {
              $('#Pharmacy').append( '<option value="'+ response['Pharmacy'][i]['PharmacyId'] +'" selected>'+ response['Pharmacy'][i]['PharmacyName'] +'</option>' );
	          }else
	          {
	              $('#Pharmacy').append( '<option value="'+ response['Pharmacy'][i]['PharmacyId'] +'">'+ response['Pharmacy'][i]['PharmacyName'] +'</option>' );
	          }
        	} 

        for(var i in response['Driver']) {
	        if(response['Driver'][i]['Id'] == driverid)
	        {
	        $('#Driver').append( '<option value="'+ response['Driver'][i]['Id'] +'" selected>'+ response['Driver'][i]['FirstName']+' '+response['Driver'][i]['LastName'] +'</option>' );
	        }
	        else{
	        $('#Driver').append( '<option value="'+ response['Driver'][i]['Id'] +'">'+ response['Driver'][i]['FirstName']+' '+response['Driver'][i]['LastName'] +'</option>' );
	        }        
      	}     
      },   
    
    });  
});
