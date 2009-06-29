<?php

class TwitterData_Frame
{
    private $subject = '';
    private $tuples;

    public function __construct($subject = '', array $tuples = array())
    {
        $this->setSubject($subject);
        $this->setTuples($tuples);
    }

    public function setSubject($subject)
    {
        if (!is_string($subject))
            throw new InvalidArgumentException('subject has to be string');

        $this->subject = $subject;
    }

    public function setTuples(array $tuples)
    {
        array_walk($tuples, array(__CLASS__, 'throwIfNotTuple'));
        $this->tuples = $tuples;
    }

    public function __toString()
    {
        return $this->subject().implode(' ', $this->tuples);
    }

    private function subject()
    {
        if (strlen($this->subject) == 0)
            return '';

        return $this->subject.' ';
    }


    private static function throwIfNotTuple($v)
    {
        if (!is_object($v) or !($v instanceof TwitterData_Tuple))
            throw new InvalidArgumentException('setTuples() expects array of TwitterData_Tuples');
    }
}