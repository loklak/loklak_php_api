<?php
include('loklak.php');

// $baseURL = 'http://172.30.107.104:9000';
$baseURL = 'http://loklak.org';

$l = new Loklak($baseURL);

$values = $l->hello();
$helloResponse = json_decode($values);
$values = $l->peers();
$peersResponse = json_decode($values);

$bodyResponse = $helloResponse->body;
$bodyResponse = json_decode($bodyResponse);
$peersResponse = $peersResponse->body;
$peersResponse = json_decode($peersResponse);

echo "<b>Given Base URL  - </b>" . $baseURL . "<br>";
echo "<b>Hello JSON Test - </b>" . $bodyResponse->status . "<br>";
echo "<b>Peers JSON Test - </b>" . $peersResponse->count . " peers <br>";
?>
