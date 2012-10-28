seld-cms
========

Seld CMS 2.X - content management system for building of any kinds of sites. It is not release, it is alpha version yet.
Main feature of this system is possibility to write code inside admin area.
All page controllers, views and specific logic can (and should) be written using admin interface (wisywig editor).
Some features not realized yet, please wait, it will coming soon :)

how to install
==============

CMS written on PHP and needed: apache web server + rewrite module, php 5.1+ and mysql extensions, mysql 5+.
Download and unzip this code on your hosting in public directory. Create database with utf8 (utf8_general_ci) charset.
Run SQL queries from INSTALL directory in order by name. Add credentials to your database in file application/config/database.php.
Try to login as super administrator on page /admin/ with credentials: login admin, password admin123.
