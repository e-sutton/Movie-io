<?php
/*
* public-list-page.php *
* Rev 1 *
* 26/02/2017 *
*
* @author Eoin Sutton *
* @reference (star rating): https://github.com/wbotelhos/raty
*/

// get connection to DB
include("connection.php");

?>

<!DOCTYPE html>

<html>
<head>
  <title>Movie iO</title>
  <!-- jquery mobile links/scripts -->
  <script src="jquery/jquery-2.1.4.min.js"></script>
  <script src="jquery/jquery.mobile-1.4.5.min.js"></script>
  <script src="jquery/jquery.raty.js"></script>
  <script src="geolocation.js"></script>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="jquery/jquery.raty.css"/>
  <link rel="stylesheet" href="jquery/themes/jquery.mobile.icons.min.css" />
  <link rel="stylesheet" href="jquery/themes/MovieO_Light.css" id="light" />
  <link rel="stylesheet alternate" href="jquery/themes/MovieO_Dark.css" id="dark" />
  <link rel="stylesheet" href="jquery/jquery.mobile.structure-1.4.5.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!--sizing -->
  <script>
  var id = "<?php echo $_GET['id'];?>";
  //load MyList page movies
  //on page load
  $(document).ready(function(){
    $.ajax({
        type: "POST",
        url: "load_mylist.php",
        data: "id="+id,
        success: function(result){
                result = $.parseJSON(result);
                $.each(result, function (key, value) {
                  //$('#listviewpage').append(list);
                    $('#listviewpage').append("<li onclick='loadMovieDataFromList(\x22" +  value.id + "\x22)'><a href='https://movie-io.byethost7.com/index.php'>"
                    + "<h2>" + value.movie_title + "</h2>"
                    +"<p> Your Score: " + value.score + "</p>" + "<p>" + value.review +"</p>" + "</a></li>").listview('refresh');;
                });
        },
        error: function(xhr, status, error){
            //$("#message")[0].value = "Ajax error!"+result;
            alert("User review fetch error " + xhr.responseText);
        }
    });

});
  </script>
  </head>
  <body>

  <!--List Page-->

  <div data-role="page" id="public-list-page" data-url="public-list-page" data-transition="slidedown">
    <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="b"
    class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

  <div class="ui-panel-inner">
  <h3>Main Menu</h3>
  <a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
  <p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
  <p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
  </div>
  </div>
  <div data-role="header" data-theme="b" data-position="fixed">
    <div data-role="navbar" data-theme="b">
      <div class="headertext">
        <span class="pagetitle">Movie io</span>
        </br>
        <button class="toggleBtn ui-btn-b">Switch Theme</button>
        </div>
        <div class="leftIcon">
          <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
        </div>
  </div>
  </div>
      <div id="listviewDiv2" class="ui-panel-wrapper">
        <ul data-role="listview" data-inset="true" id="listviewpage">
        </ul>
      </div>

  <div data-role="footer" data-id="search-page-footer" data-position="fixed" data-tap-toggle="false">
    <div data-role="navbar">
        <ul>
            <li onclick=""><a href="#main-page">What's Hot</a></li>
            <li onclick=""><a href="#search-page">Search</a></li>
            <li onclick=""><a href="#list-page" class="ui-btn-active ui-state-persist">My List</a></li>
        </ul>
    </div><!-- /navbar -->
    </div><!-- /footer -->
  </div>

  </body>
  </html>
