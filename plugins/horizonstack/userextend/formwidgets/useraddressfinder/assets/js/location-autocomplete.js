var map, autocomplete, place, address, autocompletePostalAddrss;
var geocoder;

var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'long_name',
    postal_code: 'short_name'
};

$(document).ready(function () {


    var latitude = parseFloat($("#Form-field-User-latitude").val());
    var longitude = parseFloat($("#Form-field-User-longitude").val());

    if(!latitude){
        latitude = -6.121435;
    }

    if(!longitude){
        longitude = 106.774124;
    }

    initMap(latitude, longitude);
});

function initMap(latitude, longitude) {
    geocoder = new google.maps.Geocoder();
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: latitude, lng: longitude},
        zoom: 11
    });

    var postalAddress = /** @type {!HTMLInputElement} */(document.getElementById('postal_address'));

    var input = /** @type {!HTMLInputElement} */(document.getElementById('UserAddressFinder-formAddress-textarea-address'));

    autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: {lat: latitude, lng: longitude},
    });

    marker.addListener('dragend', handleEvent);

    function handleEvent(event) {
        $("#Form-field-User-latitude").val(event.latLng.lat());
        $("#Form-field-User-longitude").val(event.latLng.lng());
        geocodePosition(marker.getPosition());
    }

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function (responses) {
            if (responses && responses.length > 0) {
                $("#UserAddressFinder-formAddress-textarea-address").val(responses[0].formatted_address);
            }
        });
    }

    autocomplete.addListener('place_changed', function () {

        infowindow.close();
        marker.setVisible(false);
        place = autocomplete.getPlace();
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
        }
        // @type {google.maps.Icon}
        /*marker.setIcon(({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));*/
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        address = '';

        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
    });

    // Populate the address fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {

    $("#Form-field-User-latitude").val(place.geometry.location.lat());
    $("#Form-field-User-longitude").val(place.geometry.location.lng());

    if (place.address_components[0].types[0] != 'street_number') {
        var inputValue = (document.getElementById('UserAddressFinder-formAddress-textarea-address').value);
        var res = inputValue.split(" ");
        if (!isNaN(res[0])) {
            document.getElementById('street_number').value = res[0];
        }
    }
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];

        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            if (addressType === 'postal_code') {
                $('#postcode').val(val);
            }
            if (addressType === 'administrative_area_level_1') {
                $('#state').val(val);
            }
            if (addressType === 'locality') {
                $('#suburb').val(val);
            }
        }
    }
}

// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    var geoOptions = {
        maximumAge: 5 * 60 * 1000,
        timeout: 10 * 1000
    };

    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(pos);
        }, function () {
            console.log('Geolocation error occurred');
        });
    } else {
        console.log("Browser doesn't support Geolocation");
    }
}