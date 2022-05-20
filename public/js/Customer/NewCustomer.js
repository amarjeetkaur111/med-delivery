$(document).ready(function() {
    $('.mdb-select').materialSelect();
    var table = $('#customer_table').DataTable({
        "ajax": {
            'url': "/customers/list/",
            // 'data': function(data) {
            //     // var status = $('#status').val();
            //     // data.searchByStatus = status;
            // },
        },
        "processing": true,
        "serverSide": true,
        "searching": true,
        "ordering": true,
        "scrollX": false,
        "scrollCollapse": true,
        "lengthMenu": [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],
        "columnDefs": [
            { "bSortable": false, "targets": [4, 5] }
        ],
        "initComplete": function(settings, json) {}
    });
});

// <----------------Phone Number Pattern-------------->

$('input[type=tel]').keypress(function() {
    $(this).val($(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/, '$1-$2-$3'))
});

// <----------------Delete Customer-------------->

$(document).on('click', '#deleteCustomer', function() {
    var element = $(this);
    var del_id = element.attr("data-id");
    var name = element.attr("data-name");
    var status;
    if ($(this).children("i").hasClass('fa-toggle-on'))
        status = 'Deactivate';
    else
        status = 'Activate';

    var id = del_id;

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to " + status + " " + name + " ?",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, " + status + " !",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/customers/delete_customer/" + id,
                data: '',
                type: 'get',
                success: function($result) {
                    // Swal.fire('Updated!','Record has been modified.','success');
                }
            });
            $(this).children("i").toggleClass('fa-toggle-on fa-toggle-off');
            $(this).children("i").toggleClass('activate deactivate');

        } else {
            return false;
        }
    });
});

// <--------------------Delete Customer Ends--------------------->

// <!--------------Add Customer Page-------------------------------->

$('input[type=radio][name=group1]').change(function() {
    if (this.value == '1') {
        $("#existing_customer_div").hide();
    } else if (this.value == '2') {
        $("#existing_customer_div").show();
    }
});

var i = 0;

// <!------------Add Multiple Phone Numbers for Patient Dynamically in Add Address Form------->

function add_multiple_phone() {
    var html_content = "";
    i++;
    html_content += '<div class="alternative-phone-div" id="multiple_phone_' + i + '">';
    html_content += '<div class="row">';
    html_content += '<div class="col-md-5 col-sm-10">';
    html_content += '<div class="md-form md-outline form-control-sm">';

    html_content += '<i class="fas fa-phone-alt prefix" style="margin-left: -10px;"></i>';
    html_content += '<select class="form-control"  name="Phone[' + i + '][PhoneTypeId]"  style="margin-left:25px;width:95%;line-height: 1.5;border-radius: .2rem;height: calc(1.5em + .5rem + 2px);">';
    html_content += '<option value="">Select Phone Type</option>';
    html_content += '<option value="1" style="color: black;"  >Home</option>';
    html_content += '<option value="2" style="color: black;"  >Mobile</option>';
    html_content += '<option value="3" style="color: black;"  >Work</option>';
    html_content += '<option value="4" style="color: black;"  >Fax</option>';
    html_content += '</select>';
    html_content += '</div>';
    html_content += '</div>';
    html_content += '<div class="col-md-5 col-sm-9">';
    html_content += '<div class="md-form md-outline">';
    html_content += '<input id="altnum' + i + '" type="tel" class="form-control multiplephonenumbers_format" name="Phone[' + i + '][PhoneNumber]" maxlength="12" autofocus>';
    html_content += '<label for="altnum' + i + '" class="">Alternative Number ' + i + '</label>';
    html_content += '</div>';
    html_content += '</div>';
    html_content += '<div class="col-md-1 col-sm-1">';
    html_content += '<button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="delete_multiple_phone(' + i + ');" style="margin-top: 2rem;"><i class="fas fa-times"></i></button>';
    html_content += '</div>';
    html_content += '</div>';
    html_content += '</div>';
    $("#multiple_phone_numbers_section").append(html_content);
    $("#multiple_phone_title").show();
    //console.log("Increment:"+i);
    $('.multiplephonenumbers_format').keypress(function() {
        var Mobile_Number = $(this).val().replace(/(\d{3})\-?(\d{3})\-?(\d{4})/, '$1-$2-$3');
        $(this).val(Mobile_Number.substr(0, 12));
    });
}
// <!-------Add Multiple Phone Numbers for Patient Dynamically in Add Address Form End-------->

// <!-------Delete Multiple Phone Numbers for Patient Dynamically in Add Address Form----------->
function delete_multiple_phone(multiple_phone_id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to remove this Alternate Phone Number!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel plx!",
        preConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            i--;
            if (i == 0) {
                $("#multiple_phone_title").hide();
            }
            $("#multiple_phone_" + multiple_phone_id).remove();
            //console.log(multiple_phone_id);
            //console.log("Decrement:"+i);
        }
    });
}

// <!------Delete Multiple Phone Numbers for Patient Dynamically in Add Address Form End------>

// <----------------AutoFill Address ----------------------------->

//Alphabets Only
function alphaOnly(event) {
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8);
};

var placeSearch, autocomplete;

var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
};



$("#autocomplete").focusin(function() {
    var options = {
        componentRestrictions: { country: 'ca' }
    };
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('autocomplete'), options);

    autocomplete.addListener('place_changed', fillInAddress);
});

function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();


    // document.getElementById('autocomplete').style.display = "none";
    var latitude = place.geometry.location.lat();
    var longitude = place.geometry.location.lng();

    document.getElementById("Latitude").value = latitude;
    document.getElementById("Longitude").value = longitude;

    for (var component in componentForm) {
        document.getElementById(component).value = '';
        document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];

        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            document.getElementById(addressType).value = val;
        }
    }
    document.getElementById('autocomplete').value = place.address_components[0]['long_name'] + ' ' + place.address_components[1]['long_name'] + ' ' + place.address_components[2]['long_name'];
    // document.getElementById('address1').style.display = "none";      
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.


// <++++++++++++++++++++++AutoFill Address Ends ++++++++++++++++++++++>
// <++++++++++++++++++++++Add Customer Page Ends ++++++++++++++++++++++>