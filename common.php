<?php

/**
* @ignore
*/
if (!defined('IN_MANGAREADER'))
{
    exit;
}

require($mangareader_root_path . 'config.php');
require($mangareader_root_path . 'includes/class-database.php');

if (!empty($dbhost))
{
    $dbhost = $dbhost . ':' . $dbport;
}

$dsn = $dsn . ':dbname=' . $dbname . ';host=' . $dbhost;

try
{
    $db = new Database($dsn, $dbuser, $dbpass);
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

}
catch (PDOException $exception)
{
    echo 'Connection failed: ' . $exception->getMessage();
}


?>