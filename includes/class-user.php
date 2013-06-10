<?php

class User
{
    
    var $data = array();
    var $lang = array();
    var $lang_name = '';
    
    function __construct()
    {
        global $config;
        
        if ((!isset($_SESSION) || !is_array($_SESSION)) && !headers_sent())
        {
            session_start();
        }
        
        // Defaults
        $this->data = array(
            'user_id'       => ANONYMOUS,
            'template_path' => $config['default_template'],
            'language_path'      => 'en_US',
        );
    }


}


?>