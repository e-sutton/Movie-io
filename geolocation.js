/*
* geolocation.js *
* Rev 1 *
* 27/03/17 *
* @reference: http://www.movable-type.co.uk/scripts/latlong.html
*/
    //global variables
    var userLat;
    var userLong;

    //get location data from reviews
    function loadGeoData(){
      //array of movie review ids where location is within 100km of user
      var arr = [];
      $.ajax({
          type: "GET",
          url: "get_location_data.php",
          success: function(result){
                  //$("#message")[0].value = "Success";
                  //alert("Geo location data fetch success! " + result);
                  result = $.parseJSON(result);
                  console.log(result);
                  //create user lat long object
                  var userLoc = new LatLon(sessionStorage.getItem("userLat"), sessionStorage.getItem("userLong"));
                  //alert("lat: "+userLat + " lng: "+userLong);

                  $.each(result, function (key, value) {
                    var reviewLoc = new LatLon(Number(value.lat), Number(value.lng));
                    //alert("saved lat: "+value.lat + " saved lng: "+value.lng);
                    //get distance
                    var dist = (((userLoc.distanceTo(reviewLoc)) /1000).toFixed(2));
                    //alert("istance: "+dist)
                    if(dist <= 100){
                      $('#listviewpage2').append("<li onclick='loadMovieDataFromList(\x22" +  value.movie_id + "\x22)'><a href='#'>"
                      + "<h2>" + value.movie_title + "</h2>"
                      +"<p> Your Score: " + value.score + "</p>" + "<p>" + value.review +"</p>" + "</a></li>").listview('refresh');;
                    }
                  });
          },
          error: function(xhr, status, error){
              //$("#message")[0].value = "Ajax error!"+result;
              alert("Geo location data fetch error " + xhr.responseText);
          }
      });
    }


        //get gps coordinates
        function getPosition(){
          if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(myPosition);
          }
          else{
            alert("Geolocation cannot be used on this browser, not supported!");
          }
        }

        function myPosition(position){
          //alert("Latitude: " + position.coords.latitude + "\nLongitude: " + position.coords.longitude);
          userLat = position.coords.latitude;
          userLong = position.coords.longitude;
          sessionStorage.setItem("userLat", userLat);
          sessionStorage.setItem("userLong", userLong);
          console.log(userLat + " : " + userLong);

    }

    //haversine function to return great circle distance between two points
    LatLon.prototype.distanceTo = function(point, radius) {
        if (!(point instanceof LatLon)) throw new TypeError('point is not LatLon object');
        radius = (radius === undefined) ? 6371e3 : Number(radius);

        var R = radius;
        var v1 = toRad(this.lat),  a1 = toRad(this.lon);
        var v2 = toRad(point.lat), a2 = toRad(point.lon);
        var v_total = v2 - v1;
        var a_total = a2 - a1;

        var a = Math.sin(v_total/2) * Math.sin(v_total/2)
              + Math.cos(v1) * Math.cos(v2)
              * Math.sin(a_total/2) * Math.sin(a_total/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;

        return d;
    };

    function LatLon(lat, lon) {
    // allow instantiation without 'new'
    if (!(this instanceof LatLon)) return new LatLon(lat, lon);
    this.lat = Number(lat);
    this.lon = Number(lon);
    }

    //calculate radians
    function toRad(x) {
      return x * Math.PI / 180;
    }
