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

$bodyResponse = $helloResponse->body;
$bodyResponse = json_decode($bodyResponse);
$peersResponse = $peersResponse->body;
$peersResponse = json_decode($peersResponse);
$statusResponse = $statusResponse->body;
$statusResponse = json_decode($statusResponse, true);

echo "<b>Given Base URL  - </b>" . $baseURL . "<br>";
echo "<b>Hello JSON Test - </b>" . $bodyResponse->status . "<br>";
echo "<b>Peers JSON Test - </b>" . $peersResponse->count . " peers <br>";
echo "<b>Status JSON Test - </b>" . $statusResponse['index']['messages']['size'] . " messages <br>";
?>
