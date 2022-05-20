// <----------------Phone Number Pattern-------------->

    $('input[type=tel]').keypress(function(){
      $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3'))
    });
    
// <----------------Phone Number Pattern-------------->

 $(document).ready(function(){
  // $('#slide-out').hide();
  $(".nav.nav-tabs li a[href*=customerinfo]").addClass("active");
  // initAutocomplete();
 });

  $('input[type=radio][name=group1]').change(function() {
      if (this.value == '1') {
        $("#existing_customer_div").hide();
      }else if(this.value == '2'){
        $("#existing_customer_div").show();
      }
  });

// <!-------Sticky Customer Name ------->

    function sticky_relocate() {
      var window_top = $(window).scrollTop();
      var div_top = $('.tag-row').offset().top;
      if (window_top > div_top) {
        $('#sticky').addClass('stick');
      } else {
        $('#sticky').removeClass('stick');
      }
    }

    $(function() {
      $(window).scroll(sticky_relocate);
      sticky_relocate();
    });

// <!-----------Sticky Customer Name Ends ------------->
// <!------Add Multiple Phone Numbers for Patient Dynamically in Add Address Form------------>
// var i=1;
  var i = parseInt($('#phonecount').val()) - 1;  


function add_multiple_phone()
{
  var html_content="";
  i++;
  html_content+='<div class="alternative-phone-div" id="multiple_phone_'+i+'">';
  html_content+='<div class="row">';
  html_content+='<div class="col-md-5 col-sm-10">';
  html_content+='<div class="md-form md-outline form-control-sm">';

  html_content+='<i class="fas fa-phone-alt prefix" style="margin-left: -10px;"></i>';
    html_content+='<select class="form-control"  name="Phone['+i+'][PhoneTypeId]"  style="margin-left:25px;width:95%;line-height: 1.5;border-radius: .2rem;height: calc(1.5em + .5rem + 2px);">';
  html_content+='<option value="">Select Phone Type</option>';
    html_content+='<option value="1" style="color: black;"  >Home</option>';
    html_content+='<option value="2" style="color: black;"  >Mobile</option>';
    html_content+='<option value="3" style="color: black;"  >Work</option>';
    html_content+='<option value="4" style="color: black;"  >Fax</option>';
    html_content+='</select>';  
  html_content+='</div>';
  html_content+='</div>';
  html_content+='<div class="col-md-5 col-sm-9">';
  html_content+='<div class="md-form md-outline">';
  html_content+='<input id="altnum'+i+'" type="tel" class="form-control altnum" name="Phone['+i+'][PhoneNumber]" maxlength="12" autofocus >';
  html_content+='<label for="altnum'+i+'" class="">Alternative Number '+i+'</label>';
  html_content+='</div>';
  html_content+='</div>';
  html_content+='<div class="col-md-1 col-sm-1">';
  html_content+='<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="delete_multiple_phone('+i+');" style="margin-top: 2rem;"><i class="fas fa-times"></i></button>';
  html_content+='</div>';
  html_content+='</div>';
  html_content+='</div>';
  $("#multiple_phone_numbers_section").append(html_content);
  // $("#multiple_phone_title").show();
  //console.log("Increment:"+i);
  $('.altnum').keypress(function(){
    var Mobile_Number=$(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/,'$1-$2-$3');
    $(this).val(Mobile_Number.substr(0,12));
  });
}
<!---------Add Multiple Phone Numbers for Patient Dynamically in Add Address Form End--------------->

<!---------Delete Multiple Phone Numbers for Patient Dynamically in Add Address Form---------------->

function delete_multiple_phone(multiple_phone_id)
{
   Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the Alternate Phone Number",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
      {
        i--;
        if(i==0)
        {
          $("#multiple_phone_title").hide();
        }
        $("#multiple_phone_"+multiple_phone_id).remove();
        //console.log(multiple_phone_id);
        //console.log("Decrement:"+i);
      } 
    }); 
}

<!---------Delete Multiple Phone Numbers for Patient Dynamically in Add Address Form End----------->

<!---------Delete Secondary Address----------->

 $(document).on('click','#deleteAddress',function(){
      var element = $(this);
      var del_id = element.attr("data-id");
      var name = element.attr("data-name");
      var id = del_id;

      Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete the Secondary Address  "+name+ "?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
      }).then((result) => {
      if (result.isConfirmed) 
        {
              $.ajax({
                  url:"/customers/delete_address/"+id,
                  data:'',
                  type:'get',
                  success:function($result)
                  {
                    Swal.fire('Deleted!','Record has been deleted.','success');
                    $("#address_"+name).remove();

                  }
                });

          }else{
              return false;
          } 
      });      
    });


<!---------Delete Secondary Address Ends----------->

<!----------------AutoFill Address ----------------------------->


var placeSearch;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};


function initAutocomplete(id) 
{  
    var autocomplete = 'autocomplete'+id;
    var options = {
      componentRestrictions: {country: 'ca'}
      };  
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'+id), options);

    autocomplete.addListener('place_changed', function() {
      fillInAddress(autocomplete, id);
    });
}

function fillInAddress(autocomplete, unique) 
{
    // Get the place details from the autocomplete object.

    var place = autocomplete.getPlace();
    
    document.getElementById('autocomplete'+id).style.display = "none";
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();  

        document.getElementById("Latitude"+unique).value = latitude;
        document.getElementById("Longitude"+unique).value = longitude;

    for (var component in componentForm) {
      if (!!document.getElementById(component + unique)) {
        document.getElementById(component + unique).value = '';
        document.getElementById(component + unique).disabled = false;
      }
    }

    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType] && document.getElementById(addressType + unique)) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType + unique).value = val;
      }
    }
    document.getElementById('autocomplete'+unique).value = place.address_components[0]['long_name'] + ' ' + place.address_components[1]['long_name'] + ' ' + place.address_components[2]['long_name'];
}

// function geolocate() {
//   if (navigator.geolocation) {
//     navigator.geolocation.getCurrentPosition(function(position) {
//       var geolocation = {
//         lat: position.coords.latitude,
//         lng: position.coords.longitude
//       };
//        var circle = new google.maps.Circle({
//         center: geolocation,
//         radius: position.coords.accuracy
//       });
//       autocomplete3.setBounds(circle.getBounds());
//     });
//   }
// }

// <++++++++++++++++++++++AutoFill Address Ends ++++++++++++++++++++++>