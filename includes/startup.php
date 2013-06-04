<?php

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