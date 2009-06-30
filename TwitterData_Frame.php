<?php
/**
 * @license MIT-style. see LICENCE file
 */

/**
 * Object of this class represents single TwitterData-frame (subject + array of tuples)
 *
 * @package TwitterData
 * @author Alexey Zakhlestin
 */
class TwitterData_Frame
{
    public static function initFromKeyValueArray(array $data)
    {
        $tuples = array();
        foreach ($data as $k => $v) {
            $tuples[] = new TwitterData_Tuple($k, $v);
        }

        return new TwitterData_Frame('', $tuples);
    }

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

    public function addTuple(TwitterData_Tuple $tuple)
    {
        $this->tuples[] = $tuple;
    }

    public function exportableFormat($include_leading_terminator = false)
    {
        return $this->subject().$this->tuples($include_leading_terminator);
    }

    public function __toString()
    {
        return $this->exportableFormat();
    }

    private function subject()
    {
        if (strlen($this->subject) == 0)
            return '';

        return $this->subject.(count($this->tuples) > 0 ? ' ' : '');
    }

    private function tuples($include_leading_terminator)
    {
        if (count($this->tuples) == 0)
            return '';

        return implode(' ', $this->tuples).($include_leading_terminator ? '$' : '');
    }

    private static function throwIfNotTuple($v)
    {
        if (!is_object($v) or !($v instanceof TwitterData_Tuple))
            throw new InvalidArgumentException('setTuples() expects array of TwitterData_Tuples');
    }
}