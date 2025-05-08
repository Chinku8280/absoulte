"use strict";

/*----------------------------------------------------------------
 * output debug data - only if debug mode is enabled
 * [returns] - bool
 *--------------------------------------------------------------*/
NXGMAP.log = function (payload1, payload2) {
  if (NX.debug_javascript) {
    if (payload1 != undefined) {
      console.log(payload1);
    }
    if (payload2 != undefined) {
      console.log(payload2);
    }
  }
};

NXGMAP.map;

//marker clusterer
NXGMAP.mc;
NXGMAP.mcOptions = { gridSize: 20, maxZoom: 17, imagePath: "https://cdn.rawgit.com/googlemaps/v3-utility-library/master/markerclustererplus/images/m" };

//global infowindow
NXGMAP.infowindow = new google.maps.InfoWindow();

//geocoder
NXGMAP.geocoder = new google.maps.Geocoder();

NXGMAP.address = [];
NXGMAP.content = [];

//min and max limits for multiplier, for random numbers
//keep the range pretty small, so markers are kept close by
NXGMAP.min = .999999;
NXGMAP.max = 1.000001;

NXGMAP.createMarker = function (latlng, text) {
  var marker = new google.maps.Marker({
    position: latlng,
    map: NXGMAP.map
  });

  ///get array of markers currently in cluster
  var allMarkers = NXGMAP.mc.getMarkers();

  //check to see if any of the existing markers match the latlng of the new marker
  if (allMarkers.length != 0) {
    for (var i = 0; i < allMarkers.length; i++) {
      var existingMarker = allMarkers[i];
      var pos = existingMarker.getPosition();

      if (latlng.equals(pos)) {
        text = text + " & " + NXGMAP.content[i];
      }
    }
  }

  google.maps.event.addListener(marker, 'click', function () {
    NXGMAP.infowindow.close();
    NXGMAP.infowindow.setContent(text);
    NXGMAP.infowindow.open(NXGMAP.map, marker);
  });
  NXGMAP.mc.addMarker(marker);
  return marker;
}

NXGMAP.initialize = function () {
  var options = {
    zoom: 11,
    center: new google.maps.LatLng(1.3521, 103.8198),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };

  NXGMAP.map = new google.maps.Map(document.getElementById('map'), options);

  //marker cluster
  var gmarkers = [];

  NXGMAP.mc = new MarkerClusterer(NXGMAP.map, [], NXGMAP.mcOptions);

  $.ajax({
    type: "GET",
    url: NX.site_url + "/map/getlivegpslocation",
    data: "{}",
    contentType: "application/json; charset=utf-8",
    dataType: "json",
    async: false,
    success: function (response) {
      // console.log(response);
      NXGMAP.log('[gmaps] updating markers with live coords');
      var markers = response;

      NXGMAP.address = [];
      NXGMAP.content = [];

      for (var i = 0; i < markers.length; i++) {
        var mdata = markers[i]
        NXGMAP.address.push(mdata.coords.lat + "," + mdata.coords.lng);
        NXGMAP.content.push(
          '<div id="content">' +
          '<div id="siteNotice">' +
          "</div>" +
          '<h4 id="firstHeading" class="firstHeading">User : <b>' + mdata.data.name + '</b></h1>' +
          '<div id="bodyContent">' +
          '<p> Accuracy :' + mdata.data.accuracy + ' (radius of uncertainity in meters) </p>' +
          '<p> Altitude :' + mdata.data.altitude + ' (altitude in meters above) </p>' +
          '<p> Time :' + mdata.data.create_time + '</p>' +
          "</div>" +
          "</div>"
        );
      }

    }, error: function (eresponse) {
      NXGMAP.log('[gmaps] error fetching live gps coords');
    }
  });

  for (var i = 0; i < NXGMAP.address.length; i++) {
    console.log(NXGMAP.address.length)
    var ptStr = NXGMAP.address[i];
    var coords = ptStr.split(",");
    var latlng = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));
    gmarkers.push(NXGMAP.createMarker(latlng, NXGMAP.content[i]));
  }

}

NXGMAP.initialize();

setInterval(function () {
  NXGMAP.initialize();
  NXGMAP.log('[gmaps] updating gps locations');
}, 60000);

