<?php
/**
 * @license MIT-style. see LICENCE file
 */

function TwitterData_autoload($classname)
{
    static $paths = null;

    if (null === $paths) {
        $root = dirname(__FILE__);
        $paths = array(
            'TwitterData_Tuple'                     => $root.'/TwitterData_Tuple.php',
            'TwitterData_Frame'                     => $root.'/TwitterData_Frame.php',
            'TwitterData_Message'                   => $root.'/TwitterData_Message.php',
            'TwitterData_Parser'                    => $root.'/TwitterData_Parser.php',
            'TwitterData_Parser_CallbackInterface'  => $root.'/TwitterData_Parser.php',
            'TwitterData_Parser_OOPGenerator'       => $root.'/TwitterData_Parser_OOPGenerator.php',
            'TwitterData_Parser_ArrayGenerator'     => $root.'/TwitterData_Parser_ArrayGenerator.php',
            'TwitterData'                           => $root.'/TwitterData.php',
        );
    }

    if (isset($paths[$classname])) {
        require $paths[$classname];
    }
}

spl_autoload_register('TwitterData_autoload');
