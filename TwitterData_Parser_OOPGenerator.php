<?php
/**
 * @license MIT-style. see LICENCE file
 */

/**
 * This class generates populated TwitterData_Message class by connecting to parser
 *
 * @package TwitterData
 * @author Alexey Zakhlestin
 */
class TwitterData_Parser_OOPGenerator implements TwitterData_Parser_CallbackInterface
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

    public function messageStarted()
    {
        $msg = new TwitterData_Message();
        array_push($this->buffer, $msg);
    }

    public function messageEnded()
    {
        $this->msg = array_pop($this->buffer);
    }

    public function frameStarted()
    {
        $frame = new TwitterData_Frame();
        array_push($this->buffer, $frame);
    }

    public function frameEnded()
    {
        $frame = array_pop($this->buffer);
        $this->bufferTip()->addFrame($frame);
    }

    public function foundSubject($subject)
    {
        $this->bufferTip()->setSubject($subject);
    }

    public function foundTuple($key, $value)
    {
        $this->bufferTip()->addTuple(new TwitterData_Tuple($key, $value));
    }

    public function export()
    {
        return $this->msg;
    }
}
