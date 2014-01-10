
<!-- ############ JavaScript ############## -->

var visit_id;
var edited_visit;

var map;
var marker;

function initializeInstitutionGoogle() {

  var mapOptions = {
		zoom: 2,
		minZoom: 2,
		center: new google.maps.LatLng(36.597889,-0.703125),
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		zoomControl: true,
		panControl: false,
		mapTypeControl: true,
		scaleControl: false,
		streetViewControl: false,
		overviewMapControl: false
  }
  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

  google.maps.event.addListener(map, 'rightclick', function(event) {
    placeMarker(event.latLng);
  });

  populateCountrySelector();
}


function populateCountrySelector()
{

	$.getJSON(SITE_URL + 'institutions/get_countries', function(countries_array) {
		$.each(countries_array, function() {
			$("#countrySelector").append("<option name='"+this.alpha_2+"' value='"+this.alpha_2+"'>"+this.name+"</option>");
		});
	});
}



function placeMarker(location) {
	if(!marker) {
	  marker = new google.maps.Marker({
	      position: location,
	      map: map
	  });
	}
	else {
		marker.setPosition(location)
	}
	$("#lat").val(location.lat());
	$("#long").val(location.lng());

}

$('#new-institution').on('shown', function () {
	if (map) {
		google.maps.event.trigger(map, 'resize')
		//map.setCenter(new google.maps.LatLng(36.597889,-0.703125));
		}
	})


function loadMapsScript() {
	if (!map) {
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.googleapis.com/maps/api/js?key=" + google_api_key + "&sensor=false&callback=initializeInstitutionGoogle";
		  document.body.appendChild(script);
		}
	}



function searchInstitution()
{
	var post_data = {
		institution: $("#institution_name").val(),
		csrf_token_name: $("input[name=csrf_token_name]").val()
	};
	var posting = $.post( SITE_URL +'institutions/search_institution', post_data, handleSearchResponse);
}

function handleSearchResponse(data)
{
	if(!$(".hide-initially").is(":visible")) {
		$(".hide-initially").slideDown();
		$("#inst-save").toggle();
		google.maps.event.trigger(map, 'resize');
	}

	if(data)
	{
		$("#search-error").remove();
		point = new google.maps.LatLng(data[0].lat, data[0].long);
		placeMarker(point);

		populateFormFields(data[0]);

		map.setZoom(7);
		map.setCenter(point);
	}
	else
	{
		if(!$("#search-error").is(":visible")) {
		$("#search").after("<div id='search-error' class='text-error text-error-inline'>No results found. Mark the location manually.</div>");
		}
	}

}

function populateFormFields(data)
{
	address1 = "";
	if(data.street_number) address1 += data.street_number;
	if(data.street_number && data.street) address1 += " ";
	if(data.street) address1 +=	data.street;
	if(address1 != "") $("#address1").val(address1);

	if(data.postal_code) $("#postal_code").val(data.postal_code);
	if(data.city) $("#city").val(data.city);

	$('#countrySelector option[name=' + data.country_code + ']').prop('selected',true);
}


//function for disabling default enter keypress
$(document).keypress(function(e) {
    if($("#institution_name").is(":focus") && e.which == 13) {
    	e.preventDefault();
    	searchInstitution();
    }
});


function saveInstitution(e)
{
	if($("#new-institution-form").validate().form()) {
		var post_data = {
				countrySelector: $("#countrySelector").val(),
				lat:  $("#lat").val(),
				long: $("#long").val(),
				institution_name: $("#institution_name").val(),
				description: $("#description").val(),
				address1: $("#address1").val(),
				address2: $("#address2").val(),
				city: $("#city").val(),
				postal_code: $("#postal_code").val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};


			$.post( SITE_URL +'institutions/new_institution', post_data, handleAJAXResponse);

		}


}


function handleAJAXResponse(data)
{
	//if we are in new visit page
	if($(location).attr('href').indexOf("new_visit") >= 0) {
		$("#institution").append("<option value="+data.id+">"+data.name+"</option>");
		$("#institution").select2("val", data.id);

		$('#department').find('option').remove().end()
	    .append('<option value="0">No Department/School</option>')
	    .val('0');
		$("#department").select2("val", "0");
		$("#inst_name_for_new_dep").val($('#institution :selected').html());

		if(!$("#new_department_button").is(":visible")) {
			$("#new_department_button").toggle();
		}
	}
	//if we are in existing_institutions page
	if($(location).attr('href').indexOf("existing_institutions") >= 0) {
		initializeInstitutions();
		if($(".new-inst-entered").is(":visible")) {
		$(".new-inst-entered").slideUp();
		}
		$(".new-inst-entered").slideDown();
	}

	$("#institution_name").val("");
	$("#description").val("");
	$("#address1").val("");
	$("#address2").val("");
	$("#postal_code").val("");

	$('#new-institution').modal('hide')

}


<!-- ############ New Institution Form validation ############## -->

$(document).ready(function(){


	$("#new_institution").on("click", function (e){
    	e.preventDefault();
    	loadMapsScript();
    	$('#new-institution').modal('show');
	});

	var $err_open = "<div class='text-error text-error-inst'><small>";
	var $err_close = "</small></div>";

	$("#new-institution-form").validate({

		errorPlacement: function(error, element) {
			error.insertAfter(element.next());
		  },

		rules: {
			institution_name: "required",
			countrySelector: "required",
			lat: "required"
		},

		messages: {
			institution_name: $err_open + "Institution name is required" + $err_close,
			countrySelector: $err_open + "Please select the country" + $err_close,
			lat: $err_open + "Please specify the location on the map" + $err_close,
		 }
	});
});
<!-- ############ End of Form validation ############## -->




<!-- ############ End of JavaScript ############## -->
