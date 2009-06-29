<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require './TwitterData.php';

class TestSerialize extends PHPUnit_Framework_TestCase
{
    public function testSimpleTuple()
    {
        $tuple = new TwitterData_Tuple('key', 'value');
        $this->assertEquals('$key value', (string)$tuple);

        $tuple = new TwitterData_Tuple('key', '$value');
        $this->assertEquals('$key $$value', (string)$tuple);
    }

    public function testSimpleFrame()
    {
        $frame = new TwitterData_Frame('', array(
            new TwitterData_Tuple('key', 'value'),
            new TwitterData_Tuple('key2', 'value2')
        ));
        $this->assertEquals('$key value $key2 value2', (string)$frame);
    }

    public function testSimpleMessage()
    {
        $frame1 = new TwitterData_Frame('', array(
            new TwitterData_Tuple('key', 'value'),
            new TwitterData_Tuple('key2', 'value2')
        ));

        $frame2 = new TwitterData_Frame('Hello, World!', array(
            new TwitterData_Tuple('key', 'value'),
            new TwitterData_Tuple('key2', 'value2')
        ));

        $message = new TwitterData_Message();
        $message->addFrame($frame1)->addFrame($frame2);
        $this->assertEquals('$key value $key2 value2$ Hello, World! $key value $key2 value2', (string)$message);
    }

    public function testTupleKeys()
    {
        // first-letter
        // -> good
        $good = array_merge(range('a', 'z'), range('A', 'Z'), array('_'));
        foreach($good as $letter) {
            try {
                new TwitterData_Tuple($letter.'key', 'string');
            } catch (UnexpectedValueException $e) {
                $this->fail();
            }
        }

        // -> bad
        $bad = array_merge(range('0', '9'), array('-', '.', '>', '#'));
        foreach($bad as $letter) {
            try {
                new TwitterData_Tuple($letter.'key', 'string');
                $this->fail();
            } catch (UnexpectedValueException $e) {
            }
        }
    }
}
