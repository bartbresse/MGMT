<html>
    <head>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <link href="http://deappdeveloper.nl/MGMT/backend/css/style.min.css" type="text/css" rel="stylesheet"/>
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"/>
        
    </head>    
<?php

/*
 * Copyright (c) 2015, Bart Bresse ,SONDER All rights reserved.
 * This software is licensed under the NEW BSD LICENSE
 * 
 */

/**
 * This will create a configuration file and a sample database.
 * Delete this file after completion of the installation
 * @author bart breunesse
 */
?>
<body>
    <form>
        <h2>Application configuration</h2>
        <h3>database</h3>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">database host</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">database username</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">database password</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">database name</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
        
        <h3>ftp</h3>
        
         <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">host</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">user</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">password</label>
        <input type="text" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>

        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">port</label>
        <input type="text" value="21" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
       
        <h3>email</h3>
        
        <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">mandrill key</label>
        <input type="text" value="21" class="form-control" id="inputSuccess4" aria-describedby="inputSuccess4Status">
        <span id="inputSuccess4Status" class="sr-only">(success)</span>
        </div>
    </form>
    <h2>Application dependencies</h2>
    <p>We would like to tell you that it takes 5 minutes but that is sometimes simply not true.
        In order for the system to function we need the following dependencies to be installed on your server:</p>
    
    
    <table>
        <tr><th>dependency</th><th>install guide linux</th></tr>
        <tr><td>THE PHALCON FRAMEWORK</td><td>phalconphp.com</td></tr>
        <tr><td>PHP_IMAP support</td><td>directadmin: PHP_IMAP recompile</td></tr>
        <tr><td>PEAR MAIL</td><td>command: pear install Mail-1.2.0 </td></tr>
        <tr><td>PEAR MAIL</td><td>command: pear install Net_SMTP </td></tr>
    </table>
    
</body>
</html>

