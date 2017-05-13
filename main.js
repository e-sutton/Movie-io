/*
* main.js
* Rev 40
* 20/04/17
* @author: Eoin Sutton
*/

/*********************************************************/
//get password if forgottten
function getPassword(){
  var username = $("#usernameF")[0].value;
  $.ajax({
      type: "POST",
      url: "get_password.php",
      data: "&username="+username,
      success: function(result){
              alert("Thank you! If your username exists in our database, a new password will be emailed to you!");
              window.location.replace("index.php");
      },
      error: function(xhr, status, error){
          alert("Password fetch error " + xhr.responseText);
      }
  });
}

/*********************************************************/
//toggle stylesheets
function toggleStyles(){
$('.toggleBtn').on('click', function() {
  var href = $('#light').attr('href');
  if(href == 'jquery/themes/MovieO_Light.css'){
    $('#light').attr('href','jquery/themes/MovieO_Dark.css');
  }
  else{
    $('#light').attr('href','jquery/themes/MovieO_Light.css');
  }
});
}
/***********************************************************
 LIST PAGE */

//load MyList page movies
//on page load
$( document ).on( "pagebeforeshow", "#list-page", function() {
  $('#listviewpage').empty();
  $.ajax({
      type: "GET",
      url: "load_mylist.php",
      success: function(result){
              //$("#message")[0].value = "Success";
              //alert("My List fetch success! " + result);
              result = $.parseJSON(result);
              //console.log(result);
              $.each(result, function (key, value) {
                //$('#listviewpage').append(list);
                //console.log("key: " + key + " value: " +value.review);
                  $('#listviewpage').append("<li onclick='loadMovieDataFromList(\x22" +  value.id + "\x22)'><a href='#'>"
                  + "<h2>" + value.movie_title + "</h2>"
                  +"<p> Your Score: " + value.score + "</p>" + "<p>" + value.review +"</p>" + "</a></li>").listview('refresh');
              });
      },
      error: function(xhr, status, error){
          //$("#message")[0].value = "Ajax error!"+result;
          alert("User review fetch error " + xhr.responseText);
      }
  });
});

/***********************************************************/
//On Click of movie title
function loadMovieDataFromList(id){
var id = id;
$.ajax({
    type: "POST",
    url: "load_movies_from_list.php",
    data: "&id="+id,
    dataType : 'json',
    success: function(result){
            console.log(result);
            loadMovieData(result);
            window.location.replace("index.php#movie-page");
    },
    error: function(xhr, status, error){
        alert("Movie for list fetch error " + xhr.responseText);
    }
});
}
/************************************************************/

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
                  list.append("<li onclick='goToPublicProfile(\x22" +  value.id + "\x22)'>" + "<div style='width:20%; height:20%;'><img src='" + value.avatar + "' style='max-width:100%; max-height:100%; border-radius: 0.5em;'></div>"
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
                  list.append("<li onclick='goToPublicProfile(\x22" +  value.id + "\x22)'>" + "<div style='width:20%; height:20%;'><img src='" + value.avatar + "' style='max-width:100%; max-height:100%; border-radius: 0.5em;'></div>"
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
/*************************************************************/

//on page load of profile
$( document ).on( "pagecreate", "#profile-page", function() {
  checkLogin();
  loadUserReviews();
});

//on page load of public profile
$( document ).on( "pageshow", "#public-profile-page", function() {
  loadPublicUserReviews();
});
/*************************************************************/

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
                  list.append("<li onclick='goToPublicProfile(\x22" +  value.id + "\x22)'>" + "<div style='width:20%; height:20%;'><img src='" + value.avatar + "' style='max-width:100%; max-height:100%; border-radius: 0.5em;'></div>" + "<h3>" + value.username + ", " + value.location + " : "+ value.date +"</h3>" + "<h4> Score: " + value.score + "</h4>"
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
  var synopsis = $('#synopsis').text().replace("Plot: ","").trim();
  var starring = $('#starring').text().replace("Starring: ","").trim();
  var awards = $('#awards').text().replace("Awards: ","").trim();
  var metascore = $('#metascore').text().replace("Metascore: ","").trim();
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
/*************************************************************/

//insert user review
function insertReview2(){
  var review = $('#txtAreaReview2').val().trim();
  var score = $('#ratingdiv2').raty('score');
  var created_by_user_id = $('#sessionuserid').text();
  var user_id = $('#useridhidden').text();

  if(review || score){
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
}
else{
  alert("Please enter a rating or review");
}
};
/*************************************************************/

//get gps co-ordinates when page first loadSearchData
$( document ).on( "pageshow", function() {
  if($("#registerlink").is(":visible")){
    getPosition();
  };
});
/*************************************************************/

//check user logged in on load of main-page data role, also grab gps position
$( document ).on( "pagecreate", "#main-page", function() {
    checkLogin();
    loadGeoData();
    toggleStyles();
    //set facebook Link
    var user_id = $('#sessionuserid').text();
    var link = "https://www.facebook.com/sharer/sharer.php?u=https://movie-io.byethost7.com/public-list-page.php?id="+ user_id +"&picture=&title=&caption=Movie-io&quote=Heres+a+list+of+my+favourite+movies+on+Movie-io!&description=";
    $("#facebookLink").attr("href", link);
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
/*************************************************************/

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

/*************************************************************/

//search page function, WEB SCRAPER
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
              alert("Error! Movie not found! Please check the name or try including the year!" + xhr.responseText);
          }


      });
      return false;
    }

  });
});
/*************************************************************/
  //cinemas page function
  $( document ).on( "pagecreate", "#map-page", function() {
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
          $('#map-canvas').html('<iframe width="100%" height="70%" frameborder="0" style="border:0"' +
          'src="https://www.google.com/maps/embed/v1/search?key=AIzaSyCoSolGmCfXef_f3hK7qtxWdUFGwCfFQqE&center='+ latLong
          +'&zoom=11&q=cinemas+near+'+ currentLoc +'" allowfullscreen></iframe>');

          //$( ":mobile-pagecontainer" ).pagecontainer( "change", "#map-page");
        };
      };
    });
  });
/*************************************************************/

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
  var title = "";
  if((e.Title.indexOf("&nsbp;")) != -1){
    title = e.Title.replace("&nbsp;"," ");
  }
  else{
    title = e.Title;
  }
  $('#title').html('<h2>' + title +'</h2>');
   $('#releasedate').html('Release Date: ' +e.Released);
    $('#synopsis').html("Plot: " + e.Plot);
    $('#starring').html("Starring: " + e.Actors);
    $('#awards').html("Awards: " + e.Awards);
    $('#metascore').html("Metascore: " + e.Metascore);
    $('#posterdiv').html('<img id="posterimg" src="' + e.Poster +'" style="max-width:100%; max-height:100%;"/>');
    $('#ratingtext').html("Score:");
    //load star ratings
    $('#ratingdiv').raty({
      starOff: 'images/star-off.png',
      starOn: 'images/star-on.png',
      size: 24
    });
    //now load reviews
    loadReviews(e);
}
/*************************************************************/

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
                          window.location = "index.php";

                  },
                  error: function(xhr, status, error){
                      //$("#message")[0].value = "Ajax error!"+result;
                      alert("Sign up unsuccessful" + xhr.responseText);
                  }


              });


      //});
      }
  /*************************************************************/

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
                              location.reload();
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
/*************************************************************/

  //login on press of enter
  $(document).ready(function(){
  $("#password").keypress(function(e){
    if(e.which == 13){
      login();
    };
  });
});
/*************************************************************/

//submit profile update Form
function updateProfile2(){
  var name = $('input[name=username]').val().trim();
  var email = $('input[name=email]').val().trim();
  var about_me = $('input[name=about_me]').val().trim();
  var location = $('input[name=location]').val().trim();
  var file = $('input[type="file"]').val().trim();

if (name || email || about_me || location || file){
$("#formdata").submit(function(){
  var formData = new FormData($(this)[0]);
  $.ajax({
    type: "POST",
    url: "profile.php",
    data: formData,
    async: false,
    success: function(data){
      alert("Update Succesful, please login again to see changes");
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
}
else{
alert("Please enter some information!");
}
};
/*************************************************************/

//load public user profile page
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
            $('#ratingtext2').html("Rate this users reviews:");
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
/*************************************************************/
