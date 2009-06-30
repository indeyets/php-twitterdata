<?php

require 'TwitterData_Tuple.php';
require 'TwitterData_Frame.php';
require 'TwitterData_Message.php';

require 'TwitterData_Parser.php';

function array_to_TwitterData(array $data)
{
    return (string)TwitterData_Frame::initFromKeyValueArray($data);
}

