<?php

include("../loklak.php");

class Testloklak extends \PHPUnit_Framework_TestCase
{
    private $loklak;

    public function setUp() {
        $this->loklak = new Loklak();
    }
}
