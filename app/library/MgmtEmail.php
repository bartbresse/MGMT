<?php

/*
 * Copyright (c) 2015, Bart Bresse ,SONDER All rights reserved.
 * This software is licensed under the NEW BSD LICENSE
 * 
 */

/**
 * Description of MgmtEmail
 *
 * @author bart
 */
class MgmtEmail 
{
    private $args;
    private $driver;
    //put your code here
    public function __construct($args)
    {
        $this->args = $args;
        $this->driver = $args->driver;
    }
    
    private function send()
    {
        if($this->driver == 'server')
        {
            $smtp = new MgmtEmail\MgmtSMTP($this->args->host,$this->args->user,$this->args->password);
        
            
        }
        else //Mandrill
        {
            
        }
    }
    
    public function download()
    {
        if($this->driver == 'server')
        {
            $imap = new MgmtEmail\MgmtIMAP($this->args->host, $this->args->user, $this->args->password);
            $headers = $imap->getHeaders();
            
            foreach($headers as $header)
            {
                $exp = explode(')',trim($header,'N '));
                $email = $imap->getEmail($exp[0]);
                
                print_r($email['header']);
              
                $tos = $email['header']->to;
                foreach($tos as $to)
                {
                    $emailadress = $to->mailbox.'@'.$to->host;
                    $user = User::findFirst('email = "'.$emailadress.'"');
                    if(!$user)
                    {
                        $user = new User();
                        $user->id = $this->uuid();
                        $user->email = $emailadress; 
                        $user->status = 0;
                        $user->clearance = 0;
                        $user->password = md5(rand(1000000,9999999));
                        
                    }
                    
                    
                    
                }
                
               
                
                $mgmtemail = new MgmtMail();
                $mgmtemail->subject = $email['header']->subject;
                $mgmtemail->status = 3;
                
                die();
            }
            die();
        }
        else //Mandrill
        {
            
        }
    }
}
