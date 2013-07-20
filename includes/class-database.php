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
* Database class extended from PDO
* @package reader
*/
class Database extends PDO
{
    private $transactions = 0;

    /**
    * Improved beginTransaction function
    */
    public function beginTransaction()
    {
        if (!$this->transactions)
        {
            $this->transactions++;
            return parent::beginTransaction();
        }

        return false;
    }

    /**
    * Improved transaction commit function
    */
    public function commit()
    {
        if ($this->transactions)
        {
            $this->transactions--;
            return parent::commit();
        }

        return false;
    }

    /**
    * Improved rollBack function
    */
    public function rollBack()
    {
        if ($this->transactions >= 0)
        {
            $this->transactions = 0;
            return parent::rollBack();
        }

        $this->transactions = 0;
        return false;
    }

    /**
    * Build sql array
    *
    * @param string $mode Query mode, available modes: INSERT | UPDATE | SELECT
    * @param array $assoc_ary Assocated array, array keys stand for sql fields, and values stand for sql values
    * @return mixed Either false if something is wrong or query string if everything is okay
    */
    public function build_array($mode, $assoc_ary)
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
                $values[] = $this->validate_data($var);
            }

            $query = '(' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
        }
        else if ($mode == 'UPDATE' || $mode == 'SELECT')
        {
            foreach ($assoc_ary as $key => $var)
            {
                $values[] = "$key = " . $this->validate_data($var);
            }

            $query = implode(($mode == 'UPDATE') ? ', ' : ' AND ', $values);
        }

        return $query;
    }

    /**
    * Validate data for sql query
    */
    private function validate_data($var)
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
