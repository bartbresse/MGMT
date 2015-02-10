MGMT Application
================

Phalcon PHP is a web framework delivered as a C extension providing high
performance and lower resource consumption.

This is a tool on top of this framework and in order for it to work you need to have the phalcon framework installed.

Please write us if you have any feedback.

Thanks.

NOTE
----
The master branch will always contain the latest stable version. If you wish
to check older versions or newer ones currently under development, please
switch to the relevant branch.

Required version: >= 1.0.0

Get Started
-----------

#### Manual install notes



#### Requirements

To run this application on your machine, you need at least:

* >= PHP 5.3.9
* Apache Web Server with mod rewrite enabled
* Latest Phalcon Framework extension installed/enabled

Then you'll need to create the database and initialize schema:

    echo 'CREATE DATABASE invo' | mysql -u root
    cat schemas/invo.sql | mysql -u root invo
