<?php

interface TwitterData_ParserInterface
{
    public function messageStarted();
    public function messageEnded();
    public function frameStarted();
    public function frameEnded();
    public function foundSubject($subject);
    public function foundTuple($key, $value);
    public function export();
}

class TwitterData_Parser_Callback implements TwitterData_ParserInterface
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

class TwitterData_Parser
{
    private $callback;

    public function __construct($string,  $callback_class = null)
    {
        if (null === $callback_class)
            $callback_class = 'TwitterData_Parser_Callback';

        if (!in_array('TwitterData_ParserInterface', class_implements($callback_class)))
            throw new InvalidArgumentException('Callback has to implement TwitterData_ParserInterface');

        $this->callback = new $callback_class;
        $this->parseMessage($string);
    }

    public function export()
    {
        return $this->callback->export();
    }

    private function parseMessage($string)
    {
        $this->callback->messageStarted();

        $pos = 0;
        $len = mb_strlen($string);
        mb_ereg_search_init($string, '(.*?[a-zA-Z0-9_])\\$(?:[ ]|$)');

        if (true === mb_ereg_search()) {
            // multiframe message
            mb_ereg_search_init($string, '(.*?[a-zA-Z0-9_])(?:\\$[ ]|\\$$|$)');

            $frames = array();

            while (true === mb_ereg_search()) {
                $parts = mb_ereg_search_getregs();
                $frames[] = $parts[1];

                $pos += mb_strlen($parts[0]);
                if ($pos >= $len)
                    break;

                mb_ereg_search_setpos($pos);
            };
            if ($pos < $len) {
                $frames[] = mb_substr($string, $pos);
            }

            array_walk($frames, array($this, 'parseFrame'));
        } else {
            // singleframe message
            $this->parseFrame($string);
        }

        $this->callback->messageEnded();
    }

    private function parseFrame($string)
    {
        $this->callback->frameStarted();

        if (mb_substr($string, 0, 1) == '$' and mb_substr($string, 1, 1) != '$') {
            // we don't have subject
            $subject = '';
        } else {
            // we have subject
            mb_ereg_search_init($string, '(.*?)[ ]\\$.*');
            mb_ereg_search();

            $parts = mb_ereg_search_getregs();
            $subject = $parts[1];

            $this->callback->foundSubject(str_replace('$$', '$', $subject));

            $string = mb_substr($string, mb_strlen($subject) + 1);
        }

        mb_ereg_search_init($string, '[ ]*\\$([a-zA-Z0-9_]+)[ ]+(.*?)(?=(?:[ ][$][^$])|$)');

        $tuples = array();
        $pos = 0;
        $len = mb_strlen($string);

        while (true === mb_ereg_search()) {
            $parts = mb_ereg_search_getregs();
            $tuple = array($parts[1], $parts[2]);

            $this->callback->foundTuple($parts[1], str_replace('$$', '$', $parts[2]));

            $pos += mb_strlen($parts[0]);

            if ($pos >= $len)
                break;

            mb_ereg_search_setpos($pos);
        };

        $this->callback->frameEnded();
    }
}
