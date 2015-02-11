<?php

/*
 * Copyright (c) 2015, Bart Bresse ,SONDER All rights reserved.
 * This software is licensed under the NEW BSD LICENSE
 * 
 */

/**
 * Description of MgmtSMTP
 *
 * @author bart
 * @ticket bartbresse@gmail.com
 */
namespace MgmtEmail;

use Mail;
require_once 'Mail.php';

class MgmtSMTP {
    //put your code here
    private $smtp;
    private $succes = false;
    public $trace = array();
    
    public function __construct($host,$user,$password)
    {
        $this->smtp = \Mail::factory('smtp',array('host' => $host,
                                                    'auth' => true,
                                                    'username' => $user,
                                                    'password' => $password));   
    }
    
    public function addAttachment()
    {
        //addAttachment ( string $file , string $c_type = 'application/octet-stream' , string $name = '' , boolean $isfile = true , string $encoding = 'base64' , string $disposition = 'attachment' , string $charset = '' , string $language = '' , string $location = '' , string $n_encoding = null , string $f_encoding = null , string $description = '' , string $h_charset = null )
    }
    
    public function send($from,$to,$subject,$body)
    {
        $from = "Admin deappdeveloper <admin@deappdeveloper.nl>";
        $to = "Bart Breunesse <bartbresse@gmail.com>";
        
        $headers = array ('From' => $from,'To' => $to,'Subject' => $subject);
        
        $mail = $this->smtp->send($to, $headers, $body);
        
        if(\PEAR::isError($mail)) 
        {
            echo("<p>" . $mail->getMessage() . "</p>");
        } 
        else 
        {
            return true;
        }
    }
    
    
    public function close()
    {
        
    }
}
