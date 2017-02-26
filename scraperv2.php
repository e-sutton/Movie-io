
<?php
include('simple_html_dom.php');
//ini_set('display_errors', 1);

// parameter
$query_params = $_POST['moviename'];
//replace spaces with +
$query_params = str_replace(' ', '+', $query_params);

// Load IMDB page
$html = file_get_html('http://www.imdb.com/find?ref_=nv_sr_fn&q=' . $query_params . '&s=all');
//get movie title href
$title_url = $html->find("td[class=result_text] a", 0)->href;

//save movie title url
$act_page = file_get_html('http://www.imdb.com' . $title_url);

//start getting movie
$title = $act_page->find("h1[itemprop=name]", 0)->plaintext;
$release_date = $act_page->find("a[title='See more release dates']", 0)->plaintext;
$plot = $act_page->find("div[class='inline canwrap'] p", 0)->plaintext;
foreach ($act_page->find("span[itemprop=actors] a") as $key) {
  $actors = $actors . $key->plaintext . ', ';
};
$director = $act_page->find("span[itemprop=director] a", 0)->plaintext;
foreach ($act_page->find("span[itemprop=awards]") as $key) {
  $awards = $awards . $key->plaintext . ', ';
};
$metascore = $act_page->find("div[class=metacriticScore]", 0)->plaintext;
$poster = html_entity_decode(($act_page->find("div[class=poster] a", 0)->children(0)->src),ENT_COMPAT, 'UTF-8');


//return values
echo json_encode(array(
  "Title" => $title,
  "Released" => $release_date,
  "Plot" => $plot,
  "Actors" => $actors,
  "Director" => $director,
  "Awards" => $awards,
  "Metascore" => $metascore,
  "Poster" => $poster
));


?>
