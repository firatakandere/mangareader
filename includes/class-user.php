<?php

class User
{
    
    public $data = array();
    
    function __construct()
    {
        global $config;
        
        if ((!isset($_SESSION) || !is_array($_SESSION)) && !headers_sent())
        {
            session_start();
        }
        $this->data['template_path'] = $config['default_template'];
    }
    

}


?>