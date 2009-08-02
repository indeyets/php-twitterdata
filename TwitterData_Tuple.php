<?php
/**
 * @license MIT-style. see LICENCE file
 */

/**
 * Object of this class represents single TwitterData-tuple (key + value)
 *
 * @package TwitterData
 * @author Alexey Zakhlestin
 */
class TwitterData_Tuple
{
    private $key;
    private $value;

    public function __construct($k, $v)
    {
        $this->setKey($k);
        $this->setValue($v);
    }

    public function __set($key, $value)
    {
        if ('key' === $key)
            $this->setKey($value);
        elseif ('value' === $key)
            $this->setValue($value);
        else
            throw new UnexpectedValueException("There's no such field in this object: ".$key);
    }

    public function __get($key)
    {
        if ('key' === $key)
            return $this->getKey();
        elseif ('value' === $key)
            return $this->getValue();
        else
            throw new UnexpectedValueException("There's no such field in this object: ".$key);
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


    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
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