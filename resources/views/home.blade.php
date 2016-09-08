@extends('layouts.app')

@section('content')
    <!--   <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwswRCLrznrKUj7_WetOirsRqt0b0Anjg&libraries=places&callback=initMap" async defer></script> -->
    <style type="text/css">
      .label {
        text-align: right;
        font-weight: bold;
        width: 100px;
        color: #303030;
      }
      #address {
        border: 1px solid #000090;
        background-color: #f0f0ff;
        width: 480px;
        padding-right: 2px;
      }
      #address td {
        font-size: 10pt;
      }
      .field {
        width: 99%;
      }
      .slimField {
        width: 80px;
      }
      .wideField {
        width: 200px;
      }
      #locationField {
        height: 20px;
        margin-bottom: 2px;
      }
    </style>

<script type="text/javascript">
  var myLatLng = {lat: -33.8688, lng: 151.2195};
  var myRadius = 500;
  var map;
  var infowindow;
  var autocomplete;
  var zoomFactor = 15;
  var markers = [];
  var marker;
  var markerListners = [];
  var place;
  var arrOfLocations = [];
  var componentForm = {
    address: 'long_name',
    city: 'long_name',
    state: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
  };
  var cityCircle;

  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      center: myLatLng,
      zoom: zoomFactor,
      mapTypeId: 'roadmap'
    });

  //  drawCircle();
  //  addNewPlace();
  //  nearbySearch();
   autocompleteSearch();
    
    map.addListener('click', function(event) {
      reverseGeoCoding(event.latLng.lat(), event.latLng.lng());
      updateLatLng(event.latLng);
    });
  } 

  function autocompleteSearch() {
    var input = document.getElementById('location-search');
    autocomplete = new google.maps.places.Autocomplete(input, {types: ['geocode']});
    var circle = new google.maps.Circle({
      radius: myRadius
    });
    
    autocomplete.setBounds(circle.getBounds());
    autocomplete.bindTo('bounds', map);

   /* map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);*/

    infowindow = new google.maps.InfoWindow();
     marker = new google.maps.Marker({
          map: map
        });
        marker.addListener('click', function(e) {
          e.preventDefault();
          infowindow.open(map, marker);
        });
    autocomplete.addListener('place_changed', autocompleteCallback);
  }

  function parseAddress(place) {
      if (place.address_components != undefined && place.address_components.length > 0) {
        for (var i = 0; i < place.address_components.length; i++) {
          var addressTypes = place.address_components[i].types;
          if ($.inArray("postal_code", addressTypes) != -1) {
             $(".dl-zipcode").html(place.address_components[i]["long_name"]);
             $(".dl-zipcode").val(place.address_components[i]["long_name"]);
          } else if ($.inArray("country", addressTypes) != -1) {
             $(".dl-country").html(place.address_components[i]['long_name']);
             $(".dl-country").val(place.address_components[i]['long_name']);
          } else if ($.inArray('administrative_area_level_1', addressTypes) != -1) {
             $(".dl-state").html(place.address_components[i]['long_name']);
             $(".dl-state").val(place.address_components[i]['long_name']);
          } else if ($.inArray('locality', addressTypes) != -1) {
             $(".dl-city").html(place.address_components[i]['long_name']);
             $(".dl-city").val(place.address_components[i]['long_name']);
          } 
       }
      
      $(".dl-address").html(place.name);
      $(".dl-address").val(place.name);
      updateLatLng(place.geometry.location);
    }
  }

  function updateLatLng(location){
    var lat = (typeof location.lat   == 'function') ? location.lat() : location.lat;
    var lng = (typeof location.lng   == 'function') ? location.lng() : location.lng;

    $("#latitude-search").val(lat);
    $("#longitude-search").val(lng);
    $(".dl-latitude").html(lat);
    $(".dl-longitude").html(lng);
    $(".dl-latitude").val(lat);
    $(".dl-longitude").val(lng);
  }

  function autocompleteCallback() {
    infowindow.close();
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }

  

    // Set the position of the marker using the place ID and location.
    

    // console.log(place);
   
    myLatLng = place.geometry.location;

    // get the datail address
    getAddressDetail(place.place_id);
    }

   function getAddressDetail(place_id) {
      var service = new google.maps.places.PlacesService(map);
    service.getDetails({
      placeId: place_id
    }, function(place, status) {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
         infowindow.setContent('<div><strong>' + place.formatted_address + '</strong><br><br>' +
            '<strong>Types:</strong> ' + place.types + '<br>' +
            '<strong>Latitude:</strong> ' + place.geometry.location.lat() + '<br>' +
            '<strong>Longitude:</strong> ' + place.geometry.location.lng() + '<br>'
            );

            if (place.geometry.viewport) {
              map.fitBounds(place.geometry.viewport);
            } else {
              map.setCenter(place.geometry.location);
              map.setZoom(17);
            }

            infowindow.open(map, marker);
            marker.setPlace({
              placeId: place.place_id,
              location: place.geometry.location
            });
            marker.setVisible(true);

            parseAddress(place);
            // console.log(place);
         }
      });
   } 
  function searchByLatLng(){
     var lat = $("#latitude-search").val();
     var lng = $("#longitude-search").val();

     var isValid = true;
     if (!$.isNumeric(lat)) {
        $(".latitude-search-error").html("Please input valid latitude");
        isValid = false;
      }
     if (!$.isNumeric(lat)) {
      $(".longitude-search-error").html("Please input valid longitude");
      isValid = false;
    }
    if(!isValid) return;

    $(".latitude-search-error").html("");
    $(".longitude-search-error").html("");

    reverseGeoCoding(lat, lng);
  };

  function reverseGeoCoding(lat, lng) {
    var formatted_latlng = lat + "," + lng;
    var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+ formatted_latlng + "&key=AIzaSyC84kJp9uRzMLiXfntY5vAg95L8K5znKbY";
      $.get(url, function(data){
          // console.log(data.results[0]);
        //  parseAddress(data.results[0]);
          getAddressDetail(data.results[0].place_id);
      })
  }

  function addLocations(location, name, address, types){
    var _location = {lat: location.lat(), lng: location.lng()};
    var _title = name;
    var _address = address;
    var _types = types;
    arrOfLocations.push({location:_location, title:_title, address:_address, types:_types});
  }

  function reloadParkingTable() {
     $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': "{!! csrf_token() !!}",
        }
    });
    $.post("/tasks", 
      {
        locations:arrOfLocations
      }, function(result){
         // console.log(result);
        $('#parking-table').html(result);
    });

    console.log(arrOfLocations);
    arrOfLocations = [];
  }

  /*new Vue({
    el: '#app',

    data: {
        formInputs: {},
        formErrors: {}
    },

    methods: {
        submitForm: function(e) {
            var form = e.target;
            var action = form.action;
            var csrfToken = form.querySelector('input[name="_token"]').value;
            
            this.$http.post(action, this.formInputs, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(function() {
                form.submit();
            })
            .catch(function (data, status, request) {
                this.formErrors = data.data;
                console.log(status);
                console.log(data);
                console.log(request);
            });
        }
    }
});*/
    </script>
  <div class="site-index">
    <div class="block-header">
        <h2>map</h2>
    </div>
    <div class="card" style="height: 600px">
      <div id="map"></div>
    </div>

    <br/>
    <div class="card">
        <div class="card-body clearfix">
          <div class="pm-body clearfix m-l-20 m-b-20">
            <div class="pmb-block">
              <div class="pmbb-header">
                <h2><i class="zmdi zmdi-equalizer m-r-5"></i> Search</h2>
              </div>
              <div class="pmbb-body p-l-30">
                <div class="pmbb-view">
                  <div class="row">
                    <div class="col-sm-5">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                        <div class="fg-line">
                          <input type="text" class="form-control" id="location-search" placeholder="Location" autocomplete="off">
                          </div>
                      </div> 
                    </div>
                    <div class="col-sm-6">
                      <div class="col-sm-5">    
                        <div class="input-group">
                          <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                          <div class="fg-line">
                            <input type="text" id="latitude-search" class="form-control" placeholder="Latitude">
                            <div class="latitude-search-error help-block with-errors c-red"></div>
                          </div>
                        </div> 
                      </div>
                      <div class="col-sm-5">    
                        <div class="input-group">
                          <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                          <div class="fg-line">
                            <input type="text" id="longitude-search" class="form-control" placeholder="Longitude">
                            <div class="longitude-search-error help-block with-errors c-red"></div>
                          </div>
                        </div> 
                      </div>
                      <div class="col-sm-2">
                        <button onClick="searchByLatLng()" id="search-by-lat-lng" class=" btn btn-primary btn-icon-text waves-effect"><i class="zmdi zmdi-search"></i> Search</button>  
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          @include('home.valid')

          <br/>
        <div class="pm-body clearfix m-l-20  m-b-20">
            <div class="pmb-block">
              <div class="pmbb-header">
                <h2><i class="zmdi zmdi-equalizer m-r-5"></i> Address table (DB)</h2>
              </div>
            <div class="pmbb-body">
              <div class="pmbb-view">
                <meta name="_token" content="{!! csrf_token() !!}" />
                <div id="parking-table">  
                  @include('parking')
                </div>
              </div>
            </div>
        </div>
    </div>                    
  </div>

@endsection


