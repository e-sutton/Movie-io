<?php
/* OLD SCRAPER File
*/

include('simple_html_dom.php');
ini_set('display_errors', 1);

// parameter
$query_params = $_POST['moviename'];

// Load IMDB page
$html = file_get_html('http://www.imdb.com/find?ref_=nv_sr_fn&q=' . $query_params . '&s=all');
//get movie title href
$title_url = $html->find("td[class=result_text] a", 0)->href;

//save movie title url
$act_page = file_get_html('http://www.imdb.com' . $title_url);

//start getting movie
$title = $act_page->find("h1[itemprop=name]", 0)->innertext;
$release_date = $act_page->find("a[title='See more release dates']", 0)->innertext;
$plot = $act_page->find("div[class='inline canwrap'] p", 0)->innertext;
$actors;
foreach ($act_page->find("span[itemprop=actors] a") as $key) {
  $actors = $actors . $key->innertext . ', ';
};
$director = $act_page->find("span[itemprop=director] a", 0)->innertext;
$awards;
foreach ($act_page->find("span[itemprop=awards]") as $key) {
  $awards = $awards . $key->innertext . ', ';
};
$metascore = $act_page->find("div[class=metacriticScore]", 0)->innertext;
$poster = $act_page->find("div[class=poster] a img", 0)->src;

//return values
echo json_encode(array(
  "title" => $title,
  "release_date" => $release_date,
  "plot" => $plot,
  "actors" => $actors,
  "director" => $director,
  "awards" => $awards,
  "metascore" => $metascore,
  "poster" => $poster
));


?>
