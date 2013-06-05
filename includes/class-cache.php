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
* Cache class
* @package reader
*/
class Cache
{
    var $cache_dir = '';
    var $not_writable = false;
    var $remove_at_the_end = array();
    
    function __construct()
    {
        global $mangareader_root_path;
        
        $this->cache_dir = $mangareader_root_path . 'cache/';
        
        if (reader_is_writable($this->cache_dir))
        {
            $this->not_writable = true;
            
            /**
            * @todo Give a system warning
            */
        }
    }
    
    function __destruct()
    {
        foreach ($this->remove_at_the_end as $filename)
        {
            $this->remove($filename);
        }
    }
    
    function put($filename, $data)
    {
        if ($this->not_writable)
        {
            return false;
        }
        
        if (empty($data))
        {
            return false;
        }
        
        $file_data = '<' . '?php exit; ?' . '>';
        $file_data .= var_export($data, true);
        
        $result = file_put_contents($filename, $file_data, LOCK_EX);
        
        return ($result !== false);
    }
    
    function get($filename)
    {
        if ($this->not_writable)
        {
            return false;
        }
        
        if (!file_exists($this->_get_file_path($filename)))
        {
            return false;
        }
        
        return include($this->_get_file_path($filename));
    }
    
    function _get_file_path($filename)
    {
        return $this->cache_dir . $filename . '.php';
    }
    
    function remove($filename)
    {
        if ($this->not_writable)
        {
            return false;
        }
        
        return @unlink($this->_get_file_path($filename));
    }
    
    function flush()
    {
        if ($this->not_writable)
        {
            return false;
        }
        
        $result = 'true';
        
        // All php files in cache directory contains cache entries
        foreach (glob($this->cache_dir . '*.php') as $filepath)
        {
            if (!@unlink($filepath))
            {
                $result = false;
            }
        }
        
        return $result;
    }
    
    function remove_end($filename)
    {
        if (!in_array($filename, $this->remove_at_the_end))
        {
            $this->remove_at_the_end[] = $filename;
        }
    }
}


?>