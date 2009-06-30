<?php
/**
 * @license MIT-style. see LICENCE file
 */

/**
 * This class generates nested array-structure by connecting to parser
 *
 * @package TwitterData
 * @author Alexey Zakhlestin
 */
class TwitterData_Parser_ArrayGenerator implements TwitterData_Parser_CallbackInterface
{
    private $buffer;
    private $msg;

    public function __construct()
    {
        $this->buffer = array();
    }

    private function bufferTip()
    {
        return $this->buffer[count($this->buffer) - 1];
    }

    private function setBufferTip($val)
    {
        return $this->buffer[count($this->buffer) - 1] = $val;
    }


    public function messageStarted()
    {
        $msg = array();
        array_push($this->buffer, $msg);
    }

    public function messageEnded()
    {
        $this->msg = array_pop($this->buffer);
    }

    public function frameStarted()
    {
        $frame = array('subject' => '', 'tuples' => array());
        array_push($this->buffer, $frame);
    }

    public function frameEnded()
    {
        $frame = array_pop($this->buffer);
        $tip = $this->bufferTip();
        array_push($tip, $frame);
        $this->setBufferTip($tip);
    }

    public function foundSubject($subject)
    {
        $tip = $this->bufferTip();
        $tip['subject'] = $subject;
        $this->setBufferTip($tip);
    }

    public function foundTuple($key, $value)
    {
        $tip = $this->bufferTip();
        $tip['tuples'][$key] = $value;
        $this->setBufferTip($tip);
    }

    public function export()
    {
        return $this->msg;
    }
}
