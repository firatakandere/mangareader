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
require($mangareader_root_path . 'includes/constants.php');
require($mangareader_root_path . 'includes/class-database.php');
require($mangareader_root_path . 'includes/class-cache.php');

// If the database port is not empty, suffix it to the database host with ':' seperator
if (!empty($dbport))
{
    $dbhost .= ':' . $dbport;
}

$dsn .= ':dbname=' . $dbname . ';host=' . $dbhost;

// Database connection
try
{
    $db = new Database($dsn, $dbuser, $dbpass);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $exception)
{
    trigger_error('Connection failed: ' . $exception->getMessage());
}

// We do not need this anymore, better to get rid of it for security
unset($dbpass);

$cache = new Cache();

// Try to fetch config from cache, otherwise fetch it via sql and store it into cache
if (($config = $cache->get('config')) === false)
{
    $sql = 'SELECT * FROM ' . CONFIG_TABLE;
    $sth = $db->prepare($sql);
    $sth->execute();
    while ($row = $sth->fetch(PDO::FETCH_ASSOC))
    {
        $config[$row['config_name']] = $row['config_value'];
    }
    $cache->put('config', $config);
}

?>