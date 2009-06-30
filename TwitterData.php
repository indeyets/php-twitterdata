<?php
/**
 * @license MIT-style. see LICENCE file
 */

/**
 * High-level wrapper for TwitterData functionality.
 *
 * @package TwitterData
 * @author Alexey Zakhlestin
 */
class TwitterData
{
    public static function array_to_TwitterData(array $data)
    {
        return (string)TwitterData_Frame::initFromKeyValueArray($data);
    }

    public static function TwitterData_to_array($data)
    {
        $parser = new TwitterData_Parser($data, 'TwitterData_Parser_ArrayGenerator');
        $result = $parser->export();
        unset($parser);

        if (!isset($result[0]))
            return array();

        return $result[0]['tuples'];
    }
}
