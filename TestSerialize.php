<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require './TwitterData.php';

class TestSerialize extends PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $tuple = new TwitterData_Tuple('key', 'value');
        $this->assertEquals('$key value', (string)$tuple);

        $frame = new TwitterData_Frame('', array($tuple, new TwitterData_Tuple('key2', 'value2')));
        $this->assertEquals('$key value $key2 value2', (string)$frame);
    }

    public function testLiteralDollar()
    {
        $tuple = new TwitterData_Tuple('key', '$value');
        $this->assertEquals('$key $$value', (string)$tuple);
    }
}
