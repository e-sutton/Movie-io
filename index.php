<?php
/*
* index.php *
* Rev 1 *
* 26/02/2017 *
*
* @author Eoin Sutton *
* @reference (star rating): https://github.com/wbotelhos/raty
*/

    // get connection to DB
    include("connection.php");

    // Check whether user is logged in
    if(empty($_SESSION['user']))
    {
      // Rediret to main menu
      //echo '<script type="text/javascript">window.location.replace("index.php");</script>';
    }
    else
    {
        //if logged in, echo js function to direct page to #main-page role
        echo '<script type="text/javascript">window.location.replace("index.php#main-page");</script>';
    }

?>
<!DOCTYPE html>

<html>
<head>
  <title>Movie iO</title>
  <!-- jquery mobile links/scripts -->
  <script src="jquery/jquery-2.1.4.min.js"></script>
  <script src="jquery/jquery.mobile-1.4.5.min.js"></script>
  <script src="http://maps.googleapis.com/maps/api/js"></script>
  <script src="jquery/jquery.raty.js"></script>
  <!--<script src="SearchMovie.js"></script>-->
  <link rel="stylesheet" href="jquery/themes/MovieO_Red.css"/>
  <link rel="stylesheet" href="jquery/jquery.raty.css"/>
  <link rel="stylesheet" href="jquery/themes/jquery.mobile.icons.min.css" />
  <link href="jquery/jquery.mobile.structure-1.4.5.css" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!--sizing -->
  <script>

      //load user reviews
      $( document ).on( "pagecreate", "#profile-page", function() {
        $.ajax({
            type: "POST",
            url: "load_user_reviews.php",
            success: function(result){
                    //$("#message")[0].value = "Success";
                    alert("User reviews fetch success! " + result);
                    result = $.parseJSON(result);
                    console.log(result);
                    $.each(result, function (key, value) {
                      var list = $('<ul></ul>');
                      $('#userReviews').append(list);
                      console.log("key: " + key + " value: " +value.review);
                        list.append("<li>" + "<div style='width:5%; height:5%;'><img src='data:image/jpeg;charset=utf-8;base64," + value.avatar + "' style='max-width:100%; max-height:100%;'></div>" + "<h3>" + value.username + ", " + value.location + "</h3>"
                                    + "<p>" + value.review +"</p>" + "</li>");
                    });
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("User review fetch error " + xhr.responseText);
            }
        });
      });

      //find movie reviews
      function loadReviews(e, title){
        if(e){
        var title = e.Title.replace("&nbsp;"," ").trim();
      }
      else{
        var title = title.replace("&nbsp;"," ").trim();
      }
        console.log("title is " + title);
        $.ajax({
            type: "POST",
            url: "load_reviews.php",
            data: "&title="+title,
            success: function(result){
                    //$("#message")[0].value = "Success";
                    alert("Movie fetch success! " + result);
                    result = $.parseJSON(result);
                    console.log(result);
                    $.each(result, function (key, value) {
                      var list = $('<ul></ul>');
                      $('#reviews').append(list);
                      console.log("key: " + key + " value: " +value.review);
                        list.append("<li>" + "<div style='width:5%; height:5%;'><img src='thumb_IMG_1126_1024.jpg' style='max-width:100%; max-height:100%;'></div>" + "<h3>" + value.username + ", " + value.location + "</h3>" + "<h4> Score: " + value.score + "</h4>"
                                    + "<p>" + value.review +"</p>" + "</li>");
                    });
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("Movie fetch error " + xhr.responseText);
            }
        });
      };

      //insert movie review
      function insertReview(){
        var review = $('#txtAreaReview').val().trim();
        var score = $('#ratingdiv').raty('score');
        var title = $('#title').text().replace("&nbsp;","").trim();
        var release_Date = $('#releasedate').text().replace("Release Date:","").trim();
        var user_id = $('#sessionuserid').text();
        alert("review text: " + review + ", user id: "+user_id + ", title: " +title + ", release: " +release_Date + ", score " + score);
        $.ajax({
            type: "POST",
            url: "review_movie.php",
            data: "review="+review +"&title="+title+"&release_date="+release_Date+"&user_id=" + user_id + "&score=" +score,
            success: function(result){
                    //$("#message")[0].value = "Success";
                    alert("Review save Success! " + JSON.stringify(result.Title));
                    //reload reviews
                    loadReviews(null, title);
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("Review JS error!" + xhr.responseText);
            }
        });
      };

      //check user logged in on load of main-page data role
      $( document ).on( "pagecreate", "#main-page", function() {
          checkLogin();
        });

        function checkLogin(){
          //alert("pageinit function loaded");
          $.ajax({
              type: "GET",
              url: "check_login.php",
              success: function(result){
                  if(result === "Not Logged In"){
                    window.location.replace("index.php");
                  };

              },
              error: function(xhr, status, error){
                  //$("#message")[0].value = "Ajax error!"+result;
                  alert("check_login.pho error" + xhr.responseText);
              }
          });
        };

      //global variables
      var movieTitle = "";

            //logout
            function logout() {
              $.ajax({
                  type: "GET",
                  url: "logout.php",
                  success: function(result){
                      window.location.replace("index.php");
                  },
                  error: function(xhr, status, error){
                      //$("#message")[0].value = "Ajax error!"+result;
                      alert("logout.php error" + xhr.responseText);
                  }
              });
            };



      //search page function
  $( document ).on( "pagecreate", "#search-page", function() {
      //alert("pageinit function loaded");
      checkLogin();
      $('#autocomplete-input').keypress(function (e) {
          if (e.which == 13) {

            var myMovie = $('#autocomplete-input').val();

            $.ajax({
                type: "POST",
                url: "scraperv2.php",
                data: "moviename="+myMovie,
                dataType : 'json',
                success: function(result){
                        //$("#message")[0].value = "Success";
                        alert("Success! " + JSON.stringify(result.Title));
                        loadSearchData(result);

                },
                error: function(xhr, status, error){
                    //$("#message")[0].value = "Ajax error!"+result;
                    alert("Scraper JS error" + xhr.responseText);
                }


            });
            return false;
          }

        });
      });

      //cinemas page function
                      $( document ).on( "pagecreate", "#map-page", function() {
                    checkLogin();
                    //set map
                    var dublin = new google.maps.LatLng(53.348244, -6.267938);
                    var mapOptions = {
                        center: dublin,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        zoom: 9,
                        zoomControl: true,
                        zoomControlOptions: {
                            position: google.maps.ControlPosition.RIGHT_CENTER
                        },

                    };
                  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
                    //google.maps.event.trigger(map, 'resize');
                    //put marker on map
                    google.maps.event.addListener(map, 'click', function(e) {
                        var marker = new google.maps.Marker({
                            position: e.latLng,
                            map: map,
                            draggable: true, //set marker draggable
                            animation: google.maps.Animation.DROP //bounce animation
                        });

                        //focus view on current marker
                        map.panTo(e.latLng);
        });
    });

  function loadSearchData(e){
      //add movie title to global variable
      movieTitle = e.Title;
      $('#autocomplete').html(
        '<li class="ui-first-child ui-last-child"><a href="#movie-page" class="ui-btn ui-btn-icon-right ui-icon-carat-r"> <img src="'+ e.Poster + '">'+
        '<h2 style="color:white !important">' + e.Title.replace("&nbsp;"," ") + '</h2>'+
        '<div><p>' + e.Plot + '</p></div>'+
        '<p class="ui-li-aside">' + e.Released+ '</p></a></li>'
      );
      loadMovieData(e);
  };

      //movie page function
      function loadMovieData(e){
        $('#title').html('<h2 style="color:white !important">' + e.Title.replace("&nbsp;"," ") +'</h2>');
         $('#releasedate').html('Release Date: ' +e.Released);
          $('#synopsis').html(e.Plot);
          $('#starring').html('Starring: ' + e.Actors);
          $('#awards').html('Awards: ' + e.Awards);
          $('#metascore').html(' Metascore: ' + e.Metascore);
          $('#posterdiv').html('<img src="' + e.Poster +'" style="max-width:100%; max-height:100%;"/>');
          //load star ratings
          $('#ratingdiv').raty({
            starOff: 'images/star-off.png',
            starOn: 'images/star-on.png',
            size: 24
          });
          //now load reviews
          loadReviews(e);
  }


      function register(){

                //$(document).ready(function(){
                    var user = $("#regusername")[0].value;
                    alert("username : " + user);
                    var email = $("#email")[0].value;
                    var password = $("#regpassword")[0].value;
                    $.ajax({
                        type: "POST",
                        url: "register.php",
                        data: "username="+user+"&email="+email+"&password="+password,
                        success: function(result){
                                //$("#message")[0].value = "Success";
                                alert("Success!");
                                window.location = "index.html";

                        },
                        error: function(xhr, status, error){
                            //$("#message")[0].value = "Ajax error!"+result;
                            alert("Sign up unsuccessful" + xhr.responseText);
                        }


                    });


            //});
            }

      function login(){
                $(document).ready(function(){
                    var user = $("#username")[0].value;
                    var password = $("#password")[0].value;
                    $.ajax({
                        type: "POST",
                        url: "login.php",
                        dataType: 'json',
                        data: "username="+user+"&password="+password,
                        success: function(result){

                                if (result === "Logged In")
                                {
                                    alert(JSON.stringify(result));
                                    window.location.replace("index.php#main-page");
                                }
                                else
                                {
                                    alert("login failed: "+ JSON.stringify(result));
                                }

                        },
                        error: function(result){
                                alert("ajax fail");
                        }


                    });

            });

        };

        //update profile
        function updateProfile(){
          var username = $("#updatenametxtarea")[0].value;
          //alert("username = " + username);
          var email = $("#updateemailtxtarea")[0].value;
          var about_me = $("#updateaboutmetxtarea")[0].value;
          var location = $("#updatelocationtxtarea")[0].value;
          var filename = $("#filetoupload").val();
          var file = $("#filetoupload").files[0];
          if (username == "" && email == "" && about_me == "" && location == "" && filename == ""){
            alert("Please enter at least one update!")
          }
          else{
          $.ajax({
              type: "POST",
              url: "profile.php",
              data: "username="+username+"&email="+email+"&about_me="+about_me+"&location="+location,
              success: function(result){
                      //$("#message")[0].value = "Success";
                      alert("Profile update successful!\n Please logout and login to see changes");
              },
              error: function(xhr, status, error){
                  //$("#message")[0].value = "Ajax error!"+result;
                  alert("Profile update unsuccessful" + xhr.responseText + error.responseText);
              }
          });
        };
      };

      //submit profile update Form
      function updateProfile2(){
      $("#formdata").submit(function(){
        var formData = new FormData($(this)[0]);
        $.ajax({
          type: "POST",
          url: "profile.php",
          data: formData,
          async: false,
          success: function(data){
            alert("Update ajax worked " + data);
          },
          error: function(xhr, status, error){
              //$("#message")[0].value = "Ajax error!"+result;
              alert("Profile update unsuccessful" + xhr.responseText + error.responseText);
          },
          cache: false,
          contentType: false,
          processData: false
        });
        return false;
      });
    };


</script>


</head>
<body>
    <div data-role="page" id="loginpage">
        <div data-role="header" data-theme="a">
          <div style="text-align: center">
            <span id="mainTitle">Welcome to Movie iO!</span>
          </div>
        </div>
        <div role="main" class="ui-content">
            <div id="loginarea" style="text-align:center">
                <h3 id="logintitle">Login</h3>
                <label for="txt-first-name">Username</label>
                <input type="text" name="txt-first-name" id="username" value="">
                <label for="txt-password">Password</label>
                <input type="password" name="txt-password" id="password" value="">
                <br/>
                <button id="submit" onclick="login()">Login</button>
                <br/>
                <a href="#registerpage" data-transition="slidedown" id="registerlink">Register</a>
            </div>
        </div>
    </div>

    <div data-role="page" id="registerpage">
      <div data-role="header" data-theme="a">
        </div>
         <div role="main" class="ui-content">
            <div id="signuparea" style="text-align:center">
                <h3 id="logintitle">Sign Up</h3>
                <label for="txt-first-name">Username</label>
                <input type="text" name="txt-first-name" id="regusername" value="">
                <label for="txt-email">Email Address</label>
                <input type="text" name="txt-email" id="email" value="">
                <label for="txt-password">Password</label>
                <input type="password" name="txt-password" id="regpassword" value="">
                <br/>
                <button id="submit" onclick="register()"> Submit</button>
                <br/>
                <a href="#loginpage" data-transition="slideup" id="registerlink">Login</a>
            </div>
        </div>
    </div>

<!--Box Office/Main Page-->

    <div data-role="page" id="main-page" data-url="main-page">
            <div data-role="panel" id="leftpanel1" data-position="left" data-display="reveal" data-theme="a"
            class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

        <div class="ui-panel-inner">
          <h3>Main Menu</h3>
          <a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
          <p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
          <p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
        </div>
          </div>
        <div data-role="header" data-theme="a" data-position="fixed">
            <div data-role="navbar" data-theme="a">
              <div id="headertext" style="text-align:center">
                <span>Movie io</span>
                </div>
                <div id="leftIcon" style="margin-right:-20%;">
                  <a href="#leftpanel1"><img src="jquery/images/icons-png/bullets-white.png"/></a>
                </div>
        </div>
    </div>
    <div id="listviewDiv" class="ui-panel-wrapper">
    <ul data-role="listview" data-inset="true">
    <li><a href="#">
    <h2>Jurassic Park</h2>
    <p>1993</p></a>
    </li>
    <li><a href="#">
    <h2>Interstellar</h2>
    <p>2014</p></a>
    </li>
    <li><a href="#">
    <h2>Star Trek</h2>
    <p>2009</p></a>
    </li>
    <li><a href="#">
    <h2>The Dark Knight</h2>
    <p>2012</p></a>
    </li>
    <li><a href="#">
    <h2>Arrival</h2>
    <p>2016</p></a>
    </li>
</ul>
</div>
    <div data-role="footer" data-id="main-page" data-position="fixed" data-tap-toggle="false">
    <div data-role="navbar">
        <ul>
            <li onclick=""><a href="#main-page" class="ui-btn-active ui-state-persist">Box Office</a></li>
            <li onclick=""><a href="#search-page">Search</a></li>
            <li onclick=""><a href="#list-page">My List</a></li>
        </ul>
    </div><!-- /navbar -->
    </div><!-- /footer -->
</div>

<!--Search Pagae-->

<div data-role="page" id="search-page" data-url="search-page" data-transition="slidedown">
  <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="a"
  class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

<div class="ui-panel-inner">
<h3>Main Menu</h3>
<a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
<p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
<p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
</div>
</div>
<div data-role="header" data-theme="a" data-position="fixed">
  <div data-role="navbar" data-theme="a">
    <div id="headertext" style="text-align:center">
      <span>Movie io</span>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
<div id="searchInputDiv" class="ui-panel-wrapper">
  <form class="ui-filterable">
  <input id="autocomplete-input" data-type="search" placeholder="Enter a movie name...">
</form>
  <ul id="autocomplete" data-role="listview" data-inset="true" data-filter="true" data-input="autocomplete-input">
 </ul>
</div>
  <div data-role="footer" data-id="search-page-footer" data-position="fixed" data-tap-toggle="false">
  <div data-role="navbar">
      <ul>
          <li onclick=""><a href="#main-page">Box Office</a></li>
          <li onclick=""><a href="#search-page" class="ui-btn-active ui-state-persist">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>

<!--List Page-->

<div data-role="page" id="list-page" data-url="list-page" data-transition="slidedown">
  <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="a"
  class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

<div class="ui-panel-inner">
<h3>Main Menu</h3>
<a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
<p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
<p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
</div>
</div>
<div data-role="header" data-theme="a" data-position="fixed">
  <div data-role="navbar" data-theme="a">
    <div id="headertext" style="text-align:center">
      <span>Movie io</span>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
    <!--Content here

     -->
<div data-role="footer" data-id="search-page-footer" data-position="fixed" data-tap-toggle="false">
  <div data-role="navbar">
      <ul>
          <li onclick=""><a href="#main-page">Box Office</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page" class="ui-btn-active ui-state-persist">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>


<!--View Movie page -->
<div data-role="page" id="movie-page" data-url="movie-page" data-transition="slidedown">
  <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="a"
  class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

<div class="ui-panel-inner">
<h3>Main Menu</h3>
<a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
<p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
<p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
</div>
</div>
<div data-role="header" data-theme="a" data-position="fixed">
  <div data-role="navbar" data-theme="a">
    <div id="headertext" style="text-align:center">
      <span>Movie io</span>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
<div id="moviediv" class="ui-panel-wrapper">
    <div id="title" class="mainDivs"></div>
    <div id="posterdiv" class="posterdiv"></div>
    <div id="releasedate" class="mainDivs"></div>
    <div id="synopsis" class="synopsis"></div>
    <div id="starring" class="mainDivs"></div>
    <div id="awards" class="mainDivs"></div>
    <div id="metascore" class="mainDivs"></div>
    <div id="ratingtext" class="mainDivs"></div><p>
    <div id="ratingdiv" data-role="none" class="mainDivs">
    </div>
    <div id="reviewArea" style="width:80%; height:40%; margin-top:5%; margin-left:2%; float:left">
    <textarea id="txtAreaReview" placeholder="What did you think?"></textarea>
    </div>
    <div id="submitbtn" style="width:70%; height:20%; margin-top:5%; margin-left:2%; float:left">
    <input type="submit" name="submit" value="Submit" id="submit" onclick="insertReview()"/>
    </div>
    <div id="reviewSection">
    <div class="reviewSection">Reviews:</div>
        <div id="reviews" class="userReviews">
        </div>
    </div>
</div>
  <div data-role="footer" data-id="search-page-footer" data-position="fixed" data-tap-toggle="false">
  <div data-role="navbar">
      <ul>
          <li onclick=""><a href="#main-page">Box Office</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>

<!--Cinema page -->

    <div data-role="page" id="map-page" data-url="map-page">
  <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="a"
  class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

<div class="ui-panel-inner">
<h3>Main Menu</h3>
<a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
<p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
<p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
</div>
</div>
<div data-role="header" data-theme="a" data-position="fixed">
  <div data-role="navbar" data-theme="a">
    <div id="headertext" style="text-align:center">
      <span>Movie io</span>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
        <div role="main" class="ui-content ui-panel-wrapper" id="map-canvas">
            <!-- map loads here... -->
        </div>

       <div data-role="footer" data-id="search-page-footer" data-position="fixed" data-tap-toggle="false">
  <div data-role="navbar">
      <ul>
          <li onclick=""><a href="#main-page">Box Office</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
    </div>

<!--profile page -->
<div data-role="page" id="profile-page" data-url="profile-page" data-transition="slidedown">
  <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="a"
  class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

<div class="ui-panel-inner">
<h3>Main Menu</h3>
<a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
<p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
<p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
</div>
</div>
<div data-role="header" data-theme="a" data-position="fixed">
  <div data-role="navbar" data-theme="a">
    <div id="headertext" style="text-align:center">
      <span>Movie io</span>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
  <div id="profilediv" class="ui-panel-wrapper">
    <div id="namediv" class="mainDivs">Username: <?php echo $_SESSION['username'];?></div>
    <div id="profileavatar" style="width:20%; height:20%; margin-top:5%; margin-right:2%; float:right;"><img src="data:image/jpeg;charset=utf-8;base64,<?php echo $_SESSION['avatar']; ?> " style="max-width:100%; border-radius:0.5em; max-height:100%;"/></div>
    <div id="emaildiv" class="mainDivs">Email: <?php echo $_SESSION['email'];?></div>
    <div id="aboutmediv" class="synopsis">About Me: <?php echo $_SESSION['about_me'];?></div>
    <div id="locationdiv" class="mainDivs">My Location: <?php echo $_SESSION['location'];?></div>
    <p>
      <form id="formdata" method="post" enctype="multipart/form-data" class="mainDivs">
      <!--<div id="updatename" style="width:40%; height:80%; margin-top:5%; margin-left:2%; float:left">
      <textarea id="updatenametxtarea" placeholder="Update Name..."></textarea>-->
      <input type="text" name="username" placeholder="Update name..."/>
      <input type="text" name="email" placeholder="Update email..."/>
      <input type="text" name="about_me" placeholder="Update about me..."/>
      <input type="text" name="location" placeholder="Update location..."/>
      Upload your image: <input type="file" name="myfile" id="filetoupload">
      <button onclick="updateProfile2()">Submit</button>
    </form>
    </p>
    <div id="userReviewSection">
    <div class="reviewSection">Comments:</div>
        <div id="userReviews" class="userReviews">
        </div>
    </div>
  </div>
<div data-role="footer" data-id="search-page-footer" data-position="fixed" data-tap-toggle="false">
  <div data-role="navbar">
      <ul>
          <li onclick=""><a href="#main-page">Box Office</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>
<!--hidden varibles to hold php values -->
<div style="display: none;" id="sessionuserid"><?php echo $_SESSION['user']['id'];?></div>
</body>
</html>
