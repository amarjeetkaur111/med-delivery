// <----------------Phone Number Pattern-------------->

    $('input[type=tel]').keyup(function(){
      $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3'))
    });
    
// <----------------Phone Number Pattern--------------> 

// <+++++++++++++++ Assign to Driver +++++++++++++++++++>

 $(document).ready(function () {
		$(".driver-section").hide();
    $('.mdb-select').materialSelect();
	  $('#assigned_to_driver_table').DataTable();
  });

 $("#driver").on('change',function(){
  if (this.value > 1)
  {
    var id = $("#driver option:selected").val();   
   	// alert(id);
    $.ajax({
      url:"/orders/batched_orders/send_to_delivery/select_driver/"+id,
      data:'',
      type:'get',
      datatype:'JSON',
      success:function($result)
      {
      	// alert($result[0]['FirstName']);
    		$('#driver_name').val($result[0]['FirstName']+' '+$result[0]['LastName']);
    		$('#forDriverName').addClass('active');
        $('#driver_mobile').val($result[0]['PhoneNumber']);
        $('#driver_email').val($result[0]['Email']);
	      }
    });
    $("#driver_detail").show();
		$(".driver-section").show();
  }
  else
  {
    $("#driver_detail").hide();
  }
});

// <+++++++++++++++ Assign to Driver Ends+++++++++++++++++++>

// <+++++++++++++++ Unassign Batch +++++++++++++++++++>

 $(document).on('click','#unassignbatch',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var delid = element.attr("id");
      var name = element.attr("data-name");
      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to Unassign Batch No."+name+ "?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
          $.ajax({
              url:"/orders/batched_orders/unassign_batch/"+del_id,
              data:'',
              type:'get',
              success:function($result)
              {
                Swal.fire('Deleted!','Record has been deleted.','success');
              }
            });
             $(this).parents("tr").animate({ backgroundColor: "#003" }, "slow")
             .animate({ opacity: "hide" }, "slow");
            $(this).parents("tr").nextUntil("tr.mainrow").animate({ backgroundColor: "#003" }, "slow")
             .animate({ opacity: "hide" }, "slow");
          }else{
              return false;
          } 
      });      
    });

// <+++++++++++++++ Unassign Batch Ends+++++++++++++++++++>
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

// <!------------------ Schedule Tooltip Ends----------------->