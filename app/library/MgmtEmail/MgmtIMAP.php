<?php

/*
 * Copyright (c) 2015, Bart Bresse ,SONDER All rights reserved.
 * This software is licensed under the NEW BSD LICENSE
 * 
 * this is a total shot in the dark, 
 * 
 */
namespace MgmtEmail;
/**
 * Description of MgmtIMAP
 *
 * @author bart
 */
class MgmtIMAP {

    private $connection;
    private $hoststring;
    public  $trace = array();
    private $success = false;
    
    /**
     * create a valid connection to an IMAP email server
     */
    public function __construct($host,$user,$password)
    {
      //  $this->connection = imap_open("{localhost:143}INBOX", $user, $password);

        // To connect to a POP3 server on port 110 on the local server, use:
     //   $this->connection = imap_open ("{localhost:110/pop3}INBOX", $user, $password);

        // To connect to an SSL IMAP or POP3 server, add /ssl after the protocol
        // specification:
     //   $this->connection = imap_open ("{localhost:993/imap/ssl}INBOX", $user, $password);

        // To connect to an SSL IMAP or POP3 server with a self-signed certificate,
        // add /ssl/novalidate-cert after the protocol specification:
        
        $this->hoststring = "{localhost:995/pop3/ssl/novalidate-cert}";
        
        $this->connection = imap_open ($this->hoststring, $user, $password);
        if($this->connection)
        {
            array_push($this->trace,'we have some sort of connection\n');
            $this->success = false;
        }
    }
    
    /**
     * @return type
     */
    public function getMailBox()
    {
        $folders = imap_listmailbox($this->connection, $this->hoststring, "*");
        if ($folders == false) 
        {
            array_push($this->trace,'getMailBox:Call failed<br />\n');
        } 
        else 
        {
            return $folders;
        }
    }
    
    /**
     * get the headers of received email messages (subject line with number)
     * @return $headers email inbox headers
     */
    public function getHeaders()
    {
        $headers = imap_headers($this->connection);
        if ($headers == false)
        {
            array_push($this->trace,'getHeaders:Call failed<br />\n');
        } 
        else 
        {
            return $headers;
        }
    }
    
    private function fetchParts($number)
    {
        $parts = array();
        $s = imap_fetchstructure($this->connection,$number);
        if(isset($s->parts) && count($s->parts) > 1)
        {
            array_push($this->trace,'multi part<br />\n');
            for($i=0; $i < count($s->parts); $i++)
            {
               array_push($parts,$this->fetchPart($number,$i,$s->parts[$i]));
            }
        }
        else
        {
            array_push($this->trace,'single part<br />\n');
            $body = imap_body($this->connection,$number);
        }
        return $parts;
    }

    private function fetchPart($number,$part,$partmeta)
    {        
        $rpart = array();
        $rpart['content'] = imap_fetchbody($this->connection,$number,$part);
        return $rpart;
    }
    
    public function getEmail($number)
    {
        $email = array();
        $email['header'] = imap_header($this->connection,$number);
        $email['parts'] = $this->fetchParts($number); 
        return $email;
    }
         
    public function close()
    {
        imap_close($this->connection);
    }
    
    
}
