<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require './TwitterData.php';

class TestParser extends PHPUnit_Framework_TestCase
{
    public function testSimpleTuple()
    {
        $orig = '$var val';
        $parser = new TwitterData_Parser($orig);
        $this->assertEquals($orig, (string)$parser->export());

        $orig = '$var val $var2 val $var val';
        $parser = new TwitterData_Parser($orig);
        $this->assertEquals($orig, (string)$parser->export());

        $orig = 'subject $var val $var2 val $var val';
        $parser = new TwitterData_Parser($orig);
        $this->assertEquals($orig, (string)$parser->export());
    }
}