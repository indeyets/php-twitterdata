<?php
/**
 * @license MIT-style. see LICENCE file
 */

/**
 * Object of this class represents single TwitterData-message (array of frames)
 *
 * @package TwitterData
 * @author Alexey Zakhlestin
 */
class TwitterData_Message
{
    private $frames;

    public function __construct()
    {
        $this->frames = array();
    }

    public function __set($key, $value)
    {
        if ('frames' === $key)
            $this->setFrames($value);
        else
            throw new UnexpectedValueException("There's no such field in this object: ".$key);
    }

    public function __get($key)
    {
        if ('frames' === $key)
            return $this->getFrames();
        else
            throw new UnexpectedValueException("There's no such field in this object: ".$key);
    }

    public function addFrame(TwitterData_Frame $frame)
    {
        $this->frames[] = $frame;
        return $this;
    }

    public function setFrames(array $frames)
    {
        array_walk($frames, array(__CLASS__, 'throwIfNotFrame'));
        $this->frames = $frames;
    }


    public function getFrames()
    {
        return $this->frames;
    }


    public function __toString()
    {
        $result = '';

        $last = count($this->frames) - 1;

        for ($i = 0; $i <= $last; $i++) {
            $result .= $this->frames[$i]->exportableFormat($i != $last);
            if ($i != $last) {
                $result .= ' ';
            }
        }

        return $result;
    }


    private static function throwIfNotFrame($v)
    {
        if (!is_object($v) or !($v instanceof TwitterData_Frame))
            throw new InvalidArgumentException('setFrames() expects array of TwitterData_Frames');
    }
}