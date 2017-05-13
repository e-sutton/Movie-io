<?php
/*
* index.php
* Rev 10
* 20/04/2017
*
* @author Eoin Sutton
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
  <script src="main.js"></script>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="jquery/jquery.raty.css"/>
  <link rel="stylesheet" href="jquery/themes/jquery.mobile.icons.min.css" />
  <link rel="stylesheet" href="jquery/themes/MovieO_Light.css" id="light" />
  <link rel="stylesheet alternate" href="jquery/themes/MovieO_Dark.css" id="dark" />
  <link rel="stylesheet" href="jquery/jquery.mobile.structure-1.4.5.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!--sizing -->

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
                <button onclick="login()">Login</button>
                <br/>
                <a href="#registerpage" data-transition="slidedown" id="registerlink">Register</a>
                <br/>
                <a href="#getpasswordpage" data-transition="slidedown" id="passwordlink">Forgot Your Password?</a>
            </div>
        </div>
    </div>

    <div data-role="page" id="registerpage">
      <div data-role="header" data-theme="b">
        <div style="text-align: center">
          <span id="mainTitle">Welcome to Movie iO!</span>
        </div>
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

    <div data-role="page" id="getpasswordpage">
        <div data-role="header" data-theme="b">
          <div style="text-align: center">
            <span id="mainTitle">Welcome to Movie iO!</span>
          </div>
        </div>
        <div role="main" class="ui-content">
            <div id="forgotpassarea" style="text-align:center">
                <h3 id="Ftitle">To reset your password, please enter your username</h3>
                <label for="txt-first-name">Username</label>
                <input type="text" name="txt-first-nameF" id="usernameF" value="">
                <br/>
                <button id="submit" onclick="getPassword()">Get Password</button>
                <br/>
                <a href="#loginpage" data-transition="slideup" id="registerlink">Login</a>
            </div>
        </div>
    </div>

<!--What's Hot/Main Page-->

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
            <li onclick=""><a href="#main-page" class="ui-btn-active ui-state-persist">What's Hot</a></li>
            <li onclick=""><a href="#search-page">Search</a></li>
            <li onclick=""><a href="#list-page">My List</a></li>
        </ul>
    </div><!-- /navbar -->
    </div><!-- /footer -->
</div>

<!--Search Pagae-->

<div data-role="page" id="search-page" data-url="search-page">
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
          <li onclick=""><a href="#main-page">What's Hot</a></li>
          <li onclick=""><a href="#search-page" class="ui-btn-active ui-state-persist">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>

<!--List Page-->

<div data-role="page" id="list-page" data-url="list-page">
  <div data-role="panel" id="leftpanel3" data-position="left" data-display="reveal" data-theme="b"
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
        <a href="#leftpanel3"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
      <div id="facebookicon">
        <a id="facebookLink"><img class="facebookimg" src="images/facebook.jpeg"/></a>
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


<!--View Movie page -->
<div data-role="page" id="movie-page" data-url="movie-page">
  <div data-role="panel" id="leftpanel4" data-position="left" data-display="reveal" data-theme="b"
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
      <button class="toggleBtn ui-btn-b">Switch Theme</button>
      </div>
      <div class="leftIcon">
        <a href="#leftpanel4"><img src="jquery/images/icons-png/bullets-white.png"/></a>
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
          <li onclick=""><a href="#main-page">What's Hot</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>

<!--Cinema page -->

  <div data-role="page" id="map-page" data-url="map-page">
  <div data-role="panel" id="leftpanel5" data-position="left" data-display="reveal" data-theme="b"
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
        <a href="#leftpanel5"><img src="jquery/images/icons-png/bullets-white.png"/></a>
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
          <li onclick=""><a href="#main-page">What's Hot</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
    </div>

<!--profile page -->
<div data-role="page" id="profile-page" data-url="profile-page">
  <div data-role="panel" id="leftpanel6" data-position="left" data-display="reveal" data-theme="b"
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
      <button class="toggleBtn ui-btn-b">Switch Theme</button>
      </div>
      <div class="leftIcon">
        <a href="#leftpanel6"><img src="jquery/images/icons-png/bullets-white.png"/></a>
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
          <li onclick=""><a href="#main-page">What's Hot</a></li>
          <li onclick=""><a href="#search-page">Search</a></li>
          <li onclick=""><a href="#list-page">My List</a></li>
      </ul>
  </div><!-- /navbar -->
  </div><!-- /footer -->
</div>

<!--PUBLIC profile page -->
<div data-role="page" id="public-profile-page" data-url="public-profile-page">
  <div data-role="panel" id="leftpanel7" data-position="left" data-display="reveal" data-theme="b"
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
      <button class="toggleBtn ui-btn-b">Switch Theme</button>
      </div>
      <div class="leftIcon">
        <a href="#leftpanel7"><img src="jquery/images/icons-png/bullets-white.png"/></a>
      </div>
</div>
</div>
  <div id="profilediv" class="ui-panel-wrapper">
    <div id="namediv2" class="mainDivs">Username: <span id="namespan2"><?php echo $_SESSION['publicuser'];?></span></div>
    <div id="profileavatar2" style="width:20%; height:20%; margin-top:5%; margin-right:2%; float:right;"><img src="<?php echo $_SESSION['publicuseravatar']; ?>" style="max-width:100%; border-radius:0.5em; max-height:100%;"/></div>
    <div id="emaildiv2" class="mainDivs">Email: <span id="emailspan2"><?php echo $_SESSION['publicuseremail'];?></span></div>
    <div id="aboutmediv2" class="synopsis">About Me: <span id="aboutmespan2"><?php echo $_SESSION['publicuserabout'];?></span></div>
    <div id="locationdiv2" class="mainDivs">My Location: <span id="locationspan2"><?php echo $_SESSION['publicuserlocation'];?></span></div>
    <div id="ratingtext2" class="mainDivs"></div>
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

    <div class="">

        <div id="userReviews2" class="userReviews">
        </div>
        <div id="useridhidden" style="display:none;"></div>
   </div>
  </div>
<div data-role="footer" data-id="search-page-footer" data-position="fixed">
  <div data-role="navbar">
      <ul>
          <li onclick=""><a href="#main-page">What's Hot</a></li>
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
