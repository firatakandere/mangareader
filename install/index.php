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
define('IN_MANGAREADER', true);
define('IN_INSTALL', true);

$mangareader_root_path = (defined('MANGAREADER_ROOT_PATH')) ? MANGAREADER_ROOT_PATH : './../';

if (version_compare(PHP_VERSION, '5.0.0') < 0)
{
    die('You are running an unsupported PHP version. Please upgrade your PHP version to at least 5.0');
}

@set_time_limit(0);
$mem_limit = @ini_get('memory_limit');
if (!empty($mem_limit))
{
    $unit = strtolower(substr($mem_limit, -1, 1));
    $mem_limit = (int) $mem_limit;

    if ($unit == 'k')
    {
        $mem_limit = floor($mem_limit / 1024);
    }
    else if ($unit == 'g')
    {
        $mem_limit *= 1024;
    }
    else if (is_numeric($unit))
    {
        $mem_limit = floor((int) ($mem_limit . $unit) / 1048576);
    }
    $mem_limit = max(128, $mem_limit) . 'M';
}
else
{
    $mem_limit = '128M';
}
@ini_set('memory_limit', $mem_limit);



?>
