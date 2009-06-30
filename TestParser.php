<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require './TwitterData.php';

class TestParser extends PHPUnit_Framework_TestCase
{
    public function testSimpleFrame()
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
    
    public function testMultiframeMessage()
    {
        $orig = 'subject $var val $var2 val $var val$ subject2 $var val';
        $parser = new TwitterData_Parser($orig);
        $this->assertEquals($orig, (string)$parser->export());
    }
    
    public function testPartialMessage()
    {
        $orig = '$var val';
        $orig2 = $orig.'$';
        $parser = new TwitterData_Parser($orig2);
        $this->assertEquals($orig, (string)$parser->export());

        $orig = 'subject $var val$ $var val2 var_ val_';
        $orig2 = $orig.'$';
        $parser = new TwitterData_Parser($orig2);
        $this->assertEquals($orig, (string)$parser->export());
    }
}