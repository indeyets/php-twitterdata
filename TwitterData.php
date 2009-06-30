<?php

require 'TwitterData_Tuple.php';
require 'TwitterData_Frame.php';
require 'TwitterData_Message.php';

require 'TwitterData_Parser.php';
require 'TwitterData_Parser_OOPGenerator.php';
require 'TwitterData_Parser_ArrayGenerator.php';

function array_to_TwitterData(array $data)
{
    return (string)TwitterData_Frame::initFromKeyValueArray($data);
}

function TwitterData_to_array($data)
{
    $parser = new TwitterData_Parser($data, 'TwitterData_Parser_ArrayGenerator');
    $result = $parser->export();
    unset($parser);

    if (!isset($result[0]))
        return array();

    return $result[0]['tuples'];
}
