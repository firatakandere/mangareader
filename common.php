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
require($mangareader_root_path . 'includes/startup.php');
require($mangareader_root_path . 'includes/functions.php');
require($mangareader_root_path . 'config.php');
require($mangareader_root_path . 'includes/class-database.php');
require($mangareader_root_path . 'includes/class-cache.php');

if (!empty($dbport))
{
    $dbhost = $dbhost . ':' . $dbport;
}

$dsn .= ':dbname=' . $dbname . ';host=' . $dbhost;

try
{
    $db = new Database($dsn, $dbuser, $dbpass);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $exception)
{
    trigger_error('Connection failed: ' . $exception->getMessage());
}

// We do not need this anymore
unset($dbpass);

$cache = new Cache();


?>