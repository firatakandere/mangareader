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

/**
* Set config, generate missing ones
*/
function set_config($config_name, $config_value)
{
    global $config, $cache, $db;
    
    // If the config already exists and the value of it is still the same, do nothing
    if (isset($config[$config_name]) && $config[$config_name] == $config_value)
    {
        return;
    }
        
    $data_ary = array('config_value' => $config_value);
    
    $sql = 'UPDATE ' . CONFIG_TABLE . '
            SET ' . $db->build_array('UPDATE', $data_ary) . '
            WHERE config_name = ' . $db->quote($config_name);
    
    // If not any column is affected, which means the config is missing, generate it
    if (!$db->exec($sql))
    {
        $data_ary['config_name'] = $config_name;
        $sql = 'INSERT INTO ' . CONFIG_TABLE . ' ' .$db->build_array('INSERT', $data_ary);
        $db->exec($sql);
    }
    
    $config[$config_name] = $config_value;
    $cache->remove_end('config');
}

/**
* Check if path is writable
*
* This will work for both *nix and windows systems
* 
* @param string $path Use trailing slash for folders!
* @return boolean Either true if the path is writable or false if it is not
*/
function reader_is_writable($path)
{
    // Recursively return a temporary file path
    if (substr($path, -1) == '/')
    {
        return reader_is_writable($path . uniqid(mt_rand()) . '.tmp');
    }
    else if (is_dir($path))
    {
        return reader_is_writable($path. '/' . uniqid(mt_rand()) . '.tmp');
    }
    
    // Check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f === false)
    {
        return false;
    }
    @fclose($f);
    
    if (!$rm)
    {
        @unlink($path);
    }
    
    return true;
}

/**
* Generate fingerprint for user browser
*
* @return string hashed fingerprint
*/
function get_browser_fingerprint()
{
    global $config;
    $data = '';
    $data .= getip();
    $data .= $_SERVER['HTTP_USER_AGENT'];
    $data .= $_SERVER['HTTP_ACCEPT'];
    $data .= $config['board_salt'];
    $data .= $_SERVER['HTTP_ACCEPT_CHARSET'];
    $data .= $_SERVER['HTTP_ACCEPT_ENCODING'];
    $data .= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    
    return hash('sha256', $data);
}

/**
* Ger user ip
*
* @return string User IP
*/
function get_ip()
{
    $table = array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'
    );
    
    foreach ($table as $key)
    {
        if (array_key_exists($key, $_SERVER) === true)
        {
            foreach (explode(',', $_SERVER[$key]) as $ip)
            {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false)
                {
                    return $ip;
                }
            }
        }
    }
}

?>