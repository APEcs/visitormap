
<!-- ############ JavaScript ############## -->

var visit_id;
var edited_visit;

var map;
var marker;

$(function () {
	$("#countrySelector").select2({
	width: "284px"
	});
	});

function initializeInstitutionGoogle() {
  var defLatLong = new google.maps.LatLng($('input[name=lat]').val(), $('input[name=long]').val());
  var mapOptions = {
		zoom: 5,
		minZoom: 2,
		center: defLatLong,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		zoomControl: true,
		panControl: false,
		mapTypeControl: true,
		scaleControl: false,
		streetViewControl: false,
		overviewMapControl: false
  }
  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);


  marker = new google.maps.Marker({
      position: defLatLong,
      map: map,
      title: 'Current instititution location'
  });

  google.maps.event.addListener(map, 'rightclick', function(event) {
    placeMarker(event.latLng);
  });

}


function placeMarker(location) {

	if(!marker) {
	  marker = new google.maps.Marker({
	      position: location,
	      map: map,
	      title: 'Current instititution location'
	  });
	}
	else {
		marker.setPosition(location)
	}
	$("input[name='lat']").val(location.lat());
	$("input[name='long']").val(location.lng());
}


function loadMapsScript() {
	if (!map) {
		  var script = document.createElement("script");
		  script.type = "text/javascript";
		  script.src = "http://maps.googleapis.com/maps/api/js?key=" + google_api_key + "&sensor=false&callback=initializeInstitutionGoogle";
		  document.body.appendChild(script);
		}
	}

$(document).ready(function () {
	loadMapsScript();
	});


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

	if(data)
	{
		$("#search-error").remove();
		point = new google.maps.LatLng(data[0].lat, data[0].long);
		placeMarker(point);
		map.setZoom(7);
		map.setCenter(point);

		populateFormFields(data[0]);

	}
	else
	{
		if(!$("#search-error")) {
		$("#search").after("<div id='search-error' class='text-error text-error-inline'>No results found</div>");
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
	if($("#edit_institution_form").validate().form()) {
		var post_data = {
				id: $("input[name=inst_id]").val(),
				alpha_2: $("#countrySelector").val(),
				lat:  $("input[name=lat]").val(),
				long: $("input[name=long]").val(),
				institution_name: $("#institution_name").val(),
				address1: $("#address1").val(),
				address2: $("#address2").val(),
				city: $("#city").val(),
				postal_code: $("#postal_code").val(),
				csrf_token_name: $("input[name=csrf_token_name]").val()
			};


			$.post( SITE_URL +'institutions/do_edit_institution', post_data, handleAJAXResponse);
		}
}

function handleAJAXResponse(data)
{
	if(data && data.success) {
		if($.getUrlVar('origin')=='edit_visit') {
			window.location = SITE_URL + 'visits/edit_visit?' +
				'id=' + $.getUrlVar('origin_id') + '&inst_edit_success=1' +
				'&inst_id=' + $.getUrlVar('id') +
				'&dep_id=' + $.getUrlVar('dep_id');
		}
		else if ($.getUrlVar('origin')=='home') {
			window.location = SITE_URL + 'home?inst_edit_success=1';
		}
		else {
			window.location = SITE_URL + 'institutions/existing_institutions?edit_ok=1';
		}

	}
}


function cancelButtonClick() {
	var origin = $.getUrlVar('origin');
	if(origin=='edit_visit') {
		window.location = SITE_URL + 'visits/edit_visit?' +
			'id=' + $.getUrlVar('origin_id') +
			'&inst_id=' + $.getUrlVar('id') +
			'&dep_id=' + $.getUrlVar('dep_id');
	}
	else if(origin == 'home') {
		window.location = SITE_URL;
	}
	else {
		window.location = SITE_URL + 'institutions/existing_institutions';
	}
}


<!-- ############ New Institution Form validation ############## -->

$(document).ready(function(){

	var $err_open = "<div class='text-error text-error-inst'><small>";
	var $err_close = "</small></div>";

	$("#edit_institution_form").validate({

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
