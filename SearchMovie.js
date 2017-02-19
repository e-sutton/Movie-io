//search movie
$( document ).on( "pageinit", "#search-page", function() {
  alert('started');
    $('#autocomplete-input').keypress(function (e) {
        if (e.which == 13) {
          e.PreventDefault();
          alert('pressed!');
          var myMovie = $('#autocomplete-input').val();
          alert("Movie = " +myMovie);
          var myUrl, myData;

          myUrl = 'https://www.omdbapi.com/?t=' + myMovie + '&type=movie&tomatoes=true';
            $.ajax({
              url: myUrl,
              dataType: "json",
              success: loadData

            });

            //return false;    //<---- prevent text disappearing
          }
      });

function loadData(e){
    $('#autocomplete').html(
      '<li><a href="#"><h2>' + e.Title + '</h2><p>' + e.Year + '</p></a></li>'
    );
}
}
