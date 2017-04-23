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
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBE3e4Xerq2saFeitH6Dooaqy_zS1fSM0"></script>
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

      //toggle stylesheets
      function toggleStyles(){
      $('.toggleBtn').on('click', function() {
        var href = $('#light').attr('href');
        if(href == 'jquery/themes/MovieO_Light.css'){
          $('#light').attr('href','jquery/themes/MovieO_Dark.css');
          //$(".flip-checkbox-2").val("dark");
        }
        else{
          $('#light').attr('href','jquery/themes/MovieO_Light.css');
          //$(".flip-checkbox-2").val("light");
        }
    //$(".flip-checkbox-2:visible").flipswitch().flipswitch( "refresh" );
      });
    }

      //load MyList page movies
      //on page load
      $( document ).on( "pagecreate", "#list-page", function() {
        $.ajax({
            type: "GET",
            url: "load_mylist.php",
            success: function(result){
                    //$("#message")[0].value = "Success";
                    //alert("My List fetch success! " + result);
                    result = $.parseJSON(result);
                    console.log(result);
                    $.each(result, function (key, value) {
                      //$('#listviewpage').append(list);
                      console.log("key: " + key + " value: " +value.review);
                        $('#listviewpage').append("<li onclick='loadMovieDataFromList(\x22" +  value.id + "\x22)'><a href='#'>"
                        + "<h2>" + value.movie_title + "</h2>"
                        +"<p> Your Score: " + value.score + "</p>" + "<p>" + value.review +"</p>" + "</a></li>").listview('refresh');;
                    });
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("User review fetch error " + xhr.responseText);
            }
        });
        //toggleBtn();

    });

    function loadMovieDataFromList(id){
      var id = id;
      $.ajax({
          type: "POST",
          url: "load_movies_from_list.php",
          data: "&id="+id,
          dataType : 'json',
          success: function(result){
                  console.log(result);
                  //$("#message")[0].value = "Success";
                  //alert("Movie for List fetch success! " + result);
                  loadMovieData(result);
                  window.location.replace("index.php#movie-page");
          },
          error: function(xhr, status, error){
              //$("#message")[0].value = "Ajax error!"+result;
              alert("Movie for list fetch error " + xhr.responseText);
          }
      });
    }

      //load user reviews
      function loadUserReviews(){
        $.ajax({
            type: "GET",
            url: "load_user_reviews.php",
            success: function(result){
                    //$("#message")[0].value = "Success";
                    //alert("User reviews fetch success! " + result);
                    result = $.parseJSON(result);
                    console.log(result);
                    $.each(result, function (key, value) {
                      var list = $('<ul></ul>');
                      $('#userReviews').append(list);
                      console.log("key: " + key + " value: " +value.review);
                        list.append("<li onclick='goToPublicProfile(\x22" +  value.id + "\x22)'>" + "<div style='width:5%; height:5%;'><img src='" + value.avatar + "' style='max-width:100%; max-height:100%;'></div>"
                        + "<h3>" + value.username + ", " + value.location + " : " + value.date +"</h3>"
                                    +"<h4> Score: " + value.score + "</h4>" + "<p>" + value.review +"</p>" + "</li>");
                    });
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("User review fetch error " + xhr.responseText);
            }
        });
      }

      //load PUBLIC user reviews
      function loadPublicUserReviews(){
        $.ajax({
            type: "GET",
            url: "load_public_user_reviews.php",
            success: function(result){
                    //$("#message")[0].value = "Success";
                    //alert("User reviews fetch success! " + result);
                    result = $.parseJSON(result);
                    console.log(result);
                    $('#userReviews2').empty();
                    $.each(result, function (key, value) {
                      var list = $('<ul></ul>');
                      $('#userReviews2').append(list);
                      console.log("key: " + key + " value: " +value.review);
                        list.append("<li onclick='goToPublicProfile(\x22" +  value.id + "\x22)'>" + "<div style='width:5%; height:5%;'><img src='" + value.avatar + "' style='max-width:100%; max-height:100%;'></div>"
                        + "<h3>" + value.username + ", " + value.location + " : " + value.date + "</h3>"
                                    +"<h4> Score: " + value.score + "</h4>"+ "<p>" + value.review +"</p>" + "</li>");
                    });
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("User review fetch error " + xhr.responseText);
            }
        });
      }

      //on page load of profile
      $( document ).on( "pagecreate", "#profile-page", function() {
        checkLogin();
        loadUserReviews();
      });

      //on page load of public profile
      $( document ).on( "pageshow", "#public-profile-page", function() {
        loadPublicUserReviews();
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
                    //alert("Movie fetch success! " + result);
                    result = $.parseJSON(result);
                    console.log(result);
                    $('#reviews').empty();
                    $.each(result, function (key, value) {
                      var list = $('<ul></ul>');
                      $('#reviews').append(list);
                      console.log("key: " + key + " value: " +value.review);
                        list.append("<li onclick='goToPublicProfile(\x22" +  value.id + "\x22)'>" + "<div style='width:5%; height:5%;'><img src='" + value.avatar + "' style='max-width:100%; max-height:100%;'></div>" + "<h3>" + value.username + ", " + value.location + " : "+ value.date +"</h3>" + "<h4> Score: " + value.score + "</h4>"
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
        var lat = sessionStorage.getItem("userLat");
        var lng = sessionStorage.getItem("userLong");;
        console.log("lat: "+lat +" lng: " +lng);
        var review = $('#txtAreaReview').val().trim();
        var score = $('#ratingdiv').raty('score');
        var title = $('#title').text().replace("&nbsp;","").trim();
        var release_Date = $('#releasedate').text().replace("Release Date:","").trim();
        var user_id = $('#sessionuserid').text();
        var movie_id = +sessionStorage.getItem("movie_id");
        //console.log("movie id=" + movie_id);
        //var movie_id = movie_id.replace('"','').trim();
        //console.log("movie id=" + movie_id);
        //alert("review text: " + review + ", user id: "+user_id + ", title: " +title + ", release: " +release_Date + ", score " + score +" LatLng: " +lat+" "+lng + " movie_id="+movie_id);
        $.ajax({
            type: "POST",
            url: "review_movie.php",
            data: "review="+review +"&title="+title+"&release_date="+release_Date+"&user_id=" + user_id + "&score=" +score + "&lat="+lat + "&lng="+lng + "&movie_id="+movie_id,
            success: function(result){
                    //alert("Review save Success! " + JSON.stringify(result.Title));
                    //reload reviews
                    loadReviews(null, title);
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("Review JS error!" + xhr.responseText);
            }
        });
      };

      //insert movie data
      function saveMovie(){
        var title = $('#title').text().replace("&nbsp;","").trim();
        var release_Date = $('#releasedate').text().replace("Release Date:","").trim();
        var synopsis = $('#synopsis').text().trim();
        var starring = $('#starring').text().trim();
        var awards = $('#awards').text().trim();
        var metascore = $('#metascore').text().trim();
        var poster = $('#posterimg').prop('src');

        var form_data = new FormData();
        form_data.append("poster", poster);
        form_data.append("title", title);
        form_data.append("release_date", release_Date);
        form_data.append("synopsis", synopsis);
        form_data.append("starring", starring);
        form_data.append("awards", awards);
        form_data.append("metascore", metascore);

        $.ajax({
            type: "POST",
            url: "save_movie.php",
            data: form_data,
            contentType: false,
            processData: false,
            //"&title="+title+"&release_date="+release_Date+"&synopsis="+synopsis+"&starring="+starring+"&awards="+awards+"&metascore="+metascore,
            success: function(result){
                    //$("#message")[0].value = "Success";
                    //alert("Movie save Success! " + result);
                    sessionStorage.setItem("movie_id", result);
                    //now also insert review
                    insertReview();
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("Movie save JS error!" + xhr.responseText);
            }
        });

      }

      //insert user review
      function insertReview2(){
        var review = $('#txtAreaReview2').val().trim();
        var score = $('#ratingdiv2').raty('score');
        var created_by_user_id = $('#sessionuserid').text();
        var user_id = $('#useridhidden').text();
        //alert("review text: " + review + ", user id: "+user_id + ", score " + score);
        $.ajax({
            type: "POST",
            url: "review_user.php",
            data: "review="+review +"&created_by_user_id=" + created_by_user_id + "&score=" +score + "&user_id=" + user_id,
            success: function(result){
                    //$("#message")[0].value = "Success";
                    //alert("User Review save Success! " + JSON.stringify(result.Title));
                    loadPublicUserReviews();
            },
            error: function(xhr, status, error){
                //$("#message")[0].value = "Ajax error!"+result;
                alert("User Review JS error!" + xhr.responseText);
            }
        });
      };

      //get gps co-ordinates when page first loadSearchData
      $( document ).on( "pageshow", function() {
        if($("#registerlink").is(":visible")){
          getPosition();
        };
      });

      //check user logged in on load of main-page data role, also grab gps position
      $( document ).on( "pagecreate", "#main-page", function() {
          checkLogin();
          loadGeoData();
          toggleStyles();
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
                        //alert("Success! " + JSON.stringify(result.Title));
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
                    $( document ).on( "pageshow", "#map-page", function() {
                    checkLogin();

                    //geocoder
                    var geocoder = new google.maps.Geocoder;

                      var lat1 = sessionStorage.getItem("userLat");
                      var lng1 = sessionStorage.getItem("userLong");
                      var latLong = lat1 + "," +lng1;

                      var input = latLong;
                      var latlngStr = input.split(',', 2);
                      var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
                      //use geocoder to find current place name and use that to grab results of map search query
                      geocoder.geocode({'location': latlng}, function(results, status) {
                        if (status == "OK"){
                          if (results[0]){
                            var currentLoc = results[0].address_components[5].long_name;
                            //alert("location name = " + results[0].address_components[5].long_name);
                            //load map
                            $('#map-canvas').html('<iframe width="100%" height="70%" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/search?key=AIzaSyCoSolGmCfXef_f3hK7qtxWdUFGwCfFQqE&center='+ latLong +'&zoom=11&q=cinemas+near+'+ currentLoc +'" allowfullscreen></iframe>');
                            //$('#map-canvas').css('background-color','red');
                            //$('#map-canvas').attr('style', 'background-color: red !important');
                          };
                        };
                      });
                    });

  function loadSearchData(e){
      //add movie title to global variable
      movieTitle = e.Title;
      $('#autocomplete').html(
        '<li class="ui-first-child ui-last-child"><a href="#movie-page" class="ui-btn ui-btn-icon-right ui-icon-carat-r"> <img src="'+ e.Poster + '">'+
        '<h2 id="searchTitle">' + e.Title.replace("&nbsp;"," ") + '</h2>'+
        '<div><p>' + e.Plot + '</p></div>'+
        '<p class="ui-li-aside">' + e.Released+ '</p></a></li>'
      );
      loadMovieData(e);
  };

      //movie page function
      function loadMovieData(e){
        //check for &nsbp in title
        //alert("title is " +e.movie_title);
        var title = "";
        if((e.Title.indexOf("&nsbp;")) != -1){
          title = e.Title.replace("&nbsp;"," ");
        }
        else{
          title = e.Title;
        }
        $('#title').html('<h2>' + title +'</h2>');
         $('#releasedate').html('Release Date: ' +e.Released);
          $('#synopsis').html(e.Plot);
          $('#starring').html(e.Actors);
          $('#awards').html(e.Awards);
          $('#metascore').html(e.Metascore);
          $('#posterdiv').html('<img id="posterimg" src="' + e.Poster +'" style="max-width:100%; max-height:100%;"/>');
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
                    //alert("username : " + user);
                    var email = $("#email")[0].value;
                    var password = $("#regpassword")[0].value;
                    $.ajax({
                        type: "POST",
                        url: "register.php",
                        data: "username="+user+"&email="+email+"&password="+password,
                        success: function(result){
                                //$("#message")[0].value = "Success";
                                //alert("Success!");
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

                                if (result != "Login Failed: Invalid Details")
                                {
                                    //alert(JSON.stringify(result));
                                    window.location.replace("index.php#main-page");
                                    //populate user data
                                    $("#namespan").html(result.username);
                                    $("#emailspan").html(result.email);
                                    $("#locationspan").html(result.location);
                                    $("#aboutmespan").html(result.about_me);
                                    $("#profileavatar").html("<img src="+ result.avatar +" style='max-width:100%; border-radius:0.5em; max-height:100%;'/>");
                                    $("#sessionuserid").html(result.id);

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

        //login on press of enter
        $(document).ready(function(){
        $("#password").keypress(function(e){
          if(e.which == 13){
            login();
          };
        });
      });

        /*//update profile
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
      };*/

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
            //alert("Update ajax worked " + data);
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

    function goToPublicProfile(id){
      //get user name
      var userid = id;
      //alert("public id  = " + userid);
      $.ajax({
          type: "POST",
          url: "load_public_user_page.php",
          data: "userid=" + userid,
          success: function(result){
                  //$("#message")[0].value = "Success";
                  //set hidden div to save user id for insertReview2() use
                  $('#useridhidden').html(userid);
                  //activate rating stars
                  $('#ratingdiv2').raty({
                    starOff: 'images/star-off.png',
                    starOn: 'images/star-on.png',
                    size: 24
                  });
                  result = JSON.parse(result);
                  //alert(JSON.stringify(result));
                  //populate user data
                  $("#namespan2").html(result.username);
                  $("#emailspan2").html(result.email);
                  $("#locationspan2").html(result.location);
                  $("#aboutmespan2").html(result.about_me);
                  $("#profileavatar2").html("<img src="+ result.avatar +" style='max-width:100%; border-radius:0.5em; max-height:100%;'/>");

                  //redirect user to public window
                  window.location.replace("index.php#public-profile-page");
          },
          error: function(xhr, status, error){
              //$("#message")[0].value = "Ajax error!"+result;
              alert("Public user load JS error!" + xhr.responseText);
          }
      });

    }


</script>


</head>
<body>
    <div data-role="page" id="loginpage">
        <div data-role="header" data-theme="b">
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
      <div data-role="header" data-theme="b">
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
            <div data-role="panel" id="leftpanel1" data-position="left" data-display="reveal" data-theme="b"
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
                  <a href="#leftpanel1"><img src="jquery/images/icons-png/bullets-white.png"/></a>
                </div>
        </div>
    </div>
    <div id="listviewDiv" class="ui-panel-wrapper">
      <ul data-role="listview" data-inset="true" id="listviewpage2">
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
    <button class="toggleBtn">Switch Theme</button>
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
      <button class="toggleBtn">Switch Theme</button>
      </div>
      <div id="leftIcon" style="margin-right:-20%; float:left; width:50px; height:50px;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
      <div id="facebookicon">
        <a href="https://www.facebook.com/sharer/sharer.php?u=https://movie-io.byethost7.com/index.php#public-list-page?id=30&picture=&title=&caption=Movie-io&quote=Heres+a+list+of+my+favourite+movies+on+Movie-io!&description="><img class="facebookimg" src="images/facebook.jpeg"/></a>
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
          <li onclick=""><a href="#main-page">Box Office</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page" class="ui-btn-active ui-state-persist">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>


<!--View Movie page -->
<div data-role="page" id="movie-page" data-url="movie-page" data-transition="slidedown">
  <div data-role="panel" id="leftpanel2" data-position="left" data-display="reveal" data-theme="b"
  class="ui-panel ui-panel-position-left ui-panel-display-reveal ui-body-a ui-panel-animate ui-panel-closed">

<div class="ui-panel-inner">
<h3>Main Menu</h3>
<a href="#profile-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Profile</a>
<p><a href="#map-page" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Cinemas</a> </p>
<p><a href="" onclick="logout()" data-rel="close" class="ui-btn ui-shadow ui-corner-all ui-btn-a ui-btn-inline">Log Out</a> </p>
</div>
</div>
<div data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
  <div data-role="navbar" data-theme="b">
    <div class="headertext">
      <span class="pagetitle">Movie io</span>
      </br>
      <button class="toggleBtn">Switch Theme</button>
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
    <input type="submit" name="submit" value="Submit" id="submit" onclick="saveMovie()"/>
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
      <button class="toggleBtn">Switch Theme</button>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
        <div class="ui-content ui-panel-wrapper">
          <div id="map-canvas" style="height:100%; width:100%">
            <!-- map loads here... -->
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

<!--profile page -->
<div data-role="page" id="profile-page" data-url="profile-page" data-transition="slidedown">
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
      <button class="toggleBtn">Switch Theme</button>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
  <div id="profilediv" class="ui-panel-wrapper">
    <div id="namediv" class="mainDivs">Username: <span id="namespan"><?php echo $_SESSION['username']; ?></span></div>
    <div id="profileavatar" style="width:20%; height:20%; margin-top:5%; margin-right:2%; float:right;"><img src="<?php echo $_SESSION['avatar']; ?>" style="max-width:100%; border-radius:0.5em; max-height:100%;"/></div>
    <div id="emaildiv" class="mainDivs">Email: <span id="emailspan"><?php echo $_SESSION['email'];?></span></div>
    <div id="aboutmediv" class="synopsis">About Me: <span id="aboutmespan"><?php echo $_SESSION['about_me'];?></span></div>
    <div id="locationdiv" class="mainDivs">My Location: <span id="locationspan"><?php echo $_SESSION['location'];?></span></div>
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

<!--PUBLIC profile page -->
<div data-role="page" id="public-profile-page" data-url="public-profile-page" data-transition="slidedown">
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
      <button class="toggleBtn">Switch Theme</button>
      </div>
      <div id="leftIcon" style="margin-right:-20%;">
        <a href="#leftpanel2"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
  <div id="profilediv" class="ui-panel-wrapper">
    <div id="namediv2" class="mainDivs">Username: <span id="namespan2"><?php echo $_SESSION['publicuser'];?></span></div>
    <div id="profileavatar2" style="width:20%; height:20%; margin-top:5%; margin-right:2%; float:right;"><img src="<?php echo $_SESSION['publicuseravatar']; ?>" style="max-width:100%; border-radius:0.5em; max-height:100%;"/></div>
    <div id="emaildiv2" class="mainDivs">Email: <span id="emailspan2"><?php echo $_SESSION['publicuseremail'];?></span></div>
    <div id="aboutmediv2" class="synopsis">About Me: <span id="aboutmespan2"><?php echo $_SESSION['publicuserabout'];?></span></div>
    <div id="locationdiv2" class="mainDivs">My Location: <span id="locationspan2"><?php echo $_SESSION['publicuserlocation'];?></span></div>
    <p>
      <div id="ratingdiv2" data-role="none" class="mainDivs">
      </div>
      <div id="reviewArea2" style="width:80%; height:40%; margin-top:5%; margin-left:2%; float:left">
      <textarea id="txtAreaReview2" placeholder="Comment on this users reviews...remember to be kind, this is a fun loving community!"></textarea>
      </div>
      <div id="submitbtn2" style="width:70%; height:20%; margin-top:5%; margin-left:2%; float:left">
      <input type="submit" name="submit" value="Submit" id="submit" onclick="insertReview2()"/>
      </div>
    </p>

    <div class="">Comments:

        <div id="userReviews2" class="userReviews">
        </div>
        <div id="useridhidden" style="display:none;"></div>
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
<div style="display: none;" id="sessionuserid"><?php echo $_SESSION['user']['id']; ?></div>
</body>
</html>
