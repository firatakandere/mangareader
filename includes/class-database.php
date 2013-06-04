<?php


/**
* @ignore
*/
if (!defined('IN_MANGAREADER'))
{
    exit;
}

class Database extends PDO
{
    private $transactions = 0;
        
    function beginTransaction()
    {
        if (!$this->transactions)
        {
            $this->transactions++;
            return parent::beginTransaction();
        }
        
        return false;
    }
    
    function commit()
    {
        if ($this->transactions)
        {
            $this->transactions--;
            return parent::commit();
        }
        
        return false;
    }
    
    function rollBack()
    {
        if ($this->transactions >= 0)
        {
            $this->transactions = 0;
            return parent::rollBack();
        }
        
        $this->transactions = 0;
        return false;
    }
    
    function build_array($mode, $assoc_ary)
    {
        if (!is_array($assoc_ary))
        {
            return false;
        }
        
        $fields = $values = array();
        
        if ($mode == 'INSERT')
        {
            foreach ($assoc_ary as $key => $var)
            {
                $fields[] = $key;
                $values[] = $this->_validate_data($var);
            }
            
            $query = '(' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
        }
        else if ($mode == 'UPDATE' || $mode == 'SELECT')
        {
            foreach ($assoc_ary as $key => $var)
            {
                $values = "$key = " . $this->_validate_data($var);
            }
            
            $query = implode(($mode == 'UPDATE') ? ', ' : ' AND ', $values);
        }
        
        return $query;
    }
    
    function _validate_data($var)
    {
        if (is_null($var))
        {
            return 'NULL';
        }
        else if (is_string($var))
        {
            return $this->quote($var);
        }
        else
        {
            return (is_bool($var)) ? intval($var) : $var;
        }
    }
}


?>