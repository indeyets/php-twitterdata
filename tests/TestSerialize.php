<?php

error_reporting(E_ALL | E_STRICT);

require_once "PHPUnit/Framework/TestCase.php";
require dirname(__FILE__).'/../autoload.php';

class TestSerialize extends PHPUnit_Framework_TestCase
{
    public function testSimpleTuple()
    {
        $tuple = new TwitterData_Tuple('key', 'value');
        $this->assertEquals('$key value', (string)$tuple);
    }

    public function testSimpleFrame()
    {
        $frame = new TwitterData_Frame('subject', array());
        $this->assertEquals('subject', (string)$frame);

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

        // correct namespace
        try {
            new TwitterData_Tuple('test>namespace', 'val');
        } catch (UnexpectedValueException $e) {
            $this->fail();
        }

        // incorrect namespace
        try {
            new TwitterData_Tuple('test>bad>namespace', 'val');
            $this->fail();
        } catch (UnexpectedValueException $e) {
        }
    }

    public function testTupleValues()
    {
        $tuple = new TwitterData_Tuple('key', '$value');
        $this->assertEquals('$key $$value', (string)$tuple);

        $tuple = new TwitterData_Tuple('key', '  value');
        $this->assertEquals('$key value', (string)$tuple);
    }

    public function testHighLevel()
    {
        $data = array('key' => 'value', 'key2' => 'value2');
        $this->assertEquals('$key value $key2 value2', TwitterData::array_to_TwitterData($data));
    }

    public function testSimplifiedApi()
    {
        $message = new TwitterData_Message();
        $message->frames[] = new TwitterData_Frame('subject');
        $message->frames[0]->tuples[] = new TwitterData_Tuple('foo', 'bar');
        $message->frames[0]->tuples[] = new TwitterData_Tuple('key', 'val');
        $message->frames[0]->tuples[0]->value = 'baz';

        $this->assertEquals('subject $foo baz $key val', (string)$message);
    }
}
