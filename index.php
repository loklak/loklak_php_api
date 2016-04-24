<?php
include('loklak.php');

// $baseURL = 'http://172.30.107.104:9000';
$baseURL = 'http://loklak.org';

$l = new Loklak($baseURL);

$values = $l->hello();
$helloResponse = json_decode($values);
$values = $l->peers();
$peersResponse = json_decode($values);
$values = $l->status();
$statusResponse = json_decode($values);
$values = $l->search("fossasia");
//These are other possible inputs
// $l->search("fossasia","2015-01-01");
// $l->search("fossasia","2015-01-01", "2016-01-01");
// $l->search("fossasia","2015-01-01", "2016-01-01", "sudheesh001");
// $l->search("fossasia","2015-01-01", "2016-01-01", "sudheesh001", 10);
// $l->search("fossasia","", "", "", 10);
// $l->search("fossasia","2015-01-01", "", "sudheesh001", 10);
// $l->search("fossasia","2015-01-01", "2016-01-01", "", 10);
// $l->search("fossasia","", "", "", 10);
$searchResponse = json_decode($values);
$values = $l->geocode('Hyderabad');
$geocodeResponse = json_decode($values);
$values = $l->user('Daminisatya');
$userResponse = json_decode($values);

$bodyResponse = $helloResponse->body;
$bodyResponse = json_decode($bodyResponse);
$peersResponse = $peersResponse->body;
$peersResponse = json_decode($peersResponse);
$statusResponse = $statusResponse->body;
$statusResponse = json_decode($statusResponse, true);
$searchResponse = $searchResponse->body;
$searchResponse = json_decode($searchResponse, true);
$geocodeResponse = $geocodeResponse->body;
$geocodeResponse = json_decode($geocodeResponse, true);
$userResponse = $userResponse->body;
$userResponse = json_decode($userResponse, true);

echo "<b>Given Base URL  - </b>" . $baseURL . "<br>";
echo "<b>Hello JSON Test - </b>" . $bodyResponse->status . "<br>";
echo "<b>Peers JSON Test - </b>" . $peersResponse->count . " peers <br>";
echo "<b>Status JSON Test - </b>" . $statusResponse['index']['messages']['size'] . " messages <br>";
echo "<b>Search JSON Test - </b>" . $searchResponse['search_metadata']['hits'] . " hits <br>";
echo "<b>Geocode JSON Test - </b>" . $geocodeResponse['locations']['Hyderabad']['country_code'] . " country code <br>";
echo "<b>User JSON Test - </b>" . $userResponse['user']['name'] . " has </b>" . $userResponse['user']['followers_count'] . " followers and is following </b>" . $userResponse['user']['friends_count'] . " friends <br>";
?>
