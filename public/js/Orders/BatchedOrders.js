 $(document).ready(function () {
  $('.mdb-select').materialSelect(); 
  $('#batched_table').DataTable();

});

    // <!------------------ Batch listing Page ----------------->

$(".forcount").click(function() {
    if($(".forcount:checked").length > 0 )
       $('#sendtodelivery').css('visibility','visible');
     else
       $('#sendtodelivery').css('visibility','hidden');
        var countCheckedCheckboxes =$(".forcount").filter(':checked').length;
        $('#count-checked-checkboxes').text(countCheckedCheckboxes);          
    });

  $('body').on('click', '#SelectAll', function () {
    var all = $('#batched_table').find('input[type="checkbox"]:not(:disabled)');
    if ($(this).hasClass('allChecked')) {
       all.prop('checked', false);
    } else {        
       all.prop('checked', true);
    }
    $(this).toggleClass('allChecked');
    var countCheckedCheckboxes =$(".forcount").filter(':checked').length;
    $('#count-checked-checkboxes').text(countCheckedCheckboxes);        
    if($(".forcount:checked").length > 0 )
       $('#sendtodelivery').css('visibility','visible');
     else
       $('#sendtodelivery').css('visibility','hidden');
  })

// <----------------Delete Batch-------------->

    $(document).on('click','#deleteBatch',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the record of "+name+ " ?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
          $.ajax({
              url:"/orders/batched_orders/delete_batch/"+id,
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

// <--------------------Delete Batch Ends--------------------->
// <--------------------Barcode Print--------------------->

function printbarcode(id)
{
  w=window.open();
  w.document.write(document.getElementsByClassName('print-section-'+id)[0].innerHTML);
  w.print();
  w.close();
}
// <!------------------ Barcode Print Ends----------------->
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

