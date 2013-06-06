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
    var $cache_dir;
    var $not_writable = false;
    var $remove_at_the_end = array();
    
    function __construct()
    {
        global $mangareader_root_path;
        
        $this->cache_dir = $mangareader_root_path . 'cache/';
        if (!reader_is_writable($this->cache_dir))
        {
            $this->not_writable = true;
            
            trigger_error('Cache directory is not writable', E_USER_WARNING);
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
        $file_data .= serialize($data);
        
        $result = file_put_contents($this->_get_file_path($filename), $file_data, LOCK_EX);
        @chmod($this->_get_file_path($filename), 0777);
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
        
        return unserialize(str_replace('<' . '?php exit; ?' . '>', '', file_get_contents($this->_get_file_path($filename))));
    }
    
    /**
    * Get the full path of a specified filename
    *
    * @param string $filename Filename without extension
    * @return string Full path of file
    */
    function _get_file_path($filename)
    {
        return $this->cache_dir . $filename . '.php';
    }
    
    /**
    * Remove a specified cache entry
    *
    * @param string $filename Filename without extension
    * @return boolean Either ture if everything is okay, or false if something is wrong
    */
    function remove($filename)
    {
        if ($this->not_writable)
        {
            return false;
        }
        
        return @unlink($this->_get_file_path($filename));
    }
    
    /**
    * Delete all cache entries
    *
    * @return boolean Either true if everything is okay, or false if something is wrong
    */
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
    
    /**
    * Remove the cache file at the end of the system
    * @param string $filename Filename without extension
    * @return void
    */
    function remove_end($filename)
    {
        if (!in_array($filename, $this->remove_at_the_end))
        {
            $this->remove_at_the_end[] = $filename;
        }
    }
}

?>