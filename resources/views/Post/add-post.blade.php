@extends('Layout.layout')
@section('title','Profile Settings')
@section('style')
<style>
   .formbuilder-embedded-bootstrap{
   display:flex;
   justify-content:space-between;
   flex-wrap: wrap;
   margin-top:30px;
   }
   .form-group{
   width: 48%;
   }
   @media only screen and (max-width: 600px){
   .form-group{
   width: 100%;
   }
   }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<style>
   #map { width:100%; height: 600px; }
</style>
@endsection
@section('content')
   <div class="container py-4">
      <div class="row justify-content-center ">
         <div class="container pb-5 mb-2 mb-md-4" >
            <!-- Content  -->
            <form action="{{route('post.save')}}" method="post" id="postForm" enctype="multipart/form-data">
               @csrf
               <section class="row mx-auto  rounded-3 px-4 py-4 mb-3" >
                  <div class="col-12 text-center">
                     <h3>Found Or Lost Something?</h3>
                     <p>We are here to help you add the details of the item below.</p>
                  </div>
                  <div class="col-md-6 my-2">
                     <label for="">Found Or Lost</label>
                     <select class="col-11 mx-auto" name="post_type" id="" required>
                        <option value="" selected disabled>Select one</option>
                        <option value="found">I have Found This Item.</option>
                        <option value="lost">I have Lost My Item.</option>
                     </select>
                  </div>
                  <div class="col-md-6 my-2">
                     <label for="">Category</label>
                     <select class="col-11 mx-auto" name="category_id" id="mainCategorySelect">
                        <option value="">Select Main Category</option>
                        @foreach($mainCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="col-md-6 my-2">
                     <label for="">Sub Category</label>
                     <select class="col-11 mx-auto" name="subcategory_id" id="subCategorySelect">
                        <option value="" selected disabled>Select Subcategory</option>
                     </select>
                  </div>
                  <div id="formrender"  class="render-wrap">
                  </div>
                  <input type="hidden" id="user_route_cords" name="user_route_cords" value="" >
                  <h5 class="mt-4 mb-2">Mark Your Route/Place where you found/lost Item</h5>
                  <div class="d-flex flex-wrap align-items-center ">

                      <button class="col-sm-4 mb-sm-0 mb-2 py-2 border-0 shadow-0 btn-primary" type="button" id="current-location-button">Get Current Location</button>
                      <button class="col-sm-4 mb-sm-0 mb-2 py-2 border-0 shadow-0 btn-danger" type="button" id="reset-markers-button">Reset Markers</button>
                      <select name="map-toption" class="col-sm-4 mb-sm-0 mb-2 py-2" id="map-option">
                          <option value="circle">Add Circle</option>
                          <option value="route">Add Route</option>
                        </select>
                    </div>

                  <div id="map"></div>
                  <div id="submit-btn" class="my-3"></div>
               </section>
            </form>
         </div>
      </div>
   </div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('admin/js/form-render.min.js')}}" ></script>
<script>
  $('#postForm').submit(function(e) {
    e.preventDefault();
    if (!$('#user_route_cords').val()) {
        alert("Select location on the map.");
    } else {
        this.submit();
    }
});

   $(document).ready(function() {



         $('#mainCategorySelect').change(function() {
             var categoryId = $(this).val();
             $('#subCategorySelect').empty();

             $.ajax({
                 url: '/get-subcategories/' + categoryId,
                 method: 'GET',
                 success: function(response) {

                     // Recursive function to populate subcategories
                     function populateSubcategories(subcategories, level) {
                         if (!Array.isArray(subcategories)) {
         console.error('Invalid subcategories data:', subcategories);
         return;
     }$('#subCategorySelect').append('<option value="" selected disabled>Select Subcategory</option>')
                         $.each(subcategories, function(index, subcategory) {
                             var indentation = Array(level + 1).join('&nbsp;&nbsp;&nbsp;');
                             $('#subCategorySelect').append('<option value="' + subcategory.id + '">' + indentation + subcategory.name + '</option>');
                             if (subcategory.subcategories && subcategory.subcategories.length > 0) {
                                 // If subcategories exist, call the function recursively
                                 populateSubcategories(subcategory.subcategories, level + 1);
                             }
                         });
                     }

                     // Start populating subcategories recursively
                     populateSubcategories(response, 0);
                 },
                 error: function(xhr, status, error) {
                     console.error('AJAX Error: ' + status + ' - ' + error);
                     // Handle the error (show a message, log it, etc.)
                 },
                 complete: function(xhr, status) {
                     console.log('AJAX Request completed with status: ' + status);
                     // This block will run regardless of whether the request was successful or not
                 }
             });
         });
     });


     $(document).ready(function() {


     $('#subCategorySelect').change(function() {
         // Get the selected subcategory ID
         var subcategoryId = $(this).val();
         const code = document.getElementById("formrender");
   code.innerHTML='       <div class="d-flex justify-content-center align-items-center" style="height: 400px"><div class="spinner-border text-primary"  role="status"><span class="sr-only"></span></div></div>';
         // Make an AJAX request to fetch data based on the selected subcategory ID
         $.ajax({
             url: '/getform/' + subcategoryId, // Replace '/getform/' with the appropriate URL
             method: 'GET',
             success: function(response) {
                 // Handle the successful response from the server
                 console.log('Form data retrieved successfully:', response);

                 if(response.form.length>0){

                 jQuery($ => {
   const escapeEl = document.createElement("textarea");
   const code = document.getElementById("formrender");
   const formData =response.form[0].formdata;  const addLineBreaks = html => html.replace(new RegExp("><", "g"), ">\n<");

   // Grab markup and escape it
   const $markup = $("<div/>");
   $markup.formRender({ formData });

   // set < code > innerText with escaped markup

   code.innerHTML = addLineBreaks($markup.formRender("html"));
   document.getElementById('submit-btn').innerHTML="<button class='btn btn-primary ms-auto' type='submit' >Submit</button>"
   });
   loadMap();
   }
   else{
     code.innerHTML='       <div class="d-flex justify-content-center align-items-center" style="height: 400px"><div class="text-danger"  role="status"><span>No Form Available</span></div></div>';
   }
                 // Perform actions with the retrieved data, e.g., update the UI
             },
             error: function(xhr, status, error) {
                 // Handle errors, show error messages, etc.
                 console.error('Error fetching form data:', error);
             }
         });
     });
   });


</script>
<script>
   var circle; // Declare the circle variable outside of the click event


// Update route and markers when the circle is dragged or resized
circle?.on('dragend resize', function() {
            clearMarkersAndPolyline();
            updateRouteAndMarkers();
            updateHiddenInput();
        });

   document.getElementById('reset-markers-button').addEventListener('click', function() {

clearMarkersAndPolyline();
updateRouteAndMarkers();

});


      var map;
      function loadMap(){
      map = L.map('map').setView([30.3753, 69.3451], 8); // Default view set to (0, 0)
   // Update circle radius on map zoom
   map.on('zoomend', function() {
       if (circle) {
           var currentRadius = circle.getRadius();
           var newRadius = currentRadius * map.getZoom() / 15; // You can adjust the division factor for appropriate scaling
           circle.setRadius(newRadius);
       }

        updateMarkers();

   });


      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
      }).addTo(map);
      // Add event listener to the "Get Current Location" button
      document.getElementById('current-location-button').addEventListener('click', getCurrentLocation);
      }
      function getCurrentLocation() {
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var latlng = L.latLng(position.coords.latitude, position.coords.longitude);

            // Clear existing markers and polyline
            clearMarkersAndPolyline();

            // Add a marker for the current location
            var currentLocationMarker = L.marker(latlng, { draggable: true }).addTo(map);
            currentLocationMarker.on('dragend', function () {
                updateRouteAndMarkers();
            });
            markers.push(currentLocationMarker);

            // Center the map on the current location with smooth animation
            map.flyTo(latlng, 14, {
                animate: true,
                duration: 1.5 // Duration of the animation in seconds
            });


        }, function (error) {
            console.error('Error getting current location:', error);
        });
    } else {
        alert('Geolocation is not supported by your browser.');
    }
}

      loadMap();
      function clearMarkersAndPolyline() {
      markers.forEach(function (marker) {
      map.removeLayer(marker);
      });
      markers = [];

      if (polyline) {
      map.removeLayer(polyline);
      polyline = null;
      }
      }
      var markers = [];
      var polyline;



      // Update the markers based on the current zoom level
      function updateMarkers() {
      var coordinates = document.getElementById('user_route_cords').value.split('|');
      markers.forEach(function(marker) {
      map.removeLayer(marker);
      });
      markers = [];

      coordinates.forEach(function(coord) {
      var latlng = coord.split(',').map(function(str) {
          return parseFloat(str);
      });
      var marker = L.marker(latlng, { draggable: true }).addTo(map);
      marker.on('dragend', function() {
          updateRouteAndMarkers();
      });
      markers.push(marker);
      });

      updateRouteAndMarkers();
      }


      function updateRouteAndMarkers() {
          if (polyline) {
              map.removeLayer(polyline);
          }

          var latlngs = markers.map(function(marker) {
              return marker.getLatLng();
          });

          polyline = L.polyline(latlngs, { color: 'blue' }).addTo(map);

          if (markers.length >= 1) {
              markers[0].unbindTooltip();
              markers[0].bindTooltip('Start', { permanent: true, direction: 'right' }).openTooltip();
          }
          if (markers.length >= 2) {
              markers[markers.length - 1].unbindTooltip();
              markers[markers.length - 1].bindTooltip('End', { permanent: true, direction: 'right' }).openTooltip();
          }

          // Update hidden input field with marker coordinates
          var markerCoords = latlngs.map(function(latlng) {
              return latlng.lat + ',' + latlng.lng;
          });
          document.getElementById('user_route_cords').value = markerCoords.join('|');
      }

      function removeMarker(marker) {
          map.removeLayer(marker);
          markers = markers.filter(function(existingMarker) {
              return existingMarker !== marker;
          });
          updateRouteAndMarkers();
      }

      function addMarker(latlng) {
          // Remove "End" label from previous markers
          markers.forEach(function(marker) {
              marker.unbindTooltip();
          });

          var marker = L.marker(latlng, { draggable: true }).addTo(map);
          marker.on('click', function() {
              removeMarker(marker);
          });
          marker.on('dragend', function() {
              updateRouteAndMarkers();
          });
          markers.push(marker);
          updateRouteAndMarkers();
      }

      function onMapClick(e) {
          addMarker(e.latlng);
      }
      function addCircle(e) {
        // Update route and markers initially
        clearMarkersAndPolyline();
        updateRouteAndMarkers();
    var radius = 50; // Default radius
    if (circle) {
        // Move the existing circle to the new clicked location
        circle.setLatLng(e.latlng);
    } else {
        // Create a new circle if it doesn't exist
        circle = L.circle(e.latlng, {
            radius: parseFloat(radius),
            draggable: true, // Make the circle draggable
        }).addTo(map);



        // Update route and markers initially
        clearMarkersAndPolyline();
        updateRouteAndMarkers();
        updateHiddenInput(); // Update hidden input field when circle is created
    }

    function updateHiddenInput() {
        var circleCoords = circle.getLatLng().lat + ',' + circle.getLatLng().lng;
        var circleRadius = circle.getRadius();
        document.getElementById('user_route_cords').value = circleCoords + '|' + circleRadius;
    }
}

      map.on('click', function(e) {
        var option=$('#map-option').val()
        if(option=="route"){

            onMapClick(e);
        }
        else{
            addCircle(e);
        }

    });
    $('#map-option').on('change',function(){

        if($('#map-option').val()=="route"){

            // if(circle){

            //     map.removeLayer(circle);
            // }

}
else{
    clearMarkersAndPolyline();
        updateRouteAndMarkers();
}
    })

      map.on('moveend', function() {
          updateMarkers();
      });

      updateMarkers();





</script>
@endsection