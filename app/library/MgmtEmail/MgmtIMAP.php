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
    /**
     * create a valid connection to an IMAP email server
     */
    public function __construct($host,$user,$password)
    {
        $this->connection = \imap_open($host,$user,$password);
        if($this->connection)
        {
            echo 'we have some sort of connection';
        }
        else
        {
            gohphai4
        }
    }
}
