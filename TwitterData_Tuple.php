<?php

class TwitterData_Tuple
{
    private $key;
    private $value;

    public function __construct($k, $v)
    {
        $this->setKey($k);
        $this->setValue($v);
    }

    public function setKey($k)
    {
        if (!is_string($k))
            throw new InvalidArgumentException();

        if (false === mb_ereg('^[a-zA-Z_]([a-zA-Z0-9_]+)?(>[a-zA-Z0-9_]+)?$', $k))
            throw new UnexpectedValueException('invalid key of tuple');

        $this->key = $k;
    }

    public function setValue($v)
    {
        $v = ltrim($v);

        if (!is_string($v))
            throw new InvalidArgumentException();

        if (strlen($v) == 0)
            throw new UnexpectedValueException('tuple-value can not be empty');

        $this->value = $v;
    }

    private function escapedValue()
    {
        return str_replace('$', '$$', $this->value);
    }

    public function __toString()
    {
        return '$'.$this->key.' '.$this->escapedValue();
    }
}