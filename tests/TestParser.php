<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require dirname(__FILE__).'/../autoload.php';

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
        $this->assertEquals($expected, TwitterData::TwitterData_to_array($orig));
    }

    public function testOfficlalSpecExamples()
    {
        $str = 'Cruising down 101 near SFO $mph 95 $lat 37.612804 $long -122.381687';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => 'Cruising down 101 near SFO',
                                'tuples' => array('mph' => '95', 'lat' => '37.612804', 'long' => '-122.381687')));
        $this->assertEquals($expected, $parser->export());

        $str = 'I love the #twitterdata proposal! $vote +1';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => 'I love the #twitterdata proposal!',
                                'tuples' => array('vote' => '+1')));
        $this->assertEquals($expected, $parser->export());

        $str = 'Call me $phone 123-456-7890';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => 'Call me',
                                'tuples' => array('phone' => '123-456-7890')));
        $this->assertEquals($expected, $parser->export());

        $str = 'Cheap #gas! $lat 37.323144 $long -121.944423 $price 1.99';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => 'Cheap #gas!',
                                'tuples' => array('lat' => '37.323144', 'long' => '-121.944423', 'price' => '1.99')));
        $this->assertEquals($expected, $parser->export());

        $str = '#wmodata $id DW1428 $temp 69F $wangle 232 $wspeed 4.0mph $rh 50% $dew 49F $press 1015.2mb';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => '#wmodata',
                                'tuples' => array('id' => 'DW1428', 'temp' => '69F', 'wangle' => '232',
                                                  'wspeed' => '4.0mph', 'rh' => '50%', 'dew' => '49F', 'press' => '1015.2mb')));
        $this->assertEquals($expected, $parser->export());

        $str = 'Saw a $aero>man MIG $in walmart$ would you believe it? ';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => 'Saw a',
                                'tuples' => array('aero>man' => 'MIG', 'in' => 'walmart')),
                          array('subject' => 'would you believe it? ',
                                'tuples' => array()));
        $this->assertEquals($expected, $parser->export());

        $str = '@romeo $foaf>loves @juliet';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => '@romeo',
                                'tuples' => array('foaf>loves' => '@juliet')));
        $this->assertEquals($expected, $parser->export());

        $str = '$s urn:game123 $move a1b2';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => '',
                                'tuples' => array('s' => 'urn:game123', 'move' => 'a1b2')));
        $this->assertEquals($expected, $parser->export());

        $str = '$s urn:roulette123 $bet $$10 $num 5';
        $parser = new TwitterData_Parser($str, 'TwitterData_Parser_ArrayGenerator');
        $expected = array(array('subject' => '',
                                'tuples' => array('s' => 'urn:roulette123', 'bet' => '$10', 'num' => '5')));
        $this->assertEquals($expected, $parser->export());
    }
}
