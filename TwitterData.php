<?php

class TwitterData
{
    public static function serialize(array $arr)
    {
        return implode(' ', array_map(array(__CLASS__, 'pair_to_string'), array_keys($arr), array_values($arr)));
    }

    public static function unserialize($twitterdata)
    {
        if (!is_string($twitterdata))
            throw new InvalidArgumentException(__METHOD__.'() expects string as parameter');
    }



    private static function pair_to_string($k, $v)
    {
        return '$'.$k.' '.$v;
    }
}