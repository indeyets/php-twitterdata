<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require './TwitterData.php';

class TestSerialize extends PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $arr = array('key' => 'value');
        $this->assertEquals('$key value', TwitterData::serialize($arr));

        $arr = array('key' => 'value', 'key2' => 'value2');
        $this->assertEquals('$key value $key2 value2', TwitterData::serialize($arr));
    }
}
