# ToDo & Co

[![Build Status](https://travis-ci.org/michaelgtfr/todoList.svg?branch=master)](https://travis-ci.org/michaelgtfr/todoList)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2f73a3fc595b4e98b7815d56ba852608)](https://www.codacy.com/manual/michaelgtfr/todoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=michaelgtfr/todoList&amp;utm_campaign=Badge_Grade)

## Presentation

ToDo & Co is a startup whose core business is an application to manage its daily tasks. The choice of the previous developer was to use the Symfony PHP framework to create the application. The application had to be developed at full speed to show potential investors that the concept is viable. Thereafter, ToDo & Co succeeded in raising funds to allow the development of the company and especially of the application.  

The site was created on its first version under Symfony 3. To have a site which can be updated, clean and of good quality according to the Bests Pratices and the SOLID principle, its passage under Symfony 4 became an obligation. After having fixed the problems of depreciations and that of the libraries used on the framework. It was possible to switch to Symfony 4.4.  

After its update under symfony 4, new functionalities were put in place and the modification of the anomalies noted during its update or requested in the specifications were resolved. Unit tests by setting up PHPUnit and functional with PHPUnit allowing to have an overview of the coverage and Behat to have an operation following that made by a user.  

To know the context of the current project you can go to the document located in Resource/doc/Context.md

## Installation

The presentation of the local installation is detailed in the `contribution.md` and `contributing.md` files located in the Resource/doc (en or fr) folder. The following presentation presents the establishment in a host.  

### Prerequisites

#### Download libraries via composer

Go to the root folder of the site. In the root folder right click the mouse and then press git bash (or equivalent software). The Open software write " composer install ". The libraries will be installed automatically in a vendor folder.  

#### Set up in a host

-Have storage space in a web host.  
-Have a domain name that will be the address on which your site will be accessible.  
-Have its ID on its hosting (host, password, Identifying).  
-Have installed an FTP on his computer.

#### Site installation on a host
  
(Example with FileZilla but all FTP works on the same principle)
  
Open your FTP software. Click on the logo "site manager". A window opens. Click on "New Site" and give it the name you want (example: "My Site"). To the right, you will have to indicate (the IP address, password and its username). Click Connect. !Warning a message warns you after you click Connect you tell yourself if you are connecting or not. After connecting, double-click in the left window on the files or click-Drop the folders in the right window that you want to send to the server. As soon as it appears in the right window, it was sent to your server. !Please note that your home page should be call index.php This is the page that will be loaded when a new visitor arrives on your site.  

#### Database installation  

##### Required
  
  -The IP address of the MySQL server  
  -Your MySQL Login  
  -Your MySQL password 
  -The name of the database, if it has already been created  
  -The PhpMyAdmin address that allows you to manage your online database  

##### Access
  
Changed the parameter file of the database (.env.local.php). Now that it's done, your scripts have access to the host database. !If your table is still empty, you have to use the phpMyAdmin that the hosting puts at your disposal to recreate the tables. On your machine, go to your local phpMyAdmin. Use it to export all your tables. This will create a. sql file on your hard disk that will contain your tables. Then go to the phpMyAdmin address of your host. Once there, use the Import feature to import the. sql file that is on your hard disk. Your tables are now loaded on the host's MySQL server.  

##### Website configuration
  
Do not forget to modify the configuration in the '.env.local.php' file.
On a web host, modify the access path to the public file for the domain on the administration page.
  
**Site installation Complete**

#### Contributing

  1.Fork it  
  2.Create your feature branch (git checkout -b my-new-feature)  
  3.Commit your changes (git commit -am 'Add some feature')  
  4.Push to the branch (git push origin my-new-feature)  
  5.Create new Pull Request  
  
The detailed presentation is written in the `contribution.md` or `contributing.md` file in the Resources/doc(en or fr) folder.  
 
