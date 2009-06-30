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

        $orig = 'subject';
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

    public function testArrayGenerator()
    {
        $orig = '$var val $var2 val $var val2';
        $parser = new TwitterData_Parser($orig, 'TwitterData_Parser_ArrayGenerator');

        $expected = array(0 => array('subject' => '', 'tuples' => array('var' => 'val2', 'var2' => 'val')));
        $this->assertEquals($expected, $parser->export());


        $orig = 'subject $var val $var2 val $var val2$ subj2';
        $parser = new TwitterData_Parser($orig, 'TwitterData_Parser_ArrayGenerator');

        $expected = array(
            0 => array('subject' => 'subject', 'tuples' => array('var' => 'val2', 'var2' => 'val')),
            1 => array('subject' => 'subj2', 'tuples' => array()))
        ;
        $this->assertEquals($expected, $parser->export());
    }

    public function testHighLevel()
    {
        $orig = 'subject $var val $var2 val $var val2$ subj2';
        $expected = array('var' => 'val2', 'var2' => 'val');
        $this->assertEquals($expected, TwitterData_to_array($orig));
    }
}
