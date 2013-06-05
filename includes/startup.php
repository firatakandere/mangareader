<?php
/**
*
* @package reader
* @version $Id$
* @copyright Copyright (c) 2013, Firat Akandere
* @author Firat Akandere <f.akandere@gmail.com>
* @license http://opensource.org/licenses/GPL-3.0 GNU Public License, version 3
*
*/

/**
* @ignore
*/
if (!defined('IN_MANGAREADER'))
{
    exit;
}

// Pretend PHP 5.3 date error
if (function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set'))
{
    date_default_timezone_set(@date_default_timezone_get());
}



?>