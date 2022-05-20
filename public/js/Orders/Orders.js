
// <!------------------ Visit Page ----------------->

 $(document).ready(function () {
  if($(".forcount:disabled").length < $('.forcount').length)
  {
       $('#autobatch').css('visibility','visible');
  }
  else{
       $('#autobatch').css('visibility','hidden');
  }
});

$(".forcount").click(function() {
    if($(".forcount:checked").length > 0 )
       $('#batchthis').css('visibility','visible');
     else
       $('#batchthis').css('visibility','hidden');
    // $('#batchthis').toggle( $(".form-check-input:checked").length > 0 );
  });

  $('body').on('click', '#selectAll', function () {
    var all = $('#orders_table').find('input[type="checkbox"]:not(:disabled)');
    if ($(this).hasClass('allChecked')) {
       all.prop('checked', false);
    } else {        
       all.prop('checked', true);
    }
    $(this).toggleClass('allChecked');
    // $('#batchthis').toggle( $(".form-check-input:checked").length > 0 );
    if($(".forcount:checked").length > 0 )
       $('#batchthis').css('visibility','visible');
     else
       $('#batchthis').css('visibility','hidden');
  })


// <!------------------ Visit Page Ends----------------->


// <----------------Phone Number Pattern-------------->

    $('input[type=tel]').keypress(function(){
      $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3'))
    });
    
// <----------------Phone Number Pattern--------------> 

// <----------------Delete Order-------------->

    $(document).on('click','.deleteOrder',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var delid = element.attr("id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete "+name+" Order No."+delid+ "?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
              $.ajax({
                  url:"/orders/delete_schedule/"+id,
                  data:'',
                  type:'get',
                  success:function($result)
                  {
                    Swal.fire('Deleted!','Record has been deleted.','success');
                  }
                });
                $(this).parents("tr").animate({ backgroundColor: "#003" }, "slow")
                 .animate({ opacity: "hide" }, "slow");
          }else{
              return false;
          } 
      });      
    });

// <--------------------Delete Order Ends--------------------->


// <+++++++++++++++++=+End Customer Summary Page +++++++++++++++++++++>

$('#existing_customer').change(function() {
    var id = $("#existing_customer option:selected").val();   
    var phone='';
    if($('#multiple_phone').length){$('#multiple_phone').remove();}
    if($('#innersubmitbutton').length){$('#innersubmitbutton').remove();}  
    $.ajax({
      url:"/orders/select_customer/"+id,
      data:'',
      type:'get',
      datatype:'JSON',
      success:function($result)
      {
        // phone = JSON.parse($result.PhoneNumbers);
        // var address = JSON.parse($result.customers_address.AddressInfo);
        $('#f').val($result[0]['CustomerId']);
        $('#f1').val($result[0]['FirstName']);
        $('#forF1').addClass('active');
        $('#f2').val($result[0]['LastName']);
        $('#f3').val($result[0]['PhoneNumbers'][0].PhoneNumber);
        $('#PrimaryPhone').val($result[0]['PhoneNumbers'][0].PhoneTypeId);
        $('#f4').val($result[0]['AddressLine']);
        $('#f5').val($result[0]['UnitNumber']);
        $('#f6').val($result[0]['City']);
        $('#f7').val($result[0]['Province']);
        $('#f8').val($result[0]['PostalCode']);
        $('#f9').val($result[0]['Country']);
        $('#AddressPhone').val($result[0]['PhoneTypeId']);
        $('#f10').val($result[0]['Extension']);
        $('#f11').val($result[0]['PhoneNumber']);
        $('#f12').val($result[0]['AdditionalInfo']);
        $('#f13').val($result[0]['DoorSecurityCode']);

        var button_content="";

        button_content+='<div id="innersubmitbutton">';        
        button_content+='<a href="/orders/add_schedule/'+$result[0]['CustomerId']+'" class="btn btn-primary waves-effect waves-light">Next</a>';
        button_content+='</div>'
        $('#submitbutton').append(button_content);

        var html_content="";        
        if($result[0]['PhoneNumbers'].length>1)
        {
        for (var i = 1; i < $result[0]['PhoneNumbers'].length; i++) 
        {
          html_content+='<div class="alternative-phone-div" id="multiple_phone">';
          html_content+='<div class="row">';
          html_content+='<div class="col-md-5 col-sm-10">';
          html_content+='<div class="md-form md-outline form-control-sm">';

          html_content+='<i class="fas fa-phone-alt prefix" style="margin-left: -10px;"></i>';
          html_content+='<select class="form-control" id="Phone['+i+'][PhoneTypeId]" name="Phone['+i+'][PhoneTypeId]"  style="margin-left:25px;width:95%;line-height: 1.5;border-radius: .2rem;height: calc(1.5em + .5rem + 2px);" disabled>';
          html_content+='<option value="">Select Phone Type</option>';
          html_content+='<option value="1" '+($result[0]['PhoneNumbers'][i].PhoneTypeId == 1?'selected':'')+'>Home</option>';
          html_content+='<option value="2" '+($result[0]['PhoneNumbers'][i].PhoneTypeId == 2?'selected':'')+'>Mobile</option>';
          html_content+='<option value="3" '+($result[0]['PhoneNumbers'][i].PhoneTypeId == 3?'selected':'')+'>Work</option>';
          html_content+='<option value="4" '+($result[0]['PhoneNumbers'][i].PhoneTypeId == 4?'selected':'')+'>Fax</option>';
          html_content+='</select>';  
          html_content+='</div>';
          html_content+='</div>';
          html_content+='<div class="col-md-5 col-sm-9">';
          html_content+='<div class="md-form md-outline">';
          html_content+='<input id="altnum'+i+'" type="tel" class="form-control" name="Phone['+i+'][PhoneNumber]" maxlength="12" autofocus value="'+$result[0]['PhoneNumbers'][i].PhoneNumber+'" readonly>';
          html_content+='<label for="altnum'+i+'" class="active">Alternative Number '+i+'</label>';
          html_content+='</div>';
          html_content+='</div>';        
          html_content+='</div>';
          html_content+='</div>';
          $("#multiple_phone_numbers_section").append(html_content);         
         
          }  
        }    
      }
    });
});

// <+++++++++++++++++=+End Customer Summary Page +++++++++++++++++++++>

// <+++++++++++++++++ Orders Page ++++++++++++++++++++++++++>

 // Material Select Initialization
    $(document).ready(function () {
      $('.mdb-select').materialSelect();
  	  $('#orders_table').DataTable({
        // "order": [[ 0, "asc" ]]
        "pageLength": 50
        });
      $(".nav.nav-tabs li a[href*=schedule]").addClass("active");
    });

  // <+++++++++++++++++=+End Orders Page +++++++++++++++++++++>

  // <++++++++++++++++++++++Add Order Page ++++++++++++++++++++++>

   $('input[type=radio][name=group1]').change(function() {
          if (this.value == '1') {
            $("#existing_customer_div").hide();
            $("#new_customer_div").show();
            $("#customer-details").hide();
          }else if(this.value == '2'){
            $("#existing_customer_div").show();
            $("#new_customer_div").hide();
            $("#customer-details").show();

          }
      });


var currentValue = 0;
function ONENDShowHide(myRadio) {
  //alert('Old value: ' + currentValue); alert('New value: ' + myRadio.value);
  currentValue = myRadio.value;
  if (currentValue == 1){
          document.getElementById("ENDD").style.display = "none";
          document.getElementById("end_date").required = false;
          document.getElementById("end_date").value = '';
      } else {
          document.getElementById("ENDD").style.display = "block";
          document.getElementById("end_date").required = true;
      }
}

 function RTSShowHide() {
      // Get the select option value
      // var RTSselected = document.getElementById("RecurrenceTypeSelector").value;
      // if(RTSselected==1 || RTSselected==2 || RTSselected==4)
      // {
       //  $(".R_days").prop('checked', false); 
      // }
      //alert(RTSselected);   
        
      // If the checkbox is checked, display the output text
      //  if (RTSselected == 1 || RTSselected==2){
        //   document.getElementById("RecurrenceIntervalSelector").style.display = "none";
      // } else {
        //   document.getElementById("RecurrenceIntervalSelector").style.display = "block";
      // } 
  }

$("body").on("change", ".RecurrenceTypeID", function(){
  var getParant = $(this).parent().parent().parent().parent().attr('id');
  
  var get_value = $(this).val();
  
  $('.hide_msg').show();
  $('.recurrenceinfo').show();
  
  if(get_value == 1){
    
  }
  if(get_value == 2){ 

  }
  
  if(get_value == 3 || get_value == 4 || get_value == 5 || get_value == 6 || get_value == 7){
    
    if(get_value == 3 ){
    }
    if(get_value == 5 ){
    }
    if(get_value == 6 ){

    }    
    $('.RecurrenceTypeDAYS'+getParant).show();
    $('.MultipleDates'+getParant).hide();
  }
  else if(get_value == 8){

    $('.MultipleDates'+getParant).show();
    $('.RecurrenceTypeDAYS'+getParant).hide();
    $('.customdate').val('');
    
  }
  else{
    $('.RecurrenceTypeDAYS'+getParant).hide();
    $('.MultipleDates'+getParant).hide();
  }
});
  

$('body').on('focus',".customdatefield", function(){
    
    function daysInMonth (month, year) {
      return new Date(year, month, 0).getDate()+1;
    }

    var today = new Date();
    var startDate = new Date(today.getFullYear(), today.getMonth(), 1);
    var endDate = new Date(today.getFullYear(), today.getMonth(), daysInMonth(today.getMonth(),today.getFullYear()));

      $(this).datepicker({
        defaultViewDate: {
          year: today.getFullYear(),
          month: 1
        },
        format: 'dd',
        multidate: true,
        startDate: startDate,
        endDate: endDate,
        clearBtn:true,
        minViewMode:0
      });
  });

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

  $('.add_recurrence').click(function(){
  var addC = parseInt($('#recurrececount').val()) + 1;
  get_allGoods(addC);
  
  var addelemt = '<div class="row" id="'+addC+'" style="border:1px solid gray; padding:5px;border-radius:5px;margin:5px;position:relative;"> <div class="col-md-3" > <p>#'+addC+' Recurrence Type</p><div class="form-group"><div class="iflex"> <select class="form-control form-control-sm RecurrenceTypeID" name="Recurrence['+addC+'][RecurrenceTypeID]" id="RecurrenceTypeSelector'+addC+'" onchange="RTSShowHide();" ><option value="">Select Recurrence Type</option><option value="1">One Time</option> <option value="2" >Daily</option> <option value="3">Weekly</option><option value="4">1st Week</option> <option value="5" >2nd Week</option> <option value="6">3rd Week</option> <option value="7">4th Week</option> <option value="8">Custom Dates</option> </select></div></div><p id="recurrence_type_error_'+addC+'" class="error"></p></div><div class="col-md-3 MultipleDates'+addC+'" style="display:none;"><p>Select Multiple Date</p><div class="form-group"> <div class="iflex"> <input type="text" name="Recurrence['+addC+'][CustomDates]" class="form-control customdatefield" id="CustomDates_'+addC+'"></div></div><p id="custom_dates_error_'+addC+'" class="error"></p></div><div class="col-md-6" ><p>Select Goods</p><div class="form-group "><div class="iflex"><select class="form-control multiselectopt_'+addC+'" multiple="multiple" name="Goods['+addC+'][SelectedGoods][]" id="SelectedGoods'+addC+'"  ></select></div></div><p id="goods_error_'+addC+'" class="error"></p></div><button type="button" recid="'+addC+'" class="close CloseRecurrence" aria-label="Close" style="position: absolute;right: 10px;"><span aria-hidden="true">&times;</span></button><div class="col-md-12 RecurrenceTypeDAYS'+addC+'" style="display:none;"><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light" ><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][1]" id="RDay1_'+addC+'" value="1">Mon </label></div><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light"><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][2]" id="RDay2_'+addC+'" value="2" >Tue </label></div><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light"><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][3]" id="RDay3_'+addC+'" value="3">Wed </label></div><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light "><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][4]" id="RDay4_'+addC+'" value="4" >Thu </label></div><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light "><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][5]" id="RDay5_'+addC+'" value="5" >Fri </label></div><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light"><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][6]" value="6" id="RDay6_'+addC+'" >Sat </label></div><div class="btn-group" data-toggle="buttons"><label class="btn btn-primary waves-effect waves-light "><input type="checkbox" class="R_days" name="Recurrence['+addC+'][RDay][7]" value="7" id="RDay7_'+addC+'" >Sun </label></div><p id="weekday_validation_'+addC+'" class="error"></p></div></div>';
  
  $('.more_recurrence').append(addelemt);
  $('#recurrececount').val(addC);
  $('.multiselectopt_'+addC).multiselect({
            includeSelectAllOption: true,
            maxHeight: 400,
        });

  var gm = parseInt(addC) - 1;
  // $('html,body').animate({scrollTop: $("#"+gm).offset().top},'slow');
  
  
      
  return false;
});

$('body').on('click',".CloseRecurrence", function(){
  var crecId = $(this).attr('recid');

  Swal.fire({
        title: "Are you sure?",
        text: "You want to remove this Recurrece Type!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
      {
        $('#'+crecId).remove();
      }
    });   
});

$(document).ready(function(){
  $("#createOrder").click(function(event)
  {
    var selectedRecurranceType = $('#RecurrenceTypeSelector').val();

    if($('.more_recurrence > div').is(':visible')) 
    {
      $('#recurrence_validation').text('');
    }
    else
    {
      event.preventDefault();
      $('#recurrence_validation').text('Select Atleast One Recurrence'); 
      $("html, body").animate({ scrollTop: 50 }, "slow");  
    }  

    if($('#end_date').val()=='' && $("#end_date").is(':visible'))
    {
      event.preventDefault();
      $('#ENDDate_validation').text('Select END Date');  
      $("html, body").animate({ scrollTop: 300 }, "slow");  
    }
    else
    {
      $('#ENDDate_validation').text('');
    } 

    if($("#Amount").val()=='')
    {
      event.preventDefault();
      $("#amount_error").text('Amount Is Required');
    }
    else
    {
      $("#amount_error").text('');
    }

    if($("#Notes").val()=='')
    {
      event.preventDefault();
      $("#notes_error").text('Note Is Required');
    }
    else
    {
      $("#notes_error").text('');
    }

    if($("#EmployeeCode").val()=='')
    {
      event.preventDefault();
      $("#empcode_error").text('Employee Code Is Required');
    }
    else
    {
      $("#empcode_error").text('');
    }    

    var ReccurenceCount=$("#recurrececount").val(); 
    for(i=1;i<=ReccurenceCount;i++)
    {
      if($("#RecurrenceTypeSelector"+i).val()==3 || $("#RecurrenceTypeSelector"+i).val()==4 || $("#RecurrenceTypeSelector"+i).val()==5 || $("#RecurrenceTypeSelector"+i).val()==6 || $("#RecurrenceTypeSelector"+i).val()==7)
      {
        if(($("#RDay1_"+i).is(':checked')) || ($("#RDay2_"+i).is(':checked')) || ($("#RDay3_"+i).is(':checked')) || ($("#RDay4_"+i).is(':checked')) ||($("#RDay5_"+i).is(':checked')) || ($("#RDay6_"+i).is(':checked')) || ($("#RDay7_"+i).is(':checked')))
        {
          $("#weekday_validation_"+i).text('');
          isValid = true;    
        } 
        else{
          event.preventDefault();
          $("#weekday_validation_"+i).text('Select Atleast One Week Day');
          $("html, body").animate({ scrollTop: 150 }, "slow");  
          isValid = false;
        }        
      }

      if($("#RecurrenceTypeSelector"+i).val()=='')
      {
        event.preventDefault();
        $("#recurrence_type_error_"+i).text('Select Recurrence Type');
        $("html, body").animate({ scrollTop: 150 }, "slow");
        isValid = false;
      }
      else
      {
        $("#recurrence_type_error_"+i).text('');
        isValid = true;
      }

      if($("#SelectedGoods"+i).val()=='')
      {
        event.preventDefault();
        $("#goods_error_"+i).text('Select Atleast One Goods');
        $("html, body").animate({ scrollTop: 150 }, "slow");
        isValid = false;
      } 
      else
      {
        $("#goods_error_"+i).text('');
        isValid = true;
      }


      if($("#CustomDates_"+i).val()=='' && $("#CustomDates_"+i).is(':visible'))
      {
        event.preventDefault();
        $("#custom_dates_error_"+i).text('Select Atleast One Date');
        $("html, body").animate({ scrollTop: 150 }, "slow");
        isValid = false;
      } 
      else
      {
        $("#custom_dates_error_"+i).text('');
        isValid = true;
      }
    }       
  });

});

// <--------------------Barcode Print--------------------->

// function printbarcode(id)
// {
//   w=window.open();
//   w.document.write(document.getElementsByClassName('print-section-'+id)[0].innerHTML);
//   w.print();
//   w.close();
// }
// <!------------------ Barcode Print Ends----------------->

// <!------------------ Amount Autofill Ends----------------->
  // $(window).scroll(function() {
  //  var hT = $('#Amount').offset().top,
  //      hH = $('#Amount').outerHeight(),
  //      wH = $(window).height(),
  //      wS = $(this).scrollTop();
  //  if (wS >= (hT+hH-wH))
  //  {

$('body').on('focus',"#Amount", function(){
      // alert('Reached Amount');
   var ReccurenceCount=$("#recurrececount").val();     
        var meds = {};
        var id = 0;

      for(i=1;i<=ReccurenceCount;i++)
      {
        $(".multiselectopt_"+i+" :selected").each(function(){
            meds['id'+id] =  $(this).val();
            id++;
        }); 
      } 
          $.ajax({
            type: "POST",
            url: "/orders/schedule/goodsamt",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {goods:meds}, 
            success: function(result){
                // alert(result);
                $("#Amount").val(result)
            }
        });
      // }
    });
// <!------------------ Amount Autofill Ends----------------->
// <!------------------ Schedule Tooltip----------------->

    $(".orderdetail_tooltip").tooltip(
      {
          // classes: {"ui-tooltip-content": "toolTipDetails"},
          // tooltipClass: "toolTipDetails",
          placement: "bottom",
          html:true,
          //trigger:"manual"
      }).on({
              mouseenter:function(){
              var id = this.id;
              //varhtml_data="";
              var $el=$(this);
              $.ajax(
              {
                  url:"/orders/schedule_details",
                  type:'post',
                  async:false,
                  dataType:"html",
                  data:{id:id},
                  headers:{
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  success:function(response){
                      console.log(response);
                      $el.attr("data-original-title",response);
                     // if($("#"+$el.attr("aria-describedby")).is(":visible")){
                          $el.tooltip("show");
                      //}
                  }
              });
          //returnhtml_data;
           },mouseleave:function(){
                  $(this).tooltip("hide");
              }
          });	


// <!------------------ Schedule Tooltip----------------->
