// <----------------Phone Number Pattern-------------->

    $('input[type=tel]').keypress(function(){
      $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3'))
    });
    
// <----------------Phone Number Pattern-------------->

   $(document).ready(function () {
  $(".nav.nav-tabs li a[href*=admin]").addClass("active");
    $('.mdb-select').materialSelect();
    $('#medication_table').DataTable();
    $('#forEditTagDesc').addClass('active');
    $('#forEditPharmacyAdd').addClass('active');    
    });

// <----------------Delete Medication-------------->

$(document).on('click','#deleteGoods',function(){
    var element = $(this);
    var del_id = element.attr("data-id");
    var name = element.attr("data-name");
    var id = del_id;
    var $tr = $(this).parents("tr");
 
    Swal.fire({
      title: "Are you sure?",
      text: "Do you want to delete the record of "+name,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel plx!",
      preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
      {
         $.ajax({
          url:"/administration/goods/delete_goods/"+id,
          data:'',
          type:'get',
          success:function($result)
          {
            $tr.animate({ backgroundColor: "#003" }, "slow").animate({ opacity: "hide" }, "slow");
            Swal.fire('Deleted!','Record has been deleted.','success');

          }
        }).fail(function (jqXHR, textStatus, error) {
          Swal.fire('Operation Denied!','Item Is In Use for Orders','fail');
        });

      }else{
        return false;
      }
    });
  });

// <----------------Delete Medication Ends-------------->

// <----------------Medication Type-------------->

$(document).on('click','#deleteGoodstype',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;
      var $tr = $(this).parents("tr");

      Swal.fire({
        title: "Do you want to delete the record of "+name+"?",
        text: "All the Medicines listed under '"+name+"' will also be deleted",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
        }).then((result) => {
        if (result.isConfirmed) 
        {
         $.ajax({
            url:"/administration/goods/delete_goodstype/"+id,
            data:'',
            type:'get',
            success:function($result)
            {
              $tr.animate({ backgroundColor: "#003" }, "slow").animate({ opacity: "hide" }, "slow");
              Swal.fire('Deleted!','Record has been deleted.','success');

            }
          }).fail(function (jqXHR, textStatus, error) {
            Swal.fire('Operation Denied!','Related Goods Are In Use for Orders','fail');
          });
        }else{
          return false;
        }
      });
    });

   $('body').on('click', '#edit_goodstype', function (event)
   {
      event.preventDefault();
      var id = $(this).data('id');
      console.log(id);   
      $.get('/administration/goods/edit_goodstype/' + id, function (data) {        
         $('#edit_goodstype_modal').modal('show');
         $('#EditGoodsTypeId').val(data.data.GoodsTypeId);
         $('#EditGoodsTypeName').val(data.data.GoodsTypeName);
       })
  });

// <----------------Medication Type Ends-------------->


// <----------------Delete Tag-------------->

$(document).on('click','#deletetag',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the record of "+name+" tag?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
      {
          $.ajax({
            url:"/administration/tags/delete_tag/"+id,
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


   $('body').on('click', '#edit_tag', function (event)
   {
      event.preventDefault();
      var id = $(this).data('id');
      $.get('/administration/tags/edit_tag/'+ id, function (data) {        
         $('#edit_tag_modal').modal('show');
         $('#EditTagId').val(data.data.TagId);
         $('#EditTagName').val(data.data.TagName);
         $('#EditTagColor').val(data.data.TagColor);
         $('#EditTagDescription').val(data.data.TagDescription);
       })
  });

// <----------------Delete Tag Ends-------------->

// <----------------Admin User-------------->

  $(document).on('click','#deleteadmin',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the record of "+name+" ?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
          $.ajax({
            url:"/administration/users/delete_user/"+id,
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


    $('body').on('click', '#edit_admin', function (event)
   {
      event.preventDefault();
      var id = $(this).data('id');
      $.get('/administration/users/edit_user/'+ id, function (data) {        
         $('#edit_admin_modal').modal('show');
         $('#EditId').val(id);
         $('#EditAdminFirstName').val(data.data.FirstName);
         $('#EditAdminLastName').val(data.data.LastName); 
         $('#EditAdminEmail').val(data.data.Email);
         $('#EditAdminPhoneNumber').val(data.data.PhoneNumber);
       })
  });
// <----------------Admin User Ends-------------->

// <----------------Driver User-------------->

  $(document).on('click','#deletedriver',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the record of "+name+" ?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
          $.ajax({
            url:"/administration/users/delete_user/"+id,
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


    $('body').on('click', '#edit_driver', function (event)
   {
      event.preventDefault();
      var id = $(this).data('id');
      $.get('/administration/users/edit_user/'+ id, function (data) {        
         $('#edit_driver_modal').modal('show');
         $('#EditDriverId').val(id);
         $('#EditDriverFirstName').val(data.data.FirstName);
         $('#EditDriverLastName').val(data.data.LastName); 
         $('#EditDriverEmail').val(data.data.Email);
         $('#EditDriverPhoneNumber').val(data.data.PhoneNumber);
       })
  });
// <----------------Driver User Ends-------------->


// <----------------Pharmacist User-------------->

  $(document).on('click','#deletepharmacist',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the record of "+name+" ?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
          $.ajax({
            url:"/administration/users/delete_user/"+id,
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


    $('body').on('click', '#edit_pharmacist', function (event)
   {
      event.preventDefault();
      var id = $(this).data('id');
      $.get('/administration/users/edit_user/'+ id, function (data) {  
         $('#edit_pharmacist_modal').modal('show');
         $('#EditPharmacistId').val(id);
         $('#EditPharmacyId').val(data.pharmacyuser[0].PharmacyId);
         $('#EditPharmacistFirstName').val(data.data.FirstName);
         $('#EditPharmacistLastName').val(data.data.LastName); 
         $('#EditPharmacistEmail').val(data.data.Email);
         $('#EditPharmacistPhoneNumber').val(data.data.PhoneNumber);        
       })
  });
// <----------------Pharmacist User Ends-------------->

// <----------------Pharmacy-------------->

$(document).on('click','#deletepharmacy',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the record of "+name+" ?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
      {
          $.ajax({
            url:"/administration/pharmacies/delete_pharmacy/"+id,
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

   $('body').on('click', '#edit_pharmacy', function (event)
   {
      event.preventDefault();
      var id = $(this).data('id');
      $.get('/administration/pharmacies/edit_pharmacy/'+ id, function (data) {        
         $('#edit_pharmacy_modal').modal('show');
         $('#EditPharmacyId').val(data.data.PharmacyId);
         $('#EditPharmacyName').val(data.data.PharmacyName);
         $('#EditPharmacyAddress').val(data.data.PharmacyAddress);
         $('#EditPharmacyManager').val(data.data.PharmacyManager);
         $('#EditPharmacyPhone').val(data.data.PharmacyPhone);
       })
  });

// <----------------Pharmacy Ends-------------->