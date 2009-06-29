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
        if (false === mb_ereg('^[a-zA-Z_]([a-zA-Z0-9_]+)?(>[a-zA-Z0-9_]+)?$', $k))
            throw new UnexpectedValueException('invalid key of tuple');

        $this->key = $k;
    }

    public function setValue($v)
    {
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