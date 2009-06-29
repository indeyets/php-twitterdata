<?php

class TwitterData_Message
{
    private $frames;

    public function __construct()
    {
        $this->frames = array();
    }

    public function addFrame(TwitterData_Frame $frame)
    {
        $this->frames[] = $frame;
        return $this;
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
}