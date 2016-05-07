<?php

include("../loklak.php");

class Testloklak extends \PHPUnit_Framework_TestCase
{
    private $loklak;

    public function setUp() {
        $this->loklak = new Loklak();
    }

    public function testStatus() {
        $result = $this->loklak->status();
        $statusResponse = json_decode($result);
        $statusResponse = $statusResponse->body;
        $statusResponse = json_decode($statusResponse, true);
        $this->assertArrayHasKey('indexes', $statusResponse);

        $resultProperties = array(
            'messages', 'mps', 'users', 'queries',
            'accounts', 'user', 'followers', 'following'
        );

        foreach($resultProperties as $property)
        {
            $error = "Indexes does not contain " . $property;
            $this->assertArrayHasKey($property, $statusResponse['indexes'], $error);
        }
    }
}
